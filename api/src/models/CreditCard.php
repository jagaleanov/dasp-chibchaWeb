<?php

namespace src\models;

class CreditCard
{
    public $customer_id;
    public $number;
    public $type;
    public $expiration_year;
    public $expiration_month;
    public $security_code;
    public $name;
    public $created_at;
    public $updated_at;

    // Constructor
    public function __construct($data = [])
    {
        $this->customer_id = isset($data['customer_id']) ? $data['customer_id'] : null;
        $this->number = isset($data['number']) ? $data['number'] : null;
        $this->type = isset($data['type']) ? $data['type'] : null;
        $this->expiration_year = isset($data['expiration_year']) ? $data['expiration_year'] : null;
        $this->expiration_month = isset($data['expiration_month']) ? $data['expiration_month'] : null;
        $this->security_code = isset($data['security_code']) ? $data['security_code'] : null;
        $this->name = isset($data['name']) ? $data['name'] : null;
        $this->created_at = isset($data['created_at']) ? $data['created_at'] : null;
        $this->updated_at = isset($data['updated_at']) ? $data['updated_at'] : null;
    }
}
