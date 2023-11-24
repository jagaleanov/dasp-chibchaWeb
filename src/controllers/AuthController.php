<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

use Exception;
use src\modules\menu\MenuController;
use src\services\ModelService;

// Controlador para gestionar clientes
class AuthController extends Controller
{
    private $userModel;
    private $roleModel;

    // Constructor que inyecta el repositorio de usuarios
    public function __construct()
    {
        parent::__construct();
        $this->userModel = ModelService::getInstance()->get('UserModel');
        $this->roleModel = ModelService::getInstance()->get('RoleModel');
    }

    public function loginForm()
    {
        // print"<pre>";print_r($_SESSION);print"</pre>";
        try {
            if (isset($_POST['submit'])) {
                // print"<pre>";print_r($_POST);print"</pre>";
                // Validación de datos de entrada
                $rules = [
                    [
                        'field' => 'email',
                        'label' => 'correo electrónico',
                        'rules' => ['required']
                    ],
                    [
                        'field' => 'password',
                        'label' => 'contraseña',
                        'rules' => ['required']
                    ],
                ];

                $validate = $this->validationService->validate($_POST, $rules);

                if ($validate->valid === true) {
                    $res = $this->login($validate->sanitizedData);

                    if ($res->success) {
                        header('Location:' . BASE_URL . '/customers/details');
                    } else {
                        $this->layoutService->setMessages([
                            'danger' => [$res->message],
                        ]);
                    }
                } else {
                    $this->layoutService->setMessages([
                        'danger' => $validate->errors,
                    ]);
                }
            }
            $menu = new MenuController();
            $this->layoutService->setModule('navBar',$menu->index());
            $this->layoutService->view('auth/login');
        } catch (\Exception $e) {
            print_r($e);
        }
    }
    // Método para iniciar sesión
    private function login($data)
    {
        try {
            $user = $this->userModel->getByEmail($data['email']);
            if (!$user) {
                throw new Exception('Usuario inexistente');
            }

            if (!password_verify($data['password'], $user->password)) {
                throw new Exception('Password inválido');
            }
            
            $_SESSION['userId'] = $user->id;

            return (object)[
                'success' => true,
                // 'data' => ['user' => $user],
            ];
        } catch (\Exception $e) {
            return (object)[
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
    // Método para iniciar sesión
    public function logout()
    {
        try {
            session_destroy();
            header('Location:'.BASE_URL.'/home');
        } catch (\Exception $e) {
            return (object)[
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
