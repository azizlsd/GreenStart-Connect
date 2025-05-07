<?php
// controllers/back-office/config.php

// Database credentials
$host    = 'localhost';
$db      = 'feedbacks';
$user    = 'root';
$pass    = '';
$charset = 'utf8mb4';

// DSN and PDO options
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_PERSISTENT         => false,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // This $pdo variable is now in the global scope
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // In production you’d log this instead
    http_response_code(500);
    echo json_encode([
        'error' => 'Database connection failed: ' . $e->getMessage()
    ]);
    exit;
}
