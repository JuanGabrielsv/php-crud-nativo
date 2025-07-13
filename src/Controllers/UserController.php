<?php

namespace App\Controllers;

use App\Model\User;

class UserController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function getAll(): void
    {
        $usuarios = $this->userModel->getAll();
        header('Content-Type: application/json');
        echo json_encode($usuarios);
    }

    public function getAllById(int $id): void
    {
        $usuario = $this->userModel->getById($id);
        header('Content-Type: application/json');
        echo json_encode($usuario ?: ['error' => 'Usuario no encontrado']);
    }

    public function create(): void
    {
        // Obtener el contenido del body
        $userData = json_decode(file_get_contents('php://input'), true);

        // Validaciones campos obligatorios
        $requiredFields = ['nombre', 'primer_apellido', 'segundo_apellido', 'edad', 'telefono', 'email', 'pagado'];

        // Recorremos $userData para comprobar que tiene todos los campos requeridos
        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $userData)) {
                http_response_code(400);
                echo json_encode(['error' => "El campo `$field` es obligatorio"]);
                return;
            }
            // Todos los campos menos pagado no pueden estar vacios o valer 0
            if ($field !== 'pagado') {
                // Para pagado no validamos vacío porque false es válido
                if (empty($userData[$field]) && $userData[$field] !== '0' && $userData[$field] !== 0) {
                    http_response_code(400);
                    echo json_encode(['error' => "El campo `$field` no puede estar vacío"]);
                    return;
                }
            }
        }

        if (!in_array($userData['pagado'], [true, false, 0, 1], true)) {
            http_response_code(400);
            echo json_encode(['error' => "El campo `pagado` solo puede ser true, false, 1 o 0"]);
            return;
        }

        // Asignamos la fecha actual a fecha registro
        $fechaRegistro = date('Y-m-d H:i:s');

        $user = new User();
        $result = $user->create(
            $userData['nombre'],
            $userData['primer_apellido'],
            $userData['segundo_apellido'],
            $userData['edad'],
            $userData['telefono'],
            $fechaRegistro,
            $userData['email'],
            $userData['pagado']
        );

        if ($result) {
            http_response_code(201);
            echo json_encode(['success' => 'Usuario creado']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al crear el usuario']);
        }
    }
}