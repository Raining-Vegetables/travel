<?php
require_once '../config/access-db.php';
require_once '../config/config.php';
require_once '../models/PlanModel.php';
require_once '../services/AiService.php';

// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Log the start of the process
error_log("Starting recommendation process");

// Get and validate parameters
$params = [
    'area' => $_GET['area'] ?? null,
    'usage_type' => $_GET['usage_type'] ?? null,
    'duration_days' => $_GET['duration_days'] ?? null
];

error_log("Raw parameters received: " . print_r($params, true));

// Validate required parameters
foreach ($params as $key => $value) {
    if (!$value) {
        error_log("Missing required parameter: {$key}");
        $_SESSION['error'] = 'Please fill in all required fields';
        header('Location: ../index.php');
        exit;
    }
}

// Convert duration to days
$duration_map = [
    'short' => 14,  // 2 weeks
    'medium' => 28, // 4 weeks
    'long' => 90    // 3 months
];

$params['duration'] = $duration_map[$params['duration_days']] ?? 28;

// Map usage patterns to data requirements
$usage_patterns = [
    'basic' => [
        'min_data' => 5,
        'ideal_data' => 10,
        'description' => 'maps and basic messaging',
        'activities' => 'Maps, WhatsApp, basic web browsing',
        'daily_estimate' => '300MB-500MB per day'
    ],
    'regular' => [
        'min_data' => 15,
        'ideal_data' => 30,
        'description' => 'social media and video calls',
        'activities' => 'Instagram, TikTok, daily video calls',
        'daily_estimate' => '1GB-2GB per day'
    ],
    'heavy' => [
        'min_data' => 35,
        'ideal_data' => 'Unlimited',
        'description' => 'streaming and constant connectivity',
        'activities' => 'Netflix, work calls, hotspot usage',
        'daily_estimate' => '3GB+ per day'
    ]
];

$usage_req = $usage_patterns[$params['usage_type']] ?? $usage_patterns['regular'];

try {
    // Verify database connection
    if (!$conn) {
        throw new Exception("Database connection failed");
    }

    // Validate area type
    $valid_areas = ['city', 'eastern', 'western', 'northern', 'southern'];
    if (!in_array($params['area'], $valid_areas)) {
        $params['area'] = 'city';
    }

    // Get base recommendations
    $recommendations = getRecommendedPlans($conn, $params);
    error_log("Raw recommendations: " . print_r($recommendations, true));

    if (!$recommendations) {
        error_log("No recommendations found for params: " . print_r($params, true), 3, "errors.log");
        $_SESSION['error'] = 'No plans found matching your criteria. Please try different options.';
        header('Location: ../index.php');
        exit;
    }

    // Initialize AI service and get enhanced recommendations
    try {
        $aiService = new AiService();

        // Prepare data for AI analysis
        // Separate the data into planData and userPreferences
        $planData = $recommendations;  // All the plan data
        $userPreferences = [
            'location' => $params['area'],
            'usage_type' => $params['usage_type'],
            'duration' => $params['duration_days'],
            'usage_pattern' => $usage_req
        ];

        // Get AI-enhanced insights
        $aiInsights = $aiService->enhanceRecommendations($planData, $userPreferences);

        if ($aiInsights) {
            $recommendations['ai_insights'] = $aiInsights;
        }
    } catch (Exception $e) {
        error_log("AI Service Error: " . $e->getMessage());
        // Continue without AI insights if there's an error
    }

    // Store in session
    $_SESSION['recommendation_data'] = [
        'recommendations' => [
            'recommended' => $recommendations['recommended'],
            'budget' => $recommendations['budget'],
            'premium' => isset($recommendations['premium']) ? $recommendations['premium'] : null,
            'usage_context' => $usage_req,
            'ai_insights' => $recommendations['ai_insights'] ?? null
        ],
        'search_params' => [
            'area' => $params['area'],
            'duration_days' => $params['duration'],
            'usage_type' => $params['usage_type']
        ]
    ];

    error_log("Final session data: " . print_r($_SESSION['recommendation_data'], true));

    // Redirect to recommendations view
    header('Location: ../views/recommendations.php');
    exit;
} catch (Exception $e) {
    error_log("Controller error: " . $e->getMessage(), 3, "errors.log");
    error_log("Stack trace: " . $e->getTraceAsString(), 3, "errors.log");
    $_SESSION['error'] = 'An error occurred while processing your request. Please try again.';
    header('Location: ../index.php');
    exit;
}
