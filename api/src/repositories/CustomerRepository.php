<?php

// Espacio de nombres utilizado por el repositorio
namespace src\repositories;

// Importaciones de otras clases que se usarán en el repositorio

use src\models\Customer;
use src\services\DatabaseService;

// Repositorio para gestionar operaciones relacionadas con los clientes en la base de datos
class CustomerRepository
{
    // Propiedad para la conexión a la base de datos
    private $connection;

    // Constructor que establece la conexión a la base de datos
    public function __construct()
    {
        $this->connection = DatabaseService::getInstance()->getConnection();
    }

    // Método para encontrar un cliente por su ID
    public function find($id)
    {
        $stmt = $this->connection->prepare(
            "SELECT c.id, u.name, u.last_name, u.email, u.password, u.role_id, c.corporation, c.address, c .user_id, c.created_at, c.updated_at
            FROM customers c 
            JOIN users u ON c.user_id = u.id 
            WHERE c.id = :id"
        );
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if ($data) {
            return new Customer($data);
        }

        // Si no se encuentra el cliente, se retorna null
        return null;
    }

    // Método para encontrar un cliente por su user ID
    public function findByUserId($userId)
    {
        $stmt = $this->connection->prepare(
            "SELECT c.id, u.name, u.last_name, u.email, u.password, u.role_id, c.corporation, c.address, c .user_id, c.created_at, c.updated_at
            FROM customers c 
            JOIN users u ON c.user_id = u.id 
            WHERE c.user_id = :user_id"
        );
        $stmt->execute(['user_id' => $userId]);
        $data = $stmt->fetch();

        if ($data) {
            return new Customer($data);
        }

        // Si no se encuentra el cliente, se retorna null
        return null;
    }

    // Método para encontrar todos los clientes, con opción de filtro por nombre o email
    public function findAll($search = null)
    {
        $query =
            "SELECT c.id, u.name, u.last_name, u.email, u.password, u.role_id, c.corporation, c.address, c .user_id, c.created_at, c.updated_at
        FROM customers c
        JOIN users u ON c.user_id = u.id";
        $params = [];

        // Aplicación de filtros si se proporcionan
        if (!empty($search)) {
            $query .= " WHERE u.name LIKE :search OR u.last_name LIKE :search OR u.email LIKE :search";
            $params['search'] = '%' . $search . '%';
        }

        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $data = $stmt->fetchAll();

        $customers = [];
        foreach ($data as $customerData) {
            $customers[] = new Customer($customerData);
        }

        return $customers;
    }

    // Método para insertar un cliente en la base de datos
    public function save(Customer $customer)
    {

        // Iniciar una transacción
        $this->connection->beginTransaction();

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
                "INSERT INTO customers (user_id, corporation, address) VALUES (:user_id, :corporation, :address)"
            );
            $stmt->execute([
                'user_id' => $userId,
                'corporation' => $customer->corporation,
                'address' => $customer->address,
            ]);

            // Confirmar la transacción
            $this->connection->commit();

            //Respuesta
            return $this->findByUserId($userId);
        } catch (\Exception $e) {
            // Si hay un error, revertir la transacción
            $this->connection->rollback();
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }

    // Método para actualizar un cliente en la base de datos
    public function update(Customer $customer)
    {
        try {
            //Validación de la relación del user y el customer
            $stmt = $this->connection->prepare(
                "SELECT c.id
                FROM customers c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.id = :id
                AND c.user_id = :user_id"
            );
            $stmt->execute([
                'id' => $customer->id,
                'user_id' => $customer->user_id,
            ]);

            // Iniciar una transacción
            $this->connection->beginTransaction();

            // Actualización del usuario
            $stmt = $this->connection->prepare(
                "UPDATE users SET 
                    name = :name, 
                    last_name = :last_name ,
                    email = :email ,
                    password = :password 
                    WHERE id = :id"
            );
            $stmt->execute([
                'name' => $customer->name,
                'last_name' => $customer->last_name,
                'email' => $customer->email,
                'password' => $customer->password,
                'id' => $customer->user_id
            ]);

            // Actualización del cliente
            $stmt = $this->connection->prepare(
                "UPDATE customers SET 
                    corporation = :corporation,
                    address = :address 
                    WHERE id = :id"
            );
            $stmt->execute([
                'corporation' => $customer->corporation,
                'address' => $customer->address,
                'id' => $customer->id
            ]);

            // Confirmar la transacción
            $this->connection->commit();

            //Respuesta
            $customerId = $customer->id;
            return $this->find($customerId);
        } catch (\Exception $e) {
            // Si hay un error, revertir la transacción
            $this->connection->rollback();
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }

    // Método para eliminar un cliente por su ID
    public function delete($id)
    {
        try {
            // Iniciar una transacción
            $this->connection->beginTransaction();

            //Validación de la relación del user y el customer
            $stmt = $this->connection->prepare("DELETE FROM customers WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->rowCount() == 1;

            // Confirmar la transacción
            $this->connection->commit();

            //Respuesta
            return $stmt->rowCount() == 1;
        } catch (\Exception $e) {
            // Si hay un error, revertir la transacción
            $this->connection->rollback();
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }
}
