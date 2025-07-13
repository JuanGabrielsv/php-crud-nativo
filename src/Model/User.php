<?php

namespace App\Model;

use App\Database\Connection;
use PDO;
use PDOException;

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

    public function create(
        string  $nombre,
        string  $primer_apellido,
        string  $segundo_apellido,
        int     $edad,
        ?string $telefono,
        string  $fechaRegistro,
        string  $email,
        bool    $pagado
    ): bool
    {
        $sql = "INSERT INTO user
        (nombre, primer_apellido, segundo_apellido, edad, telefono, fecha_registro, email, pagado)
        VALUES 
        (:nombre, :primer_apellido, :segundo_apellido, :edad, :telefono, :fecha_registro, :email, :pagado)";
        try {

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                'nombre' => $nombre,
                'primer_apellido' => $primer_apellido,
                'segundo_apellido' => $segundo_apellido,
                'edad' => $edad,
                'telefono' => $telefono,
                'fecha_registro' => $fechaRegistro,
                'email' => $email,
                'pagado' => $pagado ? 1 : 0
            ]);
        } catch (\PDOException $e) {
            // Para debug: mostramos el error en la respuesta
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'Error en base de datos: ' . $e->getMessage()]);
            exit; // Salimos para no continuar el script
        }

    }

}