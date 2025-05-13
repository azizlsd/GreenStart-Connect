<?php
session_start();
require_once __DIR__ . '/../config/config.php';

class FeedbackController {
    private $db;

    public function __construct() {
          $dbC = new Config();
        $this->db = $dbC->getConnection();
    }

    public function get($id = null, $page = 1, $limit = 10, $search = '') {
        if ($id) {
            $stmt = $this->db->prepare("
                SELECT f.*, u.nom as user_name, u.adresse as user_address 
                FROM feedbacks f
                LEFT JOIN clients u ON f.user_id = u.id
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
            return;
        }

        $offset = ($page - 1) * $limit;
        $params = [];
        $query = "
            SELECT f.*, u.nom as user_name, u.adresse as user_address 
            FROM feedbacks f
            LEFT JOIN clients u ON f.user_id = u.id
        ";

        if ($search) {
            $query .= " WHERE f.content LIKE ? OR u.nom LIKE ? OR f.type LIKE ?";
            $searchTerm = "%$search%";
            $params = array_fill(0, 3, $searchTerm);
        }

        $countQuery = "SELECT COUNT(*) as total FROM feedbacks f";
        if ($search) {
            $countQuery .= " LEFT JOIN clients u ON f.user_id = u.id WHERE f.content LIKE ? OR u.nom LIKE ? OR f.type LIKE ?";
        }

        $countStmt = $this->db->prepare($countQuery);
        $countStmt->execute($params);
        $total = $countStmt->fetch()['total'];

        $query .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $feedbacks = $stmt->fetchAll();

        echo json_encode([
            'data' => $feedbacks,
            'total' => $total,
            'page' => $page,
            'totalPages' => ceil($total / $limit)
        ]);
    }

    public function create($data) {
        if (!isset($_SESSION['client'])) {
            http_response_code(401);
            echo json_encode(['error' => 'You must be logged in to post feedback']);
            return;
        }

        if (!isset($data['type']) || !isset($data['content'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        try {
            $stmt = $this->db->prepare("
                INSERT INTO feedbacks (user_id, type, content, created_at)
                VALUES (?, ?, ?, NOW())
            ");
            $stmt->execute([
                $_SESSION['client']['id'],
                $data['type'],
                $data['content']
            ]);

            $id = $this->db->lastInsertId();
            $this->get($id);
            http_response_code(201);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create feedback: ' . $e->getMessage()]);
        }
    }

    public function update($id, $data) {
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing feedback ID']);
            return;
        }

        try {
            $stmt = $this->db->prepare("
                UPDATE feedbacks 
                SET user_id = ?, type = ?, content = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $data['user_id'],
                $data['type'],
                $data['content'],
                $id
            ]);

            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(['error' => 'Feedback not found']);
                return;
            }

            $this->get($id);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update feedback: ' . $e->getMessage()]);
        }
    }

    public function delete($id) {
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing feedback ID']);
            return;
        }

        try {
            $stmt = $this->db->prepare("DELETE FROM feedbacks WHERE id = ?");
            $stmt->execute([$id]);

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

// Routage
$controller = new FeedbackController();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $controller->get(
            $_GET['id'] ?? null,
            $_GET['page'] ?? 1,
            $_GET['limit'] ?? 10,
            $_GET['search'] ?? ''
        );
        break;
    case 'POST':
        $controller->create(json_decode(file_get_contents('php://input'), true));
        break;
    case 'PUT':
        $controller->update($_GET['id'] ?? null, json_decode(file_get_contents('php://input'), true));
        break;
    case 'DELETE':
        $controller->delete($_GET['id'] ?? null);
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
?>
