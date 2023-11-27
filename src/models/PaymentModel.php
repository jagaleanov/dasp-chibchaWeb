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
            "SELECT p.*, h.ip, u.email, cc.number as credit_card_number FROM payments p
            JOIN hosts h ON p.host_id = h.id
            JOIN customers c ON c.id=h.customer_id
            JOIN users u ON u.id=c.user_id
            JOIN credit_cards cc ON p.credit_card_number = cc.number";

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
}
