<?php

use src\router\Router;

$router = new Router();

// Definir rutas aquí
$router->add('GET', '/customers/{id}', 'CustomerController@getCustomer');
$router->add('GET', '/customers', 'CustomerController@getAllCustomers');
$router->add('POST', '/customers', 'CustomerController@createCustomer');
$router->add('PUT', '/customers/{id}', 'CustomerController@updateCustomer');
$router->add('DELETE', '/customers/{id}', 'CustomerController@deleteCustomer');

return $router;
