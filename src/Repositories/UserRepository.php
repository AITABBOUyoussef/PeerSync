<?php
namespace App\Repositories;

use App\Config\Database;
use App\Entities\User;
use PDO;

class UserRepository
{
    private PDO $db;
 public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }


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


    public function login(string $email, string $password): ?User
    {
        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();

         if ($row && $row['password'] === $password) {
            return new User(
                $row['id'],
                $row['name'],
                $row['email'],
                $row['role'],
                $row['points']
            );
        }

        return null;
    }
}