<?php

namespace src\models;

use Exception;
use src\services\DatabaseService;

class Model
{
    protected $connection;

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
                $placeholder = ':' . str_replace(".", "_", $key);

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
