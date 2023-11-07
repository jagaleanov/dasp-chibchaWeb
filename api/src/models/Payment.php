<?php

namespace src\models;

class Payment
{
    public $id;
    public $host_id;
    public $credit_card_customer_id;
    public $credit_card_number;
    public $amount;
    public $created_at;
    public $updated_at;

    // Constructor
    public function __construct($data = [])
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->host_id = isset($data['host_id']) ? $data['host_id'] : null;
        $this->credit_card_customer_id = isset($data['credit_card_customer_id']) ? $data['credit_card_customer_id'] : null;
        $this->credit_card_number = isset($data['credit_card_number']) ? $data['credit_card_number'] : null;
        $this->amount = isset($data['amount']) ? $data['amount'] : null;
        $this->created_at = isset($data['created_at']) ? $data['created_at'] : null;
        $this->updated_at = isset($data['updated_at']) ? $data['updated_at'] : null;
    }
}
