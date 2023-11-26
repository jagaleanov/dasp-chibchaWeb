<?php

// Definir rutas aquí

// Login
$router->add( '/home', 'HomeController@home');
$router->add( '/login', 'AuthController@loginForm');
$router->add( '/logout', 'AuthController@logout');

// // Usuarios
$router->add('/users/list', 'UserController@getAllUsers');

// // Clientes

$router->add('/customers/details/{id}', 'CustomerController@customerDetails');
$router->add('/customers/details', 'CustomerController@customerDetails');
$router->add('/customers/list', 'CustomerController@getAllCustomers');

// // Empleados
$router->add('/employees/edit/{id}', 'EmployeeController@editEmployee');
$router->add('/employees/details/{id}', 'EmployeeController@employeeDetails');
$router->add('/employees/new', 'EmployeeController@newEmployee');
$router->add('/employees/list', 'EmployeeController@getAllEmployees');

// // Providers
$router->add('/providers/list', 'ProviderController@getAllProviders');
$router->add('/providers/details/{id}', 'ProviderController@providerDetails');

// // Domains
$router->add('/domains/new/{hostId}', 'DomainController@newDomain');
$router->add('/domains/list', 'DomainController@getAllDomains');

// // Credit-cards
$router->add('/credit-cards/validate', 'CreditCardController@validateCreditCard');

// // Hosts
$router->add('/hosts/list', 'HostController@getAllHosts');
$router->add('/hosts/details/{id}', 'HostController@hostDetails');

// // Tickets
$router->add('/tickets/new/{hostId}', 'TicketController@newTicket');
$router->add('/tickets/list', 'TicketController@getAllTickets');

// // Payments
$router->add('/payments/new/{hostId}', 'PaymentController@newPayment');
$router->add('/payments/list', 'PaymentController@getAllPayments');

// Orders
$router->add('/orders/amount', 'OrderController@getAmount');
$router->add('/orders/new', 'OrderController@newOrder');

return $router;
