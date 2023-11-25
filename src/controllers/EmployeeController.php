<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador

use Exception;
use src\modules\menu\MenuController;
use src\services\ModelService;
use stdClass;

// Controlador para gestionar empleados
class EmployeeController extends Controller
{
    // Propiedad para el repositorio de empleados
    private $employeeModel, $roleModel;

    // Constructor que inyecta el repositorio de empleados
    public function __construct()
    {
        parent::__construct();
        $this->employeeModel = ModelService::getInstance()->get('EmployeeModel');
        $this->roleModel = ModelService::getInstance()->get('RoleModel');
    }

    public function newEmployee()
    {
        try {

            if ($this->postService->get('submit')) {
                // Validación de datos de entrada
                $rules = [
                    [
                        'field' => 'name',
                        'label' => 'nombre',
                        'rules' => ['required', 'alpha']
                    ],
                    [
                        'field' => 'last_name',
                        'label' => 'apellido',
                        'rules' => ['required', 'alpha']
                    ],
                    [
                        'field' => 'email',
                        'label' => 'correo electrónico',
                        'rules' => ['required', 'email']
                    ],
                    [
                        'field' => 'password',
                        'label' => 'contraseña',
                        'rules' => ['required', 'min_length:6']
                    ],
                    [
                        'field' => 'role_id',
                        'label' => 'role',
                        'rules' => ['required', 'number', 'min:1']
                    ],
                    [
                        'field' => 'mobile_phone',
                        'label' => 'celular',
                        'rules' => ['required', 'number', 'min_length:10']
                    ],
                ];

                $validate = $this->validationService->validate($this->postService->get(), $rules);

                if ($validate->valid === true) {
                    $validatedData = $validate->sanitizedData;
                    $res = $this->createEmployee($validatedData);

                    if ($res->success) {
                        header('Location:' . BASE_URL . '/employees/list');
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

            $roles = $this->roleModel->findAll();

            $data = [
                'post' => $this->postService,
                'roles' => $roles,
            ];
            $menu = new MenuController();
            $this->layoutService->setModule('navBar', $menu->index());

            $this->layoutService->view('employees/new', $data);
        } catch (\Exception $e) {
            print_r($e);
        }
    }

    private function createEmployee($data)
    {
        try {

            $employee = new stdClass();
            $employee->name = $data['name'];
            $employee->last_name = $data['last_name'];
            $employee->email = $data['email'];
            $employee->password = password_hash($data['password'], PASSWORD_DEFAULT);
            $employee->role_id = $data['role_id'];
            $employee->mobile_phone = $data['mobile_phone'];

            $employee = $this->employeeModel->save($employee);
            if (!$employee) {
                throw new Exception('Datos de cliente inválidos');
            }

            return (object)[
                'success' => true,
                'data' => $employee,
            ];
        } catch (\Exception $e) {
            return (object)[
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    // Método para obtener todos los empleados
    public function getAllEmployees()
    {
        try {

            $employees = $this->employeeModel->findAll();

            $data = [
                'post' => $this->postService,
                'employees' => $employees,
            ];
            $menu = new MenuController();
            $this->layoutService->setModule('navBar', $menu->index());

            $this->layoutService->view('employees/list', $data);
        } catch (\Exception $e) {
            print_r($e);
        }
    }

    // // Método para obtener un empleado por su ID
    // public function getEmployee($id)
    // {
    //     try {
    //         $users = $this->employeeModel->find($id);
    //         return $this->successResponse($users);
    //     } catch (\Exception $e) {
    //         return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString(), self::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    // // Método para actualizar un empleado por su ID
    // public function updateEmployee($id)
    // {
    //     try {
    //         $data = $this->getInputData();

    //         if (empty($data['name']) || empty($data['last_name']) || empty($data['email']) || empty($data['password']) || empty($data['mobile_phone'])) {
    //             return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
    //         }

    //         $employee = $this->employeeModel->find($id);

    //         if (!$employee) {
    //             return $this->notFoundResponse();
    //         }

    //         $employee->name = $data['name'];
    //         $employee->last_name = $data['last_name'];
    //         $employee->email = $data['email'];
    //         $employee->password = password_hash($data['password'], PASSWORD_DEFAULT);
    //         $employee->mobile_phone = $data['mobile_phone'];
    //         $this->employeeModel->update($employee);

    //         return $this->successResponse(['employee' => $employee]);
    //     } catch (\Exception $e) {
    //         return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString(), self::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    // // Método para eliminar un empleado por su ID
    // public function deleteEmployee($id)
    // {
    //     try {
    //         $user = $this->employeeModel->find($id);

    //         if (!$user) {
    //             return $this->notFoundResponse();
    //         }

    //         $this->employeeModel->delete($user);
    //         return $this->successResponse(['message' => 'Empleado eliminado exitosamente']);
    //     } catch (\Exception $e) {
    //         return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString(), self::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }
}
