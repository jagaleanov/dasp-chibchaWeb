<?php

namespace src\models;

class Roles
{

    public $id;
    public $name;

    // Constructor
    public function __construct($data = [])
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->name = isset($data['name']) ? $data['name'] : null;
    }
}
