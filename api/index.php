<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

// Incluir configuraciones y servicios
require_once 'config/database.php';
require_once 'src/services/DatabaseService.php';
require_once 'src/services/ContainerService.php';

// Autocarga simple 
spl_autoload_register(function ($class) {
    $path =  __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) {
        require $path;
    }
});

use src\middlewares\AuthMiddleware;
use src\repositories\UserRepository;
use src\services\ContainerService;

// Inicializar el contenedor de servicios
$container = ContainerService::getInstance();

// Registrar servicios y repositorios en el contenedor
$container->register('DatabaseService', DatabaseService::class);
$container->register('UserRepository', UserRepository::class);

// Inicializar el enrutador y definir rutas, y pasar el contenedor al enrutador
$router = new \src\router\Router($container);

// Inicializar el enrutador y definir rutas
require_once 'config/routes.php';

// Inicializar middleware global (si lo tienes)
$authMiddleware = new AuthMiddleware($router);
$authMiddleware->handle();

// Despachar la solicitud basada en la URI y el método HTTP
$response = $router->dispatch($_SERVER["REQUEST_METHOD"], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
// print $response;

// Envía respuesta
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
