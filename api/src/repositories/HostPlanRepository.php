<?php

// Espacio de nombres utilizado por el repositorio
namespace src\repositories;

// Importaciones de otras clases que se usarán en el repositorio

use src\models\HostPlan;

// Repositorio para gestionar operaciones relacionadas con los host_plans en la base de datos
class HostPlanRepository extends Repository
{
    // Método para encontrar un rol por su ID
    public function find($id)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM host_plans WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if ($data) {
            return new HostPlan($data);
        }

        // Si no se encuentra el cliente, se retorna null
        return null;
    }

    // Método para encontrar todos los host_plans
    public function findAll($search = null)
    {
        $query =
            "SELECT * FROM host_plans ";
        $params = [];

        // Aplicación de filtros si se proporcionan
        if (!empty($search)) {
            $query .= "WHERE name LIKE :search";
            $params['search'] = '%' . $search . '%';
        }

        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $data = $stmt->fetchAll();

        $hostPlans = [];
        foreach ($data as $hostPlanData) {
            $hostPlans[] = new HostPlan($hostPlanData);
        }

        return $hostPlans;
    }

    // Método para insertar un rol en la base de datos
    public function save(HostPlan $hostPlan)
    {
        try {
            // Inserción del usuario 
            $stmt = $this->connection->prepare(
                "INSERT INTO host_plans (name) VALUES (:name)"
            );
            $stmt->execute([
                'name' => $hostPlan->name,
            ]);
            $hostPlanId = $this->connection->lastInsertId();

            //Respuesta
            return $this->find($hostPlanId);
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }

    // Método para actualizar un rol en la base de datos
    public function update(HostPlan $hostPlan)
    {
        try {
            // Actualización del cliente
            $stmt = $this->connection->prepare(
                "UPDATE host_plans SET
                name = :name
                WHERE id = :id"
            );
            $stmt->execute([
                'name' => $hostPlan->name,
                'id' => $hostPlan->id
            ]);

            //Respuesta
            return $this->find($hostPlan->id);
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }

    // Método para eliminar un rol por su ID
    public function delete($id)
    {
        try {
            //Validación de la relación del user y el hostplan
            $stmt = $this->connection->prepare("DELETE FROM host_plans WHERE id = :id");
            $stmt->execute(['id' => $id]);

            //Respuesta
            return $stmt->rowCount() == 1;
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }
}
