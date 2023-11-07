USE chibchaweb;
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
    address VARCHAR (100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at datetime DEFAULT NULL,
    INDEX IXFK_customers_users (user_id ASC),
    PRIMARY KEY (id)
);

CREATE TABLE employees (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at datetime DEFAULT NULL,
    mobile_phone VARCHAR (10) NOT NULL,
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

CREATE TABLE providers (
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
    ip VARCHAR (15) NOT NULL,
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
    number VARCHAR (16) NOT NULL,
    type ENUM('VISA', 'MASTERCARD', 'AMEX') NOT NULL,
    expiration_year INT (4) UNSIGNED NOT NULL,
    expiration_month TINYINT (2) UNSIGNED NOT NULL,
    security_code VARCHAR (3) NOT NULL,
    name VARCHAR (50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at datetime DEFAULT NULL,
    INDEX IXFK_credit_cards_customers (customer_id ASC),
    PRIMARY KEY (number, customer_id),
    UNIQUE INDEX U_credit_cards (customer_id ASC,number ASC),
    CONSTRAINT CHK_expiration_month CHECK (month >= 1 AND month <= 12)
);

CREATE TABLE domains (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    customer_id INT NOT NULL,
    provider_id INT NOT NULL,
    domain VARCHAR (50) NOT NULL,
    status TINYINT (1) UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at datetime DEFAULT NULL,
    INDEX IXFK_domains_customers (customer_id ASC),
    INDEX IXFK_domains_providers (provider_id ASC),
    PRIMARY KEY (id),
    UNIQUE INDEX U_domains_domain (domain ASC)
);

CREATE TABLE payments (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    host_id INT UNSIGNED NOT NULL,
    credit_card_customer_id INT UNSIGNED NOT NULL,
    credit_card_number VARCHAR (16) NOT NULL,
    amount INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at datetime DEFAULT NULL,
    INDEX IXFK_payments_hosts (host_id ASC),
    INDEX IXFK_payments_credit_cards (credit_card_customer_id ASC,credit_card_number ASC),
    PRIMARY KEY (id)
);

CREATE TABLE tickets (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    host_id INT UNSIGNED NOT NULL,
    role_id INT DEFAULT NULL,
    status TINYINT (1) UNSIGNED NOT NULL DEFAULT 0,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at datetime DEFAULT NULL,
    INDEX IXFK_tickets_hosts (host_id ASC),
    INDEX IXFK_tickets_roles (role_id ASC),
    PRIMARY KEY (id)
);