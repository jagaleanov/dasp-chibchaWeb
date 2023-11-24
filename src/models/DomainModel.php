<?php

// Espacio de nombres utilizado por el repositorio
namespace src\models;

// Importaciones de otras clases que se usarán en el repositorio


// Repositorio para gestionar operaciones relacionadas con los domains en la base de datos
class DomainModel extends Model
{
    // Método para encontrar un proveedor de dominios por su ID
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

    // Método para encontrar todos los clientes, con opción de filtro por nombre o email
    public function findAll($filters = [])
    {
        $query =
            "SELECT d.*, h.ip 
            FROM domains d 
            JOIN hosts h ON d.host_id = h.id";

        // Utilizar la función buildWhereClause para construir la cláusula WHERE y los parámetros
        $whereData = $this->buildWhereClause($filters);
        $query .= $whereData['whereClause'];
        $params = $whereData['params'];

        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $data = $stmt->fetchAll();

        $domains = [];
        foreach ($data as $domainData) {
            $domains[] = (object) $domainData;
        }

        return $domains;
    }

    // Método para insertar un proveedor de dominios en la base de datos
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

    // Método para actualizar un proveedor de dominios en la base de datos
    public function update($domain)
    {
        try {
            // Actualización del cliente
            $stmt = $this->connection->prepare(
                "UPDATE domains SET 
                customer_id = :customer_id,
                provider_id = :provider_id,
                domain = :domain,
                status = :status,
                updated_at = :updated_at
                WHERE id = :id"
            );
            $stmt->execute([
                'customer_id' => $domain->customer_id,
                'provider_id' => $domain->provider_id,
                'domain' => $domain->domain,
                'status' => $domain->status,
                'updated_at' => date('Y-m-d H:i:s'),
                'id' => $domain->id
            ]);

            //Respuesta
            return $this->find($domain->id);
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }

    // // Método para eliminar un proveedor de dominios por su ID
    // public function delete($id)
    // {
    //     try {
    //         //Validación de la relación del user y el domain
    //         $stmt = $this->connection->prepare("DELETE FROM domains WHERE id = :id");
    //         $stmt->execute(['id' => $id]);

    //         //Respuesta
    //         return $stmt->rowCount() == 1;
    //     } catch (\Exception $e) {
    //         throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
    //     }
    // }
}
