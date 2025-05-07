<?php
session_start();

class SessionController {
    public function checkLoginStatus() {
        header('Content-Type: application/json');
        
        if (isset($_SESSION['user'])) {
            echo json_encode([
                'loggedIn' => true,
                'userName' => $_SESSION['user']['nom']
            ]);
        } else {
            echo json_encode([
                'loggedIn' => false
            ]);
        }
    }
}

// Utilisation
$controller = new SessionController();
$controller->checkLoginStatus();
?>
