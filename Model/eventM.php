<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\config.php';

class EventModel {
    // Fetch all events
    public static function getAllEvents() {
        try {
            $pdo = config::getConnexion();
            $query = "SELECT id_event, titre_event, description_event, localisation, date_debut, date_fin, max_participants FROM events ORDER BY date_debut DESC";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            return [];
        }
    }

    // Fetch a single event by ID
    public static function getEventById($id) {
        try {
            $pdo = config::getConnexion();
            $query = "SELECT * FROM events WHERE id_event = :id_event";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':id_event' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            return null;
        }
    }

    // Add a new event
    public static function addEvent($data) {
        try {
            $pdo = config::getConnexion();
            $query = "INSERT INTO events (titre_event, description_event, localisation, date_debut, date_fin, max_participants)
                      VALUES (:titre_event, :description_event, :localisation, :date_debut, :date_fin, :max_participants)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':titre_event' => $data['titre_event'],
                ':description_event' => $data['description_event'],
                ':localisation' => $data['localisation'],
                ':date_debut' => $data['date_debut'],
                ':date_fin' => $data['date_fin'],
                ':max_participants' => $data['max_participants']
            ]);
            return true;
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            return false;
        }
    }

    // Update an existing event
    public static function updateEvent($id, $data) {
        try {
            $pdo = config::getConnexion();
            $query = "UPDATE events SET 
                          titre_event = :titre_event, 
                          description_event = :description_event, 
                          localisation = :localisation, 
                          date_debut = :date_debut, 
                          date_fin = :date_fin, 
                          max_participants = :max_participants 
                      WHERE id_event = :id_event";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':id_event' => $id,
                ':titre_event' => $data['titre_event'],
                ':description_event' => $data['description_event'],
                ':localisation' => $data['localisation'],
                ':date_debut' => $data['date_debut'],
                ':date_fin' => $data['date_fin'],
                ':max_participants' => $data['max_participants']
            ]);
            return true;
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            return false;
        }
    }

    // Delete an event
    public static function deleteEvent($id) {
        try {
            $pdo = config::getConnexion();
            $query = "DELETE FROM events WHERE id_event = :id_event";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':id_event' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            return false;
        }
    }
}
?>