<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador

use src\models\Employee;
use src\services\ContainerService;

// Controlador para gestionar usuarios
class EmployeeController extends Controller
{
    // Propiedad para el repositorio de usuarios
    private $employeeRepository;

    // Constructor que inyecta el repositorio de usuarios
    public function __construct()
    {
        $this->employeeRepository = ContainerService::getInstance()->get('EmployeeRepository');
    }

    // Método para obtener todos los usuarios
    public function getAllEmployees()
    {
        try {
            $users = $this->employeeRepository->findAll();
            return $this->successResponse($users);
        } catch (\Exception $e) {
            // En caso de error, se retorna un mensaje de error
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para obtener un usuario por su ID
    public function getEmployee($id)
    {
        try {
            $users = $this->employeeRepository->find($id);
            return $this->successResponse($users);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para crear un nuevo usuario
    public function createEmployee()
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
            if (empty($data['name']) || empty($data['last_name']) || empty($data['email']) || empty($data['password']) || empty($data['job_title'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $employee = new Employee();
            $employee->name = $data['name'];
            $employee->last_name = $data['last_name'];
            $employee->email = $data['email'];
            $employee->password = password_hash($data['password'], PASSWORD_DEFAULT);
            $employee->job_title = $data['job_title'];

            $employee = $this->employeeRepository->save($employee);
            return $this->successResponse(['employee' => $employee]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
            
        }
    }

    // Método para actualizar un usuario por su ID
    public function updateEmployee($id)
    {
        try {
            $data = $this->getInputData();

            if (empty($data['name']) || empty($data['email'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $employee = $this->employeeRepository->find($id);

            if (!$employee) {
                return $this->notFoundResponse();
            }

            $employee->name = $data['name'];
            $employee->last_name = $data['last_name'];
            $employee->email = $data['email'];
            $employee->password = password_hash($data['password'], PASSWORD_DEFAULT);
            $employee->job_title = $data['job_title'];
            $this->employeeRepository->update($employee);
            
            return $this->successResponse(['employee' => $employee]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para eliminar un usuario por su ID
    public function deleteEmployee($id)
    {
        try {
            $user = $this->employeeRepository->find($id);

            if (!$user) {
                return $this->notFoundResponse();
            }

            $this->employeeRepository->delete($user);
            return $this->successResponse(['message' => 'Empleado eliminado exitosamente']);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
