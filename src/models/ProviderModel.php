<?php

// Espacio de nombres utilizado por el repositorio
namespace src\models;

class ProviderModel extends Model
{
    public function find($id)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM providers WHERE id = :id"
        );
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
        $query =
            "SELECT * FROM providers ";

        // Utilizar la función buildWhereClause para construir la cláusula WHERE y los parámetros
        $whereData = $this->buildWhereClause($filters);
        $query .= $whereData['whereClause'];
        $params = $whereData['params'];

        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $data = $stmt->fetchAll();

        $providers = [];
        foreach ($data as $providerData) {
            $providers[] = (object) $providerData;
        }

        return $providers;
    }

    // // Método para insertar un proveedor de dominios en la base de datos
    // public function save($provider)
    // {
    //     try {
    //         // Inserción del usuario 
    //         $stmt = $this->connection->prepare(
    //             "INSERT INTO providers (name) VALUES (:name)"
    //         );
    //         $stmt->execute([
    //             'name' => $provider->name,
    //         ]);
    //         $providerId = $this->connection->lastInsertId();

    //         //Respuesta
    //         return $this->find($providerId);
    //     } catch (\Exception $e) {
    //         throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
    //     }
    // }

    // // Método para actualizar un proveedor de dominios en la base de datos
    // public function update($provider)
    // {
    //     try {

    //         // Actualización del cliente
    //         $stmt = $this->connection->prepare(
    //             "UPDATE providers SET 
    //             name = :name
    //             WHERE id = :id"
    //         );
    //         $stmt->execute([
    //             'name' => $provider->name,
    //             'id' => $provider->id
    //         ]);

    //         //Respuesta
    //         return $this->find($provider->id);
    //     } catch (\Exception $e) {
    //         throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
    //     }
    // }

    // // Método para eliminar un proveedor de dominios por su ID
    // public function delete($id)
    // {
    //     try {

    //         //Validación de la relación del user y el provider
    //         $stmt = $this->connection->prepare("DELETE FROM providers WHERE id = :id");
    //         $stmt->execute(['id' => $id]);

    //         //Respuesta
    //         return $stmt->rowCount() == 1;
    //     } catch (\Exception $e) {
    //         // Si hay un error, revertir la transacción
    //     }
    // }
}
