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

    public function index(): void
    {
        $usuarios = $this->userModel->getAll();
        header('Content-Type: application/json');
        echo json_encode($usuarios);
    }

    public function show(int $id): void
    {
        $usuario = $this->userModel->getById($id);
        header('Content-Type: application/json');
        echo json_encode($usuario ?: ['error' => 'Usuario no encontrado']);
    }
}