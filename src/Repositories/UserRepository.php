<?php
namespace App\Repositories;

use App\Config\Database;
use App\Entities\User;
use PDO;

class UserRepository
{
    private PDO $db;

    // Mni kan-creew l'Repository, kan3tiwh l'connexion dyal la base de données
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Had l'méthode katjib lina User wa7ed mn la base de données b l'ID dyalo
     */
    public function getUserById(int $id): ?User
    {
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);

        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

 if (!$row) {
            return null;
        }

       return new User(
            $row['id'],
            $row['name'],
            $row['email'],
            $row['role'],
            $row['points']
        );
    }
}