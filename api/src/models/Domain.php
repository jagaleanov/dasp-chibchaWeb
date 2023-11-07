<?php

namespace src\models;

class Domain
{
    public $id;
    public $customer_id;
    public $provider_id;
    public $domain;
    public $status;
    public $created_at;
    public $updated_at;

    // Constructor
    public function __construct($data = [])
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->customer_id = isset($data['customer_id']) ? $data['customer_id'] : null;
        $this->provider_id = isset($data['provider_id']) ? $data['provider_id'] : null;
        $this->domain = isset($data['domain']) ? $data['domain'] : null;
        $this->status = isset($data['status']) ? $data['status'] : null;
        $this->created_at = isset($data['created_at']) ? $data['created_at'] : null;
        $this->updated_at = isset($data['updated_at']) ? $data['updated_at'] : null;
    }
}
