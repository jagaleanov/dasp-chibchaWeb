<?php

// Definir rutas aquí

// Login
$router->add('POST', '/api/login', 'AuthController@login', true);

// Usuarios
$router->add('GET', '/api/users/{id}','UserController@getUser');
$router->add('GET', '/api/users','UserController@getAllUsers');

// Clientes
$router->add('GET', '/api/customers/{id}', 'CustomerController@getCustomer');
$router->add('GET', '/api/customers', 'CustomerController@getAllCustomers');
$router->add('POST', '/api/customers', 'CustomerController@createCustomer', true);
$router->add('PUT', '/api/customers/{id}', 'CustomerController@updateCustomer');
// $router->add('DELETE', '/api/customers/{id}', 'CustomerController@deleteCustomer');

// Empleados
$router->add('GET', '/api/employees/{id}', 'EmployeeController@getEmployee');
$router->add('GET', '/api/employees', 'EmployeeController@getAllEmployees');
$router->add('POST', '/api/employees', 'EmployeeController@createEmployee');
$router->add('PUT', '/api/employees/{id}', 'EmployeeController@updateEmployee');
// $router->add('DELETE', '/api/customers/{id}', 'EmployeeController@deleteEmployee');

// Roles
$router->add('GET', '/api/roles/{id}', 'RoleController@getRole');
$router->add('GET', '/api/roles', 'RoleController@getAllRoles');
$router->add('POST', '/api/roles', 'RoleController@createRole');
$router->add('PUT', '/api/roles/{id}', 'RoleController@updateRole');
// $router->add('DELETE', '/api/roles/{id}', 'RoleController@deleteRole');

// Providers
$router->add('GET', '/api/providers/{id}', 'ProviderController@getProvider', true);
$router->add('GET', '/api/providers', 'ProviderController@getAllProviders', true);
$router->add('POST', '/api/providers', 'ProviderController@createProvider');
$router->add('PUT', '/api/providers/{id}', 'ProviderController@updateProvider');
// $router->add('DELETE', '/api/providers/{id}', 'ProviderController@deleteProvider');

// Payment-plans
$router->add('GET', '/api/payment-plans/{id}', 'PaymentPlanController@getPaymentPlan', true);
$router->add('GET', '/api/payment-plans', 'PaymentPlanController@getAllPaymentPlans', true);
$router->add('POST', '/api/payment-plans', 'PaymentPlanController@createPaymentPlan');
$router->add('PUT', '/api/payment-plans/{id}', 'PaymentPlanController@updatePaymentPlan');
// $router->add('DELETE', '/api/payment-plans/{id}', 'PaymentPlanController@deletePaymentPlan');

// Host-plans
$router->add('GET', '/api/host-plans/{id}', 'HostPlanController@getHostPlan', true);
$router->add('GET', '/api/host-plans', 'HostPlanController@getAllHostPlans', true);
$router->add('POST', '/api/host-plans', 'HostPlanController@createHostPlan');
$router->add('PUT', '/api/host-plans/{id}', 'HostPlanController@updateHostPlan');
// $router->add('DELETE', '/api/host-plans/{id}', 'HostPlanController@deleteHostPlan');

// Operative-systems
$router->add('GET', '/api/operative-systems/{id}', 'OperativeSystemController@getOperativeSystem', true);
$router->add('GET', '/api/operative-systems', 'OperativeSystemController@getAllOperativeSystems', true);
$router->add('POST', '/api/operative-systems', 'OperativeSystemController@createOperativeSystem');
$router->add('PUT', '/api/operative-systems/{id}', 'OperativeSystemController@updateOperativeSystem');
// $router->add('DELETE', '/api/operative-systems/{id}', 'OperativeSystemController@deleteOperativeSystem');

// Domains
$router->add('GET', '/api/domains/{id}', 'DomainController@getDomainRequest');
$router->add('GET', '/api/domains', 'DomainController@getAllDomainRequests');
$router->add('POST', '/api/domains', 'DomainController@createDomainRequest');
$router->add('PUT', '/api/domains/{id}', 'DomainController@updateDomainRequest');
// $router->add('DELETE', '/api/domains/{id}', 'DomainController@deleteDomainRequest');

// Credit-cards
$router->add('GET', '/api/credit-cards/{customerId}/{number}', 'CreditCardController@getCreditCard');
$router->add('GET', '/api/credit-cards/{number}', 'CreditCardController@validateCreditCard', true);
$router->add('GET', '/api/credit-cards', 'CreditCardController@getAllCreditCards');
$router->add('POST', '/api/credit-cards', 'CreditCardController@createCreditCard', true);
$router->add('DELETE', '/api/credit-cards/{customerId,number}', 'CreditCardController@deleteCreditCard');

// Hosts
$router->add('GET', '/api/hosts/{id}', 'HostController@getHost', true);
$router->add('GET', '/api/hosts', 'HostController@getAllHosts', true);
$router->add('POST', '/api/hosts', 'HostController@createHost', true);
$router->add('PUT', '/api/hosts/{id}', 'HostController@updateHost', true);
// $router->add('DELETE', '/api/hosts/{id}', 'HostController@deleteHost');

// Tickets
$router->add('GET', '/api/tickets/{id}', 'TicketController@getTicket', true);
$router->add('GET', '/api/tickets', 'TicketController@getAllTickets', true);
$router->add('POST', '/api/tickets', 'TicketController@createTicket', true);
$router->add('PATCH', '/api/tickets/{id}/set-role', 'TicketController@updateTicketRole', true);
$router->add('PATCH', '/api/tickets/{id}/set-status', 'TicketController@updateTicketStatus', true);
// $router->add('DELETE', '/api/tickets/{id}', 'TicketController@deleteTicket');

// Payments
$router->add('GET', '/api/payments/{id}', 'PaymentController@getPayment', true);
$router->add('GET', '/api/payments', 'PaymentController@getAllPayments', true);
$router->add('POST', '/api/payments', 'PaymentController@createPayment', true);
$router->add('PUT', '/api/payments/{id}', 'PaymentController@updatePayment', true);
// $router->add('DELETE', '/api/payments/{id}', 'PaymentController@deletePayment');

// Orders
$router->add('GET', '/api/orders/amount/{hostingPlanId}/{operativeSystemId}/{paymentPlanId}', 'OrderController@getAmount', true);

return $router;
