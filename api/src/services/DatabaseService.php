<?php

namespace src\services;

class DatabaseService {

    private static $instance = null;
    private $connection;

    private function __construct() {
        $this->connection = new \PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new DatabaseService();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}
