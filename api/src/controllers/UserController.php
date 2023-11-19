<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador
use src\models\User;
use src\services\LayoutService;
use src\services\RepositoryService;

// Controlador para gestionar usuarios
class UserController extends Controller
{
    // Propiedad para el repositorio de usuarios
    private $userRepository,$layoutService;

    // Constructor que inyecta el repositorio de usuarios
    public function __construct()
    {
        $this->userRepository = RepositoryService::getInstance()->get('UserRepository');
        $this->layoutService = LayoutService::getInstance();
    }

    // Método para obtener todos los usuarios
    public function getAllUsers()
    {
        try {
            $users = $this->userRepository->findAll();
            $this->layoutService->view('home');
            return $this->successResponse($users);
        } catch (\Exception $e) {
            // En caso de error, se retorna un mensaje de error
            return $this->errorResponse($e->getMessage(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para obtener un usuario por su ID
    public function getUser($id)
    {
        try {
            $user = $this->userRepository->find($id);
            return $this->successResponse($user);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para eliminar un usuario por su ID
    public function deleteUser($id)
    {
        try {
            $user = $this->userRepository->find($id);

            if (!$user) {
                return $this->notFoundResponse();
            }

            $this->userRepository->delete($user);
            return $this->successResponse(['message' => 'Cliente eliminado exitosamente']);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
