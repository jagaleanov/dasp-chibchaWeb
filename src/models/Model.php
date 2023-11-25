<?php

// Espacio de nombres utilizado por el repositorio
namespace src\models;

// Importaciones de otras clases que se usarán en el repositorio

use Exception;
use src\services\DatabaseService;

// Repositorio para gestionar operaciones relacionadas con los clientes en la base de datos
class Model
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
    protected function buildWhereClause($filters = [])
    {
        $conditions = [];
        $params = [];

        foreach ($filters as $key => $filter) {
            if (!empty($filter['value'])) {
                $operator = isset($filter['operator']) ? $filter['operator'] : 'LIKE';
                $placeholder = ':'.str_replace(".", "_", $key);

                switch ($operator) {
                    case 'LIKE':
                        $conditions[] = "$key LIKE $placeholder";
                        $params[$placeholder] = '%' . $filter['value'] . '%';
                        break;
                    case '=':
                    case '<':
                    case '>':
                    case '<=':
                    case '>=':
                    case '!=':
                        $conditions[] = "$key $operator $placeholder";
                        $params[$key] = $filter['value'];
                        break;
                    default:
                        throw new Exception("Operador no soportado: $operator");
                }
            }
        }

        $whereClause = '';
        if (!empty($conditions)) {
            $whereClause = ' WHERE ' . implode(' AND ', $conditions);
        }

        return ['whereClause' => $whereClause, 'params' => $params];
    }
}
