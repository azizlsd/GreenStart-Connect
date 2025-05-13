<?php
// Inclure les fichiers nécessaires
require_once 'Model/eventM.php';

class EventController {
    private $model;

    // Le constructeur reçoit la connexion à la base de données
    public function __construct($db) {
        $this->model = new EventModel($db);
    }

    // Méthode pour afficher les événements
    public function showEvents() {
        $events = $this->model->getAllEvents(); // Récupérer les événements
        include 'View/FrontOffice/event.php';    // Inclure la vue pour afficher les événements
    }
}
?>
