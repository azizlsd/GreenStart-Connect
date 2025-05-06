<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\config.php';

class EventModel {
    // Fetch all events with search and sort
    public static function getAllEvents($searchTerm = '', $searchColumn = 'titre_event', $sortColumn = 'id_event', $sortOrder = 'asc') {
        try {
            $pdo = config::getConnexion();

            // Build the SQL query with dynamic search and sorting
            $query = "SELECT id_event, titre_event, description_event, localisation, date_debut, date_fin, max_participants 
                      FROM events 
                      WHERE $searchColumn LIKE :searchTerm
                      ORDER BY $sortColumn $sortOrder";

            // Prepare and execute the query
            $stmt = $pdo->prepare($query);
            $stmt->execute([':searchTerm' => '%' . $searchTerm . '%']);
            
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
    public static function getUniqueParticipantsCount() {
        $db = config::getConnexion();
        try {
            $query = $db->query("SELECT COUNT(DISTINCT id_user) as total FROM reservations");
            $result = $query->fetch();
            return $result['total'];
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
    public static function getMostPopularEvent() {
        $db = config::getConnexion();
        try {
            $sql = "
                SELECT e.titre_event, COUNT(r.id_res) AS total_reservations
                FROM events e
                JOIN reservations r ON e.id_event = r.id_event
                GROUP BY e.id_event
                ORDER BY total_reservations DESC
                LIMIT 1
            ";
            $query = $db->query($sql);
            return $query->fetch();
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
        
}
?>
