<?php
require_once ROOT_PATH . '/config.php'; // Inclut ROOT_PATH et la connexion à la base de données
require_once ROOT_PATH . '/Controller/eventC.php';

try {
    $db = config::getConnexion();
    $controller = new EventController($db);

    $action = $_GET['action'] ?? 'show';
    $id = $_GET['id'] ?? null;

    switch ($action) {
        case 'add':
            $controller->addEvent();
            break;
        case 'edit':
            $controller->editEvent($id);
            break;
        case 'delete':
            $controller->deleteEvent($id);
            break;
        default:
            $controller->showEvents();
    }
} catch (Exception $e) {
    error_log("Application Error: " . $e->getMessage());
    echo "An error occurred. Please try again later.";
}
?>