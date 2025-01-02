<?php
// controllers/RecommendationController.php

require_once __DIR__ . '/../config/access-db.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../services/AiService.php';

class RecommendationController
{
    private $conn;
    private $aiService;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->aiService = new AiService();
    }

    public function processRequest()
    {
        try {
            error_log("Starting recommendation process", 3, "errors.log");

            // Validate and get parameters
            $params = $this->validateParameters();

            // 1. Get AI Recommendations
            error_log("Getting AI recommendations...", 3, "errors.log");
            $aiRecommendations = $this->aiService->getRecommendation($params);
            error_log("AI recommendations received: " . print_r($aiRecommendations, true), 3, "errors.log");

            if (!$aiRecommendations) {
                throw new Exception("Failed to get AI recommendations");
            }

            // 2. Enrich recommendations with database information
            $enrichedRecommendations = $this->enrichRecommendations($aiRecommendations, $params);
            error_log("Enriched recommendations: " . print_r($enrichedRecommendations, true), 3, "errors.log");

            // 3. Store in session and redirect
            $this->storeAndRedirect($enrichedRecommendations, $params);
        } catch (Exception $e) {
            error_log("Controller error: " . $e->getMessage(), 3, "errors.log");
            error_log("Stack trace: " . $e->getTraceAsString(), 3, "errors.log");
            $_SESSION['error'] = 'An error occurred while processing your request. Please try again.';
            header('Location: ../index.php');
            exit;
        }
    }

    private function validateParameters()
    {
        $params = [
            'area' => $_GET['area'] ?? null,
            'usage_type' => $_GET['usage_type'] ?? null,
            'duration_days' => $_GET['duration_days'] ?? null
        ];

        foreach ($params as $key => $value) {
            if (!$value) {
                throw new Exception("Missing required parameter: {$key}");
            }
        }

        return $params;
    }

    private function enrichRecommendations($aiRecommendations, $params)
    {
        error_log("Starting enrichment process", 3, "errors.log");
        $enriched = [];

        foreach (['recommended', 'budget', 'premium'] as $type) {
            if (isset($aiRecommendations[$type])) {
                try {
                    error_log("Enriching $type plan", 3, "errors.log");
                    $plan = $aiRecommendations[$type];

                    // Start with the AI recommendation data
                    $enriched[$type] = $plan;

                    // Add required fields that the view expects
                    $enriched[$type]['carrier_name'] = $plan['carrier'];
                    $enriched[$type]['plan_explanation'] = $this->formatPlanReasoning($plan['reasoning'], $type); // Add this line
                    $enriched[$type]['features'] = [
                        "No Lock-in Contract",
                        "Instant Activation",
                        $plan['data_amount'] . " Data",
                        "Australian Mobile Number"
                    ];

                    // Try to get additional data from database
                    try {
                        $carrierId = $this->getCarrierId($plan['carrier']);
                        if ($carrierId) {
                            $coverageDetails = $this->getCoverageDetails($carrierId, $params['area']);
                            $stores = $this->getNearbyStores($carrierId, $params['area']);
                            $honestInsights = $this->getHonestInsights($carrierId, $params['area']);

                            // Add database info if available
                            $enriched[$type] = array_merge($enriched[$type], [
                                'stores' => $stores,
                                'data_speed_min' => $coverageDetails['data_speed_min'] ?? 25,
                                'data_speed_max' => $coverageDetails['data_speed_max'] ?? 100,
                                'coverage_rating' => $coverageDetails['rating'] ?? 4.0,
                                'honest_insights' => $honestInsights,
                                'support_info' => $this->getSupportInfo($carrierId)
                            ]);
                        }
                    } catch (Exception $e) {
                        error_log("Database enrichment failed for $type plan: " . $e->getMessage(), 3, "errors.log");
                        // Add fallback values if database operations fail
                        $enriched[$type] = array_merge($enriched[$type], [
                            'stores' => [],
                            'data_speed_min' => 25,
                            'data_speed_max' => 100,
                            'coverage_rating' => 4.0,
                            'honest_insights' => [],
                            'support_info' => [
                                'balance_check' => '*100#',
                                'customer_service' => '1300 000 000'
                            ]
                        ]);
                    }
                } catch (Exception $e) {
                    error_log("Error enriching $type plan: " . $e->getMessage(), 3, "errors.log");
                }
            }
        }

        return $enriched;
    }

    private function getCarrierId($carrierName)
    {
        $stmt = $this->conn->prepare("SELECT id FROM carriers WHERE LOWER(name) LIKE LOWER(?)");
        $carrierPattern = '%' . $carrierName . '%';
        $stmt->bind_param("s", $carrierPattern);
        $stmt->execute();
        $result = $stmt->get_result();
        $carrier = $result->fetch_assoc();
        return $carrier ? $carrier['id'] : null;
    }

    private function getCoverageDetails($carrierId, $area)
    {
        $stmt = $this->conn->prepare("
            SELECT rating, data_speed_min, data_speed_max
            FROM coverage 
            WHERE carrier_id = ? AND area_type = ?
        ");
        $stmt->bind_param("is", $carrierId, $area);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    private function getNearbyStores($carrierId, $area)
    {
        $stmt = $this->conn->prepare("
            SELECT * FROM stores 
            WHERE carrier_id = ? AND area_type = ? AND status = 'active'
            LIMIT 2
        ");
        $stmt->bind_param("is", $carrierId, $area);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    private function getHonestInsights($carrierId, $area)
    {
        $stmt = $this->conn->prepare("
            SELECT insight_type, marketing_claim, reality, recommendation
            FROM honest_insights 
            WHERE carrier_id = ? AND area_type = ?
            LIMIT 2
        ");
        $stmt->bind_param("is", $carrierId, $area);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    private function getSupportInfo($carrierId)
    {
        if (!$carrierId) {
            return [
                'balance_check' => '*100#',
                'customer_service' => '1300 000 000'
            ];
        }

        $stmt = $this->conn->prepare("
            SELECT balance_check, customer_service
            FROM carrier_support 
            WHERE carrier_id = ?
        ");
        $stmt->bind_param("i", $carrierId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        return $result ?: [
            'balance_check' => '*100#',
            'customer_service' => '1300 000 000'
        ];
    }

    private function formatPlanReasoning($reasoning, $planType)
    {
        if (!is_array($reasoning)) {
            return $this->getFallbackExplanation($planType);
        }

        // Convert bullet points into a cohesive paragraph
        $explanation = implode(' ', array_map(function ($reason) {
            return trim($reason, '.');
        }, $reasoning));

        // Add type-specific context
        switch ($planType) {
            case 'recommended':
                $explanation .= " This plan provides the best balance of features and value for your needs.";
                break;
            case 'budget':
                $explanation .= " While this is our budget option, it still provides reliable service for basic needs.";
                break;
            case 'premium':
                $explanation .= " This premium plan is ideal if you need the absolute best service quality.";
                break;
        }

        return $explanation;
    }

    private function getFallbackExplanation($planType)
    {
        switch ($planType) {
            case 'recommended':
                return "This plan offers the best balance of coverage and value. It provides reliable service with enough data for your needs while maintaining cost-effectiveness.";
            case 'budget':
                return "This budget-friendly option provides essential services at a lower cost. While it may have some limitations, it's suitable for basic usage needs.";
            case 'premium':
                return "This premium plan offers the highest level of service with priority network access and additional features. Ideal for users who need the best possible service.";
            default:
                return "This plan has been selected based on your specific requirements and usage patterns.";
        }
    }

    private function storeAndRedirect($recommendations, $params)
    {
        $_SESSION['recommendation_data'] = [
            'recommendations' => [
                'recommended' => $recommendations['recommended'] ?? null,
                'budget' => $recommendations['budget'] ?? null,
                'premium' => $recommendations['premium'] ?? null,
                'usage_context' => [
                    'location' => $params['area'],
                    'duration' => $params['duration_days'],
                    'usage_type' => $params['usage_type']
                ]
            ],
            'search_params' => $params
        ];

        header('Location: ../views/recommendations.php');
        exit;
    }
}

// Initialize and process request
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$controller = new RecommendationController($conn);
$controller->processRequest();
