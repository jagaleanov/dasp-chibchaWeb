<?php

namespace src\models;

class Employee extends User
{

    public $id;
    public $user_id;
    public $job_title;
    public $created_at;
    public $updated_at;

    // Constructor
    public function __construct($data = [])
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->user_id = isset($data['user_id']) ? $data['user_id'] : null;
        $this->job_title = isset($data['job_title']) ? $data['job_title'] : null;
        $this->created_at = isset($data['created_at']) ? $data['created_at'] : null;
        $this->updated_at = isset($data['updated_at']) ? $data['updated_at'] : null;
    }
}
