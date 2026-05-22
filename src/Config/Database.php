<?php
namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private ?PDO $conn = null;

    public function getConnection(): PDO
    {
        if ($this->conn === null) {
            try {
                // L'fix hna: kan9raw mn $_SERVER, w ila mal9inahch kandiro les valeurs par défaut
                $host = $_ENV['DB_HOST'] ?? $_SERVER['DB_HOST'] ?? 'localhost';
                $db_name = $_ENV['DB_NAME'] ?? $_SERVER['DB_NAME'] ?? 'peersync';
                $username = $_ENV['DB_USER'] ?? $_SERVER['DB_USER'] ?? 'root';
                $password = $_ENV['DB_PASS'] ?? $_SERVER['DB_PASS'] ?? '';

                $dsn = "mysql:host=" . $host . ";dbname=" . $db_name . ";charset=utf8mb4";

                $this->conn = new PDO($dsn, $username, $password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            } catch(PDOException $e) {
                echo "Erreur de connexion à la base de données : " . $e->getMessage();
                exit;
            }
        }

        return $this->conn;
    }
}