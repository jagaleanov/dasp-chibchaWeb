<?php

namespace src\models;

class DomainModel extends Model
{
    public function find($id)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM domains WHERE id = :id"
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
            "SELECT d.*, h.ip, p.name AS provider_name
            FROM domains d 
            JOIN hosts h ON d.host_id = h.id
            JOIN providers p ON p.id=d.provider_id";

        $whereData = $this->buildWhereClause($filters);
        $query .= $whereData['whereClause'];
        $params = $whereData['params'];
        $query .= " ORDER BY d.created_at DESC";

        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $data = $stmt->fetchAll();

        $domains = [];
        foreach ($data as $domainData) {
            $domains[] = (object) $domainData;
        }

        return $domains;
    }

    public function save($domain)
    {
        try {
            // Inserción del usuario 
            $stmt = $this->connection->prepare(
                "INSERT INTO domains (host_id, domain, provider_id) VALUES (:host_id, :domain, :provider_id)"
            );
            $stmt->execute([
                'host_id' => $domain->host_id,
                'domain' => $domain->domain,
                'provider_id' => $domain->provider_id,
            ]);
            $domainId = $this->connection->lastInsertId();

            //Respuesta
            return $this->find($domainId);
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }

    public function updateStatus($id, $status)
    {
        try {
            $stmt = $this->connection->prepare(
                "UPDATE domains SET 
                status = :status,
                updated_at = :updated_at
                WHERE id = :id"
            );
            $stmt->execute([
                'status' => $status,
                'updated_at' => date('Y-m-d H:i:s'),
                'id' => $id
            ]);

            //Respuesta
            return $this->find($id);
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }

    public function countApprovedDomains($providerId)
    {
        try {
            $stmt = $this->connection->prepare(
                "SELECT COUNT(*) AS counter FROM domains WHERE provider_id = :provider_id AND status = :status"
            );
            $stmt->execute([
                'provider_id' => $providerId,
                'status' => 1,
            ]);
            $data = $stmt->fetch();

            //Respuesta
            return $data['counter'];
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }
}
