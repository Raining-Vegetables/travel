<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/access-db.php';

session_start();

// Get the anchor point
$anchor_point = $_GET['anchor_point'] ?? '';

if (empty($anchor_point)) {
    header('Location: ../neighborhood-match.php?error=missing_anchor');
    exit;
}

// Store initial data in session
$_SESSION['neighborhood_data'] = [
    'anchor_point' => $anchor_point,
    'step' => 1
];

// Redirect to the lifestyle questions page
header('Location: ../neighborhood-questions.php');
exit;
