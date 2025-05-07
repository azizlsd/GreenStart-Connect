<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\Model\resM.php';

class ReservationController {

    // Add a new reservation
    public static function handleAddReservation($data) {
        return ReservationModel::addReservation($data);
    }

    // Fetch all reservations
    public static function getAllReservations() {
        return ReservationModel::getAllReservations();
    }

    // Fetch a single reservation by ID
    public static function getReservationById($id) {
        return ReservationModel::getReservationById($id);
    }

    // Update a reservation
    public static function handleUpdateReservation($id, $data) {
        return ReservationModel::updateReservation($id, $data);
    }

    // Delete a reservation
    public static function handleDeleteReservation($id) {
        return ReservationModel::deleteReservation($id);
    }

    public static function getFilteredReservations($searchTerm = '', $searchColumn = 'nom_user', $sortColumn = 'id_res', $sortOrder = 'asc') {
        $pdo = config::getConnexion();
    
        $allowedColumns = ['id_event', 'id_user', 'nom_user', 'accom_res', 'id_res'];
        if (!in_array($searchColumn, $allowedColumns)) $searchColumn = 'nom_user';
        if (!in_array($sortColumn, $allowedColumns)) $sortColumn = 'id_res';
        $sortOrder = $sortOrder === 'desc' ? 'DESC' : 'ASC';
    
        $query = "SELECT * FROM reservations WHERE $searchColumn LIKE :search ORDER BY $sortColumn $sortOrder";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':search' => "%$searchTerm%"]);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
