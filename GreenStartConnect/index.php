<?php
session_start();
require_once __DIR__ . '/Controller\ClientController.php';

$controller = new ClientController();
$action = $_GET['action'] ?? 'login';
$id = $_GET['id'] ?? null;

// Vérifie si la méthode existe
if (method_exists($controller, $action)) {
    // Si l'action attend un ID (edit/delete/ban/unban), on le passe
    if (in_array($action, ['edit', 'delete', 'ban', 'unban'])) {
        $controller->$action($id);
    } else {
        $controller->$action();
    }
} else {
    echo "❌ Action non trouvée : " . htmlspecialchars($action);
}