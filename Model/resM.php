<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\config.php';

class ReservationModel {

    // Add a new reservation
    public static function addReservation($data) {
        try {
            $pdo = config::getConnexion();
            $query = "INSERT INTO reservations (id_event, id_user, nom_user, accom_res) 
                      VALUES (:id_event, :id_user, :nom_user, :accom_res)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':id_event' => $data['id_event'],
                ':id_user' => $data['id_user'],
                ':nom_user' => $data['nom_user'],
                ':accom_res' => $data['accom_res']
            ]);
            return true;
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            return false;
        }
    }

    // Fetch all reservations
    public static function getAllReservations() {
        try {
            $pdo = config::getConnexion();
            $query = "SELECT * FROM reservations";
            $stmt = $pdo->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            return [];
        }
    }

    // Fetch a single reservation by ID
    public static function getReservationById($id) {
        try {
            $pdo = config::getConnexion();
            $query = "SELECT * FROM reservations WHERE id_res = :id_res";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':id_res' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            return null;
        }
    }

    // Update reservation by ID
    public static function updateReservation($id, $data) {
        try {
            $pdo = config::getConnexion();
            $query = "UPDATE reservations SET id_event = :id_event, id_user = :id_user, nom_user = :nom_user, accom_res = :accom_res WHERE id_res = :id_res";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':id_res' => $id,
                ':id_event' => $data['id_event'],
                ':id_user' => $data['id_user'],
                ':nom_user' => $data['nom_user'],
                ':accom_res' => $data['accom_res']
            ]);
            return true;
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            return false;
        }
    }

    // Delete a reservation by ID
    public static function deleteReservation($id) {
        try {
            $pdo = config::getConnexion();
            $query = "DELETE FROM reservations WHERE id_res = :id_res";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':id_res' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            return false;
        }
    }
}
?>
