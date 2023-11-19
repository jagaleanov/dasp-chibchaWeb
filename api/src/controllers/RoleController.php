<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador

use src\models\Role;
use src\services\RepositoryService;

// Controlador para gestionar roles
class RoleController extends Controller
{
    // Propiedad para el repositorio de roles
    private $roleRepository;

    // Constructor que inyecta el repositorio de roles
    public function __construct()
    {
        $this->roleRepository = RepositoryService::getInstance()->get('RoleRepository');
    }

    // Método para obtener todos los roles
    public function getAllRoles()
    {
        try {
            $roles = $this->roleRepository->findAll();
            return $this->successResponse($roles);
        } catch (\Exception $e) {
            // En caso de error, se retorna un mensaje de error
            return $this->errorResponse($e->getMessage() /*. ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString()*/ , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para obtener un rol por su ID
    public function getRole($id)
    {
        try {
            $role = $this->roleRepository->find($id);
            return $this->successResponse($role);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() /*. ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString()*/ , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para crear un nuevo rol
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
            return $this->errorResponse($e->getMessage() /*. ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString()*/ , self::HTTP_INTERNAL_SERVER_ERROR);
            
        }
    }

    // Método para actualizar un rol por su ID
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
            return $this->errorResponse($e->getMessage() /*. ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString()*/ , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para eliminar un rol por su ID
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
            return $this->errorResponse($e->getMessage() /*. ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString()*/ , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
