<?php

// Definir rutas aquí

// Login
$router->add('POST', '/api/login', 'AuthController@login', true);

// Usuarios
$router->add('GET', '/api/users/{id}','UserController@getUser', true);
$router->add('GET', '/api/users','UserController@getAllUsers', true);

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
$router->add('GET', '/api/roles/{id}', 'RoleController@getRole', true);
$router->add('GET', '/api/roles', 'RoleController@getAllRoles', true);
$router->add('POST', '/api/roles', 'RoleController@createRole', true);
$router->add('PUT', '/api/roles/{id}', 'RoleController@updateRole', true);
// $router->add('DELETE', '/api/roles/{id}', 'RoleController@deleteRole');

// Providers
$router->add('GET', '/api/providers/{id}', 'ProviderController@getProvider', true);
$router->add('GET', '/api/providers', 'ProviderController@getAllProviders', true);
$router->add('POST', '/api/providers', 'ProviderController@createProvider', true);
$router->add('PUT', '/api/providers/{id}', 'ProviderController@updateProvider', true);
// $router->add('DELETE', '/api/providers/{id}', 'ProviderController@deleteProvider');

// Payment-plans
$router->add('GET', '/api/payment-plans/{id}', 'PaymentPlanController@getPaymentPlan', true);
$router->add('GET', '/api/payment-plans', 'PaymentPlanController@getAllPaymentPlans', true);
$router->add('POST', '/api/payment-plans', 'PaymentPlanController@createPaymentPlan', true);
$router->add('PUT', '/api/payment-plans/{id}', 'PaymentPlanController@updatePaymentPlan', true);
// $router->add('DELETE', '/api/payment-plans/{id}', 'PaymentPlanController@deletePaymentPlan');

// Host-plans
$router->add('GET', '/api/host-plans/{id}', 'HostPlanController@getHostPlan', true);
$router->add('GET', '/api/host-plans', 'HostPlanController@getAllHostPlans', true);
$router->add('POST', '/api/host-plans', 'HostPlanController@createHostPlan', true);
$router->add('PUT', '/api/host-plans/{id}', 'HostPlanController@updateHostPlan', true);
// $router->add('DELETE', '/api/host-plans/{id}', 'HostPlanController@deleteHostPlan');

// Operative-systems
$router->add('GET', '/api/operative-systems/{id}', 'OperativeSystemController@getOperativeSystem', true);
$router->add('GET', '/api/operative-systems', 'OperativeSystemController@getAllOperativeSystems', true);
$router->add('POST', '/api/operative-systems', 'OperativeSystemController@createOperativeSystem', true);
$router->add('PUT', '/api/operative-systems/{id}', 'OperativeSystemController@updateOperativeSystem', true);
// $router->add('DELETE', '/api/operative-systems/{id}', 'OperativeSystemController@deleteOperativeSystem');

// Domains
$router->add('GET', '/api/domains/{id}', 'DomainController@getDomainRequest', true);
$router->add('GET', '/api/domains', 'DomainController@getAllDomainRequests', true);
$router->add('POST', '/api/domains', 'DomainController@createDomainRequest', true);
$router->add('PUT', '/api/domains/{id}', 'DomainController@updateDomainRequest', true);
// $router->add('DELETE', '/api/domains/{id}', 'DomainController@deleteDomainRequest');

// Credit-cards
$router->add('GET', '/api/credit-cards/{customerId,number}', 'CreditCardController@getCreditCard', true);
$router->add('GET', '/api/credit-cards', 'CreditCardController@getAllCreditCards', true);
$router->add('POST', '/api/credit-cards', 'CreditCardController@createCreditCard', true);
$router->add('DELETE', '/api/credit-cards/{customerId,number}', 'CreditCardController@deleteCreditCard',true);

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

return $router;
