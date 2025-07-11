<?php

namespace App\Database;

use PDO;
use PDOException;

class Connection
{
    public static function connection(): PDO
    {
        $host = "localhost";
        $dbname = "curso_crud_php";
        $user = "root";
        $pass = "root";
        $charset = "utf8mb4";

        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

        try {
            return new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (PDOException $e) {
            die("Conexión fallida: " . $e->getMessage());
        }

    }

}