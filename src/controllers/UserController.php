<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

use src\modules\menu\MenuController;
use src\services\ModelService;

// Controlador para gestionar usuarios
class UserController extends Controller
{
    // Propiedad para el repositorio de usuarios
    private $userModel;

    // Constructor que inyecta el repositorio de usuarios
    public function __construct()
    {
        parent::__construct();
        $this->userModel = ModelService::getInstance()->get('UserModel');
    }

    // Método para obtener todos los usuarios
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

    // Método para obtener un usuario por su ID
    public function getUser($id)
    {
        try {
            $user = $this->userModel->find($id);
            return $this->successResponse($user);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para eliminar un usuario por su ID
    public function deleteUser($id)
    {
        try {
            $user = $this->userModel->find($id);

            if (!$user) {
                return $this->notFoundResponse();
            }

            $this->userModel->delete($user);
            return $this->successResponse(['message' => 'Cliente eliminado exitosamente']);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
