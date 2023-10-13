<?php

namespace src\repositories;

use src\services\DatabaseService;
use src\models\Customer;

class CustomerRepository {

    private $connection;

    public function __construct() {
        $this->connection = DatabaseService::getInstance()->getConnection();
    }

    public function find($id) {
        $stmt = $this->connection->prepare("SELECT * FROM customers WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if ($data) {
            return new Customer($data['id'], $data['name'], $data['email'], $data['created_at'], $data['updated_at']);
        }

        return null;
    }

    public function findAll($filter = []) {
        $query = "SELECT * FROM customers";
        $params = [];
        
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

    public function save(Customer $customer) {
        if ($customer->id) {
            // Update
            $stmt = $this->connection->prepare(
                "UPDATE customers SET name = :name, email = :email WHERE id = :id"
            );
            $stmt->execute([
                'name' => $customer->name,
                'email' => $customer->email,
                'id' => $customer->id
            ]);
        } else {
            // Insert
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

    public function delete($id) {
        $stmt = $this->connection->prepare("DELETE FROM customers WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }
}
