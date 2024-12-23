<?php
require_once '../config/access-db.php';
require_once '../config/config.php';
require_once '../models/PlanModel.php';

// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log the start of the process
error_log("Starting recommendation process", 3, "errors.log");

// Get and validate parameters
$params = [
    'area' => $_GET['area'] ?? null,
    'usage_type' => $_GET['usage_type'] ?? null,
    'duration_days' => $_GET['duration_days'] ?? null
];

error_log("Raw parameters received: " . print_r($params, true), 3, "errors.log");

// Validate required parameters
if (!$params['area'] || !$params['usage_type'] || !$params['duration_days']) {
    error_log("Missing required parameters", 3, "errors.log");
    $_SESSION['error'] = 'Please fill in all required fields';
    header('Location: ../index.php');
    exit;
}

// Convert duration to days
$duration_map = [
    'short' => 14,  // 2 weeks
    'medium' => 28, // 4 weeks
    'long' => 90    // 3 months
];
$params['duration'] = $duration_map[$params['duration_days']] ?? 28;

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

$usage_req = $usage_patterns[$params['usage_type']] ?? $usage_patterns['regular'];
error_log("Usage requirements: " . print_r($usage_req, true), 3, "errors.log");

try {
    // Debug database connection
    if (!$conn) {
        error_log("Database connection failed", 3, "errors.log");
        throw new Exception("Database connection failed");
    }

    // Validate area type
    $valid_areas = ['city', 'eastern', 'western', 'northern', 'southern'];
    if (!in_array($params['area'], $valid_areas)) {
        error_log("Invalid area type: " . $params['area'], 3, "errors.log");
        $params['area'] = 'city'; // Default to city if invalid
    }

    // Get recommendations
    $raw_recommendations = getRecommendedPlans($conn, $params);

    if (!$raw_recommendations) {
        error_log("No recommendations found", 3, "errors.log");
        $_SESSION['error'] = 'No plans found matching your criteria. Please try different options.';
        header('Location: ../index.php');
        exit;
    }

    // Structure the recommendations correctly for the template
    $formatted_recommendations = [
        'recommended' => enhancePlanWithContext($conn, $raw_recommendations['recommended'], $params, $usage_req),
        'budget' => enhancePlanWithContext($conn, $raw_recommendations['budget'], $params, $usage_req),
        'premium' => enhancePlanWithContext($conn, $raw_recommendations['premium'], $params, $usage_req),
        'usage_context' => $usage_req
    ];

    error_log("Final formatted recommendations: " . print_r($formatted_recommendations, true), 3, "errors.log");

    // Store in session with correct structure
    $_SESSION['recommendation_data'] = [
        'recommendations' => $formatted_recommendations,
        'search_params' => [
            'area' => $params['area'],
            'duration_days' => $params['duration'],
            'usage_type' => $params['usage_type']
        ]
    ];

    // Redirect to recommendations view
    header('Location: ../views/recommendations.php');
    exit;
} catch (Exception $e) {
    error_log("Error in recommendation process: " . $e->getMessage(), 3, "errors.log");
    error_log("Stack trace: " . $e->getTraceAsString(), 3, "errors.log");
    $_SESSION['error'] = 'An error occurred while processing your request. Please try again.';
    header('Location: ../index.php');
    exit;
}

// In RecommendationController.php
function processRecommendations($conn, $params)
{
    // Add logging to debug parameters
    error_log('Processing recommendations with params: ' . print_r($params, true));

    // Translate duration string to days
    $params['duration'] = translateDuration($params['duration_days']);

    // Get recommendations
    $recommendations = getRecommendedPlans($conn, $params);

    if ($recommendations) {
        $_SESSION['recommendation_data'] = [
            'recommendations' => $recommendations,
            'search_params' => $params
        ];
        return true;
    }
    return false;
}

function translateDuration($duration_string)
{
    switch ($duration_string) {
        case 'short':
            return 14;  // Less than 2 weeks
        case 'medium':
            return 28;  // 2-4 weeks
        case 'long':
            return 31;  // More than a month
        default:
            return 28;  // Default to medium stay
    }
}
