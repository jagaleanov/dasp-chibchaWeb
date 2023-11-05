<?php

// Espacio de nombres utilizado por el repositorio
namespace src\repositories;

// Importaciones de otras clases que se usarán en el repositorio

use src\models\User;

// Repositorio para gestionar operaciones relacionadas con los clientes en la base de datos
class UserRepository extends Repository
{
    // Método para encontrar un cliente por su ID
    public function find($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if ($data) {
            return new User($data);
        }

        // Si no se encuentra el cliente, se retorna null
        return null;
    }

    // Método para encontrar todos los clientes, con opción de filtro por nombre o email
    public function findAll($search = null)
    {
        $query = "SELECT * FROM users";
        $params = [];

        // Aplicación de filtros si se proporcionan
        if (!empty($search)) {
            $query .= " WHERE name LIKE :search OR last_name LIKE :search OR email LIKE :search";
            $params['search'] = '%' . $search . '%';
        }

        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $data = $stmt->fetchAll();

        $users = [];
        foreach ($data as $userData) {
            $users[] = new User($userData);
        }

        return $users;
    }

    // Método para encontrar un usuario por su email
    public function getByEmail($email)
    {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch();

        if ($data) {
            return new User($data);
        }

        // Si no se encuentra el cliente, se retorna null
        return null;
    }
}
