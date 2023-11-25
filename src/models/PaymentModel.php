<?php

// Espacio de nombres utilizado por el repositorio
namespace src\models;

class PaymentModel extends Model
{
    public function find($id)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM payments WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if ($data) {
            return (object) $data;
        }

        return null;
    }

    public function findHostLastPayment($hostId)
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM payments WHERE host_id = :host_id ORDER BY created_at DESC LIMIT 1"
        );
        $stmt->execute(['host_id' => $hostId]);
        $data = $stmt->fetch();

        if ($data) {
            return (object) $data;
        }

        return null;
    }

    public function findAll($filters = [])
    {
        $query =
            "SELECT p.*, h.ip FROM payments p
            JOIN hosts h ON p.host_id = h.id";

            // Utilizar la función buildWhereClause para construir la cláusula WHERE y los parámetros
            $whereData = $this->buildWhereClause($filters);
            $query .= $whereData['whereClause'];
            $params = $whereData['params'];
    
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
    
            $data = $stmt->fetchAll();

        $payments = [];
        foreach ($data as $paymentData) {
            $payments[] = (object) $paymentData;
        }

        return $payments;
    }

    public function save($payment)
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

    // public function update($payment)
    // {
    //     try {
    //         // Actualización del cliente
    //         $stmt = $this->connection->prepare(
    //             "UPDATE payments SET 
    //             host_id = :host_id,
    //             credit_card_customer_id = :credit_card_customer_id,
    //             credit_card_number = :credit_card_number,
    //             amount = :amount,
    //             updated_at = :updated_at
    //             WHERE id = :id"
    //         );
    //         $stmt->execute([
    //             'host_id' => $payment->host_id,
    //             'credit_card_customer_id' => $payment->credit_card_customer_id,
    //             'credit_card_number' => $payment->credit_card_number,
    //             'amount' => $payment->amount,
    //             'updated_at' => date('Y-m-d H:i:s'),
    //             'id' => $payment->id
    //         ]);

    //         //Respuesta
    //         return $this->find($payment->id);
    //     } catch (\Exception $e) {
    //         throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
    //     }
    // }

    // public function delete($id)
    // {
    //     try {
    //         //Validación de la relación del user y el payment
    //         $stmt = $this->connection->prepare("DELETE FROM payments WHERE id = :id");
    //         $stmt->execute(['id' => $id]);

    //         //Respuesta
    //         return $stmt->rowCount() == 1;
    //     } catch (\Exception $e) {
    //         throw $e;  // Lanzar la excepción para que pueda ser manejada en una capa superior
    //     }
    // }
}
