<?php

// Espacio de nombres utilizado por el repositorio
namespace src\models;

// Repositorio para gestionar operaciones relacionadas con los tickets en la base de datos
class TicketModel extends Model
{
    // Método para encontrar un proveedor de dominios por su ID
    public function find($id)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM tickets WHERE id = :id"
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
        $query = "SELECT t.*, h.ip FROM tickets t
        JOIN hosts h ON t.host_id = h.id";

        // Utilizar la función buildWhereClause para construir la cláusula WHERE y los parámetros
        $whereData = $this->buildWhereClause($filters);
        $query .= $whereData['whereClause'];
        $params = $whereData['params'];
        $query .= " ORDER BY t.created_at DESC";

        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $data = $stmt->fetchAll();

        $tickets = [];
        foreach ($data as $ticketData) {
            $tickets[] = (object) $ticketData;
        }

        return $tickets;
    }

    // Método para insertar un proveedor de dominios en la base de datos
    public function save($ticket)
    {
        try {
            // Inserción del usuario 
            $stmt = $this->connection->prepare(
                "INSERT INTO tickets (host_id, description) VALUES (:host_id, :description)"
            );
            $stmt->execute([
                'host_id' => $ticket->host_id,
                'description' => $ticket->description,
            ]);
            $ticketId = $this->connection->lastInsertId();

            //Respuesta
            return $this->find($ticketId);
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }

    // Método para actualizar un proveedor de dominios en la base de datos
    public function updateRole($id, $role_id)
    {
        try {
            // Actualización del cliente
            $stmt = $this->connection->prepare(
                "UPDATE tickets SET 
                role_id = :role_id,
                updated_at = :updated_at
                WHERE id = :id"
            );
            $stmt->execute([
                'role_id' => $role_id,
                'updated_at' => date('Y-m-d H:i:s'),
                'id' => $id
            ]);

            //Respuesta
            return $this->find($id);
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }

    public function updateStatus($id, $status)
    {
        try {
            // Actualización del cliente
            $stmt = $this->connection->prepare(
                "UPDATE tickets SET 
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

    // // Método para eliminar un proveedor de dominios por su ID
    // public function delete($id)
    // {
    //     try {
    //         //Validación de la relación del user y el ticket
    //         $stmt = $this->connection->prepare("DELETE FROM tickets WHERE id = :id");
    //         $stmt->execute(['id' => $id]);

    //         //Respuesta
    //         return $stmt->rowCount() == 1;
    //     } catch (\Exception $e) {
    //         throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
    //     }
    // }
}
