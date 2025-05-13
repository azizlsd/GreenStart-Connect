<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
require_once __DIR__ . '/../config/config.php';

class ResponseController {
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
            $this->getSingleResponse($_GET['id']);
        } elseif (isset($_GET['feedback_id'])) {
            $this->getResponsesForFeedback($_GET['feedback_id']);
        } else {
            $this->listResponses();
        }
    }

    private function getSingleResponse($id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT r.*, u.nom as user_name, f.content as feedback_content
                FROM response r
                JOIN clients u ON r.user_id = u.id
                JOIN feedbacks f ON r.feedback_id = f.id
                WHERE r.id = ?
            ");
            $stmt->execute([$id]);
            $response = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($response) {
                echo json_encode($response);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Response not found']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        }
    }

    private function getResponsesForFeedback($feedbackId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT r.*, u.nom as user_name
                FROM response r
                JOIN clients u ON r.user_id = u.id
                WHERE r.feedback_id = ? AND r.is_public = 1
                ORDER BY r.created_at DESC
            ");
            $stmt->execute([$feedbackId]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        }
    }
    
    
    
    
    // Call these when opening the response modal
   
    private function listResponses() {
        try {
            // Pagination parameters
            $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
            $limit = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : 10;
            $offset = ($page - 1) * $limit;

            // Filter parameters
            $feedbackId = isset($_GET['feedback_id']) ? $_GET['feedback_id'] : null;
            $responseType = isset($_GET['type']) ? $_GET['type'] : null;
            $isPublic = isset($_GET['is_public']) ? intval($_GET['is_public']) : null;
            $search = isset($_GET['search']) ? $_GET['search'] : null;

            // Base query
            $query = "
                SELECT r.*, u.nom as user_name, f.content as feedback_content
                FROM response r
                JOIN clients u ON r.user_id = u.id
                JOIN feedbacks f ON r.feedback_id = f.id
            ";

            $where = [];
            $params = [];

            // Apply filters
            if ($feedbackId) {
                $where[] = "r.feedback_id = ?";
                $params[] = $feedbackId;
            }

            if ($responseType) {
                $where[] = "r.response_type = ?";
                $params[] = $responseType;
            }

            if ($isPublic !== null) {
                $where[] = "r.is_public = ?";
                $params[] = $isPublic;
            }

            if ($search) {
                $where[] = "(r.content LIKE ? OR f.content LIKE ?)";
                $params[] = "%$search%";
                $params[] = "%$search%";
            }

            // Add WHERE clause if needed
            if (!empty($where)) {
                $query .= " WHERE " . implode(" AND ", $where);
            }

            // Count total records
            $countQuery = "SELECT COUNT(*) as total FROM response r";
            if (!empty($where)) {
                $countQuery .= " JOIN clients u ON r.user_id = u.id JOIN feedbacks f ON r.feedback_id = f.id WHERE " . implode(" AND ", $where);
            }
            
            $countStmt = $this->pdo->prepare($countQuery);
            $countStmt->execute($params);
            $total = $countStmt->fetch()['total'];

            // Add sorting and pagination
          $query .= " ORDER BY r.created_at DESC LIMIT $limit OFFSET $offset";

            // Execute main query
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            $responses = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Return response with pagination info
            echo json_encode([
                'data' => $responses,
                'total' => $total,
                'page' => $page,
                'totalPages' => ceil($total / $limit)
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        }
    }

    private function handlePost() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['feedback_id'], $data['user_id'], $data['response_type'], $data['content'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO response 
                (feedback_id, user_id, response_type, content, is_public, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $data['feedback_id'],
                $data['user_id'],
                $data['response_type'],
                $data['content'],
                $data['is_public'] ?? 0
            ]);

            $this->getSingleResponse($this->pdo->lastInsertId());
            http_response_code(201);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create response: ' . $e->getMessage()]);
        }
    }

    private function handlePut() {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing response ID']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $stmt = $this->pdo->prepare("
                UPDATE response 
                SET response_type = ?, content = ?, is_public = ?, updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([
                $data['response_type'],
                $data['content'],
                $data['is_public'] ?? 0,
                $_GET['id']
            ]);

            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(['error' => 'Response not found']);
                return;
            }

            $this->getSingleResponse($_GET['id']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update response: ' . $e->getMessage()]);
        }
    }

    private function handleDelete() {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing response ID']);
            return;
        }

        try {
            $stmt = $this->pdo->prepare("DELETE FROM response WHERE id = ?");
            $stmt->execute([$_GET['id']]);

            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(['error' => 'Response not found']);
            } else {
                echo json_encode(['success' => true]);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete response: ' . $e->getMessage()]);
        }
    }
}

// Instantiate and handle the request
try {
    $controller = new ResponseController();
    $controller->handleRequest();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}