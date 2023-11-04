<?php

// Definir rutas aquí
$router->add('POST', '/api/login', 'AuthController@login', true);
// $router->add('POST', '/api/register', 'AuthController@register', true);

//Usuarios
$router->add('GET', '/api/users/{id}','UserController@getUser', true);
$router->add('GET', '/api/users','UserController@getAllUsers', true);
$router->add('POST', '/api/users', 'UserController@createUser', true);
$router->add('PUT', '/api/users/{id}','UserController@updateUser', true);
// $router->add('DELETE', '/api/users/{id}', 'UserController@deleteCustomer');

// Clientes
$router->add('GET', '/api/customers/{id}', 'CustomerController@getCustomer', true);
$router->add('GET', '/api/customers', 'CustomerController@getAllCustomers', true);
$router->add('POST', '/api/customers', 'CustomerController@createCustomer', true);
$router->add('PUT', '/api/customers/{id}', 'CustomerController@updateCustomer', true);
// $router->add('DELETE', '/api/customers/{id}', 'CustomerController@deleteCustomer');

return $router;
