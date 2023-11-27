<?php

namespace src\models;

class HostPlanModel extends Model
{
    public function find($id)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM host_plans WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if ($data) {
            return (object) $data;
        }

        // Si no se encuentra el cliente, se retorna null
        return null;
    }

    public function findAll($filters = [])
    {
        $query =
            "SELECT * FROM host_plans ";

            $whereData = $this->buildWhereClause($filters);
            $query .= $whereData['whereClause'];
            $params = $whereData['params'];
            $query .= " ORDER BY created_at DESC";
    
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
    
            $data = $stmt->fetchAll();

        $hostPlans = [];
        foreach ($data as $hostPlanData) {
            $hostPlans[] = (object) $hostPlanData;
        }

        return $hostPlans;
    }
}
