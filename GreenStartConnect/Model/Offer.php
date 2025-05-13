<?php
require_once __DIR__ . '/../config/config.php';
class Offer {
    private $db;

    public function __construct() {
         $dbC = new Config();
        $this->db = $dbC->getConnection();
    }

    public function getAllOffers() {
        $query = "SELECT * FROM offers ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOfferById($id) {
        $query = "SELECT * FROM offers WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createOffer($data) {
        $query = "INSERT INTO offers (title, description, requirements, location, type, status, created_at) 
                 VALUES (:title, :description, :requirements, :location, :type, :status, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':requirements', $data['requirements']);
        $stmt->bindParam(':location', $data['location']);
        $stmt->bindParam(':type', $data['type']);
        $stmt->bindParam(':status', $data['status']);
        return $stmt->execute();
    }

    public function updateOffer($id, $data) {
        $query = "UPDATE offers 
                 SET title = :title, 
                     description = :description, 
                     requirements = :requirements, 
                     location = :location, 
                     type = :type, 
                     status = :status 
                 WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':requirements', $data['requirements']);
        $stmt->bindParam(':location', $data['location']);
        $stmt->bindParam(':type', $data['type']);
        $stmt->bindParam(':status', $data['status']);
        return $stmt->execute();
    }

    public function deleteOffer($id) {
        $query = "DELETE FROM offers WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getOfferWithPostulations($id) {
        $query = "SELECT o.*, p.* 
                 FROM offers o 
                 LEFT JOIN postulations p ON o.id = p.offer_id 
                 WHERE o.id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalOffers() {
        $query = "SELECT COUNT(*) as total FROM offers";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getOffersByStatus() {
        $query = "SELECT status, COUNT(*) as count FROM offers GROUP BY status";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 