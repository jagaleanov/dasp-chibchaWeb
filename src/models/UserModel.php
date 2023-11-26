<?php

namespace src\models;

class UserModel extends Model
{
    public function find($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE id = :id");
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
        $query = "SELECT * FROM users";

        $whereData = $this->buildWhereClause($filters);
        $query .= $whereData['whereClause'];
        $params = $whereData['params'];

        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $data = $stmt->fetchAll();

        $users = [];
        foreach ($data as $userData) {
            $users[] = (object) $userData;
        }

        return $users;
    }

    public function getByEmail($email)
    {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch();

        if ($data) {
            return (object) $data;
        }

        // Si no se encuentra el cliente, se retorna null
        return null;
    }
}
