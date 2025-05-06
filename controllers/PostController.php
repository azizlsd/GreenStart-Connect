<?php
require_once __DIR__ . '/../config/db.php';

class PostController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index($whereClause = '', $params = array()) {
        $sql = "SELECT * FROM post " . $whereClause . " ORDER BY date_creation DESC";
        $stmt = $this->pdo->prepare($sql);
        
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $stmt->bindValue($key + 1, $value);
            }
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM post WHERE id_post = ?");
        $stmt->execute(array($id));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function store($data) {
        // Check for required fields
        $required = array('questions', 'date_creation', 'id_user', 'type');
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                error_log("Missing required field: " . $field);
                return false;
            }
        }
    
        $sql = "INSERT INTO post (questions, date_creation, id_user, type, imagePath) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        
        $imagePath = isset($data['imagePath']) ? $data['imagePath'] : null;
        
        try {
            $success = $stmt->execute(array(
                $data['questions'],
                $data['date_creation'],
                $data['id_user'],
                $data['type'],
                $imagePath
            ));
            
            return $success;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data) {
        // Check for required fields
        $required = array('questions', 'date_creation', 'id_user', 'type');
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                error_log("Missing required field: " . $field);
                return false;
            }
        }

        $sql = "UPDATE post SET questions = ?, date_creation = ?, id_user = ?, type = ?, imagePath = ? WHERE id_post = ?";
        $stmt = $this->pdo->prepare($sql);
        
        $imagePath = isset($data['imagePath']) ? $data['imagePath'] : null;
        
        try {
            $success = $stmt->execute(array(
                $data['questions'],
                $data['date_creation'],
                $data['id_user'],
                $data['type'],
                $imagePath,
                $id
            ));
            
            return $success;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM post WHERE id_post = ?");
            return $stmt->execute(array($id));
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }
    public function getAll() {
        $stmt = $this->pdo->prepare("SELECT * FROM post");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}