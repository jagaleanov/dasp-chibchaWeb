<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir configuraciones y servicios
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'src/services/DatabaseService.php';
require_once 'src/services/ModelService.php';

// Autocarga simple 
spl_autoload_register(function ($class) {
    $path =  __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) {
        require $path;
    }
});

use src\middlewares\AuthMiddleware;


use src\models\CreditCardModel;
use src\models\UserModel;
use src\models\CustomerModel;
use src\models\DomainModel;
use src\models\EmployeeModel;
use src\models\HostPlanModel;
use src\models\HostModel;
use src\models\OperativeSystemModel;
use src\models\PaymentPlanModel;
use src\models\PaymentModel;
use src\models\ProviderModel;
use src\models\RoleModel;
use src\models\TicketModel;
use src\services\ModelService;

// Inicializar el contenedor de servicios
$container = ModelService::getInstance();

// Registrar  repositorios en el contenedor

$container->register('UserModel', UserModel::class);
$container->register('CustomerModel', CustomerModel::class);
$container->register('EmployeeModel', EmployeeModel::class);
$container->register('RoleModel', RoleModel::class);
$container->register('ProviderModel', ProviderModel::class);
$container->register('PaymentPlanModel', PaymentPlanModel::class);
$container->register('HostPlanModel', HostPlanModel::class);
$container->register('OperativeSystemModel', OperativeSystemModel::class);
$container->register('DomainModel', DomainModel::class);
$container->register('HostModel', HostModel::class);
$container->register('PaymentModel', PaymentModel::class);
$container->register('TicketModel', TicketModel::class);
$container->register('CreditCardModel', CreditCardModel::class);

// Inicializar el enrutador y definir rutas, y pasar el contenedor al enrutador
$router = new \src\router\Router($container);

// Inicializar el enrutador y definir rutas
require_once 'config/routes.php';

// Despachar la solicitud basada en la URI y el método HTTP
/*$response = */$router->dispatch(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// // Envía respuesta
// header('Content-Type: application/json; charset=utf-8');
// echo json_encode($response, JSON_UNESCAPED_UNICODE);
