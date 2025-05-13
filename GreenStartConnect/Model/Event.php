<?php
require_once __DIR__ . '/../config/config.php';

class Event {
    private $pdo;

    public function __construct() {
        $dbC = new Config();
        $this->pdo = $dbC->getConnection();
    }

    public function getTotalEvents() {
        $query = "SELECT COUNT(*) as total FROM events";
        $stmt = $this->pdo->query($query);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function getUpcomingEvents($limit = 5) {
        $query = "SELECT * FROM events WHERE start_date >= CURRENT_DATE ORDER BY start_date ASC LIMIT :limit";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllEvents() {
        $query = "SELECT * FROM events ORDER BY start_date DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEventById($id) {
        $query = "SELECT * FROM events WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createEvent($data) {
        $query = "INSERT INTO events (title, description, start_date, end_date, location, status, created_at) 
                 VALUES (:title, :description, :start_date, :end_date, :location, :status, NOW())";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':location', $data['location']);
        $stmt->bindParam(':status', $data['status']);
        return $stmt->execute();
    }

    public function updateEvent($id, $data) {
        $query = "UPDATE events 
                 SET title = :title, 
                     description = :description, 
                     start_date = :start_date, 
                     end_date = :end_date, 
                     location = :location, 
                     status = :status 
                 WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':location', $data['location']);
        $stmt->bindParam(':status', $data['status']);
        return $stmt->execute();
    }

    public function deleteEvent($id) {
        $query = "DELETE FROM events WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Méthode pour créer la table events si elle n'existe pas
    public function createTableIfNotExists() {
        $query = "CREATE TABLE IF NOT EXISTS events (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            start_date DATETIME NOT NULL,
            end_date DATETIME,
            location VARCHAR(255),
            status VARCHAR(50) DEFAULT 'Planifié',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        try {
            $this->pdo->exec($query);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la création de la table events: " . $e->getMessage());
        }
    }
}
?> 