<?php

namespace src\controllers;

use src\services\AclService;
use src\services\LayoutService;
use src\services\PostService;
use src\services\ValidationService;

class Controller
{
    protected $aclService;
    protected $validationService;
    protected $layoutService;
    protected $postService;
    
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->aclService = AclService::getInstance();
        $this->validationService = ValidationService::getInstance();
        $this->layoutService = LayoutService::getInstance();
        $this->postService = PostService::getInstance();
    }
}
