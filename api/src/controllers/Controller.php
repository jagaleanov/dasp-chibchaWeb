<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

use src\services\AclService;
use src\services\LayoutService;
use src\services\ValidationService;

class Controller
{
    protected $aclService;
    protected $validationService;
    protected $layoutService;
    
    public function __construct()
    {
        session_start();
        $this->aclService = AclService::getInstance();
        $this->validationService = ValidationService::getInstance();
        $this->layoutService = LayoutService::getInstance();
    }
}
