<?php

// Definir rutas aquí
$router->add('POST', '/api/login', 'AuthController@login', true);

// Usuarios
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

// Empleados
$router->add('GET', '/api/employees/{id}', 'EmployeeController@getCustomer', true);
$router->add('GET', '/api/employees', 'EmployeeController@getAllCustomers', true);
$router->add('POST', '/api/employees', 'EmployeeController@createCustomer', true);
$router->add('PUT', '/api/employees/{id}', 'EmployeeController@updateCustomer', true);
// $router->add('DELETE', '/api/customers/{id}', 'EmployeeController@deleteCustomer');

// Roles
$router->add('GET', '/api/roles/{id}', 'RolesController@getRole', true);
$router->add('GET', '/api/roles', 'RolesController@getAllRoles', true);
$router->add('POST', '/api/roles', 'RolesController@createRole', true);
$router->add('PUT', '/api/roles/{id}', 'RolesController@updateRole', true);
// $router->add('DELETE', '/api/roles/{id}', 'RolesController@deleteRole');

return $router;
