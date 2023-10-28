<?php

// Espacio de nombres utilizado por el repositorio
namespace src\repositories;

// Importaciones de otras clases que se usarán en el repositorio

use PDOException;
use src\services\DatabaseService;
use src\models\Roles;

// Repositorio para gestionar operaciones relacionadas con los roles en la base de datos
class RolesRepository 
{

    // Propiedad para la conexión a la base de datos
    private $connection;

    // Constructor que establece la conexión a la base de datos
    public function __construct()
    {
        $this->connection = DatabaseService::getInstance()->getConnection();
    }

    // Método para encontrar un rol por su ID
    public function find($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM roles WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if ($data) {
            return new Roles($data); 
        }

        // Si no se encuentra el rol, se retorna null
        return null;
    }

    // Método para encontrar todos los roles, con opción de filtro por nombre
    public function findAll($search = null)
    {
        $query = "SELECT * FROM roles";
        $params = [];

        // Aplicación de filtros si se proporcionan
        if (!empty($search)) {
            $query .= " WHERE name LIKE :search";
            $params['search'] = '%' . $search . '%';
        }

        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $data = $stmt->fetchAll();

        $roles = [];
        foreach ($data as $roleData) {
            $roles[] = new Roles($roleData); 
        }

        return $roles;
    }

    // Método para guardar (insertar o actualizar) un rol en la base de datos
    public function save(Roles $role)
    {

        try {

            if ($role->id) {
                // Actualización del rol si ya tiene un ID
                $stmt = $this->connection->prepare(
                    "UPDATE roles SET 
                    name = :name
                    WHERE id = :id"
                );
                $stmt->execute([
                    'name' => $role->name,
                    'id' => $role->id
                ]);
                $roleId = $role->id;
            } else {
                // Inserción del rol si no tiene un ID
                $stmt = $this->connection->prepare(
                    "INSERT INTO roles (name) VALUES (:name)"
                );
                $stmt->execute([
                    'name' => $role->name
                ]);
                $roleId = $this->connection->lastInsertId();
            }

            $stmt = $this->connection->prepare("SELECT * FROM roles WHERE id = :id");
            $stmt->execute(['id' => $roleId]);
            $role = $stmt->fetch();
            return $role;
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }

    // Método para eliminar un rol por su ID
    public function delete($id)
    {
        $stmt = $this->connection->prepare("DELETE FROM roles WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }
}
