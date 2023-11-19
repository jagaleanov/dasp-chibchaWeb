<?php

namespace src\services;

use src\services\ModelService;
use stdClass;

class AclService
{
    private static $instance = null;
    private $user;
    // private $permissions;
    private $userModel;

    private function __construct()
    {
        $this->user = new stdClass();
        // $this->permissions = [];
        $this->userModel = ModelService::getInstance()->get('UserModel');
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
            $this->user->role = 1;
        }

        // $this->setPermissions();
    }
    public function getUser()
    {
        return $this->user;
    }

    public function isLoggedIn()
    {
        if (isset($this->user->users_id)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*
	public function setPermissions()
	{
        $role_permissions = $this->CI->acl_model->get_role_permissions( $this->user->users_role );
        
        foreach($role_permissions as $permission)
        {
            $perm = $this->CI->acl_model->get_permission($permission->role_permissions_perm );
            
            $this->permissions[$perm->permissions_name] = TRUE;
        }
	}

	public function hasPermission($permission = '')
	{
        return isset($this->permissions[$permission]) && $this->permissions[$permission];
	}
    */
}
