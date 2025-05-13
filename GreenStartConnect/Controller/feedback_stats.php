<?php
header('Content-Type: application/json');

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 0);
error_reporting(E_ALL);

try {
    // Include your database connection
    require_once __DIR__ . '/../config/config.php';
    
     $dbConfig = new Config();
        $db = $dbConfig->getConnection(); // Use the connection already created in config.php

    // Initialize stats array
    $stats = [
        'feedback' => [
            'total' => 0,
            'types' => [
                'complaint' => 0,
                'suggestion' => 0,
                'praise' => 0,
                'question' => 0
            ]
        ],
        'response' => [
            'total' => 0,
            'types' => [
                'solution' => 0,
                'information' => 0,
                'rejection' => 0,
                'follow_up' => 0
            ]
        ],
        'timeline' => [
            'labels' => [],
            'data' => []
        ]
    ];

    // Get feedback counts
    $query = "SELECT type, COUNT(*) as count FROM feedbacks GROUP BY type";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $stats['feedback']['total'] += $row['count'];
        if (isset($stats['feedback']['types'][$row['type']])) {
            $stats['feedback']['types'][$row['type']] = $row['count'];
        }
    }

    // Get response counts
    $query = "SELECT response_type AS type, COUNT(*) as count FROM response GROUP BY response_type";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $stats['response']['total'] += $row['count'];
        if (isset($stats['response']['types'][$row['type']])) {
            $stats['response']['types'][$row['type']] = $row['count'];
        }
    }

    // Generate timeline data (last 7 days)
    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $stats['timeline']['labels'][] = date('M j', strtotime("-$i days"));
        
        $query = "SELECT COUNT(*) as count FROM feedbacks WHERE DATE(created_at) = :date";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['timeline']['data'][] = (int)$row['count'];
    }

    echo json_encode($stats);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
?>