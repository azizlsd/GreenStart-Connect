<?php
require_once __DIR__ . '/../config/config.php';

class FeedbackController {
    private $pdo;

    public function __construct() {
         $db = new Config();
        $this->pdo = $db->getConnection();
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET':
                $this->handleGet();
                break;
            case 'POST':
                $this->handlePost();
                break;
            case 'PUT':
                $this->handlePut();
                break;
            case 'DELETE':
                $this->handleDelete();
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
                break;
        }
    }

    private function handleGet() {
        if (isset($_GET['id'])) {
            $this->getSingleFeedback($_GET['id']);
        } else {
            $this->listFeedbacks();
        }
    }

    private function getSingleFeedback($id) {
        $stmt = $this->pdo->prepare("
            SELECT f.*, u.nom as user_name, u.adresse as user_address 
            FROM feedbacks f
            LEFT JOIN user u ON f.user_id = u.id
            WHERE f.id = ?
        ");
        $stmt->execute([$id]);
        $feedback = $stmt->fetch();

        if ($feedback) {
            echo json_encode($feedback);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Feedback not found']);
        }
    }

    public  function listFeedbacks() {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : 10;
        $offset = ($page - 1) * $limit;
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $type = isset($_GET['type']) ? $_GET['type'] : '';

        $query = "
            SELECT f.*, u.nom as user_name, u.adresse as user_address 
            FROM feedbacks f
            LEFT JOIN user u ON f.user_id = u.id
        ";

        $params = [];
        if ($search && $type) {
            $query .= " WHERE f.content LIKE ? AND f.type LIKE ?";
            $searchTerm = "%$search%";
            $params = [$searchTerm, "%$type%"];
        } elseif ($search) {
            $query .= " WHERE f.content LIKE ?";
            $params = ["%$search%"];
        } elseif ($type) {
            $query .= " WHERE f.type LIKE ?";
            $params = ["%$type%"];
        }
    
        $countQuery = "SELECT COUNT(*) as total FROM feedbacks f";
        if ($search || $type) {
            $countQuery .= " LEFT JOIN user u ON f.user_id = u.id WHERE 1";
            if ($search) {
                $countQuery .= " AND f.content LIKE ?";
            }
            if ($type) {
                $countQuery .= " AND f.type LIKE ?";
            }
        }
        $countStmt = $this->pdo->prepare($countQuery);
        $countStmt->execute($params);
        $total = $countStmt->fetch()['total'];

        $query .= " ORDER BY f.created_at DESC LIMIT $limit OFFSET $offset";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        $feedbacks = $stmt->fetchAll();

        echo json_encode([
            'data' => $feedbacks,
            'total' => $total,
            'page' => $page,
            'totalPages' => ceil($total / $limit)
        ]);
    }
    public function getAll($page = 1, $limit = 10, $search = '', $type = '') {
        $offset = ($page - 1) * $limit;
    
        $query = "
            SELECT f.*, u.nom as user_name, u.adresse as user_address 
            FROM feedbacks f
            LEFT JOIN user u ON f.user_id = u.id
        ";
    
        $params = [];
        if ($search && $type) {
            $query .= " WHERE f.content LIKE ? AND f.type LIKE ?";
            $searchTerm = "%$search%";
            $params = [$searchTerm, "%$type%"];
        } elseif ($search) {
            $query .= " WHERE f.content LIKE ?";
            $params = ["%$search%"];
        } elseif ($type) {
            $query .= " WHERE f.type LIKE ?";
            $params = ["%$type%"];
        }
    
        $query .= " ORDER BY f.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
    
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    private function handlePost() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['user_id'], $data['type'], $data['content'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO feedbacks (user_id, type, content, created_at)
                VALUES (?, ?, ?, NOW())
            ");
            $stmt->execute([
                $data['user_id'],
                $data['type'],
                $data['content']
            ]);

            $this->getSingleFeedback($this->pdo->lastInsertId());
            http_response_code(201);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create feedback: ' . $e->getMessage()]);
        }
    }

    private function handlePut() {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing feedback ID']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $stmt = $this->pdo->prepare("
                UPDATE feedbacks 
                SET user_id = ?, type = ?, content = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $data['user_id'],
                $data['type'],
                $data['content'],
                $_GET['id']
            ]);

            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(['error' => 'Feedback not found']);
                return;
            }

            $this->getSingleFeedback($_GET['id']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update feedback: ' . $e->getMessage()]);
        }
    }

    private function handleDelete() {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing feedback ID']);
            return;
        }

        try {
            $stmt = $this->pdo->prepare("DELETE FROM feedbacks WHERE id = ?");
            $stmt->execute([$_GET['id']]);

            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(['error' => 'Feedback not found']);
            } else {
                echo json_encode(['success' => true]);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete feedback: ' . $e->getMessage()]);
        }
    }
}

// Instantiate and handle the request
$controller = new FeedbackController();
$controller->handleRequest();
?>
