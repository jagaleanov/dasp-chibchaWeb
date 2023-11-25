<?php

// Definir rutas aquí

// Login
$router->add( '/home', 'HomeController@home');
$router->add( '/login', 'AuthController@loginForm');
$router->add( '/logout', 'AuthController@logout');
// $router->add('POST', '/login', 'AuthController@login', true);

// // Usuarios
$router->add('/users/list', 'UserController@getAllUsers');
// $router->add('GET', '/users/{id}','UserController@getUser', true);

// // Clientes

$router->add('/customers/details/{id}', 'CustomerController@customerDetails');
$router->add('/customers/details', 'CustomerController@customerDetails');
$router->add('/customers/list', 'CustomerController@getAllCustomers');

// // Empleados
$router->add('/employees/new', 'EmployeeController@newEmployee');
$router->add('/employees/list', 'EmployeeController@getAllEmployees');

// // Providers
$router->add('/providers/list', 'ProviderController@getAllProviders');
// $router->add('GET', '/providers/{id}', 'ProviderController@getProvider', true);
// // $router->add('POST', '/providers', 'ProviderController@createProvider');
// // $router->add('PUT', '/providers/{id}', 'ProviderController@updateProvider');
// // $router->add('DELETE', '/providers/{id}', 'ProviderController@deleteProvider');

// // Domains
$router->add('/domains/new/{hostId}', 'DomainController@newDomain');
$router->add('/domains/list', 'DomainController@getAllDomains');

// // Credit-cards
// $router->add('GET', '/credit-cards/{customerId}/{number}', 'CreditCardController@getCreditCard');
$router->add('/credit-cards/validate', 'CreditCardController@validateCreditCard');
// $router->add('GET', '/credit-cards', 'CreditCardController@getAllCreditCards');
// $router->add('POST', '/credit-cards', 'CreditCardController@createCreditCard', true);
// $router->add('DELETE', '/credit-cards/{customerId,number}', 'CreditCardController@deleteCreditCard');

// // Hosts
// $router->add('GET', '/hosts/customer/{id}', 'HostController@getCustomerHosts', true);
// $router->add('GET', '/hosts/{id}', 'HostController@getHost', true);
// $router->add('GET', '/hosts', 'HostController@getAllHosts', true);
// // $router->add('POST', '/hosts', 'HostController@createHost', true);
// $router->add('PUT', '/hosts/{id}', 'HostController@updateHost', true);
// // $router->add('DELETE', '/hosts/{id}', 'HostController@deleteHost');

// // Tickets
$router->add('/tickets/new/{hostId}', 'TicketController@newTicket');
$router->add('/tickets/list', 'TicketController@getAllTickets');
// $router->add('GET', '/tickets/{id}', 'TicketController@getTicket', true);
// $router->add('PATCH', '/tickets/{id}/set-role', 'TicketController@updateTicketRole', true);
// $router->add('PATCH', '/tickets/{id}/set-status', 'TicketController@updateTicketStatus', true);
// // $router->add('DELETE', '/tickets/{id}', 'TicketController@deleteTicket');

// // Payments
$router->add('/payments/new/{hostId}', 'PaymentController@newPayment');
$router->add('/payments/list', 'PaymentController@getAllPayments');
// $router->add('GET', '/payments/{id}', 'PaymentController@getPayment', true);
// $router->add('PATCH', '/payments/{id}/set-status', 'PaymentController@updatePaymentStatus', true);
// $router->add('PUT', '/payments/{id}', 'PaymentController@updatePayment', true);
// // $router->add('DELETE', '/payments/{id}', 'PaymentController@deletePayment');

// Orders
$router->add('/orders/amount', 'OrderController@getAmount');
// $router->add('/orders', 'OrderController@createOrder');

$router->add('/orders/new', 'OrderController@newOrder');

return $router;
