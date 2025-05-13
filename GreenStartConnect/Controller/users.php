<?php
header("Access-Control-Allow-Origin: *"); // Only for dev
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

require_once __DIR__ . '/../config/config.php';

class UserController {
    private $pdo;

    public function __construct() {
        $db = new Config();
        $this->pdo = $db->getConnection();
    }

    public function getUsersBasicInfo() {
        try {
            $stmt = $this->pdo->prepare("SELECT id, nom, adresse FROM clients ORDER BY date_creation DESC");
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($users);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to fetch users: ' . $e->getMessage()]);
        }
    }
}

$controller = new UserController();
$controller->getUsersBasicInfo();
?>
