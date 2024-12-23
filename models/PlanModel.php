<?php

function getRecommendedPlansOld($conn, $params)
{
    try {
        // Map usage type to data requirements
        $usage_data_map = [
            'basic' => ['min' => '1GB', 'max' => '5GB'],
            'regular' => ['min' => '5GB', 'max' => '15GB'],
            'heavy' => ['min' => '15GB', 'max' => 'Unlimited']
        ];

        $query = "
            SELECT 
                p.*,
                c.name as carrier_name,
                c.type as carrier_type,
                cov.rating as coverage_rating,
                cov.data_speed_min,
                cov.data_speed_max,
                cov.reliability_score,
                COALESCE(
                    (SELECT GROUP_CONCAT(DISTINCT problem_type) 
                    FROM common_problems cp 
                    WHERE cp.carrier_id = c.id 
                    AND cp.area_id = a.id 
                    AND cp.frequency IN ('common', 'very_common')
                    AND cp.status = 'ongoing'),
                    ''
                ) as common_issues
            FROM plans p
            JOIN carriers c ON p.carrier_id = c.id
            JOIN coverage cov ON c.id = cov.carrier_id
            JOIN areas a ON cov.area_id = a.id
            WHERE p.status = 'active'
            AND a.type = ?
            AND p.is_tourist_friendly = 1
            AND p.duration_days >= ?
        ";

        // Add usage-based filtering
        if (isset($usage_data_map[$params['usage_type']])) {
            $data_range = $usage_data_map[$params['usage_type']];
            $query .= " AND (
                (p.data_amount LIKE '%Unlimited%')
                OR (
                    CAST(REGEXP_REPLACE(p.data_amount, '[^0-9.]', '') AS DECIMAL) >= " .
                intval($data_range['min']) . "
                )
            )";
        }

        // Sort by best match
        $query .= "
            ORDER BY 
                cov.rating DESC,
                cov.reliability_score DESC,
                p.price ASC
            LIMIT 3
        ";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $params['area'], $params['duration']);
        $stmt->execute();
        $plans = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Enhance plans with real-world experiences
        foreach ($plans as &$plan) {
            $plan['experiences'] = getRealWorldExperiences($conn, $plan['id'], $params['area']);
            $plan['insights'] = getHonestInsights($conn, $plan['carrier_id'], $params['area']);
        }

        return $plans;
    } catch (Exception $e) {
        error_log("Error getting recommendations: " . $e->getMessage());
        return false;
    }
}


function getRecommendedPlans($conn, $params)
{
    try {
        // Map usage patterns to data requirements and descriptions
        $usage_patterns = [
            'basic' => [
                'min_data' => '5',
                'ideal_data' => '10',
                'description' => 'maps and basic messaging',
                'activities' => 'Maps, WhatsApp, basic web browsing',
                'daily_estimate' => '300MB-500MB per day'
            ],
            'regular' => [
                'min_data' => '15',
                'ideal_data' => '30',
                'description' => 'social media and video calls',
                'activities' => 'Instagram, TikTok, daily video calls',
                'daily_estimate' => '1GB-2GB per day'
            ],
            'heavy' => [
                'min_data' => '35',
                'ideal_data' => 'Unlimited',
                'description' => 'streaming and constant connectivity',
                'activities' => 'Netflix, work calls, hotspot usage',
                'daily_estimate' => '3GB+ per day'
            ]
        ];

        // Get usage requirements
        $usage_req = $usage_patterns[$params['usage_type']] ?? $usage_patterns['regular'];

        // Get best overall match
        $best_match = getBestMatch($conn, $params, $usage_req);

        // Get budget option
        $budget_option = getBudgetOption($conn, $params, $usage_req);

        // Get premium option
        $premium_option = getPremiumOption($conn, $params, $usage_req);

        // Enhance recommendations with context
        return [
            'recommended' => enhancePlanWithContext($conn, $best_match, $params, $usage_req),
            'budget' => enhancePlanWithContext($conn, $budget_option, $params, $usage_req),
            'premium' => enhancePlanWithContext($conn, $premium_option, $params, $usage_req),
            'usage_context' => $usage_req
        ];
    } catch (Exception $e) {
        error_log("Error getting recommendations: " . $e->getMessage());
        return false;
    }
}



function debugTablesData($conn)
{
    $debug_info = [];

    // Check carriers
    $sql = "SELECT COUNT(*) as count FROM carriers WHERE status = 'active'";
    $result = $conn->query($sql);
    $debug_info['active_carriers'] = $result->fetch_assoc()['count'];

    // Check plans
    $sql = "SELECT COUNT(*) as count FROM plans WHERE status = 'active' AND is_tourist_friendly = 1";
    $result = $conn->query($sql);
    $debug_info['tourist_plans'] = $result->fetch_assoc()['count'];

    // Check areas
    $sql = "SELECT type, COUNT(*) as count FROM areas GROUP BY type";
    $result = $conn->query($sql);
    $debug_info['areas'] = [];
    while ($row = $result->fetch_assoc()) {
        $debug_info['areas'][$row['type']] = $row['count'];
    }

    // Check coverage
    $sql = "SELECT COUNT(*) as count FROM coverage c 
            JOIN carriers carr ON c.carrier_id = carr.id 
            WHERE carr.status = 'active'";
    $result = $conn->query($sql);
    $debug_info['coverage_records'] = $result->fetch_assoc()['count'];

    // Debug a sample query
    $sql = "SELECT p.*, c.name as carrier_name, cov.rating as coverage_rating
            FROM plans p
            JOIN carriers c ON p.carrier_id = c.id
            JOIN coverage cov ON c.id = cov.carrier_id
            JOIN areas a ON cov.area_id = a.id
            WHERE p.status = 'active'
            AND p.is_tourist_friendly = 1
            AND a.type = 'city'
            AND p.duration_days >= 28
            LIMIT 1";
    $result = $conn->query($sql);
    $debug_info['sample_plan'] = $result->fetch_assoc();

    error_log("Debug info: " . print_r($debug_info, true));
    return $debug_info;
}


function getBestMatch($conn, $params, $usage_req) {
    error_log("getBestMatch params: " . print_r($params, true), 3, "errors.log");
    
    $query = "
        SELECT 
            p.*,
            c.name as carrier_name,
            c.type as carrier_type,
            c.network_type,
            cov.rating as coverage_rating,
            cov.reliability_score,
            cov.peak_hour_impact,
            cov.data_speed_min,
            cov.data_speed_max,
            ROUND(
                CASE 
                    WHEN p.data_amount LIKE '%unlimited%' THEN 100
                    ELSE CAST(REGEXP_REPLACE(p.data_amount, '[^0-9.]', '') AS DECIMAL)
                END / p.price, 2
            ) as value_score
        FROM plans p
        JOIN carriers c ON p.carrier_id = c.id
        JOIN coverage cov ON c.id = cov.carrier_id
        JOIN areas a ON cov.area_id = a.id
        WHERE p.status = 'active'
        AND a.type = ?
        AND p.is_tourist_friendly = 1
        AND p.duration_days >= ?
        AND (
            p.data_amount LIKE '%unlimited%'
            OR CAST(REGEXP_REPLACE(p.data_amount, '[^0-9.]', '') AS DECIMAL) >= ?
        )
        ORDER BY 
            cov.rating DESC,
            value_score DESC
        LIMIT 1
    ";

    try {
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error, 3, "errors.log");
            return null;
        }

        $duration = intval($params['duration']);
        $min_data = intval($usage_req['min_data']);
        
        $stmt->bind_param('sii', $params['area'], $duration, $min_data);
        
        if (!$stmt->execute()) {
            error_log("Execute failed: " . $stmt->error, 3, "errors.log");
            return null;
        }
        
        $result = $stmt->get_result()->fetch_assoc();
        error_log("getBestMatch result: " . print_r($result, true), 3, "errors.log");
        return $result;
        
    } catch (Exception $e) {
        error_log("Error in getBestMatch: " . $e->getMessage(), 3, "errors.log");
        return null;
    }
}



function getBudgetOption($conn, $params, $usage_req) {
    error_log("getBudgetOption params: " . print_r($params, true), 3, "errors.log");
    
    $query = "
        SELECT 
            p.*,
            c.name as carrier_name,
            c.type as carrier_type,
            c.network_type,
            cov.rating as coverage_rating,
            cov.reliability_score,
            cov.peak_hour_impact,
            cov.data_speed_min,
            cov.data_speed_max,
            ROUND(
                CASE 
                    WHEN p.data_amount LIKE '%unlimited%' THEN 100
                    ELSE CAST(REGEXP_REPLACE(p.data_amount, '[^0-9.]', '') AS DECIMAL)
                END / p.price, 2
            ) as value_score
        FROM plans p
        JOIN carriers c ON p.carrier_id = c.id
        JOIN coverage cov ON c.id = cov.carrier_id
        JOIN areas a ON cov.area_id = a.id
        WHERE p.status = 'active'
        AND a.type = ?
        AND p.is_tourist_friendly = 1
        AND p.duration_days >= ?
        AND (
            p.data_amount LIKE '%unlimited%'
            OR CAST(REGEXP_REPLACE(p.data_amount, '[^0-9.]', '') AS DECIMAL) >= ?
        )
        AND cov.rating >= 3
        ORDER BY 
            p.price ASC,
            value_score DESC
        LIMIT 1
    ";

    try {
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed in getBudgetOption: " . $conn->error, 3, "errors.log");
            return null;
        }

        $duration = intval($params['duration']);
        $min_data = intval($usage_req['min_data']);
        
        $stmt->bind_param('sii', $params['area'], $duration, $min_data);
        
        if (!$stmt->execute()) {
            error_log("Execute failed in getBudgetOption: " . $stmt->error, 3, "errors.log");
            return null;
        }
        
        $result = $stmt->get_result()->fetch_assoc();
        error_log("getBudgetOption result: " . print_r($result, true), 3, "errors.log");
        return $result;
        
    } catch (Exception $e) {
        error_log("Error in getBudgetOption: " . $e->getMessage(), 3, "errors.log");
        return null;
    }
}

function getPremiumOption($conn, $params, $usage_req) {
    error_log("getPremiumOption params: " . print_r($params, true), 3, "errors.log");
    
    $query = "
        SELECT 
            p.*,
            c.name as carrier_name,
            c.type as carrier_type,
            c.network_type,
            cov.rating as coverage_rating,
            cov.reliability_score,
            cov.peak_hour_impact,
            cov.data_speed_min,
            cov.data_speed_max,
            ROUND(
                CASE 
                    WHEN p.data_amount LIKE '%unlimited%' THEN 100
                    ELSE CAST(REGEXP_REPLACE(p.data_amount, '[^0-9.]', '') AS DECIMAL)
                END / p.price, 2
            ) as value_score
        FROM plans p
        JOIN carriers c ON p.carrier_id = c.id
        JOIN coverage cov ON c.id = cov.carrier_id
        JOIN areas a ON cov.area_id = a.id
        WHERE p.status = 'active'
        AND a.type = ?
        AND p.is_tourist_friendly = 1
        AND p.duration_days >= ?
        AND (
            p.data_amount LIKE '%unlimited%'
            OR CAST(REGEXP_REPLACE(p.data_amount, '[^0-9.]', '') AS DECIMAL) >= ?
        )
        ORDER BY 
            cov.rating DESC,
            cov.reliability_score DESC,
            p.price DESC
        LIMIT 1
    ";

    try {
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed in getPremiumOption: " . $conn->error, 3, "errors.log");
            return null;
        }

        $duration = intval($params['duration']);
        $ideal_data = is_numeric($usage_req['ideal_data']) ? intval($usage_req['ideal_data']) : 50;
        
        $stmt->bind_param('sii', $params['area'], $duration, $ideal_data);
        
        if (!$stmt->execute()) {
            error_log("Execute failed in getPremiumOption: " . $stmt->error, 3, "errors.log");
            return null;
        }
        
        $result = $stmt->get_result()->fetch_assoc();
        error_log("getPremiumOption result: " . print_r($result, true), 3, "errors.log");
        return $result;
        
    } catch (Exception $e) {
        error_log("Error in getPremiumOption: " . $e->getMessage(), 3, "errors.log");
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
        'typical_activities' => $usage_req['activities']
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

function getNearbyStores($conn, $area_type, $carrier_id) {
    try {
        $query = "
            SELECT 
                s.*,
                c.name as carrier_name,
                c.logo_url
            FROM stores s
            JOIN carriers c ON s.carrier_id = c.id
            JOIN areas a ON s.area_id = a.id
            WHERE a.type = ?
            AND s.carrier_id = ?  // Added carrier_id filter
            AND s.status = 'active'
            AND s.has_instant_activation = 1
            LIMIT 5
        ";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $area_type, $carrier_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
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
