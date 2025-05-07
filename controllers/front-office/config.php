<?php
$host = "localhost";
$dbname = "feedbacks";
$user = "root";
$password = ""; // mot de passe (souvent vide en local)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
