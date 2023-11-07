<?php

namespace src\models;

class Host
{
    public $id;
    public $customer_id;
    public $host_plan_id;
    public $payment_plan_id;
    public $operative_system_id;
    public $ip;
    public $created_at;
    public $updated_at;

    // Constructor
    public function __construct($data = [])
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->customer_id = isset($data['customer_id']) ? $data['customer_id'] : null;
        $this->host_plan_id = isset($data['host_plan_id']) ? $data['host_plan_id'] : null;
        $this->payment_plan_id = isset($data['payment_plan_id']) ? $data['payment_plan_id'] : null;
        $this->operative_system_id = isset($data['operative_system_id']) ? $data['operative_system_id'] : null;
        $this->ip = isset($data['ip']) ? $data['ip'] : null;
        $this->created_at = isset($data['created_at']) ? $data['created_at'] : null;
        $this->updated_at = isset($data['updated_at']) ? $data['updated_at'] : null;
    }
}
