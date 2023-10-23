<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador
use src\models\User;
use src\services\ContainerService;

// Controlador para gestionar usuarios
class UserController extends Controller
{
    // Propiedad para el repositorio de usuarios
    private $userRepository;

    // Constructor que inyecta el repositorio de usuarios
    public function __construct()
    {
        $this->userRepository = ContainerService::getInstance()->get('UserRepository');
    }

    // Método para obtener todos los usuarios
    public function getAllUsers()
    {
        try {
            $users = $this->userRepository->findAll();
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
            $users = $this->userRepository->find($id);
            return $this->successResponse($users);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para crear un nuevo usuario
    public function createUser()
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
            if (empty($data['name']) || empty($data['last_name']) || empty($data['email']) || empty($data['password'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $user = new User();
            $user->name = $data['name'];
            $user->last_name = $data['last_name'];
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = password_hash($data['password'], PASSWORD_DEFAULT);

            $user = $this->userRepository->save($user);
            return $this->successResponse(['user' => $user]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para actualizar un usuario por su ID
    public function updateUser($id)
    {
        try {
            $data = $this->getInputData();

            if (empty($data['name']) || empty($data['email'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $user = $this->userRepository->find($id);

            if (!$user) {
                return $this->notFoundResponse();
            }

            $user->name = $data['name'];
            $user->email = $data['email'];
            $this->userRepository->save($user);

            return $this->successResponse(['message' => 'Cliente actualizado exitosamente']);
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
