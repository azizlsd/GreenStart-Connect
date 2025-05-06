<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\Model\eventM.php';

class EventController {
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
        return EventModel::addEvent($data);
    }

    // Update an existing event
    public static function handleUpdateEvent($id, $data) {
        return EventModel::updateEvent($id, $data);
    }

    // Delete an event
    public static function handleDeleteEvent($id) {
        return EventModel::deleteEvent($id);
    }
    public static function getUniqueParticipantsCount() {
        return EventModel::getUniqueParticipantsCount(); // adjust class name if different
    }
    
    public static function getMostPopularEvent() {
        return EventModel::getMostPopularEvent();
    }
    
        
}
?>
