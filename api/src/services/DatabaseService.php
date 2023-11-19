<?php

namespace src\services;

use PDO;

// Servicio para gestionar la conexión a la base de datos usando el patrón Singleton
class DatabaseService
{

    // Propiedad estática para almacenar la única instancia de DatabaseService
    private static $instance = null;

    // Propiedad para la conexión a la base de datos
    private $connection;

    // Constructor privado para evitar la creación directa de objetos de DatabaseService
    private function __construct()
    {
        // Establece la conexión a la base de datos usando PDO
        $this->connection = new \PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    // Método estático para obtener la única instancia de DatabaseService
    public static function getInstance()
    {
        // Si la instancia no existe, la crea
        if (self::$instance == null) {
            self::$instance = new self();
        }
        // Devuelve la única instancia de DatabaseService
        return self::$instance;
    }

    // Método para obtener la conexión a la base de datos
    public function getConnection()
    {
        return $this->connection;
    }
}
