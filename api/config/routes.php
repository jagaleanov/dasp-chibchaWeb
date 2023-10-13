<?php

use src\router\Router;

$router = new Router();

// Definir rutas aquí
$router->add('GET', '/api/customers/{id}', 'CustomerController@getCustomer');
$router->add('GET', '/api/customers', 'CustomerController@getAllCustomers');
$router->add('POST', '/api/customers', 'CustomerController@createCustomer');
$router->add('PUT', '/api/customers/{id}', 'CustomerController@updateCustomer');
$router->add('DELETE', '/api/customers/{id}', 'CustomerController@deleteCustomer');

return $router;
