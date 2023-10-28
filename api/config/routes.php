<?php

// use src\router\Router;

// $router = new Router();

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
$router->add('GET', '/api/customers/{id}', 'CustomerController@getCustomer');
$router->add('GET', '/api/customers', 'CustomerController@getAllCustomers');
$router->add('POST', '/api/customers', 'CustomerController@createCustomer');
$router->add('PUT', '/api/customers/{id}', 'CustomerController@updateCustomer');
// $router->add('DELETE', '/api/customers/{id}', 'CustomerController@deleteCustomer');

// Roles
$router->add('GET', '/api/roles/{id}', 'RolesController@getRole');
$router->add('GET', '/api/roles', 'RolesController@getAllRoles');
$router->add('POST', '/api/roles', 'RolesController@createRole');
$router->add('PUT', '/api/roles/{id}', 'RolesController@updateRole');
// $router->add('DELETE', '/api/roles/{id}', 'RolesController@deleteRole');

return $router;
