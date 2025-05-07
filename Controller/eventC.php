<?php
require_once __DIR__ . '/../Model/eventM.php';

class EventController {
    // Secret key for CSRF token generation (should be stored securely, e.g., in config)
    private static $csrfSecret = 'your_secure_csrf_secret_key'; // Replace with a strong, unique key

    // Validate CSRF token
    private static function validateCsrfToken($token) {
        $expectedToken = hash_hmac('sha256', 'event_form', self::$csrfSecret);
        return hash_equals($expectedToken, $token);
    }

    // Fetch all events with search and sort
    public static function getAllEvents($searchTerm = '', $searchColumn = 'titre_event', $sortColumn = 'id_event', $sortOrder = 'asc') {
        return EventModel::getAllEvents($searchTerm, $searchColumn, $sortColumn, $sortOrder);
    }

    // Fetch a single event by ID
    public static function getEventById($id) {
        return EventModel::getEventById($id);
    }

    // Add a new event
    public static function handleAddEvent($data) {
        if (!isset($_POST['csrf_token']) || !self::validateCsrfToken($_POST['csrf_token'])) {
            throw new Exception('CSRF validation failed');
        }

        // Validate input
        if (empty($data['titre_event']) || !is_numeric($data['max_participants']) || $data['max_participants'] < 1) {
            throw new Exception('Veuillez remplir tous les champs correctement.');
        }
        if (strtotime($data['date_debut']) >= strtotime($data['date_fin'])) {
            throw new Exception('La date de début doit être antérieure à la date de fin.');
        }

        return EventModel::addEvent($data);
    }

    // Update an existing event
    public static function updateEvent($id, $data) {
        if (!isset($_POST['csrf_token']) || !self::validateCsrfToken($_POST['csrf_token'])) {
            throw new Exception('CSRF validation failed');
        }

        // Validate input
        if (empty($data['titre_event']) || !is_numeric($data['max_participants']) || $data['max_participants'] < 1) {
            throw new Exception('Veuillez remplir tous les champs correctement.');
        }
        if (strtotime($data['date_debut']) >= strtotime($data['date_fin'])) {
            throw new Exception('La date de début doit être antérieure à la date de fin.');
        }

        return EventModel::updateEvent($id, $data);
    }

    // Delete an event
    public static function handleDeleteEvent($id) {
        return EventModel::deleteEvent($id);
    }

    // Get unique participants count
    public static function getUniqueParticipantsCount() {
        return EventModel::getUniqueParticipantsCount();
    }

    // Get most popular event
    public static function getMostPopularEvent() {
        return EventModel::getMostPopularEvent();
    }
}
?>