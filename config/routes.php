<?php

// Definir rutas aquí

// Login
$router->add( '/home', 'HomeController@home');
$router->add( '/login', 'AuthController@loginForm');
$router->add( '/logout', 'AuthController@logout');
// $router->add('POST', '/login', 'AuthController@login', true);

// // Usuarios
// $router->add('GET', '/users/{id}','UserController@getUser', true);
// $router->add('GET', '/users','UserController@getAllUsers', true);

// // Clientes

$router->add('/customers/details', 'CustomerController@customerDetails');
// $router->add('GET', '/customers/new', 'CustomerController@newCustomer', true);
// $router->add('GET', '/customers/{id}', 'CustomerController@getCustomer');
// $router->add('GET', '/customers', 'CustomerController@getAllCustomers', true);
// $router->add('POST', '/customers', 'CustomerController@createCustomer', true);
// $router->add('PUT', '/customers/{id}', 'CustomerController@updateCustomer');
// // $router->add('DELETE', '/customers/{id}', 'CustomerController@deleteCustomer');

// // Empleados
// $router->add('GET', '/employees/{id}', 'EmployeeController@getEmployee');
// $router->add('GET', '/employees', 'EmployeeController@getAllEmployees');
// $router->add('POST', '/employees', 'EmployeeController@createEmployee');
// $router->add('PUT', '/employees/{id}', 'EmployeeController@updateEmployee');
// // $router->add('DELETE', '/customers/{id}', 'EmployeeController@deleteEmployee');

// // Roles
// $router->add('GET', '/roles/{id}', 'RoleController@getRole');
// $router->add('GET', '/roles', 'RoleController@getAllRoles');
// // $router->add('POST', '/roles', 'RoleController@createRole');
// // $router->add('PUT', '/roles/{id}', 'RoleController@updateRole');
// // $router->add('DELETE', '/roles/{id}', 'RoleController@deleteRole');

// // Providers
// $router->add('GET', '/providers/{id}', 'ProviderController@getProvider', true);
// $router->add('GET', '/providers', 'ProviderController@getAllProviders', true);
// // $router->add('POST', '/providers', 'ProviderController@createProvider');
// // $router->add('PUT', '/providers/{id}', 'ProviderController@updateProvider');
// // $router->add('DELETE', '/providers/{id}', 'ProviderController@deleteProvider');

// // Payment-plans
// $router->add('GET', '/payment-plans/{id}', 'PaymentPlanController@getPaymentPlan', true);
// $router->add('GET', '/payment-plans', 'PaymentPlanController@getAllPaymentPlans', true);
// // $router->add('POST', '/payment-plans', 'PaymentPlanController@createPaymentPlan');
// // $router->add('PUT', '/payment-plans/{id}', 'PaymentPlanController@updatePaymentPlan');
// // $router->add('DELETE', '/payment-plans/{id}', 'PaymentPlanController@deletePaymentPlan');

// // Host-plans
// $router->add('GET', '/host-plans/{id}', 'HostPlanController@getHostPlan', true);
// $router->add('GET', '/host-plans', 'HostPlanController@getAllHostPlans', true);
// // $router->add('POST', '/host-plans', 'HostPlanController@createHostPlan');
// // $router->add('PUT', '/host-plans/{id}', 'HostPlanController@updateHostPlan');
// // $router->add('DELETE', '/host-plans/{id}', 'HostPlanController@deleteHostPlan');

// // Operative-systems
// $router->add('GET', '/operative-systems/{id}', 'OperativeSystemController@getOperativeSystem', true);
// $router->add('GET', '/operative-systems', 'OperativeSystemController@getAllOperativeSystems', true);
// // $router->add('POST', '/operative-systems', 'OperativeSystemController@createOperativeSystem');
// // $router->add('PUT', '/operative-systems/{id}', 'OperativeSystemController@updateOperativeSystem');
// // $router->add('DELETE', '/operative-systems/{id}', 'OperativeSystemController@deleteOperativeSystem');

// // Domains
$router->add('/domains/new/{hostId}', 'DomainController@newDomain');
// $router->add('GET', '/domains/{id}', 'DomainController@getDomainRequest');
// $router->add('GET', '/domains', 'DomainController@getAllDomainRequests');
// // $router->add('POST', '/domains', 'DomainController@createDomainRequest');
// // $router->add('PUT', '/domains/{id}', 'DomainController@updateDomainRequest');
// // $router->add('DELETE', '/domains/{id}', 'DomainController@deleteDomainRequest');

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
// $router->add('GET', '/tickets/{id}', 'TicketController@getTicket', true);
// $router->add('GET', '/tickets', 'TicketController@getAllTickets', true);
// $router->add('POST', '/tickets', 'TicketController@createTicket', true);
// $router->add('PATCH', '/tickets/{id}/set-role', 'TicketController@updateTicketRole', true);
// $router->add('PATCH', '/tickets/{id}/set-status', 'TicketController@updateTicketStatus', true);
// // $router->add('DELETE', '/tickets/{id}', 'TicketController@deleteTicket');

// // Payments
// $router->add('GET', '/payments/{id}', 'PaymentController@getPayment', true);
// $router->add('GET', '/payments', 'PaymentController@getAllPayments', true);
// $router->add('POST', '/payments', 'PaymentController@createPayment', true);
// $router->add('PATCH', '/payments/{id}/set-status', 'PaymentController@updatePaymentStatus', true);
// $router->add('PUT', '/payments/{id}', 'PaymentController@updatePayment', true);
// // $router->add('DELETE', '/payments/{id}', 'PaymentController@deletePayment');

// Orders
$router->add('/orders/amount', 'OrderController@getAmount');
// $router->add('/orders', 'OrderController@createOrder');

$router->add('/orders/new', 'OrderController@newOrder');

return $router;
