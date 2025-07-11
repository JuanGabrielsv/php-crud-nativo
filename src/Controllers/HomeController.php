<?php
namespace App\Controllers;

class HomeController
{
    public function index(): void
    {
        // Cargar la vista
        require __DIR__ . '/../../templates/home.php';
    }
}
