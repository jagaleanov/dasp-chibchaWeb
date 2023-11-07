<?php

// Espacio de nombres utilizado por el repositorio
namespace src\repositories;

// Importaciones de otras clases que se usarán en el repositorio

use src\models\Payment;

// Repositorio para gestionar operaciones relacionadas con los payments en la base de datos
class PaymentRepository extends Repository
{
    // Método para encontrar un proveedor de dominios por su ID
    public function find($id)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM payments WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if ($data) {
            return new Payment($data);
        }

        // Si no se encuentra el cliente, se retorna null
        return null;
    }

    // Método para encontrar todos los clientes, con opción de filtro por nombre o email
    public function findAll($search = null)
    {
        $query =
            "SELECT * FROM payments";
        $params = [];

        // Aplicación de filtros si se proporcionan
        if (!empty($search)) {
            $query .= " WHERE ip LIKE :search";
            $params['search'] = '%' . $search . '%';
        }

        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $data = $stmt->fetchAll();

        $payments = [];
        foreach ($data as $paymentData) {
            $payments[] = new Payment($paymentData);
        }

        return $payments;
    }

    // Método para insertar un proveedor de dominios en la base de datos
    public function save(Payment $payment)
    {
        try {
            // Inserción del usuario 
            $stmt = $this->connection->prepare(
                "INSERT INTO payments (host_id, credit_card_customer_id, credit_card_number, amount) VALUES (:host_id, :credit_card_customer_id, :credit_card_number, :amount)"
            );
            $stmt->execute([
                'host_id' => $payment->host_id,
                'credit_card_customer_id' => $payment->credit_card_customer_id,
                'credit_card_number' => $payment->credit_card_number,
                'amount' => $payment->amount,
            ]);
            $paymentId = $this->connection->lastInsertId();

            //Respuesta
            return $this->find($paymentId);
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }

    // Método para actualizar un proveedor de dominios en la base de datos
    public function update(Payment $payment)
    {
        try {
            // Actualización del cliente
            $stmt = $this->connection->prepare(
                "UPDATE payments SET 
                host_id = :host_id,
                credit_card_customer_id = :credit_card_customer_id,
                credit_card_number = :credit_card_number,
                amount = :amount,
                updated_at = :updated_at
                WHERE id = :id"
            );
            $stmt->execute([
                'host_id' => $payment->host_id,
                'credit_card_customer_id' => $payment->credit_card_customer_id,
                'credit_card_number' => $payment->credit_card_number,
                'amount' => $payment->amount,
                'updated_at' => date('Y-m-d H:i:s'),
                'id' => $payment->id
            ]);

            //Respuesta
            return $this->find($payment->id);
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }

    // Método para eliminar un proveedor de dominios por su ID
    public function delete($id)
    {
        try {
            //Validación de la relación del user y el payment
            $stmt = $this->connection->prepare("DELETE FROM payments WHERE id = :id");
            $stmt->execute(['id' => $id]);

            //Respuesta
            return $stmt->rowCount() == 1;
        } catch (\Exception $e) {
            throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
        }
    }
}
