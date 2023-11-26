<?php

namespace src\models;

class ProviderModel extends Model
{
    public function find($id)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM providers WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if ($data) {
            return (object) $data;
        }
        
        return null;
    }

    public function findAll($filters = [])
    {
        $query =
            "SELECT * FROM providers ";

        $whereData = $this->buildWhereClause($filters);
        $query .= $whereData['whereClause'];
        $params = $whereData['params'];

        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $data = $stmt->fetchAll();

        $providers = [];
        foreach ($data as $providerData) {
            $providers[] = (object) $providerData;
        }

        return $providers;
    }
}
