<?php
namespace App\Repositories;

use App\Config\Database;
use PDO;

class TagRepository
{
    public function getAllTags(): array
    {
        $db = (new Database())->getConnection();
        return $db->query("SELECT * FROM tags ORDER BY name ASC")->fetchAll();
    }
}