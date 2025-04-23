<?php

class config
{
    private static $pdo = null;

    public static function getConnexion()
    {
        if (!isset(self::$pdo)) {
            try {
                self::$pdo = new PDO(
                    'mysql:host=localhost;dbname=gscdb', // Corrigé le nom de la base (cf. remarque plus bas)
                    'root',
                    '',
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
                // echo "Connected successfully";
            } catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
