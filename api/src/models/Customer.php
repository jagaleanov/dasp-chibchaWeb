<?php

namespace src\models;

class Customer extends User
{

    public $id;
    public $user_id;
    public $corporation;
    public $address;
    public $created_at;
    public $updated_at;

    // Constructor
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->user_id = isset($data['user_id']) ? $data['user_id'] : null;
        $this->corporation = isset($data['corporation']) ? $data['corporation'] : null;
        $this->address = isset($data['address']) ? $data['address'] : null;
        $this->created_at = isset($data['created_at']) ? $data['created_at'] : null;
        $this->updated_at = isset($data['updated_at']) ? $data['updated_at'] : null;
    }
}
