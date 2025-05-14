<?php
class config
{
    private static $pdo = null;

    public static $smtp_config = [
        'host' => 'smtp.gmail.com',
        'username' => 'noreply.gsconnect@gmail.com', // Sender email
        'password' => 'GSConnect123', // Replace with Gmail App Password (e.g., abcd efgh ijkl mnop)
        'port' => 587,
        'admin_email' => 'mouhmaedaziz.boulifi@esprit.tn' // Receiver email
    ];

    public static function getConnexion()
    {
        if (!isset(self::$pdo)) {
            try {
                self::$pdo = new PDO(
                    'mysql:host=localhost;dbname=gscdb',
                    'root',
                    '',
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
                self::$pdo->exec("set names utf8");
            } catch (PDOException $e) {
                die('Erreur de connexion: ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
?>