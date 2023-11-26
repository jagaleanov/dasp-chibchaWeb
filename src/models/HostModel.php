<?php

namespace src\models;

class HostModel extends Model
{
    public function find($id)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM hosts WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if ($data) {
            return (object) $data;
        }

        return null;
    }

    public function findByIp($ip)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM hosts WHERE ip = :ip"
        );
        $stmt->execute(['ip' => $ip]);
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
            "SELECT h.*,
            os.name AS operative_system_name,
            pp.name AS payment_plan_name,
            hp.name AS host_plan_name
            FROM hosts h
            JOIN operative_systems os ON h.operative_system_id = os.id
            JOIN payment_plans pp ON h.payment_plan_id = pp.id
            JOIN host_plans hp ON h.host_plan_id = hp.id";

        // Utilizar la función buildWhereClause para construir la cláusula WHERE y los parámetros
        $whereData = $this->buildWhereClause($filters);
        $query .= $whereData['whereClause'];
        $params = $whereData['params'];
        $query .= " ORDER BY h.created_at DESC";

        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $data = $stmt->fetchAll();

        $hosts = [];
        foreach ($data as $hostData) {
            $hosts[] = (object) $hostData;
        }

        return $hosts;
    }

    public function save($host)
    {
        try {
            do {
                // IP address format: xxx.xxx.xxx.xxx
                // Each xxx should be a number between 0 and 255.
                $ip = rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255);
            } while ($this->findByIp($ip != null));

            // Inserción del usuario 
            $stmt = $this->connection->prepare(
                "INSERT INTO hosts (customer_id, host_plan_id, payment_plan_id, operative_system_id,ip) VALUES (:customer_id, :host_plan_id, :payment_plan_id, :operative_system_id,:ip)"
            );


            $stmt->execute([
                'customer_id' => $host->customer_id,
                'host_plan_id' => $host->host_plan_id,
                'payment_plan_id' => $host->payment_plan_id,
                'operative_system_id' => $host->operative_system_id,
                'ip' => $ip,
            ]);
            $hostId = $this->connection->lastInsertId();

            //Respuesta
            return $this->find($hostId);
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }
}
