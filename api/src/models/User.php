<?php

namespace src\models;

class User
{
    public $id;
    public $role_id;
    public $name;
    public $last_name;
    public $email;
    public $password;
    public $created_at;
    public $updated_at;

    // Constructor
    public function __construct($data = [])
    {
        $this->id = isset($data['user_id']) ? $data['user_id'] : null;
        $this->role_id = isset($data['role_id']) ? $data['role_id'] : null;
        $this->name = isset($data['name']) ? $data['name'] : null;
        $this->last_name = isset($data['last_name']) ? $data['last_name'] : null;
        $this->email = isset($data['email']) ? $data['email'] : null;
        $this->password = isset($data['password']) ? $data['password'] : null;
        $this->created_at = isset($data['created_at']) ? $data['created_at'] : null;
        $this->updated_at = isset($data['updated_at']) ? $data['updated_at'] : null;
    }
}
