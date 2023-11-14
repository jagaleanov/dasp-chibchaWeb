<?php

// Espacio de nombres utilizado por el repositorio
namespace src\repositories;

// Importaciones de otras clases que se usarán en el repositorio

use src\services\DatabaseService;

// Repositorio para gestionar operaciones relacionadas con los clientes en la base de datos
class Repository
{
    // Propiedad para la conexión a la base de datos
    protected $connection;

    // Constructor que establece la conexión a la base de datos
    public function __construct()
    {
        $this->connection = DatabaseService::getInstance()->getConnection();
    }
    
    public function beginTransaction()
    {
        $this->connection->beginTransaction();
    }
    
    public function commit()
    {
        $this->connection->commit();
    }
    
    public function rollback()
    {
        $this->connection->rollback();
    }
}
