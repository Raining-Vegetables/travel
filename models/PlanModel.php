<?php

function getRecommendedPlans($conn, $params)
{
    try {
        error_log("Starting getRecommendedPlans with params: " . print_r($params, true));

        // 1. Define core requirements
        $data_requirements = [
            'basic' => [
                'min_gb' => 4,
                'ideal_gb' => 8,
                'max_price' => 30,
                'description' => 'maps and basic messaging',
                'activities' => 'Maps, WhatsApp, basic web browsing',
                'daily_estimate' => '300MB-500MB per day'
            ],
            'regular' => [
                'min_gb' => 12,
                'ideal_gb' => 20,
                'max_price' => 45,
                'description' => 'social media and video calls',
                'activities' => 'Instagram, TikTok, daily video calls',
                'daily_estimate' => '1GB-2GB per day'
            ],
            'heavy' => [
                'min_gb' => 20,
                'ideal_gb' => 'unlimited',
                'max_price' => 60,
                'description' => 'streaming and constant connectivity',
                'activities' => 'Netflix, work calls, hotspot usage',
                'daily_estimate' => '3GB+ per day'
            ]
        ];

        // 2. Define location preferences
        $location_preferences = [
            'eastern' => ['Telstra', 'Optus'],
            'city' => ['all'],
            'northern' => ['Telstra', 'Optus'],
            'western' => ['Optus', 'Vodafone'],
            'southern' => ['Telstra', 'Optus']
        ];

        // 3. Define duration adjustments
        $duration_multiplier = [
            'short' => 0.5,
            'medium' => 1,
            'long' => 1.5
        ];

        // Get base requirements
        $requirements = $data_requirements[$params['usage_type']] ?? $data_requirements['regular'];
        $multiplier = $duration_multiplier[$params['duration_days']] ?? 1;
        $carriers = $location_preferences[$params['area']] ?? ['all'];

        // Adjust requirements based on duration
        $requirements['min_gb'] = ceil($requirements['min_gb'] * $multiplier);
        $requirements['ideal_gb'] = $requirements['ideal_gb'] === 'unlimited' ?
            'unlimited' : ceil($requirements['ideal_gb'] * $multiplier);

        // Get all recommendations
        $recommended = getRecommendedPlan($conn, $params, $requirements, $carriers);
        $budget = getBudgetPlan($conn, $params, $requirements, $carriers);
        $premium = getPremiumPlan($conn, $params, $requirements, $carriers);

        $result = [
            'recommended' => $recommended ? enhancePlanWithContext($conn, $recommended, $params, $requirements) : null,
            'budget' => $budget ? enhancePlanWithContext($conn, $budget, $params, $requirements) : null,
            'premium' => $premium ? enhancePlanWithContext($conn, $premium, $params, $requirements) : null,
            'usage_context' => $requirements
        ];

        error_log("Final recommendations generated: " . print_r($result, true));
        return $result;
    } catch (Exception $e) {
        error_log("Error in getRecommendedPlans: " . $e->getMessage());
        throw $e;
    }
}

function getRecommendedPlan($conn, $params, $requirements, $carriers)
{
    try {
        error_log("Starting getRecommendedPlan with requirements: " . print_r($requirements, true));

        // Build carrier condition
        $carrier_condition = "";
        $carrier_params = [];
        if ($carriers[0] !== 'all') {
            $placeholders = str_repeat('?,', count($carriers) - 1) . '?';
            $carrier_condition = "AND c.name IN ($placeholders)";
            $carrier_params = $carriers;
        }

        // Main query
        $query = "
            SELECT 
                p.*,
                c.id as carrier_id,
                c.name as carrier_name,
                c.type as carrier_type,
                c.network_type,
                CASE 
                    WHEN p.data_amount LIKE '%unlimited%' THEN 999999
                    ELSE CAST(REGEXP_REPLACE(p.data_amount, '[^0-9.]', '') AS DECIMAL(10,2))
                END as data_gb,
                c.coverage_rating,
                c.reliability_score
            FROM plans p
            JOIN carriers c ON p.carrier_id = c.id
            WHERE p.status = 'active'
            AND p.is_tourist_friendly = 1
            AND c.type = 'main'
            AND p.price <= ?
            AND (
                p.data_amount LIKE '%unlimited%'
                OR CAST(REGEXP_REPLACE(p.data_amount, '[^0-9.]', '') AS DECIMAL(10,2)) >= ?
            )
            $carrier_condition
            ORDER BY 
                -- Prioritize plans that match ideal data requirement
                CASE 
                    WHEN p.data_amount LIKE '%unlimited%' AND ? = 'unlimited' THEN 0
                    WHEN CAST(REGEXP_REPLACE(p.data_amount, '[^0-9.]', '') AS DECIMAL(10,2)) >= ? THEN 1
                    ELSE 2
                END,
                -- Then consider coverage rating for area
                c.coverage_rating DESC,
                -- Then consider value (price per GB)
                CASE 
                    WHEN p.data_amount LIKE '%unlimited%' THEN 0
                    ELSE p.price / CAST(REGEXP_REPLACE(p.data_amount, '[^0-9.]', '') AS DECIMAL(10,2))
                END ASC,
                -- Finally, absolute price
                p.price ASC
            LIMIT 1";

        // Prepare statement
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }

        // Build parameters array
        $params = array_merge(
            ['d', 'd', 's', 'd'],  // Types for base parameters
            array_fill(0, count($carrier_params), 's')  // Types for carrier names
        );
        $values = array_merge(
            [
                $requirements['max_price'],
                $requirements['min_gb'],
                $requirements['ideal_gb'],
                is_numeric($requirements['ideal_gb']) ? $requirements['ideal_gb'] : PHP_FLOAT_MAX
            ],
            $carrier_params
        );

        // Bind parameters
        $stmt->bind_param(implode('', $params), ...$values);

        // Execute and get results
        if (!$stmt->execute()) {
            throw new Exception("Failed to execute statement: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $plan = $result->fetch_assoc();

        error_log("Recommended plan found: " . print_r($plan, true));

        // If no plan found, try with relaxed criteria
        if (!$plan) {
            error_log("No plan found with strict criteria, trying relaxed criteria...");
            return getRecommendedPlanRelaxed($conn, $requirements, $carriers);
        }

        return $plan;
    } catch (Exception $e) {
        error_log("Error in getRecommendedPlan: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        throw $e;
    }
}
function getRecommendedPlanRelaxed($conn, $requirements, $carriers)
{
    try {
        error_log("Starting getRecommendedPlanRelaxed with relaxed requirements...");

        // Relax the requirements
        $relaxed_requirements = [
            'max_price' => $requirements['max_price'] * 1.2,    // 20% higher price tolerance
            'min_gb' => $requirements['min_gb'] * 0.8,          // 20% lower data requirement
            'ideal_gb' => is_numeric($requirements['ideal_gb']) ?
                $requirements['ideal_gb'] * 0.8 :
                'unlimited'
        ];

        error_log("Relaxed requirements: " . print_r($relaxed_requirements, true));

        // Query with relaxed criteria
        $query = "
            SELECT 
                p.*,
                c.id as carrier_id,
                c.name as carrier_name,
                c.type as carrier_type,
                c.network_type,
                CASE 
                    WHEN p.data_amount LIKE '%unlimited%' THEN 999999
                    ELSE CAST(REGEXP_REPLACE(p.data_amount, '[^0-9.]', '') AS DECIMAL(10,2))
                END as data_gb,
                c.coverage_rating,
                c.reliability_score
            FROM plans p
            JOIN carriers c ON p.carrier_id = c.id
            WHERE p.status = 'active'
            AND p.is_tourist_friendly = 1
            AND (
                p.data_amount LIKE '%unlimited%'
                OR CAST(REGEXP_REPLACE(p.data_amount, '[^0-9.]', '') AS DECIMAL(10,2)) >= ?
            )
            AND p.price <= ?
            ORDER BY 
                -- Prioritize main carriers over MVNOs
                c.type = 'main' DESC,
                -- Then consider coverage rating
                c.coverage_rating DESC,
                -- Then prioritize meeting relaxed data requirement
                CASE 
                    WHEN p.data_amount LIKE '%unlimited%' THEN 0
                    WHEN CAST(REGEXP_REPLACE(p.data_amount, '[^0-9.]', '') AS DECIMAL(10,2)) >= ? THEN 1
                    ELSE 2
                END,
                -- Then consider value (price per GB)
                CASE 
                    WHEN p.data_amount LIKE '%unlimited%' THEN 0
                    ELSE p.price / CAST(REGEXP_REPLACE(p.data_amount, '[^0-9.]', '') AS DECIMAL(10,2))
                END ASC,
                -- Finally, absolute price
                p.price ASC
            LIMIT 1";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Failed to prepare relaxed statement: " . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param(
            "ddd",
            $relaxed_requirements['min_gb'],
            $relaxed_requirements['max_price'],
            $relaxed_requirements['min_gb']
        );

        if (!$stmt->execute()) {
            throw new Exception("Failed to execute relaxed statement: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $plan = $result->fetch_assoc();

        error_log("Relaxed search result: " . print_r($plan, true));

        // If still no plan found, try one final time with minimal constraints
        if (!$plan) {
            error_log("No plan found with relaxed criteria, trying minimal constraints...");
            return getRecommendedPlanMinimal($conn);
        }

        return $plan;
    } catch (Exception $e) {
        error_log("Error in getRecommendedPlanRelaxed: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        return null;
    }
}

function getRecommendedPlanMinimal($conn)
{
    try {
        error_log("Starting getRecommendedPlanMinimal with minimal constraints...");

        // Final attempt with minimal constraints
        $query = "
            SELECT 
                p.*,
                c.id as carrier_id,
                c.name as carrier_name,
                c.type as carrier_type,
                c.network_type,
                c.coverage_rating,
                c.reliability_score
            FROM plans p
            JOIN carriers c ON p.carrier_id = c.id
            WHERE p.status = 'active'
            AND p.is_tourist_friendly = 1
            ORDER BY 
                c.coverage_rating DESC,
                p.price ASC
            LIMIT 1";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Failed to prepare minimal statement: " . $conn->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Failed to execute minimal statement: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $plan = $result->fetch_assoc();

        error_log("Minimal search result: " . print_r($plan, true));

        return $plan;
    } catch (Exception $e) {
        error_log("Error in getRecommendedPlanMinimal: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        return null;
    }
}

function getPremiumPlan($conn, $params, $requirements, $carriers)
{
    try {
        error_log("Starting getPremiumPlan with requirements: " . print_r($requirements, true), 3, "errors.log");

        // Calculate premium requirements (higher than recommended)
        $premium_requirements = [
            'min_price' => $requirements['max_price'],     // Start from max recommended price
            'min_gb' => $requirements['ideal_gb'] * 1.5,   // 50% more data than ideal
            'preferred_carriers' => $carriers
        ];

        error_log("Premium requirements calculated: " . print_r($premium_requirements, true), 3, "errors.log");

        // Build query for premium plans
        $query = "
            SELECT 
                p.*,
                c.id as carrier_id,
                c.name as carrier_name,
                c.type as carrier_type,
                c.network_type,
                c.coverage_rating,
                c.reliability_score,
                CASE 
                    WHEN p.data_amount LIKE '%unlimited%' THEN 999999
                    ELSE CAST(REGEXP_REPLACE(p.data_amount, '[^0-9.]', '') AS DECIMAL(10,2))
                END as data_gb
            FROM plans p
            JOIN carriers c ON p.carrier_id = c.id
            WHERE p.status = 'active'
            AND p.is_tourist_friendly = 1
            AND c.type = 'main'
            AND c.coverage_rating >= 4  -- Premium plans should have good coverage
            AND (
                p.data_amount LIKE '%unlimited%'
                OR CAST(REGEXP_REPLACE(p.data_amount, '[^0-9.]', '') AS DECIMAL(10,2)) >= ?
            )
            AND p.price >= ?";  // Should be more expensive than recommended

        // Add carrier preference if specified
        if ($carriers[0] !== 'all') {
            $placeholders = str_repeat('?,', count($carriers) - 1) . '?';
            $query .= " AND c.name IN ($placeholders)";
        }

        $query .= " ORDER BY 
                CASE 
                    WHEN p.data_amount LIKE '%unlimited%' THEN 0
                    ELSE 1
                END,
                c.coverage_rating DESC,
                c.reliability_score DESC,
                p.international_calls DESC,  -- Prioritize international calling
                p.price ASC
            LIMIT 1";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_log("Failed to prepare premium statement: " . $conn->error, 3, "errors.log");
            throw new Exception("Failed to prepare premium statement");
        }

        // Build parameters array
        $types = ['d', 'd'];  // min_gb and min_price
        $values = [
            $premium_requirements['min_gb'],
            $premium_requirements['min_price']
        ];

        // Add carrier parameters if needed
        if ($carriers[0] !== 'all') {
            $types = array_merge($types, array_fill(0, count($carriers), 's'));
            $values = array_merge($values, $carriers);
        }

        $stmt->bind_param(implode('', $types), ...$values);

        if (!$stmt->execute()) {
            error_log("Failed to execute premium statement: " . $stmt->error, 3, "errors.log");
            throw new Exception("Failed to execute premium statement");
        }

        $result = $stmt->get_result();
        $plan = $result->fetch_assoc();

        error_log("Initial premium plan search result: " . print_r($plan, true), 3, "errors.log");

        // If no premium plan found, try fallback
        if (!$plan) {
            error_log("No premium plan found with strict criteria, trying fallback...", 3, "errors.log");
            return getPremiumPlanFallback($conn, $premium_requirements);
        }

        return $plan;
    } catch (Exception $e) {
        error_log("Error in getPremiumPlan: " . $e->getMessage(), 3, "errors.log");
        error_log("Stack trace: " . $e->getTraceAsString(), 3, "errors.log");
        return null;
    }
}

function getPremiumPlanFallback($conn, $premium_requirements)
{
    try {
        error_log("Starting getPremiumPlanFallback...", 3, "errors.log");

        // Relaxed query focusing only on unlimited data and coverage
        $query = "
            SELECT 
                p.*,
                c.id as carrier_id,
                c.name as carrier_name,
                c.type as carrier_type,
                c.network_type,
                c.coverage_rating,
                c.reliability_score
            FROM plans p
            JOIN carriers c ON p.carrier_id = c.id
            WHERE p.status = 'active'
            AND p.is_tourist_friendly = 1
            AND c.type = 'main'
            AND (
                p.data_amount LIKE '%unlimited%'
                OR CAST(REGEXP_REPLACE(p.data_amount, '[^0-9.]', '') AS DECIMAL(10,2)) >= ?
            )
            ORDER BY 
                p.data_amount LIKE '%unlimited%' DESC,
                c.coverage_rating DESC,
                p.international_calls DESC,
                p.price DESC
            LIMIT 1";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_log("Failed to prepare premium fallback statement: " . $conn->error, 3, "errors.log");
            throw new Exception("Failed to prepare premium fallback statement");
        }

        $stmt->bind_param("d", $premium_requirements['min_gb']);

        if (!$stmt->execute()) {
            error_log("Failed to execute premium fallback statement: " . $stmt->error, 3, "errors.log");
            throw new Exception("Failed to execute premium fallback statement");
        }

        $result = $stmt->get_result();
        $plan = $result->fetch_assoc();

        error_log("Premium fallback search result: " . print_r($plan, true), 3, "errors.log");

        return $plan;
    } catch (Exception $e) {
        error_log("Error in getPremiumPlanFallback: " . $e->getMessage(), 3, "errors.log");
        error_log("Stack trace: " . $e->getTraceAsString(), 3, "errors.log");
        return null;
    }
}


function getBudgetPlan($conn, $params, $requirements, $carriers)
{
    try {
        error_log("Starting getBudgetPlan with requirements: " . print_r($requirements, true), 3, "errors.log");

        // Calculate budget targets
        $budget_requirements = [
            'max_price' => $requirements['max_price'] * 0.7,    // 30% cheaper than max
            'min_gb' => $requirements['min_gb'] * 0.8,          // 20% less data acceptable
            'ideal_gb' => is_numeric($requirements['ideal_gb']) ?
                $requirements['ideal_gb'] * 0.8 :
                $requirements['min_gb']
        ];

        error_log("Budget requirements calculated: " . print_r($budget_requirements, true), 3, "errors.log");

        // First try MVNOs on preferred networks
        $query = "
            SELECT 
                p.*,
                c.id as carrier_id,
                c.name as carrier_name,
                c.type as carrier_type,
                c.network_type,
                c.parent_carrier_id,
                c.coverage_rating,
                c.reliability_score,
                CASE 
                    WHEN p.data_amount LIKE '%unlimited%' THEN 999999
                    ELSE CAST(REGEXP_REPLACE(p.data_amount, '[^0-9.]', '') AS DECIMAL(10,2))
                END as data_gb
            FROM plans p
            JOIN carriers c ON p.carrier_id = c.id
            JOIN carriers parent ON c.parent_carrier_id = parent.id
            WHERE p.status = 'active'
            AND p.is_tourist_friendly = 1
            AND c.type = 'mvno'
            AND p.price <= ?
            AND (
                p.data_amount LIKE '%unlimited%'
                OR CAST(REGEXP_REPLACE(p.data_amount, '[^0-9.]', '') AS DECIMAL(10,2)) >= ?
            )";

        // Add carrier preference if specified
        if ($carriers[0] !== 'all') {
            $placeholders = str_repeat('?,', count($carriers) - 1) . '?';
            $query .= " AND parent.name IN ($placeholders)";
        }

        $query .= " ORDER BY 
                c.coverage_rating DESC,
                CASE 
                    WHEN p.data_amount LIKE '%unlimited%' THEN 0
                    WHEN CAST(REGEXP_REPLACE(p.data_amount, '[^0-9.]', '') AS DECIMAL(10,2)) >= ? THEN 1
                    ELSE 2
                END,
                p.price ASC
            LIMIT 1";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_log("Failed to prepare budget statement: " . $conn->error, 3, "errors.log");
            throw new Exception("Failed to prepare budget statement: " . $conn->error);
        }

        // Build parameters array
        $types = ['d', 'd'];  // price and min_gb
        $values = [
            $budget_requirements['max_price'],
            $budget_requirements['min_gb']
        ];

        // Add carrier parameters if needed
        if ($carriers[0] !== 'all') {
            $types = array_merge($types, array_fill(0, count($carriers), 's'));
            $values = array_merge($values, $carriers);
        }

        // Add ideal_gb parameter
        $types[] = 'd';
        $values[] = $budget_requirements['min_gb'];

        $stmt->bind_param(implode('', $types), ...$values);

        if (!$stmt->execute()) {
            error_log("Failed to execute budget statement: " . $stmt->error, 3, "errors.log");
            throw new Exception("Failed to execute budget statement: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $plan = $result->fetch_assoc();

        error_log("Initial budget plan search result: " . print_r($plan, true), 3, "errors.log");

        // If no MVNO plan found, try fallback options
        if (!$plan) {
            error_log("No MVNO budget plan found, trying fallback options...", 3, "errors.log");
            return getBudgetPlanFallback($conn, $budget_requirements);
        }

        return $plan;
    } catch (Exception $e) {
        error_log("Error in getBudgetPlan: " . $e->getMessage(), 3, "errors.log");
        error_log("Stack trace: " . $e->getTraceAsString(), 3, "errors.log");
        return null;
    }
}

function getBudgetPlanFallback($conn, $budget_requirements)
{
    try {
        error_log("Starting getBudgetPlanFallback...", 3, "errors.log");

        // Try any carrier with relaxed constraints
        $query = "
            SELECT 
                p.*,
                c.id as carrier_id,
                c.name as carrier_name,
                c.type as carrier_type,
                c.network_type,
                c.coverage_rating,
                c.reliability_score
            FROM plans p
            JOIN carriers c ON p.carrier_id = c.id
            WHERE p.status = 'active'
            AND p.is_tourist_friendly = 1
            AND p.price <= ?
            AND (
                p.data_amount LIKE '%unlimited%'
                OR CAST(REGEXP_REPLACE(p.data_amount, '[^0-9.]', '') AS DECIMAL(10,2)) >= ?
            )
            ORDER BY 
                p.price ASC,
                c.coverage_rating DESC
            LIMIT 1";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_log("Failed to prepare budget fallback statement: " . $conn->error, 3, "errors.log");
            throw new Exception("Failed to prepare budget fallback statement");
        }

        // Increase price limit by 10% for fallback
        $fallback_price = $budget_requirements['max_price'] * 1.1;

        $stmt->bind_param("dd", $fallback_price, $budget_requirements['min_gb']);

        if (!$stmt->execute()) {
            error_log("Failed to execute budget fallback statement: " . $stmt->error, 3, "errors.log");
            throw new Exception("Failed to execute budget fallback statement");
        }

        $result = $stmt->get_result();
        $plan = $result->fetch_assoc();

        error_log("Budget fallback search result: " . print_r($plan, true), 3, "errors.log");

        return $plan;
    } catch (Exception $e) {
        error_log("Error in getBudgetPlanFallback: " . $e->getMessage(), 3, "errors.log");
        error_log("Stack trace: " . $e->getTraceAsString(), 3, "errors.log");
        return null;
    }
}


function enhancePlanWithContext($conn, $plan, $params, $usage_req)
{
    if (!$plan) return null;

    // Get required additional data
    $stores = getNearbyStores($conn, $params['area'], $plan['carrier_id']);
    $experiences = getRealWorldExperiences($conn, $plan['id'], $params['area']);
    $problems = getCommonProblems($conn, $plan['carrier_id'], $params['area']);

    // Get parent network info for MVNOs
    $network_info = "";
    if (!in_array(strtolower($plan['carrier_name']), ['telstra', 'optus', 'vodafone'])) {
        $network_query = "
            SELECT network_type, parent_network 
            FROM carriers 
            WHERE id = ?
        ";
        $stmt = $conn->prepare($network_query);
        $stmt->bind_param('i', $plan['carrier_id']);
        $stmt->execute();
        $network_result = $stmt->get_result()->fetch_assoc();
        if ($network_result) {
            $network_info = "Uses " . $network_result['parent_network'] . " network";
        }
    }

    // Build reasons array based on plan features
    $reasons = [];
    if ($plan['coverage_rating'] >= 4) {
        $reasons[] = "Strong coverage in " . ucfirst($params['area']) . " area";
    }
    if ($plan['data_amount'] === 'Unlimited' || strpos(strtolower($plan['data_amount']), 'unlimited') !== false) {
        $reasons[] = "Unlimited data for worry-free usage";
    } else {
        $reasons[] = "Enough data for " . $usage_req['activities'];
    }
    if (!empty($stores)) {
        $reasons[] = "Easy activation at nearby store" . (count($stores) > 1 ? 's' : '');
    }
    if ($network_info) {
        $reasons[] = $network_info;
    }

    // Build warnings array
    $warnings = [];
    if ($plan['peak_hour_impact'] === 'significant') {
        $warnings[] = "Network may slow down during peak hours (usually 6-8pm)";
    }
    if ($problems) {
        foreach ($problems as $problem) {
            if ($problem['frequency'] === 'very_common') {
                $warnings[] = $problem['title'];
            }
        }
    }

    // Create the exact structure the template expects
    return [
        'price' => $plan['price'],
        'carrier_name' => $plan['carrier_name'],
        'data_amount' => $plan['data_amount'],
        'reasons' => $reasons,
        'stores' => $stores,
        'warnings' => $warnings,
        'experiences' => $experiences,
        'coverage_rating' => $plan['coverage_rating'],
        'reliability_score' => $plan['reliability_score'],
        'data_speed_min' => $plan['data_speed_min'],
        'data_speed_max' => $plan['data_speed_max'],
        'network_type' => $plan['network_type'],
        'carrier_type' => $plan['carrier_type'],
        'value_score' => $plan['value_score'] ?? null,
        'peak_hour_impact' => $plan['peak_hour_impact'],
        'data_per_day' => $usage_req['daily_estimate'],
        'typical_activities' => $usage_req['activities'],
        'network_info' => $network_info
    ];
}



function getCommonProblems($conn, $carrier_id, $area_type)
{
    try {
        $query = "
            SELECT 
                cp.problem_type,
                cp.title,
                cp.description,
                cp.frequency,
                cp.workaround
            FROM common_problems cp
            JOIN areas a ON cp.area_id = a.id
            WHERE cp.carrier_id = ?
            AND a.type = ?
            AND cp.status = 'ongoing'  -- Only show current problems
            AND cp.frequency IN ('common', 'very_common')  -- Only show significant issues
            ORDER BY 
                CASE cp.frequency
                    WHEN 'very_common' THEN 1
                    WHEN 'common' THEN 2
                    ELSE 3
                END,
                cp.problem_type
            LIMIT 3";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $carrier_id, $area_type);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        error_log("Error getting common problems: " . $e->getMessage());
        return [];
    }
}

function getRealWorldExperiences($conn, $plan_id, $area_type)
{
    try {
        $query = "
            SELECT 
                rwe.experience_type,
                rwe.rating,
                rwe.title,
                rwe.context,
                rwe.pros,
                rwe.cons,
                rwe.usage_period
            FROM real_world_experiences rwe
            JOIN areas a ON rwe.area_id = a.id
            WHERE rwe.plan_id = ?
            AND a.type = ?
            AND rwe.verified_purchase = 1
            ORDER BY rwe.rating DESC
            LIMIT 3
        ";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $plan_id, $area_type);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        error_log("Error getting experiences: " . $e->getMessage());
        return [];
    }
}

function getAreaCoverage($conn, $area_type)
{
    try {
        $query = "
            SELECT 
                c.name as carrier_name,
                cov.rating,
                cov.data_speed_min,
                cov.data_speed_max,
                cov.reliability_score,
                cov.peak_hour_impact,
                cov.blackspots,
                cov.popular_activities_rating,
                a.typical_data_usage,
                a.popular_activities
            FROM coverage cov
            JOIN carriers c ON cov.carrier_id = c.id
            JOIN areas a ON cov.area_id = a.id
            WHERE a.type = ?
            AND c.status = 'active'
            ORDER BY cov.rating DESC, cov.reliability_score DESC
        ";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $area_type);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        error_log("Error getting area coverage: " . $e->getMessage());
        return [];
    }
}

function getNearbyStores($conn, $area_type, $carrier_id)
{
    try {
        // First get main stores
        $query = "
            SELECT 
                s.*,
                c.name as carrier_name,
                c.logo_url,
                'main' as store_category,
                NULL as distance_meters,
                NULL as walking_time,
                s.type as store_type
            FROM stores s
            JOIN carriers c ON s.carrier_id = c.id
            JOIN areas a ON s.area_id = a.id
            WHERE s.carrier_id = ?
            AND a.type = ?
            AND s.status = 'active'
            AND s.has_instant_activation = 1
            ORDER BY s.type = 'official' DESC
            LIMIT 2
        ";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $carrier_id, $area_type);
        $stmt->execute();
        $main_stores = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Get any relevant backup stores regardless of main stores
        $backup_query = "
            SELECT 
                bs.*,
                s.carrier_id,
                'backup' as store_category,
                bs.distance_meters,
                bs.walking_time,
                'backup' as store_type
            FROM backup_stores bs
            JOIN stores s ON bs.main_store_id = s.id
            WHERE s.carrier_id = ?
            AND bs.status = 'active'
            ORDER BY bs.distance_meters ASC
            LIMIT 3
        ";

        $stmt = $conn->prepare($backup_query);
        $stmt->bind_param("i", $carrier_id);
        $stmt->execute();
        $backup_stores = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Combine all stores
        $all_stores = array_merge($main_stores, $backup_stores);

        // Only sort if we have stores to return
        if (!empty($all_stores)) {
            usort($all_stores, function ($a, $b) {
                if ($a['store_category'] !== $b['store_category']) {
                    return $a['store_category'] === 'main' ? -1 : 1;
                }
                if ($a['store_category'] === 'backup') {
                    return ($a['distance_meters'] ?? 0) - ($b['distance_meters'] ?? 0);
                }
                return $a['store_type'] === 'official' ? -1 : 1;
            });
        }

        return $all_stores;
    } catch (Exception $e) {
        error_log("Error getting stores: " . $e->getMessage(), 3, "errors.log");
        return [];
    }
}
function getHonestInsights($conn, $carrier_id, $area_type)
{
    try {
        $query = "
            SELECT 
                hi.insight_type,
                hi.marketing_claim,
                hi.reality,
                hi.recommendation
            FROM honest_insights hi
            JOIN areas a ON hi.area_id = a.id
            WHERE hi.carrier_id = ?
            AND a.type = ?
            LIMIT 2
        ";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $carrier_id, $area_type);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        error_log("Error getting insights: " . $e->getMessage());
        return [];
    }
}





function logUserSearch($conn, $params, $results_count)
{
    try {
        $query = "INSERT INTO search_logs 
                 (search_params, results_count, ip_address, user_agent, created_at)
                 VALUES (?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "siss",
            json_encode($params),
            $results_count,
            $_SERVER['REMOTE_ADDR'],
            $_SERVER['HTTP_USER_AGENT']
        );

        return $stmt->execute();
    } catch (Exception $e) {
        error_log("Error logging search: " . $e->getMessage());
        return false;
    }
}
