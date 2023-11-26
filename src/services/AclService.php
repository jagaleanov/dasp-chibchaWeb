<?php

namespace src\services;

use src\services\ModelService;
use stdClass;

class AclService
{
    private static $instance = null;
    private $user;
    // private $permissions;
    private $userModel, $roleModel;

    private function __construct()
    {
        $this->user = new stdClass();
        // $this->permissions = [];
        $this->userModel = ModelService::getInstance()->get('UserModel');
        $this->roleModel = ModelService::getInstance()->get('RoleModel');
        $this->setUser();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setUser()
    {
        if (isset($_SESSION['userId'])) {
            $this->user =  $this->userModel->find($_SESSION['userId']);
        } else {
            $this->user = new stdClass();
            $this->user->role_id = 0;
        }

        // $this->setPermissions();
    }
    public function getUser()
    {
        return $this->user;
    }

    public function isLoggedIn()
    {
        if (isset($this->user->id)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function isRoleIn($roles)
    {
        if (in_array($this->user->role_id,$roles)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
