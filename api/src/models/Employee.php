<?php

namespace src\models;

class Employee extends User
{

    public $id;
    public $user_id;
    public $mobile_phone;
    public $created_at;
    public $updated_at;

    // Constructor
    public function __construct($data = [])
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->user_id = isset($data['user_id']) ? $data['user_id'] : null;
        $this->mobile_phone = isset($data['mobile_phone']) ? $data['mobile_phone'] : null;
        $this->created_at = isset($data['created_at']) ? $data['created_at'] : null;
        $this->updated_at = isset($data['updated_at']) ? $data['updated_at'] : null;
    }
}
