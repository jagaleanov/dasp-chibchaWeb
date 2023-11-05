<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador

use src\models\Role;
use src\services\ContainerService;

// Controlador para gestionar usuarios
class RoleController extends Controller
{
    // Propiedad para el repositorio de usuarios
    private $roleRepository;

    // Constructor que inyecta el repositorio de usuarios
    public function __construct()
    {
        $this->roleRepository = ContainerService::getInstance()->get('RoleRepository');
    }

    // Método para obtener todos los usuarios
    public function getAllRoles()
    {
        try {
            $users = $this->roleRepository->findAll();
            return $this->successResponse($users);
        } catch (\Exception $e) {
            // En caso de error, se retorna un mensaje de error
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para obtener un usuario por su ID
    public function getRole($id)
    {
        try {
            $users = $this->roleRepository->find($id);
            return $this->successResponse($users);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para crear un nuevo usuario
    public function createRole()
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
            if (empty($data['name'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $role = new Role();
            $role->name = $data['name'];
            $role = $this->roleRepository->save($role);
            
            return $this->successResponse(['role' => $role]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
            
        }
    }

    // Método para actualizar un usuario por su ID
    public function updateRole($id)
    {
        try {
            $data = $this->getInputData();

            if (empty($data['name'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $role = $this->roleRepository->find($id);

            if (!$role) {
                return $this->notFoundResponse();
            }

            $role->name = $data['name'];
            $this->roleRepository->update($role);
            
            return $this->successResponse(['role' => $role]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para eliminar un usuario por su ID
    public function deleteRole($id)
    {
        try {
            $user = $this->roleRepository->find($id);

            if (!$user) {
                return $this->notFoundResponse();
            }

            $this->roleRepository->delete($user);
            return $this->successResponse(['message' => 'Cliente eliminado exitosamente']);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
