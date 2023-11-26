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

    public function findAll($search = null)
    {
        $query =
            "SELECT * FROM host_plans ";
        $params = [];

        // Aplicación de filtros si se proporcionan
        if (!empty($search)) {
            $query .= "WHERE name LIKE :search";
            $params['search'] = '%' . $search . '%';
        }

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
