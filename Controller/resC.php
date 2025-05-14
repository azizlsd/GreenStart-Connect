<?php
require_once __DIR__ . '/../Model/resM.php';

class ReservationController {
    public static function handleAddReservation($data) {
        try {
            $result = ReservationModel::addReservation($data);
            error_log($result ? "handleAddReservation: Success" : "handleAddReservation: Failed");
            return $result;
        } catch (Exception $e) {
            error_log("Error in handleAddReservation: " . $e->getMessage());
            return false;
        }
    }

    public static function getAllReservations() {
        try {
            $result = ReservationModel::getAllReservations();
            error_log("getAllReservations: " . count($result) . " reservations retrieved");
            return $result;
        } catch (Exception $e) {
            error_log("Error in getAllReservations: " . $e->getMessage());
            return [];
        }
    }

    public static function getReservationById($id) {
        try {
            $result = ReservationModel::getReservationById($id);
            error_log($result ? "getReservationById: Found ID $id" : "getReservationById: Not found ID $id");
            return $result;
        } catch (Exception $e) {
            error_log("Error in getReservationById: " . $e->getMessage());
            return null;
        }
    }

    public static function handleUpdateReservation($id, $data) {
        try {
            $result = ReservationModel::updateReservation($id, $data);
            error_log($result ? "handleUpdateReservation: Success ID $id" : "handleUpdateReservation: Failed ID $id");
            return $result;
        } catch (Exception $e) {
            error_log("Error in handleUpdateReservation: " . $e->getMessage());
            return false;
        }
    }

    public static function handleDeleteReservation($id) {
        try {
            $result = ReservationModel::deleteReservation($id);
            error_log($result ? "handleDeleteReservation: Success ID $id" : "handleDeleteReservation: Failed ID $id");
            return $result;
        } catch (Exception $e) {
            error_log("Error in handleDeleteReservation: " . $e->getMessage());
            return false;
        }
    }

    public static function getFilteredReservations($searchTerm = '', $searchColumn = 'nom_user', $sortColumn = 'id_res', $sortOrder = 'asc') {
        try {
            $allowedColumns = ['id_res', 'id_event', 'id_user', 'nom_user', 'accom_res'];
            if (!in_array($searchColumn, $allowedColumns)) {
                $searchColumn = 'nom_user';
            }
            if (!in_array($sortColumn, $allowedColumns)) {
                $sortColumn = 'id_res';
            }
            $sortOrder = strtoupper($sortOrder) === 'DESC' ? 'DESC' : 'ASC';

            $result = ReservationModel::getFilteredReservations($searchTerm, $searchColumn, $sortColumn, $sortOrder);
            error_log("getFilteredReservations: " . count($result) . " reservations found for search '$searchTerm'");
            return $result;
        } catch (Exception $e) {
            error_log("Error in getFilteredReservations: " . $e->getMessage());
            return [];
        }
    }
    
?>