<?php
require_once dirname(__DIR__) . '/config.php';

class EventModel {
    public static function getAllEvents($searchTerm, $searchColumn, $sortColumn, $sortOrder) {
        try {
            $pdo = config::getConnexion();
            $query = "SELECT * FROM events WHERE $searchColumn LIKE :searchTerm ORDER BY $sortColumn $sortOrder";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':searchTerm' => '%' . $searchTerm . '%']);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Get all events error: ' . $e->getMessage());
            throw new Exception('Erreur lors de la récupération des événements.');
        }
    }

    public static function getEventById($id) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("SELECT * FROM events WHERE id_event = :id_event");
            $stmt->execute([':id_event' => $id]); // Fixed: removed 'schm'
            $event = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$event) {
                throw new Exception('Événement non trouvé.');
            }
            return $event;
        } catch (PDOException $e) {
            error_log('Get event by ID error: ' . $e->getMessage());
            throw new Exception('Erreur lors de la récupération de l\'événement.');
        }
    }

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
            error_log('Add event error: ' . $e->getMessage());
            throw new Exception('Erreur lors de l\'ajout de l\'événement.');
        }
    }

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
            error_log('Update event error: ' . $e->getMessage());
            throw new Exception('Erreur lors de la mise à jour de l\'événement.');
        }
    }

    public static function deleteEvent($id) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("DELETE FROM events WHERE id_event = :id_event");
            $stmt->execute([':id_event' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log('Delete event error: ' . $e->getMessage());
            throw new Exception('Erreur lors de la suppression de l\'événement.');
        }
    }

    public static function getUniqueParticipantsCount() {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("SELECT COUNT(DISTINCT id_user) as count FROM reservations");
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        } catch (PDOException $e) {
            error_log('Get unique participants error: ' . $e->getMessage());
            throw new Exception('Erreur lors de la récupération du nombre de participants.');
        }
    }

    public static function getMostPopularEvent() {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("
                SELECT e.titre_event, COUNT(r.id_user) as participant_count
                FROM events e
                LEFT JOIN reservations r ON e.id_event = r.id_event
                GROUP BY e.id_event, e.titre_event
                ORDER BY participant_count DESC
                LIMIT 1
            ");
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Get most popular event error: ' . $e->getMessage());
            throw new Exception('Erreur lors de la récupération de l\'événement le plus populaire.');
        }
    }
}
?>