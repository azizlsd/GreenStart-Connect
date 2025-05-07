<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM user WHERE adresse = ?");
    $stmt->execute([$email]);
    $existingUser = $stmt->fetch();

    if ($existingUser) {
        $_SESSION['error'] = "Email already registered.";
        header("Location: http://localhost/startup2/view/front-office/signup.html");
        exit;
    } else {
        $stmt = $pdo->prepare("INSERT INTO user (nom, adresse, password) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $email, $password]);

        $userId = $pdo->lastInsertId();
        $_SESSION['user'] = [
            'id' => $userId,
            'nom' => $nom,
            'adresse' => $email
        ];

        header("Location: http://localhost/startup2/view/front-office/index.html");
        exit;
    }
}
?>
