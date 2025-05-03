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
}
?>
