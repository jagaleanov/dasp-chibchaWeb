<?php

namespace src\models;

class PaymentPlanModel extends Model
{
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

        return null;
    }

    public function findAll($search = null)
    {
        $query =
            "SELECT * FROM payment_plans ";
        $params = [];

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
}
