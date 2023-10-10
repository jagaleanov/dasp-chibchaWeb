<?php

// Incluir configuraciones y servicios
require_once 'config/database.php';
require_once 'src/services/DatabaseService.php';

// Autocarga simple sin Composer
spl_autoload_register(function($class) {
    $path =  __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) {
        require $path;
    }
});


use src\middlewares\AuthMiddleware;

// Inicializar middleware global (si lo tienes)
$authMiddleware = new AuthMiddleware();
$authMiddleware->handle();

// Inicializar el enrutador y definir rutas
$router = require_once 'config/routes.php';

// Despachar la solicitud basada en la URI y el método HTTP
$response = $router->dispatch($_SERVER["REQUEST_METHOD"], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Envía respuesta
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
