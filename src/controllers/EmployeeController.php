<?php

namespace src\controllers;

use Exception;
use src\modules\menu\MenuController;
use src\services\ModelService;
use stdClass;

class EmployeeController extends Controller
{
    private $employeeModel, $roleModel, $ticketModel;

    public function __construct()
    {
        parent::__construct();
        $this->employeeModel = ModelService::getInstance()->get('EmployeeModel');
        $this->roleModel = ModelService::getInstance()->get('RoleModel');
        $this->ticketModel = ModelService::getInstance()->get('TicketModel');

        if (!$this->aclService->isRoleIn([5,6])) {
            header('Location:' . BASE_URL . '/home');
        }
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

            $roles = $this->roleModel->findAll([
                'name' => ['value' => 'Cliente', 'operator' => '!=']
            ]);

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
                throw new Exception('Datos de empleado inválidos');
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

    public function editEmployee($id)
    {
        try {

            if ($this->postService->get('submitEmployee')) {
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
                    // [
                    //     'field' => 'password',
                    //     'label' => 'contraseña',
                    //     'rules' => ['required', 'min_length:6']
                    // ],
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
                    $validatedData['id'] = $id;
                    $res = $this->updateEmployee($validatedData);

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

            if ($this->postService->get('submitPassword')) {
                // Validación de datos de entrada
                $rules = [
                    [
                        'field' => 'password',
                        'label' => 'contraseña',
                        'rules' => ['required', 'min_length:6']
                    ],
                ];

                $validate = $this->validationService->validate($this->postService->get(), $rules);

                if ($validate->valid === true) {
                    $validatedData = $validate->sanitizedData;
                    $validatedData['id'] = $id;
                    $validatedData['password'] = password_hash($validatedData['password'], PASSWORD_DEFAULT);
                    $res = $this->updateEmployeePassword($validatedData);

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

            $employee = $this->employeeModel->find($id);

            $roles = $this->roleModel->findAll([
                'name' => ['value' => 'Cliente', 'operator' => '!=']
            ]);

            $data = [
                'post' => $this->postService,
                'employee' => $employee,
                'roles' => $roles,
            ];
            $menu = new MenuController();
            $this->layoutService->setModule('navBar', $menu->index());
            // print"<pre>";print_r($data);print"</pre>";
            $this->layoutService->view('employees/edit', $data);
        } catch (\Exception $e) {
            print_r($e);
        }
    }

    private function updateEmployee($data)
    {
        try {
            $employee = $this->employeeModel->find($data['id']);

            $employee->name = $data['name'];
            $employee->last_name = $data['last_name'];
            $employee->email = $data['email'];
            $employee->role_id = $data['role_id'];
            $employee->mobile_phone = $data['mobile_phone'];

            $employee = $this->employeeModel->update($employee);
            if (!$employee) {
                throw new Exception('Datos de empleado inválidos');
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

    private function updateEmployeePassword($data)
    {
        try {
            $employee = $this->employeeModel->find($data['id']);

            $employeeId = $data['id'];
            $employeePassword = $data['password'];

            $employee = $this->employeeModel->updatePassword($employeeId, $employeePassword);
            if (!$employee) {
                throw new Exception('Datos de empleado inválidos');
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

    public function employeeDetails($id)
    {
        try {
            $employee = $this->employeeModel->find($id);
            $tickets = $this->ticketModel->findAll([
                'employee_id' => ['value' => $employee->id, 'operator' => '=']
            ]);

            $data = [
                'employee' => $employee,
                'tickets' => $tickets,
            ];

            $menu = new MenuController();
            $this->layoutService->setModule('navBar',$menu->index());
            $this->layoutService->view('employees/details', $data);
        } catch (\Exception $e) {
            print_r($e);
        }
    }
}
