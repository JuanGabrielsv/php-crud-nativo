<?php
// Autoload generado por Composer
require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\HomeController;
use App\Controllers\UserController;

// Obtener la URI y metodo HTTP
$uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
if ($uri === '') $uri = '/';
$method = $_SERVER['REQUEST_METHOD'];

//Si no encuentra favicon
if ($uri === '/favicon.ico') {
    http_response_code(204); // No Content
    exit;
}

// Routing
if ($uri === '/' || $uri === '/home') {
    $controlador = new HomeController();
    $controlador->index();

} elseif ($uri === '/usuarios' && $method === 'GET') {
    $controlador = new UserController();
    $controlador->getAll();

} elseif (preg_match('#^/usuarios/(\d+)$#', $uri, $matches) && $method === 'GET') {
    $controlador = new UserController();
    $controlador->getAllById((int)$matches[1]);

} elseif ($uri === '/usuarios' && $method === 'POST') {
    $controlador = new UserController();
    $controlador->create();

} elseif ($uri === '/usuarios' && $method === 'PUT') {

} else {
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Ruta no válida'], JSON_UNESCAPED_UNICODE);
}
