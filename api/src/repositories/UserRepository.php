<?php

// Espacio de nombres utilizado por el repositorio
namespace src\repositories;

// Importaciones de otras clases que se usarán en el repositorio

use PDOException;
use src\services\DatabaseService;
use src\models\User;

// Repositorio para gestionar operaciones relacionadas con los clientes en la base de datos
class UserRepository
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

    // Método para encontrar un cliente por su email
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

    // Método para guardar (insertar o actualizar) un cliente en la base de datos
    public function save(User $user)
    {

        try {

            if ($user->id) {
                // Actualización del cliente si ya tiene un ID
                $stmt = $this->connection->prepare(
                    "UPDATE users SET 
                    name = :name, 
                    last_name = :last_name ,
                    email = :email ,
                    password = :password 
                    WHERE id = :id"
                );
                $stmt->execute([
                    'name' => $user->name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'password' => $user->password,
                    'id' => $user->id
                ]);
                $userId = $user->id;
            } else {
                // Inserción del cliente si no tiene un ID
                $stmt = $this->connection->prepare(
                    "INSERT INTO users (name, last_name, email, password, role_id) VALUES (:name, :last_name, :email, :password, :role_id)"
                );
                $stmt->execute([
                    'name' => $user->name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'password' => $user->password,
                    'role_id' => 1
                ]);
                $userId = $this->connection->lastInsertId();
            }

            $stmt = $this->connection->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->execute(['id' => $userId]);
            $user = $stmt->fetch();
            return $user;
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }

    // Método para eliminar un cliente por su ID
    public function delete($id)
    {
        $stmt = $this->connection->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }
}
