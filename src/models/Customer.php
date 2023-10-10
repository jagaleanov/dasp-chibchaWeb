<?php
namespace src\models;

class Customer {

    public $id;
    public $name;
    public $email;
    public $created_at;
    public $updated_at;

    // Constructor
    public function __construct($id = null, $name = null, $email = null, $created_at = null, $updated_at = null) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}
