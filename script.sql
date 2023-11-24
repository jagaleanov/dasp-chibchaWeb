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
    updated_at DATETIME DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE INDEX U_users_email (email ASC),
    INDEX IX_users_roles (role_id ASC),
    CONSTRAINT FK_users_roles FOREIGN KEY (role_id) REFERENCES roles(id)
);


CREATE TABLE customers (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    address VARCHAR (100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at DATETIME DEFAULT NULL,
    PRIMARY KEY (id),
    INDEX IX_customers_users (user_id ASC),
    CONSTRAINT FK_customers_users FOREIGN KEY (user_id) REFERENCES users(id)
);


CREATE TABLE employees (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    mobile_phone VARCHAR (10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at DATETIME DEFAULT NULL,
    PRIMARY KEY (id),
    INDEX IX_employees_users (user_id ASC),
    CONSTRAINT FK_employees_users FOREIGN KEY (user_id) REFERENCES users(id)
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
    updated_at DATETIME DEFAULT NULL,
    PRIMARY KEY (id),
    INDEX IX_hosts_customers (customer_id ASC),
    CONSTRAINT FK_hosts_customers FOREIGN KEY (customer_id) REFERENCES customers(id),
    INDEX IX_hosts_host_plans (host_plan_id ASC),
    CONSTRAINT FK_hosts_host_plans FOREIGN KEY (host_plan_id) REFERENCES host_plans(id),
    INDEX IX_hosts_operative_systems (operative_system_id ASC),
    CONSTRAINT FK_hosts_operative_systems FOREIGN KEY (operative_system_id) REFERENCES operative_systems(id),
    INDEX IX_hosts_payment_plans (payment_plan_id ASC),
    CONSTRAINT FK_hosts_payment_plans FOREIGN KEY (payment_plan_id) REFERENCES payment_plans(id)
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
    updated_at DATETIME DEFAULT NULL,
    PRIMARY KEY (number, customer_id),
    UNIQUE INDEX U_credit_cards (customer_id ASC, number ASC),
    INDEX IX_credit_cards_customers (customer_id ASC),
    CONSTRAINT FK_credit_cards_customers FOREIGN KEY (customer_id) REFERENCES customers(id),
    CONSTRAINT CHK_expiration_month CHECK (expiration_month >= 1 AND expiration_month <= 12)
);


CREATE TABLE domains (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    host_id INT UNSIGNED,
    provider_id INT UNSIGNED NOT NULL,
    domain VARCHAR (50) NOT NULL,
    status TINYINT (1) UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at DATETIME DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE INDEX U_domains_domain (domain ASC),
    INDEX IX_domains_hosts (host_id ASC),
    CONSTRAINT FK_domains_hosts FOREIGN KEY (host_id) REFERENCES hosts(id),
    INDEX IX_domains_providers (provider_id ASC),
    CONSTRAINT FK_domains_providers FOREIGN KEY (provider_id) REFERENCES providers(id)
);


CREATE TABLE payments (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    host_id INT UNSIGNED NOT NULL,
    credit_card_customer_id INT UNSIGNED NOT NULL,
    credit_card_number VARCHAR (16) NOT NULL,
    amount INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at DATETIME DEFAULT NULL,
    PRIMARY KEY (id),
    INDEX IX_payments_hosts (host_id ASC),
    CONSTRAINT FK_payments_hosts FOREIGN KEY (host_id) REFERENCES hosts(id),
    INDEX IX_payments_credit_cards (credit_card_customer_id ASC, credit_card_number ASC),
    CONSTRAINT payments_FK FOREIGN KEY (credit_card_number, credit_card_customer_id) REFERENCES credit_cards (number, customer_id)
);


CREATE TABLE tickets (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    host_id INT UNSIGNED NOT NULL,
    role_id INT UNSIGNED DEFAULT NULL,
    status TINYINT (1) UNSIGNED NOT NULL DEFAULT 0,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at DATETIME DEFAULT NULL,
    PRIMARY KEY (id),
    INDEX IX_tickets_hosts (host_id ASC),
    CONSTRAINT FK_tickets_hosts FOREIGN KEY (host_id) REFERENCES hosts(id),
    INDEX IX_tickets_roles (role_id ASC),
    CONSTRAINT FK_tickets_roles FOREIGN KEY (role_id) REFERENCES roles(id)
);


INSERT INTO host_plans VALUES (1,'Chibcha-Platino'),(2,'Chibcha-Plata'),(3,'Chibcha-Oro');
INSERT INTO operative_systems VALUES (1,'Linux'),(2,'Windows');
INSERT INTO payment_plans VALUES (1,'Semanal'),(2,'Mensual'),(3,'Trimestral'),(4,'Anual');
INSERT INTO roles VALUES (1,'Cliente'),(2,'Soporte pagos'),(3,'Soporte host'),(4,'Soporte dominio'),(5,'Soporte tecnico'),(6,'Super Admin');