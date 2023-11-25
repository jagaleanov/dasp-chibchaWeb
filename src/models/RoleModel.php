<?php

// Espacio de nombres utilizado por el repositorio
namespace src\models;

// Repositorio para gestionar operaciones relacionadas con los roles en la base de datos
class RoleModel extends Model
{
    // Método para encontrar un rol por su ID
    public function find($id)
    {
        $stmt = $this->connection->prepare(
            "SELECT *
            FROM roles WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if ($data) {
            return (object) $data;
        }

        // Si no se encuentra el cliente, se retorna null
        return null;
    }

    // Método para encontrar todos los roles
    // public function findAll($search = null)
    // {
    //     $query =
    //         "SELECT * FROM roles ";
    //     $params = [];

    //     // Aplicación de filtros si se proporcionan
    //     if (!empty($search)) {
    //         $query .= "WHERE name LIKE :search";
    //         $params['search'] = '%' . $search . '%';
    //     }

    //     $stmt = $this->connection->prepare($query);
    //     $stmt->execute($params);

    //     $data = $stmt->fetchAll();

    //     $roles = [];
    //     foreach ($data as $roleData) {
    //         $roles[] = (object) $roleData;
    //     }

    //     return $roles;
    // }
    public function findAll($filters = [])
    {
        $query = "SELECT * FROM roles";

        // Utilizar la función buildWhereClause para construir la cláusula WHERE y los parámetros
        $whereData = $this->buildWhereClause($filters);
        // print "LIKE OK";
        // print "<pre>";print_r($whereData);print "</pre>";
        $query .= $whereData['whereClause'];
        $params = $whereData['params'];

        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $data = $stmt->fetchAll();

        $roles = [];
        foreach ($data as $roleData) {
            $roles[] = (object) $roleData;
        }

        return $roles;
    }

    // Método para insertar un rol en la base de datos
    public function save($role)
    {
        try {
            // Inserción del usuario 
            $stmt = $this->connection->prepare(
                "INSERT INTO roles (name) VALUES (:name)"
            );
            $stmt->execute([
                'name' => $role->name,
            ]);
            $roleId = $this->connection->lastInsertId();

            //Respuesta
            return $this->find($roleId);
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }

    // Método para actualizar un rol en la base de datos
    public function update($role)
    {
        try {

            // Actualización del cliente
            $stmt = $this->connection->prepare(
                "UPDATE roles SET 
                name = :name
                WHERE id = :id"
            );
            $stmt->execute([
                'name' => $role->name,
                'id' => $role->id
            ]);

            //Respuesta
            return $this->find($role->id);
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }

    // Método para eliminar un rol por su ID
    public function delete($id)
    {
        try {
            //Validación de la relación del user y el role
            $stmt = $this->connection->prepare("DELETE FROM roles WHERE id = :id");
            $stmt->execute(['id' => $id]);

            //Respuesta
            return $stmt->rowCount() == 1;
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }
}
