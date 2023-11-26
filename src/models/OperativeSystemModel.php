<?php

namespace src\models;

class OperativeSystemModel extends Model
{
    public function find($id)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM operative_systems WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if ($data) {
            return (object) $data;
        }

        return null;
    }

    public function findAll($search = null)
    {
        $query =
            "SELECT * FROM operative_systems ";
        $params = [];

        if (!empty($search)) {
            $query .= "WHERE name LIKE :search";
            $params['search'] = '%' . $search . '%';
        }

        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $data = $stmt->fetchAll();

        $operativeSystems = [];
        foreach ($data as $operativeSystemData) {
            $operativeSystems[] = (object) $operativeSystemData;
        }

        return $operativeSystems;
    }
}
