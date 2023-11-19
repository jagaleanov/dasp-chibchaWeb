<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir configuraciones y servicios
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'src/services/DatabaseService.php';
require_once 'src/services/RepositoryService.php';

// Autocarga simple 
spl_autoload_register(function ($class) {
    $path =  __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) {
        require $path;
    }
});

use src\middlewares\AuthMiddleware;


use src\repositories\CreditCardRepository;
use src\repositories\UserRepository;
use src\repositories\CustomerRepository;
use src\repositories\DomainRepository;
use src\repositories\EmployeeRepository;
use src\repositories\HostPlanRepository;
use src\repositories\HostRepository;
use src\repositories\OperativeSystemRepository;
use src\repositories\PaymentPlanRepository;
use src\repositories\PaymentRepository;
use src\repositories\ProviderRepository;
use src\repositories\RoleRepository;
use src\repositories\TicketRepository;
use src\services\RepositoryService;

// Inicializar el contenedor de servicios
$container = RepositoryService::getInstance();

// Registrar  repositorios en el contenedor

$container->register('UserRepository', UserRepository::class);
$container->register('CustomerRepository', CustomerRepository::class);
$container->register('EmployeeRepository', EmployeeRepository::class);
$container->register('RoleRepository', RoleRepository::class);
$container->register('ProviderRepository', ProviderRepository::class);
$container->register('PaymentPlanRepository', PaymentPlanRepository::class);
$container->register('HostPlanRepository', HostPlanRepository::class);
$container->register('OperativeSystemRepository', OperativeSystemRepository::class);
$container->register('DomainRepository', DomainRepository::class);
$container->register('HostRepository', HostRepository::class);
$container->register('PaymentRepository', PaymentRepository::class);
$container->register('TicketRepository', TicketRepository::class);
$container->register('CreditCardRepository', CreditCardRepository::class);

// Inicializar el enrutador y definir rutas, y pasar el contenedor al enrutador
$router = new \src\router\Router($container);

// Inicializar el enrutador y definir rutas
require_once 'config/routes.php';

// Despachar la solicitud basada en la URI y el método HTTP
/*$response = */$router->dispatch(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// // Envía respuesta
// header('Content-Type: application/json; charset=utf-8');
// echo json_encode($response, JSON_UNESCAPED_UNICODE);
