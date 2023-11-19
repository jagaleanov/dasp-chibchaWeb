<?php

// Espacio de nombres utilizado por el repositorio
namespace src\models;

// Repositorio para gestionar operaciones relacionadas con los payment_plans en la base de datos
class PaymentPlanModel extends Model
{
    // Método para encontrar un rol por su ID
    public function find($id)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM payment_plans WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if ($data) {
            return (object) $data;
        }

        // Si no se encuentra el cliente, se retorna null
        return null;
    }

    // Método para encontrar todos los payment_plans
    public function findAll($search = null)
    {
        $query =
            "SELECT * FROM payment_plans ";
        $params = [];

        // Aplicación de filtros si se proporcionan
        if (!empty($search)) {
            $query .= "WHERE name LIKE :search";
            $params['search'] = '%' . $search . '%';
        }

        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $data = $stmt->fetchAll();
        $paymentPlans = [];
        foreach ($data as $paymentPlanData) {
            $paymentPlans[] = (object) $paymentPlanData;
        }

        return $paymentPlans;
    }

    // // Método para insertar un rol en la base de datos
    // public function save($paymentPlan)
    // {
    //     try {
    //         // Inserción del usuario 
    //         $stmt = $this->connection->prepare(
    //             "INSERT INTO payment_plans (name) VALUES (:name)"
    //         );
    //         $stmt->execute([
    //             'name' => $paymentPlan->name
    //         ]);
    //         $paymentPlanId = $this->connection->lastInsertId();

    //         //Respuesta
    //         return $this->find($paymentPlanId);
    //     } catch (\Exception $e) {
    //         throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
    //     }
    // }

    // // Método para actualizar un rol en la base de datos
    // public function update($paymentPlan)
    // {
    //     try {
    //         // Actualización del cliente
    //         $stmt = $this->connection->prepare(
    //             "UPDATE payment_plans SET 
    //             name = :name
    //             WHERE id = :id"
    //         );
    //         $stmt->execute([
    //             'name' => $paymentPlan->name,
    //             'id' => $paymentPlan->id
    //         ]);

    //         //Respuesta
    //         return $this->find($paymentPlan->id);
    //     } catch (\Exception $e) {
    //         throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
    //     }
    // }

    // // Método para eliminar un rol por su ID
    // public function delete($id)
    // {
    //     try {
    //         // Iniciar una transacción
    //         $this->connection->beginTransaction();

    //         //Validación de la relación del user y el paymentplan
    //         $stmt = $this->connection->prepare("DELETE FROM payment_plans WHERE id = :id");
    //         $stmt->execute(['id' => $id]);

    //         //Respuesta
    //         return $stmt->rowCount() == 1;
    //     } catch (\Exception $e) {
    //         throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
    //     }
    // }
}
