<?php
session_start();
require_once 'config.php';

class AuthController {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function login($email, $password) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM user WHERE adresse = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && $password == $user['password']) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'nom' => $user['nom'],
                    'adresse' => $user['adresse']
                ];
                header("Location: http://localhost/startup2/view/front-office/index.html");
                exit;
            } else {
                header("Location: http://localhost/startup2/view/front-office/login.html?error=1");
                exit;
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Login failed: ' . $e->getMessage()]);
        }
    }
}

// Utilisation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $auth = new AuthController($pdo);
    $auth->login($email, $password);
}
?>
