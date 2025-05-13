<?php
require_once __DIR__ . '/../config/config.php';
class Postulation {
    private $db;

    public function __construct() {
        $dbC = new Config();
        $this->db = $dbC->getConnection();
    }

    public function getAll($sort = 'date_asc') {
        $order = $sort === 'date_desc' ? 'DESC' : 'ASC';
        $query = "SELECT p.*, pr.title as project_title 
                  FROM postulations p 
                  JOIN projects pr ON p.project_id = pr.id 
                  ORDER BY p.created_at $order";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT p.*, pr.title as project_title 
                 FROM postulations p 
                 JOIN projects pr ON p.project_id = pr.id 
                 WHERE p.id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $query = "INSERT INTO postulations (nom, prenom, project_id, feedback, created_at) 
                 VALUES (:nom, :prenom, :project_id, :feedback, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nom', $data['nom']);
        $stmt->bindParam(':prenom', $data['prenom']);
        $stmt->bindParam(':project_id', $data['project_id']);
        $stmt->bindParam(':feedback', $data['feedback']);
        return $stmt->execute();
    }

    public function update($id, $data) {
        $query = "UPDATE postulations 
                 SET nom = :nom, 
                     prenom = :prenom, 
                     project_id = :project_id, 
                     feedback = :feedback 
                 WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nom', $data['nom']);
        $stmt->bindParam(':prenom', $data['prenom']);
        $stmt->bindParam(':project_id', $data['project_id']);
        $stmt->bindParam(':feedback', $data['feedback']);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM postulations WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getByProjectId($projectId) {
        $query = "SELECT * FROM postulations WHERE project_id = :project_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':project_id', $projectId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalPostulations() {
        $query = "SELECT COUNT(*) as total FROM postulations";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getPostulationsByStatus() {
        $query = "SELECT status, COUNT(*) as count FROM postulations GROUP BY status";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search($search, $sort = 'date_asc') {
        $order = $sort === 'date_desc' ? 'DESC' : 'ASC';
        $query = "SELECT p.*, pr.title as project_title 
                  FROM postulations p 
                  JOIN projects pr ON p.project_id = pr.id 
                  WHERE p.nom LIKE :search 
                     OR p.prenom LIKE :search 
                     OR pr.title LIKE :search
                  ORDER BY p.created_at $order";
        $stmt = $this->db->prepare($query);
        $like = '%' . $search . '%';
        $stmt->bindParam(':search', $like);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 