<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';

class FeedbackController {
    private $db;

    public function __construct() {
          $db = new Config();
        $this->pdo = $db->getConnection();
    }

    public function createFeedback($data) {
        try {
            $stmt = $this->db->prepare("INSERT INTO feedbacks (user_id, type, content) VALUES (?, ?, ?)");
            $stmt->execute([$data['user_id'], $data['type'], $data['content']]);
            
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create feedback: ' . $e->getMessage()]);
        }
    }

    public function getAllFeedbacks() {
        try {
            $stmt = $this->db->query("
                SELECT f.*, u.nom as user_name 
                FROM feedbacks f
                JOIN user u ON f.user_id = u.id
                ORDER BY f.created_at DESC
            ");
            $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode($feedbacks);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to fetch feedbacks: ' . $e->getMessage()]);
        }
    }
}

// Utilisation
$controller = new FeedbackController($db);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $controller->createFeedback($data);
        break;
    case 'GET':
        $controller->getAllFeedbacks();
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
?>
