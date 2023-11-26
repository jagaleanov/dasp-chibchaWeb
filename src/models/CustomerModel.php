<?php

namespace src\models;
class CustomerModel extends Model
{
    public function find($id)
    {
        $stmt = $this->connection->prepare(
            "SELECT c.id, u.name, u.last_name, u.email, u.password, c.address, c .user_id, c.created_at, c.updated_at
            FROM customers c 
            JOIN users u ON c.user_id = u.id 
            WHERE c.id = :id"
        );
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if ($data) {
            return (object) $data;
        }

        // Si no se encuentra el cliente, se retorna null
        return null;
    }

    public function findByUserId($userId)
    {
        $stmt = $this->connection->prepare(
            "SELECT c.id, u.name, u.last_name, u.email, u.password, c.address, c .user_id, c.created_at, c.updated_at
            FROM customers c 
            JOIN users u ON c.user_id = u.id 
            WHERE c.user_id = :user_id"
        );
        $stmt->execute(['user_id' => $userId]);
        $data = $stmt->fetch();

        if ($data) {
            return (object) $data;
        }

        return null;
    }
    
    public function findAll($filters = [])
    {
        $query =
            "SELECT c.id, u.name, u.last_name, u.email, u.password, c.address, c .user_id, c.created_at, c.updated_at
        FROM customers c
        JOIN users u ON c.user_id = u.id
        JOIN roles r ON u.role_id = r.id";
        
        // Utilizar la función buildWhereClause para construir la cláusula WHERE y los parámetros
        $whereData = $this->buildWhereClause($filters);
        $query .= $whereData['whereClause'];
        $params = $whereData['params'];

        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $data = $stmt->fetchAll();

        $customers = [];
        foreach ($data as $customerData) {
            $customers[] = (object) $customerData;
        }

        return $customers;
    }
    
    public function save($customer)
    {
        // Iniciar una transacción
        // $this->connection->beginTransaction();

        try {
            // Inserción del usuario 
            $stmt = $this->connection->prepare(
                "INSERT INTO users (name, last_name, email, password, role_id) VALUES (:name, :last_name, :email, :password, :role_id)"
            );
            $stmt->execute([
                'name' => $customer->name,
                'last_name' => $customer->last_name,
                'email' => $customer->email,
                'password' => $customer->password,
                'role_id' => 1
            ]);
            $userId = $this->connection->lastInsertId();

            // Inserción del cliente 
            $stmt = $this->connection->prepare(
                "INSERT INTO customers (user_id,  address) VALUES (:user_id, :address)"
            );
            $stmt->execute([
                'user_id' => $userId,
                'address' => $customer->address,
            ]);

            // Confirmar la transacción
            // $this->connection->commit();

            //Respuesta
            return $this->findByUserId($userId);

        } catch (\Exception $e) {
            // Si hay un error, revertir la transacción
            // $this->connection->rollback();
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }
}
