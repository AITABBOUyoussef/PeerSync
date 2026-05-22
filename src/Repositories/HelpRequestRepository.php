<?php
namespace App\Repositories;

use App\Config\Database;
use PDO;

class HelpRequestRepository
{
    private PDO $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * njbdo ga3 les demandes li ba9in kaysnaw (PENDING)
     */
    public function getPendingRequests(): array
    {
        $query = "SELECT * FROM help_requests WHERE status = 'PENDING' ORDER BY created_at DESC";
        $stmt = $this->db->query($query);

        return $stmt->fetchAll();
    }

    /**
     * Nzidou demande jdida f la base de données
     */
    public function createRequest(string $title, string $description, int $apprenantId, int $tagId): bool
    {
        $query = "INSERT INTO help_requests (title, description, apprenant_id, tag_id, status) 
                  VALUES (:title, :description, :apprenant_id, :tag_id, 'PENDING')";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            'title' => $title,
            'description' => $description,
            'apprenant_id' => $apprenantId,
            'tag_id' => $tagId
        ]);
    }
}