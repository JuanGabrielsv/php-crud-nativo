<?php
// Autoload generado por Composer
require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\HomeController;

// Instancia el controlador principal y ejecuta metodo index
$controlador = new HomeController();
$controlador->index();