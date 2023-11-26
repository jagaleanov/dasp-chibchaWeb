<?php

namespace src\models;

class TicketModel extends Model
{
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

    public function findAll($filters = [])
    {
        $query = "SELECT t.*, h.ip, r.name AS role_name FROM tickets t
        JOIN hosts h ON t.host_id = h.id
        LEFT JOIN roles r ON t.role_id = r.id";

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

    public function save($ticket)
    {
        try {
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
            throw $e;
        }
    }

    public function updateRole($id, $role_id)
    {
        try {
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

            return $this->find($id);
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }

    public function updateStatus($id, $employeeId)
    {
        try {
            $stmt = $this->connection->prepare(
                "UPDATE tickets SET 
                status = :status,
                employee_id = :employee_id,
                updated_at = :updated_at
                WHERE id = :id"
            );
            $stmt->execute([
                'status' => 1,
                'employee_id' => $employeeId,
                'updated_at' => date('Y-m-d H:i:s'),
                'id' => $id
            ]);

            //Respuesta
            return $this->find($id);
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }
}
