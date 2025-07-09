<?php
namespace App\Controladores;

class HomeController
{
    public function index()
    {
        // Cargar la vista
        require __DIR__ . '/../../templates/home.php';
    }
}
