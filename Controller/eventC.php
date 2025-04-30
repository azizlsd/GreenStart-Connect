<?php
require_once 'C:\xampp\htdocs\GreenStartConnect\Model\eventM.php';

class EventController {
    // Fetch all events
    public static function getAllEvents() {
        return EventModel::getAllEvents(); // Ensure this matches the method name in EventModel
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
}
?>