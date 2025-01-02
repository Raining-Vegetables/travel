<?php
// models/PlanModel.php

class PlanModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function findPlanByDetails($carrier, $dataAmount, $price, $tolerance = 0.1)
    {
        // Find the closest matching plan in the database
        $minPrice = $price * (1 - $tolerance);
        $maxPrice = $price * (1 + $tolerance);

        $query = "
            SELECT p.*, c.name as carrier_name, c.type as carrier_type
            FROM plans p
            JOIN carriers c ON p.carrier_id = c.id
            WHERE c.name LIKE ?
            AND p.status = 'active'
            AND p.is_tourist_friendly = 1
            AND p.price BETWEEN ? AND ?
            AND (
                p.data_amount = ?
                OR (
                    p.data_amount LIKE '%unlimited%' AND ? LIKE '%unlimited%'
                )
            )
            LIMIT 1
        ";

        $carrierPattern = '%' . $carrier . '%';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sddss", $carrierPattern, $minPrice, $maxPrice, $dataAmount, $dataAmount);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function getCarrierDetails($carrierId)
    {
        $query = "
            SELECT 
                c.*,
                pc.name as parent_carrier_name
            FROM carriers c
            LEFT JOIN carriers pc ON c.parent_carrier_id = pc.id
            WHERE c.id = ?
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $carrierId);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function findFallbackPlan($carrier, $maxPrice)
    {
        // Find any suitable plan from the carrier within price range
        $query = "
            SELECT p.*, c.name as carrier_name, c.type as carrier_type
            FROM plans p
            JOIN carriers c ON p.carrier_id = c.id
            WHERE c.name LIKE ?
            AND p.status = 'active'
            AND p.is_tourist_friendly = 1
            AND p.price <= ?
            ORDER BY p.price DESC
            LIMIT 1
        ";

        $carrierPattern = '%' . $carrier . '%';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sd", $carrierPattern, $maxPrice);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function getSupportInfo($carrierId)
    {
        $query = "
            SELECT 
                support_phone,
                support_hours,
                activation_process,
                tourist_support_available
            FROM carrier_support
            WHERE carrier_id = ?
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $carrierId);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }
}
