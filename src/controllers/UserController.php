<?php

namespace src\controllers;

use src\modules\menu\MenuController;
use src\services\ModelService;
class UserController extends Controller
{
    private $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = ModelService::getInstance()->get('UserModel');

        if (!$this->aclService->isRoleIn([2, 3, 4, 5, 6])) {
            $_SESSION['systemMessages'] = [
                'danger'=>'Acceso restringido.'
            ];
            header('Location:' . BASE_URL . '/home');
            exit;
        }
    }

    public function getAllUsers()
    {
        try {

            $users = $this->userModel->findAll();

            $data = [
                'post' => $this->postService,
                'users' => $users,
            ];
            $menu = new MenuController();
            $this->layoutService->setModule('navBar', $menu->index());
            $this->layoutService->view('users/list', $data);
        } catch (\Exception $e) {
            print_r($e);
        }
    }
}
