CREATE TABLE roles (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR (50) NULL,
    PRIMARY KEY (id)
);

CREATE TABLE users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    role_id INT UNSIGNED NOT NULL,
    email VARCHAR (50) NOT NULL,
    name VARCHAR (50) NOT NULL,
    last_name VARCHAR (50) NOT NULL,
    password VARCHAR (255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at datetime DEFAULT NULL,
    INDEX IXFK_users_roles (role_id ASC),
    PRIMARY KEY (id),
    UNIQUE INDEX U_users_email (email ASC)
);

CREATE TABLE customers (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    corporation VARCHAR (50) NULL,
    address VARCHAR (100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at datetime DEFAULT NULL,
    INDEX IXFK_customers_users (user_id ASC),
    PRIMARY KEY (id)
);

CREATE TABLE employees (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    job_title VARCHAR (50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at datetime DEFAULT NULL,
    INDEX IXFK_employees_users (user_id ASC),
    PRIMARY KEY (id)
);

CREATE TABLE payment_plans (
    id TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR (50) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE host_plans (
    id TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR (50) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE operative_systems (
    id TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR (50) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE domain_distribuitors (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR (50) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE hosts (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    customer_id INT UNSIGNED NOT NULL,
    host_plan_id TINYINT UNSIGNED NOT NULL,
    payment_plan_id TINYINT UNSIGNED NOT NULL,
    operative_system_id TINYINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at datetime DEFAULT NULL,
    INDEX IXFK_hosts_customers (customer_id ASC),
    INDEX IXFK_hosts_host_plans (host_plan_id ASC),
    INDEX IXFK_hosts_operative_systems (operative_system_id ASC),
    INDEX IXFK_hosts_payment_plans (payment_plan_id ASC),
    PRIMARY KEY (id)
);

CREATE TABLE credit_cards (
    customer_id INT UNSIGNED NOT NULL,
    number VARCHAR (15) NOT NULL,
    type ENUM('VISA', 'MASTERCARD', 'AMEX') NOT NULL,
    expiration_date DATE NOT NULL,
    security_code VARCHAR (3) NOT NULL,
    name VARCHAR (50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at datetime DEFAULT NULL,
    INDEX IXFK_credit_cards_customers (customer_id ASC),
    PRIMARY KEY (number, customer_id)
);

CREATE TABLE domain_requests (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    customer_id INT NOT NULL,
    domain_distribuitor_id INT NOT NULL,
    domain VARCHAR (50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at datetime DEFAULT NULL,
    INDEX IXFK_domain_requests_customers (customer_id ASC),
    INDEX IXFK_domain_requests_domain_distribuitors (domain_distribuitor_id ASC),
    PRIMARY KEY (id),
    UNIQUE INDEX U_domain_requests_domain (domain ASC)
);

CREATE TABLE payments (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    host_id INT UNSIGNED NOT NULL,
    amount INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at datetime DEFAULT NULL,
    INDEX IXFK_payments_hosts (host_id ASC),
    PRIMARY KEY (id)
);

CREATE TABLE tickets (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    host_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NULL,
    status VARCHAR (50) NOT NULL,
    employee_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at datetime DEFAULT NULL,
    INDEX IXFK_tickets_employees (employee_id ASC),
    INDEX IXFK_tickets_hosts (host_id ASC),
    PRIMARY KEY (id, host_id)
);