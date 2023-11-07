<?php

namespace src\models;

class Ticket
{
    public $id;
    public $host_id;
    public $user_id;
    public $role_id;
    public $description;
    public $status;
    public $created_at;
    public $updated_at;

    // Constructor
    public function __construct($data = [])
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->host_id = isset($data['host_id']) ? $data['host_id'] : null;
        $this->user_id = isset($data['user_id']) ? $data['user_id'] : null;
        $this->role_id = isset($data['role_id']) ? $data['role_id'] : null;
        $this->description = isset($data['description']) ? $data['description'] : null;
        $this->status = isset($data['status']) ? $data['status'] : null;
        $this->created_at = isset($data['created_at']) ? $data['created_at'] : null;
        $this->updated_at = isset($data['updated_at']) ? $data['updated_at'] : null;
    }
}
