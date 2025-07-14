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

    public function getById(int $id): void
    {
        $usuario = $this->userModel->getById($id);
        header('Content-Type: application/json');
        echo json_encode($usuario ?: ['error' => 'Usuario no encontrado']);
    }

    // CREATE
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
                    echo json_encode(['error' => "El campo `$field` no puede estar vacío"], JSON_UNESCAPED_UNICODE);
                    return;
                }
            }
        }
        // Atributo pagado debe ser true, false, 0 o 1
        if (!in_array($userData['pagado'], [true, false, 0, 1], true)) {
            http_response_code(400);
            echo json_encode(['error' => "El campo `pagado` solo puede ser true, false, 1 o 0"]);
            return;
        }
        // Filtramos que el email sea correcto
        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['error' => 'El formato del email no es válido']);
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

    // PUT
    public function put(): void
    {
        $user = new User();
        $userData = json_decode(file_get_contents('php://input'), true);
        $requiredFields = ['id', 'nombre', 'primer_apellido', 'segundo_apellido', 'edad', 'telefono', 'email', 'pagado'];

        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $userData)) {
                http_response_code(400);
                echo json_encode(['error' => "El campo `$field` es obligatorio"]);
                return;
            }
            if ($field !== 'pagado') {
                if (empty($userData[$field])) {
                    http_response_code(400);
                    echo json_encode(['error' => "El campo `$field` no puede estar vacío"], JSON_UNESCAPED_UNICODE);
                    return;
                }
            }
        }

        if (!in_array($userData['pagado'], [true, false, 0, 1], true)) {
            http_response_code(400);
            echo json_encode(['error' => "El campo `pagado` solo puede ser true, false, 1 o 0"]);
            return;
        }

        // Filtramos que el email sea correcto
        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['error' => 'El formato del email no es válido'], JSON_UNESCAPED_UNICODE);
            return;
        }

        // Comprobamos que usuario exista
        $userExiste = $user->getById($userData['id']);
        if (empty($userExiste)) {
            http_response_code(400);
            echo json_encode(['error' => "El usuario no existe"]);
            return;
        }

        // Asignamos la fecha actual a fecha registro
        $fechaRegistro = date('Y-m-d H:i:s');

        $result = $user->put(
            $userData['id'],
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
            echo json_encode(['success' => 'Usuario Actualizado']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al actualizar el usuario']);
        }
    }

    // PATCH
    public function patch(): void
    {
        $userRepository = new User();
        $userData = json_decode(file_get_contents('php://input'), true);

        // Validar que venga el ID
        if (empty($userData['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'El campo `id` es obligatorio']);
            return;
        }

        $usuarioExistente = $userRepository->getById((int)$userData['id']);
        if (!$usuarioExistente) {
            http_response_code(404);
            echo json_encode(['error' => 'El usuario no existe']);
            return;
        }

        // Campos válidos que se pueden actualizar
        $camposActualizables = ['nombre', 'primer_apellido', 'segundo_apellido', 'edad', 'telefono', 'email', 'pagado'];
        $datosActualizados = [];

        foreach ($camposActualizables as $campo) {
            if (array_key_exists($campo, $userData)) {
                if ($campo !== 'pagado' && empty($userData[$campo])) {
                    http_response_code(400);
                    echo json_encode(['error' => "El campo `$campo` no puede estar vacío"], JSON_UNESCAPED_UNICODE);
                    return;
                }

                if ($campo === 'email' && !filter_var($userData[$campo], FILTER_VALIDATE_EMAIL)) {
                    http_response_code(400);
                    echo json_encode(['error' => 'El formato del email no es válido']);
                    return;
                }

                if ($campo === 'pagado' && !in_array($userData[$campo], [true, false, 0, 1], true)) {
                    http_response_code(400);
                    echo json_encode(['error' => "El campo `pagado` solo puede ser true, false, 1 o 0"]);
                    return;
                }

                $datosActualizados[$campo] = $userData[$campo];
            }
        }

        if (empty($datosActualizados)) {
            http_response_code(400);
            echo json_encode(['error' => 'No se proporcionaron campos válidos para actualizar'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $result = $userRepository->patch((int)$userData['id'], $datosActualizados);

        if ($result) {
            http_response_code(200);
            echo json_encode(['success' => 'Usuario actualizado parcialmente']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al actualizar el usuario']);
        }
    }

    // DELETE
    public function deleteById(int $id): void
    {
        $user = new User();

        //Comprobamos que el usuario existe
        if (!$user->getById($id)) {
            http_response_code(400);
            echo json_encode(['error' => "El usuario no existe"]);
            return;
        }

        $user->deleteById($id);

    }


}