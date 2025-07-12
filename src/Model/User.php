<?php

namespace App\Model;

use App\Database\Connection;
use PDO;

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::connection();
    }

    // GET
    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM user");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE id = :id");
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}