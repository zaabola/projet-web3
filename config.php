<?php

class Config
{
    private static $pdo = null;

    public static function getConnexion()
    {
        if (!isset(self::$pdo)) {
            $host = "localhost";
            $username = "root";
            $password = "";
            $dbname = "empreinte1";

            try {
                self::$pdo = new PDO(
                    "mysql:host=$host;dbname=$dbname",
                    $username,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
                echo "connected successfully";
            } catch (Exception $e) {
                die('Erreur: ' . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}

Config::getConnexion();
?>
