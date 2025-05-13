<?php
class EventModel {
    private $pdo;

    public function __construct($db) {
        $this->pdo = $db;
    }

    public function getAllEvents() {
        $stmt = $this->pdo->query("SELECT * FROM events");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
