<?php

// Espacio de nombres utilizado por el repositorio
namespace src\repositories;

// Importaciones de otras clases que se usarán en el repositorio

use src\models\Role;

// Repositorio para gestionar operaciones relacionadas con los roles en la base de datos
class RoleRepository extends Repository
{
    // Método para encontrar un rol por su ID
    public function find($id)
    {
        $stmt = $this->connection->prepare(
            "SELECT *
            FROM roles r 
            WHERE r.id = :id"
        );
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if ($data) {
            return new Role($data);
        }

        // Si no se encuentra el cliente, se retorna null
        return null;
    }

    // Método para encontrar todos los roles
    public function findAll()
    {
        $query =
            "SELECT *
            FROM roles r";
        // $params = [];

        $stmt = $this->connection->prepare($query);
        $stmt->execute(/*$params*/);

        $data = $stmt->fetchAll();

        $roles = [];
        foreach ($data as $roleData) {
            $roles[] = new Role($roleData);
        }

        return $roles;
    }

    // Método para insertar un rol en la base de datos
    public function save(Role $role)
    {

        // Iniciar una transacción
        $this->connection->beginTransaction();

        try {
            // Inserción del usuario 
            $stmt = $this->connection->prepare(
                "INSERT INTO roles (name) VALUES (:name)"
            );
            $stmt->execute([
                'name' => $role->name,
            ]);
            $roleId = $this->connection->lastInsertId();

            // Confirmar la transacción
            $this->connection->commit();

            //Respuesta
            return $this->find($roleId);
        } catch (\Exception $e) {
            // Si hay un error, revertir la transacción
            $this->connection->rollback();
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }

    // Método para actualizar un rol en la base de datos
    public function update(Role $role)
    {
        try {

            // Actualización del cliente
            $stmt = $this->connection->prepare(
                "UPDATE roles SET 
                    name = :name"
            );
            $stmt->execute([
                'name' => $role->name
            ]);

            // Confirmar la transacción
            $this->connection->commit();

            //Respuesta
            $roleId = $role->id;
            return $this->find($roleId);
        } catch (\Exception $e) {
            // Si hay un error, revertir la transacción
            $this->connection->rollback();
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }

    // Método para eliminar un rol por su ID
    public function delete($id)
    {
        try {
            // Iniciar una transacción
            $this->connection->beginTransaction();

            //Validación de la relación del user y el role
            $stmt = $this->connection->prepare("DELETE FROM roles WHERE id = :id");
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
