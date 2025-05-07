<?php
require_once 'config.php';

class UserController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getUsersBasicInfo() {
        header('Content-Type: application/json');
        try {
            $stmt = $this->pdo->query("SELECT id, nom, adresse FROM user");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($users);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to fetch users: ' . $e->getMessage()]);
        }
    }
}

// Utilisation
$controller = new UserController($pdo);
$controller->getUsersBasicInfo();
?>
