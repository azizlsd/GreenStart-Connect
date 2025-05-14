<?php
require_once __DIR__ . '/../config.php';

class ReservationModel {
    public static function addReservation($data) {
        try {
            $pdo = config::getConnexion();
            $pdo->beginTransaction();

            $query = "INSERT INTO reservations (id_event, id_user, nom_user, accom_res) 
                      VALUES (:id_event, :id_user, :nom_user, :accom_res)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                'id_event' => $data['id_event'],
                'id_user' => $data['id_user'],
                'nom_user' => $data['nom_user'],
                'accom_res' => $data['accom_res']
            ]);

            if ($stmt->rowCount() > 0) {
                $pdo->commit();
                error_log("Reservation added: nom_user = " . $data['nom_user']);
                return true;
            } else {
                $pdo->rollBack();
                error_log("No rows affected when adding reservation for " . $data['nom_user']);
                return false;
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log('Error in addReservation: ' . $e->getMessage());
            return false;
        }
    }

    public static function getAllReservations() {
        try {
            $pdo = config::getConnexion();
            $query = "SELECT * FROM reservations";
            $stmt = $pdo->query($query);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("getAllReservations: " . count($result) . " reservations found");
            return $result;
        } catch (PDOException $e) {
            error_log('Error in getAllReservations: ' . $e->getMessage());
            return [];
        }
    }

    public static function getReservationById($id) {
        try {
            $pdo = config::getConnexion();
            $query = "SELECT * FROM reservations WHERE id_res = :id_res";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['id_res' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log($result ? "Reservation found: ID $id" : "No reservation found: ID $id");
            return $result;
        } catch (PDOException $e) {
            error_log('Error in getReservationById: ' . $e->getMessage());
            return null;
        }
    }

    public static function updateReservation($id, $data) {
        try {
            $pdo = config::getConnexion();
            $query = "UPDATE reservations SET id_event = :id_event, id_user = :id_user, nom_user = :nom_user, accom_res = :accom_res WHERE id_res = :id_res";
            $stmt = $pdo->prepare($query);
            $result = $stmt->execute([
                'id_res' => $id,
                'id_event' => $data['id_event'],
                'id_user' => $data['id_user'],
                'nom_user' => $data['nom_user'],
                'accom_res' => $data['accom_res']
            ]);
            error_log($result ? "Reservation updated: ID $id" : "Failed to update reservation: ID $id");
            return $result;
        } catch (PDOException $e) {
            error_log('Error in updateReservation: ' . $e->getMessage());
            return false;
        }
    }

    public static function deleteReservation($id) {
        try {
            $pdo = config::getConnexion();
            $query = "DELETE FROM reservations WHERE id_res = :id_res";
            $stmt = $pdo->prepare($query);
            $result = $stmt->execute(['id_res' => $id]);
            error_log($result ? "Reservation deleted: ID $id" : "Failed to delete reservation: ID $id");
            return $result;
        } catch (PDOException $e) {
            error_log('Error in deleteReservation: ' . $e->getMessage());
            return false;
        }
    }

    public static function getFilteredReservations($search = '', $searchColumn = 'nom_user', $sort = 'id_res', $order = 'ASC') {
        $allowedColumns = ['id_res', 'id_event', 'id_user', 'nom_user', 'accom_res'];
        if (!in_array($searchColumn, $allowedColumns)) {
            $searchColumn = 'nom_user';
        }
        if (!in_array($sort, $allowedColumns)) {
            $sort = 'id_res';
        }
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

        try {
            $pdo = config::getConnexion();
            $sql = "SELECT * FROM reservations";
            $params = [];

            if (!empty($search)) {
                $sql .= " WHERE $searchColumn LIKE :search";
                $params['search'] = '%' . $search . '%';
            }

            $sql .= " ORDER BY $sort $order";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("getFilteredReservations: " . count($result) . " reservations found for search '$search'");
            return $result;
        } catch (PDOException $e) {
            error_log("Error in getFilteredReservations: " . $e->getMessage());
            return [];
        }
    }
}
?>