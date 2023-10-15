<?php

// Espacio de nombres utilizado por el repositorio
namespace src\repositories;

// Importaciones de otras clases que se usarán en el repositorio
use src\services\DatabaseService;
use src\models\Customer;

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
        $stmt = $this->connection->prepare("SELECT * FROM customers WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if ($data) {
            return new Customer($data['id'], $data['name'], $data['email'], $data['created_at'], $data['updated_at']);
        }

        // Si no se encuentra el cliente, se retorna null
        return null;
    }

    // Método para encontrar todos los clientes, con opción de filtro por nombre o email
    public function findAll($filter = [])
    {
        $query = "SELECT * FROM customers";
        $params = [];

        // Aplicación de filtros si se proporcionan
        if (!empty($filter['name'])) {
            $query .= " WHERE name LIKE :name";
            $params['name'] = '%' . $filter['name'] . '%';
        } elseif (!empty($filter['email'])) {
            $query .= " WHERE email LIKE :email";
            $params['email'] = '%' . $filter['email'] . '%';
        }

        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $data = $stmt->fetchAll();

        $customers = [];
        foreach ($data as $customerData) {
            $customers[] = new Customer($customerData['id'], $customerData['name'], $customerData['email'], $customerData['created_at'], $customerData['updated_at']);
        }

        return $customers;
    }

    // Método para guardar (insertar o actualizar) un cliente en la base de datos
    public function save(Customer $customer)
    {
        if ($customer->id) {
            // Actualización del cliente si ya tiene un ID
            $stmt = $this->connection->prepare(
                "UPDATE customers SET name = :name, email = :email WHERE id = :id"
            );
            $stmt->execute([
                'name' => $customer->name,
                'email' => $customer->email,
                'id' => $customer->id
            ]);
        } else {
            // Inserción del cliente si no tiene un ID
            $stmt = $this->connection->prepare(
                "INSERT INTO customers (name, email) VALUES (:name, :email)"
            );
            $stmt->execute([
                'name' => $customer->name,
                'email' => $customer->email
            ]);
            $customer->id = $this->connection->lastInsertId();
        }

        return $customer;
    }

    // Método para eliminar un cliente por su ID
    public function delete($id)
    {
        $stmt = $this->connection->prepare("DELETE FROM customers WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }
}
