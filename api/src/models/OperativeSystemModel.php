<?php

// Espacio de nombres utilizado por el repositorio
namespace src\models;

// Repositorio para gestionar operaciones relacionadas con los operative_systems en la base de datos
class OperativeSystemModel extends Model
{
    // Método para encontrar un rol por su ID
    public function find($id)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM operative_systems WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if ($data) {
            return (object) $data;
        }

        // Si no se encuentra el cliente, se retorna null
        return null;
    }

    // Método para encontrar todos los operative_systems
    public function findAll($search = null)
    {
        $query =
            "SELECT * FROM operative_systems ";
        $params = [];

        // Aplicación de filtros si se proporcionan
        if (!empty($search)) {
            $query .= "WHERE name LIKE :search";
            $params['search'] = '%' . $search . '%';
        }

        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $data = $stmt->fetchAll();

        $operativeSystems = [];
        foreach ($data as $operativeSystemData) {
            $operativeSystems[] = (object) $operativeSystemData;
        }

        return $operativeSystems;
    }

    // // Método para insertar un rol en la base de datos
    // public function save($operativeSystem)
    // {

    //     // Iniciar una transacción
    //     $this->connection->beginTransaction();

    //     try {
    //         // Inserción del usuario 
    //         $stmt = $this->connection->prepare(
    //             "INSERT INTO operative_systems (name) VALUES (:name)"
    //         );
    //         $stmt->execute([
    //             'name' => $operativeSystem->name,
    //         ]);
    //         $operativeSystemId = $this->connection->lastInsertId();

    //         // Confirmar la transacción
    //         $this->connection->commit();

    //         //Respuesta
    //         return $this->find($operativeSystemId);
    //     } catch (\Exception $e) {
    //         // Si hay un error, revertir la transacción
    //         $this->connection->rollback();
    //         throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
    //     }
    // }

    // // Método para actualizar un rol en la base de datos
    // public function update($operativeSystem)
    // {
    //     try {

    //         // Actualización del cliente
    //         $stmt = $this->connection->prepare(
    //             "UPDATE operative_systems SET 
    //             name = :name
    //             WHERE id = :id"
    //         );
    //         $stmt->execute([
    //             'name' => $operativeSystem->name,
    //             'id' => $operativeSystem->id
    //         ]);

    //         // Confirmar la transacción
    //         $this->connection->commit();

    //         //Respuesta
    //         return $this->find($operativeSystem->id);
    //     } catch (\Exception $e) {
    //         // Si hay un error, revertir la transacción
    //         $this->connection->rollback();
    //         throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
    //     }
    // }

    // // Método para eliminar un rol por su ID
    // public function delete($id)
    // {
    //     try {
    //         //Validación de la relación del user y el hostplan
    //         $stmt = $this->connection->prepare("DELETE FROM operative_systems WHERE id = :id");
    //         $stmt->execute(['id' => $id]);

    //         //Respuesta
    //         return $stmt->rowCount() == 1;
    //     } catch (\Exception $e) {
    //         throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
    //     }
    // }
}
