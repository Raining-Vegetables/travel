<?php
// models/PlanModel.php

// class PlanModel
// {
//     private $conn;

//     public function __construct($conn)
//     {
//         $this->conn = $conn;
//     }

//     public function findPlanByDetails($carrier, $dataAmount, $price, $tolerance = 0.1)
//     {
//         // Find the closest matching plan in the database
//         $minPrice = $price * (1 - $tolerance);
//         $maxPrice = $price * (1 + $tolerance);

//         $query = "
//             SELECT p.*, c.name as carrier_name, c.type as carrier_type
//             FROM plans p
//             JOIN carriers c ON p.carrier_id = c.id
//             WHERE c.name LIKE ?
//             AND p.status = 'active'
//             AND p.is_tourist_friendly = 1
//             AND p.price BETWEEN ? AND ?
//             AND (
//                 p.data_amount = ?
//                 OR (
//                     p.data_amount LIKE '%unlimited%' AND ? LIKE '%unlimited%'
//                 )
//             )
//             LIMIT 1
//         ";

//         $carrierPattern = '%' . $carrier . '%';
//         $stmt = $this->conn->prepare($query);
//         $stmt->bind_param("sddss", $carrierPattern, $minPrice, $maxPrice, $dataAmount, $dataAmount);
//         $stmt->execute();

//         return $stmt->get_result()->fetch_assoc();
//     }

//     public function getCarrierDetails($carrierId)
//     {
//         $query = "
//             SELECT 
//                 c.*,
//                 pc.name as parent_carrier_name
//             FROM carriers c
//             LEFT JOIN carriers pc ON c.parent_carrier_id = pc.id
//             WHERE c.id = ?
//         ";

//         $stmt = $this->conn->prepare($query);
//         $stmt->bind_param("i", $carrierId);
//         $stmt->execute();

//         return $stmt->get_result()->fetch_assoc();
//     }

//     public function findFallbackPlan($carrier, $maxPrice)
//     {
//         // Find any suitable plan from the carrier within price range
//         $query = "
//             SELECT p.*, c.name as carrier_name, c.type as carrier_type
//             FROM plans p
//             JOIN carriers c ON p.carrier_id = c.id
//             WHERE c.name LIKE ?
//             AND p.status = 'active'
//             AND p.is_tourist_friendly = 1
//             AND p.price <= ?
//             ORDER BY p.price DESC
//             LIMIT 1
//         ";

//         $carrierPattern = '%' . $carrier . '%';
//         $stmt = $this->conn->prepare($query);
//         $stmt->bind_param("sd", $carrierPattern, $maxPrice);
//         $stmt->execute();

//         return $stmt->get_result()->fetch_assoc();
//     }

//     public function getSupportInfo($carrierId)
//     {
//         $query = "
//             SELECT 
//                 support_phone,
//                 support_hours,
//                 activation_process,
//                 tourist_support_available
//             FROM carrier_support
//             WHERE carrier_id = ?
//         ";

//         $stmt = $this->conn->prepare($query);
//         $stmt->bind_param("i", $carrierId);
//         $stmt->execute();

//         return $stmt->get_result()->fetch_assoc();
//     }
// }



class PlanModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function enrichPlanData($plan, $area)
    {
        if (!$plan) return null;

        // Get carrier ID
        $carrierId = $this->getCarrierId($plan['carrier']);
        if (!$carrierId) return $plan;

        // Add store information
        $stores = $this->getStores($carrierId, $area);
        $plan['stores'] = $stores;

        // Add honest insights
        $insights = $this->getHonestInsights($carrierId, $area);
        $plan['honest_insights'] = $insights;

        // Add coverage details
        $coverage = $this->getCoverageDetails($carrierId, $area);
        if ($coverage) {
            $plan['data_speed_min'] = $coverage['data_speed_min'];
            $plan['data_speed_max'] = $coverage['data_speed_max'];
            $plan['coverage_rating'] = $coverage['rating'];
        }

        // Add support information
        $support = $this->getSupportInfo($carrierId);
        if ($support) {
            $plan['support_info'] = [
                'balance_check' => $support['balance_check'],
                'customer_service' => $support['customer_service']
            ];
        }

        return $plan;
    }

    private function getCarrierId($carrierName)
    {
        $stmt = $this->conn->prepare("
            SELECT id 
            FROM carriers 
            WHERE LOWER(name) LIKE LOWER(?)
        ");
        $carrierPattern = '%' . $carrierName . '%';
        $stmt->bind_param("s", $carrierPattern);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? $result['id'] : null;
    }

    private function getStores($carrierId, $area)
    {
        try {
            // First get the area_id from the areas table
            $areaStmt = $this->conn->prepare("
                SELECT id 
                FROM areas 
                WHERE type = ?
            ");

            if (!$areaStmt) {
                throw new Exception("Failed to prepare area query: " . $this->conn->error);
            }

            $areaStmt->bind_param("s", $area);
            $areaStmt->execute();
            $areaResult = $areaStmt->get_result()->fetch_assoc();

            if (!$areaResult) {
                throw new Exception("Area not found");
            }

            $areaId = $areaResult['id'];

            // Now get the stores with the area_id
            $query = "
                SELECT 
                    s.*,
                    c.name as carrier_name,
                    'main' as store_category,
                    bs.name as backup_store_name,
                    bs.address as backup_store_address,
                    bs.hours as backup_store_hours,
                    bs.distance_meters as backup_store_distance,
                    bs.walking_time as backup_store_walking_time
                FROM stores s
                JOIN carriers c ON s.carrier_id = c.id
                LEFT JOIN backup_stores bs ON bs.main_store_id = s.id AND bs.status = 'active'
                WHERE s.carrier_id = ?
                AND s.area_id = ?
                AND s.status = 'active'
                AND s.has_instant_activation = 1
                ORDER BY s.type = 'official' DESC
                LIMIT 2
            ";

            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Failed to prepare store query: " . $this->conn->error);
            }

            $stmt->bind_param("ii", $carrierId, $areaId);
            $stmt->execute();
            $stores = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            // If no stores found, return fallback data
            if (empty($stores)) {
                return [[
                    'name' => "Main " . ucfirst($this->getCarrierName($carrierId)) . " Store",
                    'address' => "Sydney " . ucfirst($area) . " Area",
                    'hours' => json_encode(['weekday' => '9AM-6PM', 'weekend' => '10AM-5PM']),
                    'type' => 'official',
                    'has_instant_activation' => 1,
                    'speaks_english' => 1,
                    'status' => 'active',
                    'backup_store' => [
                        'name' => "Alternate Store Location",
                        'address' => "Sydney " . ucfirst($area) . " Alternative Area",
                        'hours' => '9AM-7PM',
                        'distance_meters' => 1500,
                        'walking_time' => '15-20 minutes'
                    ]
                ]];
            }

            // Format stores to match view expectations
            return array_map(function ($store) {
                if ($store['backup_store_name']) {
                    $store['backup_store'] = [
                        'name' => $store['backup_store_name'],
                        'address' => $store['backup_store_address'],
                        'hours' => $store['backup_store_hours'],
                        'distance_meters' => $store['backup_store_distance'],
                        'walking_time' => $store['backup_store_walking_time']
                    ];
                }

                // Remove the flat backup store fields
                unset($store['backup_store_name']);
                unset($store['backup_store_address']);
                unset($store['backup_store_hours']);
                unset($store['backup_store_distance']);
                unset($store['backup_store_walking_time']);

                return $store;
            }, $stores);
        } catch (Exception $e) {
            error_log("Store lookup failed: " . $e->getMessage(), 3, "errors.log");
            // Return fallback data in case of error
            return [[
                'name' => "Main " . ucfirst($this->getCarrierName($carrierId)) . " Store",
                'address' => "Sydney " . ucfirst($area) . " Area",
                'hours' => json_encode(['weekday' => '9AM-6PM', 'weekend' => '10AM-5PM']),
                'type' => 'official',
                'has_instant_activation' => 1,
                'speaks_english' => 1,
                'status' => 'active',
                'backup_store' => [
                    'name' => "Alternate Store Location",
                    'address' => "Sydney " . ucfirst($area) . " Alternative Area",
                    'hours' => '9AM-7PM',
                    'distance_meters' => 1500,
                    'walking_time' => '15-20 minutes'
                ]
            ]];
        }
    }

    private function getHonestInsights($carrierId, $area)
    {
        try {
            // First get the area_id
            $areaStmt = $this->conn->prepare("
                SELECT id 
                FROM areas 
                WHERE type = ?
            ");

            if (!$areaStmt) {
                throw new Exception("Failed to prepare area query: " . $this->conn->error);
            }

            $areaStmt->bind_param("s", $area);
            $areaStmt->execute();
            $areaResult = $areaStmt->get_result()->fetch_assoc();

            if (!$areaResult) {
                throw new Exception("Area not found");
            }

            $areaId = $areaResult['id'];

            // Now get the insights
            $stmt = $this->conn->prepare("
                SELECT 
                    insight_type,
                    marketing_claim,
                    reality,
                    recommendation
                FROM honest_insights 
                WHERE carrier_id = ? 
                AND area_id = ?
                LIMIT 3
            ");

            if (!$stmt) {
                throw new Exception("Failed to prepare insights query: " . $this->conn->error);
            }

            $stmt->bind_param("ii", $carrierId, $areaId);
            $stmt->execute();
            $insights = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            // If no insights found, return fallback data
            if (empty($insights)) {
                return [[
                    'insight_type' => 'coverage',
                    'marketing_claim' => 'Best network coverage in Sydney',
                    'reality' => 'Coverage is generally good in populated areas but may vary in some locations',
                    'recommendation' => 'Download offline maps when heading to less populated areas'
                ]];
            }

            return $insights;
        } catch (Exception $e) {
            error_log("Insights lookup failed: " . $e->getMessage(), 3, "errors.log");
            return [[
                'insight_type' => 'coverage',
                'marketing_claim' => 'Best network coverage in Sydney',
                'reality' => 'Coverage is generally good in populated areas but may vary in some locations',
                'recommendation' => 'Download offline maps when heading to less populated areas'
            ]];
        }
    }

    private function getCarrierName($carrierId)
    {
        try {
            $stmt = $this->conn->prepare("
            SELECT name 
            FROM carriers 
            WHERE id = ?
        ");

            if (!$stmt) {
                throw new Exception("Failed to prepare carrier query: " . $this->conn->error);
            }

            $stmt->bind_param("i", $carrierId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();

            return $result['name'] ?? 'Unknown Carrier';
        } catch (Exception $e) {
            error_log("Carrier name lookup failed: " . $e->getMessage(), 3, "errors.log");
            return 'Unknown Carrier';
        }
    }



    private function getCoverageDetails($carrierId, $area)
    {
        try {
            // First get the area_id
            $areaStmt = $this->conn->prepare("
            SELECT id 
            FROM areas 
            WHERE type = ?
        ");

            if (!$areaStmt) {
                throw new Exception("Failed to prepare area query: " . $this->conn->error);
            }

            $areaStmt->bind_param("s", $area);
            $areaStmt->execute();
            $areaResult = $areaStmt->get_result()->fetch_assoc();

            if (!$areaResult) {
                throw new Exception("Area not found");
            }

            $areaId = $areaResult['id'];

            // Now get the coverage details
            $stmt = $this->conn->prepare("
            SELECT 
                rating,
                data_speed_min,
                data_speed_max
            FROM coverage 
            WHERE carrier_id = ? 
            AND area_id = ?
        ");

            if (!$stmt) {
                throw new Exception("Failed to prepare coverage query: " . $this->conn->error);
            }

            $stmt->bind_param("ii", $carrierId, $areaId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();

            return $result ?: [
                'rating' => 4.0,
                'data_speed_min' => 25,
                'data_speed_max' => 100
            ];
        } catch (Exception $e) {
            error_log("Coverage lookup failed: " . $e->getMessage(), 3, "errors.log");
            return [
                'rating' => 4.0,
                'data_speed_min' => 25,
                'data_speed_max' => 100
            ];
        }
    }

    private function getSupportInfo($carrierId)
    {
        try {
            $stmt = $this->conn->prepare("
            SELECT 
                support_phone,
                support_email,
                support_hours
            FROM carriers 
            WHERE id = ?
        ");

            if (!$stmt) {
                throw new Exception("Failed to prepare support info query: " . $this->conn->error);
            }

            $stmt->bind_param("i", $carrierId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();

            if ($result) {
                // Convert support_hours JSON string to array if needed
                $hours = json_decode($result['support_hours'], true);

                return [
                    'balance_check' => '*100#',  // Default since it's not in carriers table
                    'customer_service' => $result['support_phone'],
                    'support_hours' => $hours ?: ['weekday' => '9AM-5PM', 'weekend' => '10AM-4PM']
                ];
            }

            return [
                'balance_check' => '*100#',
                'customer_service' => '1300 000 000',
                'support_hours' => ['weekday' => '9AM-5PM', 'weekend' => '10AM-4PM']
            ];
        } catch (Exception $e) {
            error_log("Support info lookup failed: " . $e->getMessage(), 3, "errors.log");
            return [
                'balance_check' => '*100#',
                'customer_service' => '1300 000 000',
                'support_hours' => ['weekday' => '9AM-5PM', 'weekend' => '10AM-4PM']
            ];
        }
    }
}
