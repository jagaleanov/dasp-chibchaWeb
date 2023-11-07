<?php

// Espacio de nombres utilizado por el repositorio
namespace src\repositories;

// Importaciones de otras clases que se usarán en el repositorio

use src\models\Provider;

// Repositorio para gestionar operaciones relacionadas con los providers en la base de datos
class ProviderRepository extends Repository
{
    // Método para encontrar un proveedor de dominios por su ID
    public function find($id)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM providers WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if ($data) {
            return new Provider($data);
        }

        // Si no se encuentra el cliente, se retorna null
        return null;
    }

    // Método para encontrar todos los clientes, con opción de filtro por nombre o email
    public function findAll($search = null)
    {
        $query =
            "SELECT * FROM providers ";
        $params = [];

        // Aplicación de filtros si se proporcionan
        if (!empty($search)) {
            $query .= "WHERE name LIKE :search";
            $params['search'] = '%' . $search . '%';
        }

        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $data = $stmt->fetchAll();

        $providers = [];
        foreach ($data as $providerData) {
            $providers[] = new Provider($providerData);
        }

        return $providers;
    }

    // Método para insertar un proveedor de dominios en la base de datos
    public function save(Provider $provider)
    {
        try {
            // Inserción del usuario 
            $stmt = $this->connection->prepare(
                "INSERT INTO providers (name) VALUES (:name)"
            );
            $stmt->execute([
                'name' => $provider->name,
            ]);
            $providerId = $this->connection->lastInsertId();

            //Respuesta
            return $this->find($providerId);
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }

    // Método para actualizar un proveedor de dominios en la base de datos
    public function update(Provider $provider)
    {
        try {

            // Actualización del cliente
            $stmt = $this->connection->prepare(
                "UPDATE providers SET 
                name = :name
                WHERE id = :id"
            );
            $stmt->execute([
                'name' => $provider->name,
                'id' => $provider->id
            ]);

            //Respuesta
            return $this->find($provider->id);
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }

    // Método para eliminar un proveedor de dominios por su ID
    public function delete($id)
    {
        try {

            //Validación de la relación del user y el provider
            $stmt = $this->connection->prepare("DELETE FROM providers WHERE id = :id");
            $stmt->execute(['id' => $id]);

            //Respuesta
            return $stmt->rowCount() == 1;
        } catch (\Exception $e) {
            // Si hay un error, revertir la transacción
        }
    }
}
