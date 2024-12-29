<?php

function getRecommendedPlans($conn, $params)
{
    try {
        error_log("Starting getRecommendedPlans with params: " . print_r($params, true));

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
        error_log("Usage requirements: " . print_r($usage_req, true));

        // Debug query to check available plans
        $debug_query = "
            SELECT COUNT(*) as count, 
                   GROUP_CONCAT(DISTINCT c.name) as carriers,
                   GROUP_CONCAT(DISTINCT p.type) as plan_types,
                   MIN(p.price) as min_price,
                   MAX(p.price) as max_price
            FROM plans p
            JOIN carriers c ON p.carrier_id = c.id
            WHERE p.status = 'active'";
        $debug_result = $conn->query($debug_query);
        $debug_info = $debug_result->fetch_assoc();
        error_log("Available plans info: " . print_r($debug_info, true));

        // Get the best match from major carriers
        $best_match = getBestMatch($conn, $params, $usage_req);
        error_log("Best match result: " . print_r($best_match, true));

        if (!$best_match) {
            error_log("No best match found. Trying with relaxed criteria...");
            // Try with relaxed criteria
            $params['duration_days'] = 'medium'; // Default to medium duration
            $best_match = getBestMatch($conn, $params, $usage_req);
            error_log("Relaxed criteria best match: " . print_r($best_match, true));
        }

        if (!$best_match) {
            error_log("Still no best match found with relaxed criteria");
            return false;
        }

        // Use the best match to find a budget alternative
        $budget_option = getBudgetOption($conn, $params, $usage_req, $best_match);
        error_log("Budget option found: " . print_r($budget_option, true));

        return [
            'recommended' => enhancePlanWithContext($conn, $best_match, $params, $usage_req),
            'budget' => enhancePlanWithContext($conn, $budget_option, $params, $usage_req),
            'usage_context' => $usage_req
        ];
    } catch (Exception $e) {
        error_log("Error in getRecommendedPlans: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        return false;
    }
}

function getBestMatch($conn, $params, $usage_req)
{
    error_log("Starting basic getBestMatch");

    // Basic query to find any matching plan
    $query = "
        SELECT 
            p.*,
            c.id as carrier_id,
            c.name as carrier_name,
            c.type as carrier_type,
            c.network_type
        FROM plans p
        JOIN carriers c ON p.carrier_id = c.id
        WHERE p.status = 'active'
        AND LOWER(c.name) IN ('telstra', 'optus', 'vodafone')
        LIMIT 1
    ";

    try {
        // First test if any plans exist
        $test_result = $conn->query($query);
        if (!$test_result || $test_result->num_rows === 0) {
            error_log("No plans found in initial test");
            return null;
        }

        // Now do the actual search with criteria
        $main_query = "
            SELECT 
                p.*,
                c.id as carrier_id,
                c.name as carrier_name,
                c.type as carrier_type,
                c.network_type
            FROM plans p
            JOIN carriers c ON p.carrier_id = c.id
            WHERE p.status = 'active'
            AND LOWER(c.name) IN ('telstra', 'optus', 'vodafone')
            AND (
                p.data_amount LIKE '%unlimited%'
                OR CAST(REGEXP_REPLACE(p.data_amount, '[^0-9.]', '') AS DECIMAL) >= ?
            )
            ORDER BY 
                CASE 
                    WHEN p.data_amount LIKE '%unlimited%' THEN 1
                    ELSE 2
                END,
                p.price DESC
            LIMIT 1
        ";

        $stmt = $conn->prepare($main_query);
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            return null;
        }

        $min_data = floatval($usage_req['min_data']);
        $stmt->bind_param('d', $min_data);

        if (!$stmt->execute()) {
            error_log("Execute failed: " . $stmt->error);
            return null;
        }

        $result = $stmt->get_result()->fetch_assoc();
        error_log("Found plan: " . print_r($result, true));
        return $result;
    } catch (Exception $e) {
        error_log("Error in getBestMatch: " . $e->getMessage());
        return null;
    }
}

function getBudgetOption($conn, $params, $usage_req, $main_plan)
{
    if (!$main_plan) return null;

    error_log("Starting basic getBudgetOption");

    // Find a budget plan
    $query = "
        SELECT 
            p.*,
            c.id as carrier_id,
            c.name as carrier_name,
            c.type as carrier_type,
            c.network_type
        FROM plans p
        JOIN carriers c ON p.carrier_id = c.id
        WHERE p.status = 'active'
        AND LOWER(c.name) NOT IN ('telstra', 'optus', 'vodafone')
        AND p.price < ?
        AND (
            p.data_amount LIKE '%unlimited%'
            OR CAST(REGEXP_REPLACE(p.data_amount, '[^0-9.]', '') AS DECIMAL) >= ?
        )
        ORDER BY p.price ASC
        LIMIT 1
    ";

    try {
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed in getBudgetOption: " . $conn->error);
            return null;
        }

        $max_price = $main_plan['price'] * 0.9; // 10% cheaper than main plan
        $min_data = floatval($usage_req['min_data']) * 0.8; // 20% less data requirement

        $stmt->bind_param('dd', $max_price, $min_data);

        if (!$stmt->execute()) {
            error_log("Execute failed in getBudgetOption: " . $stmt->error);
            return null;
        }

        $result = $stmt->get_result()->fetch_assoc();
        error_log("Found budget plan: " . print_r($result, true));
        return $result;
    } catch (Exception $e) {
        error_log("Error in getBudgetOption: " . $e->getMessage());
        return null;
    }
}

// Helper function to get area IDs
function getAreaIds($conn, $area_type)
{
    $area_query = "SELECT id FROM areas WHERE type = ?";
    $area_stmt = $conn->prepare($area_query);
    $area_stmt->bind_param('s', $area_type);
    $area_stmt->execute();
    $area_result = $area_stmt->get_result();

    if ($area_result->num_rows === 0) {
        $area_type = 'city';
        $area_stmt->bind_param('s', $area_type);
        $area_stmt->execute();
        $area_result = $area_stmt->get_result();
    }

    $area_ids = [];
    while ($row = $area_result->fetch_assoc()) {
        $area_ids[] = $row['id'];
    }

    return implode(',', $area_ids);
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


// function enhancePlanWithContext($conn, $plan, $params, $usage_req)
// {
//     if (!$plan) return null;

//     // Get required additional data
//     $stores = getNearbyStores($conn, $params['area'], $plan['carrier_id']);
//     $experiences = getRealWorldExperiences($conn, $plan['id'], $params['area']);
//     $problems = getCommonProblems($conn, $plan['carrier_id'], $params['area']);

//     // Build reasons array based on plan features
//     $reasons = [];
//     if ($plan['coverage_rating'] >= 4) {
//         $reasons[] = "Strong coverage in " . ucfirst($params['area']) . " area";
//     }
//     if ($plan['data_amount'] === 'Unlimited' || strpos(strtolower($plan['data_amount']), 'unlimited') !== false) {
//         $reasons[] = "Unlimited data for worry-free usage";
//     } else {
//         $reasons[] = "Enough data for " . $usage_req['activities'];
//     }
//     if (!empty($stores)) {
//         $reasons[] = "Easy activation at nearby store" . (count($stores) > 1 ? 's' : '');
//     }

//     // Build warnings array
//     $warnings = [];
//     if ($plan['peak_hour_impact'] === 'significant') {
//         $warnings[] = "Network may slow down during peak hours (usually 6-8pm)";
//     }
//     if ($problems) {
//         foreach ($problems as $problem) {
//             if ($problem['frequency'] === 'very_common') {
//                 $warnings[] = $problem['title'];
//             }
//         }
//     }

//     // Create the exact structure the template expects
//     return [
//         'price' => $plan['price'],
//         'carrier_name' => $plan['carrier_name'],
//         'data_amount' => $plan['data_amount'],
//         'reasons' => $reasons,
//         'stores' => $stores,
//         'warnings' => $warnings,
//         'experiences' => $experiences,
//         'coverage_rating' => $plan['coverage_rating'],
//         'reliability_score' => $plan['reliability_score'],
//         'data_speed_min' => $plan['data_speed_min'],
//         'data_speed_max' => $plan['data_speed_max'],
//         'network_type' => $plan['network_type'],
//         'carrier_type' => $plan['carrier_type'],
//         'value_score' => $plan['value_score'] ?? null,
//         'peak_hour_impact' => $plan['peak_hour_impact'],
//         'data_per_day' => $usage_req['daily_estimate'],
//         'typical_activities' => $usage_req['activities']
//     ];
// }

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
