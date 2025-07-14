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
        string $nombre,
        string $primer_apellido,
        string $segundo_apellido,
        int    $edad,
        string $telefono,
        string $fechaRegistro,
        string $email,
        bool   $pagado
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
        } catch (PDOException $e) {
            // Para debug: mostramos el error en la respuesta
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'Error en base de datos: ' . $e->getMessage()]);
            exit; // Salimos para no continuar el script
        }

    }

    // PUT
    public function put(
        int    $id,
        string $nombre,
        string $primer_apellido,
        string $segundo_apellido,
        int    $edad,
        string $telefono,
        string $fechaRegistro,
        string $email,
        bool   $pagado
    ): bool
    {
        $sql = "UPDATE user SET 
                nombre = :nombre,
                primer_apellido = :primer_apellido,
                segundo_apellido = :segundo_apellido,
                edad = :edad,
                telefono = :telefono,
                fecha_registro = :fecha_registro,
                email = :email,
                pagado = :pagado
            WHERE id = :id";

        try {
            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                'id' => $id,
                'nombre' => $nombre,
                'primer_apellido' => $primer_apellido,
                'segundo_apellido' => $segundo_apellido,
                'edad' => $edad,
                'telefono' => $telefono,
                'fecha_registro' => $fechaRegistro,
                'email' => $email,
                'pagado' => $pagado ? 1 : 0
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error en base de datos: ' . $e->getMessage()]);
            return false;
        }
    }

    public function patch(int $id, array $campos): bool
    {
        // Si no hay campos, no tiene sentido ejecutar
        if (empty($campos)) {
            return false;
        }

        $setParts = [];
        $params = [];

        foreach ($campos as $key => $value) {
            $setParts[] = "`$key` = :$key";
            // Convertimos pagado a 1/0 si es booleano
            if ($key === 'pagado') {
                $params[$key] = $value ? 1 : 0;
            } else {
                $params[$key] = $value;
            }
        }

        $setClause = implode(', ', $setParts);
        $params['id'] = $id;

        $sql = "UPDATE user SET $setClause WHERE id = :id";

        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error en base de datos: ' . $e->getMessage()]);
            return false;
        }
    }
}