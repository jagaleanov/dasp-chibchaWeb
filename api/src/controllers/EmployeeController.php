<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador

use src\services\ModelService;

// Controlador para gestionar empleados
class EmployeeController extends Controller
{
    // Propiedad para el repositorio de empleados
    private $employeeModel;

    // Constructor que inyecta el repositorio de empleados
    public function __construct()
    {
        $this->employeeModel = ModelService::getInstance()->get('EmployeeModel');
    }

    // Método para obtener todos los empleados
    public function getAllEmployees()
    {
        try {
            $users = $this->employeeModel->findAll();
            return $this->successResponse($users);
        } catch (\Exception $e) {
            // En caso de error, se retorna un mensaje de error
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para obtener un empleado por su ID
    public function getEmployee($id)
    {
        try {
            $users = $this->employeeModel->find($id);
            return $this->successResponse($users);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para crear un nuevo empleado
    public function createEmployee()
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
            if (empty($data['name']) || empty($data['last_name']) || empty($data['email']) || empty($data['password']) || empty($data['mobile_phone']) || empty($data['role_id'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $employee = new Employee();
            $employee->name = $data['name'];
            $employee->last_name = $data['last_name'];
            $employee->email = $data['email'];
            $employee->role_id = $data['role_id'];
            $employee->password = password_hash($data['password'], PASSWORD_DEFAULT);
            $employee->mobile_phone = $data['mobile_phone'];

            $employee = $this->employeeModel->save($employee);
            return $this->successResponse(['employee' => $employee]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
            
        }
    }

    // Método para actualizar un empleado por su ID
    public function updateEmployee($id)
    {
        try {
            $data = $this->getInputData();

            if (empty($data['name']) || empty($data['last_name']) || empty($data['email']) || empty($data['password']) || empty($data['mobile_phone'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $employee = $this->employeeModel->find($id);

            if (!$employee) {
                return $this->notFoundResponse();
            }

            $employee->name = $data['name'];
            $employee->last_name = $data['last_name'];
            $employee->email = $data['email'];
            $employee->password = password_hash($data['password'], PASSWORD_DEFAULT);
            $employee->mobile_phone = $data['mobile_phone'];
            $this->employeeModel->update($employee);
            
            return $this->successResponse(['employee' => $employee]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para eliminar un empleado por su ID
    public function deleteEmployee($id)
    {
        try {
            $user = $this->employeeModel->find($id);

            if (!$user) {
                return $this->notFoundResponse();
            }

            $this->employeeModel->delete($user);
            return $this->successResponse(['message' => 'Empleado eliminado exitosamente']);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
