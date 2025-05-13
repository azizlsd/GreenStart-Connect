<?php
require_once __DIR__ . '/../config/config.php';

class Project {
    private $pdo;

    public function __construct() {
        $dbC = new Config();
        $this->pdo = $dbC->getConnection();
    }

    public function createTableIfNotExists() {
        $query = "CREATE TABLE IF NOT EXISTS projects (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            start_date DATE,
            end_date DATE,
            status VARCHAR(50) DEFAULT 'En cours',
            budget DECIMAL(10,2) DEFAULT 0.00,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        try {
            $this->pdo->exec($query);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la création de la table projects: " . $e->getMessage());
        }
    }

    public function getAllProjects() {
        $query = "SELECT * FROM projects ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProjectById($id) {
        $query = "SELECT * FROM projects WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createProject($data) {
        $query = "INSERT INTO projects (title, description, start_date, end_date, status, budget, created_at) 
                 VALUES (:title, :description, :start_date, :end_date, :status, :budget, NOW())";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':budget', $data['budget']);
        return $stmt->execute();
    }

    public function updateProject($id, $data) {
        $query = "UPDATE projects 
                 SET title = :title, 
                     description = :description, 
                     start_date = :start_date, 
                     end_date = :end_date, 
                     status = :status, 
                     budget = :budget 
                 WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':budget', $data['budget']);
        return $stmt->execute();
    }

    public function deleteProject($id) {
        $query = "DELETE FROM projects WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getTotalProjects() {
        $query = "SELECT COUNT(*) as total FROM projects";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function getProjectsByStatus() {
        $query = "SELECT status, COUNT(*) as count FROM projects GROUP BY status";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalBudget() {
        $query = "SELECT COALESCE(SUM(budget), 0) as total FROM projects";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function getRecentProjects($limit = 5) {
        $query = "SELECT * FROM projects ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActiveProjects() {
        $query = "SELECT COUNT(*) as total FROM projects WHERE status = 'En cours'";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function getAll() {
        return $this->getAllProjects();
    }
}
?> 