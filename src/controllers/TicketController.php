<?php

namespace src\controllers;

use Exception;
use src\modules\menu\MenuController;
use src\services\ModelService;
use stdClass;

class TicketController extends Controller
{
    private $ticketModel, $hostModel, $roleModel, $employeeModel;

    public function __construct()
    {
        parent::__construct();
        $this->ticketModel = ModelService::getInstance()->get('TicketModel');
        $this->hostModel = ModelService::getInstance()->get('HostModel');
        $this->roleModel = ModelService::getInstance()->get('RoleModel');
        $this->employeeModel = ModelService::getInstance()->get('EmployeeModel');
    }

    public function newTicket($hostId)
    {
        try {

            if ($this->postService->get('submit')) {
                // Validación de datos de entrada
                $rules = [
                    [
                        'field' => 'description',
                        'label' => 'descripcion',
                        'rules' => ['required', 'alpha']
                    ],
                ];

                $validate = $this->validationService->validate($this->postService->get(), $rules);

                if ($validate->valid === true) {
                    $validatedData = $validate->sanitizedData;
                    $validatedData['host_id'] = $hostId;
                    $res = $this->createTicket($validatedData);

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

            $host = $this->hostModel->find($hostId);

            $data = [
                'post' => $this->postService,
                'host' => $host,
            ];
            $menu = new MenuController();
            $this->layoutService->setModule('navBar', $menu->index());

            $this->layoutService->view('tickets/new', $data);
        } catch (\Exception $e) {
            print_r($e);
        }
    }

    private function createTicket($data)
    {
        try {

            $ticket = new stdClass();
            $ticket->description = $data['description'];
            $ticket->host_id = $data['host_id'];

            $ticket = $this->ticketModel->save($ticket);
            if (!$ticket) {
                throw new Exception('Datos de ticket inválidos');
            }

            return (object)[
                'success' => true,
                'data' => $ticket,
            ];
        } catch (\Exception $e) {
            return (object)[
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function getAllTickets()
    {
        try {

            if ($this->postService->get('submitRole')) {
                $rules = [
                    [
                        'field' => 'ticket_id',
                        'label' => 'ticket id',
                        'rules' => ['required', 'integer', 'min:1']
                    ],
                    [
                        'field' => 'role_id',
                        'label' => 'role',
                        'rules' => ['required', 'integer', 'min:1']
                    ],
                ];

                $validate = $this->validationService->validate($this->postService->get(), $rules);

                if ($validate->valid === true) {
                    $validatedData = $validate->sanitizedData;
                    $res = $this->updateTicketRole($validatedData);

                    if ($res->success) {
                        header('Location:' . BASE_URL . '/tickets/list');
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

            if ($this->postService->get('submitStatus')) {
                $rules = [
                    [
                        'field' => 'ticket_id',
                        'label' => 'ticket id',
                        'rules' => ['required', 'integer', 'min:1']
                    ],
                ];

                $validate = $this->validationService->validate($this->postService->get(), $rules);

                if ($validate->valid === true) {
                    $validatedData = $validate->sanitizedData;
                    $res = $this->updateTicketStatus($validatedData);

                    if ($res->success) {
                        header('Location:' . BASE_URL . '/tickets/list');
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

            $roleId = $this->aclService->getUser()->role_id;

            if ($roleId == 6) {
                $tickets = $this->ticketModel->findAll();
            } else {
                $tickets = $this->ticketModel->findAll([
                    'role_id' => ['value' => $roleId, 'operator' => '=']
                ]);
            }
            $roles = $this->roleModel->findAll([
                'name' => ['value' => 'Soporte', 'operator' => 'LIKE']
            ]);

            $changeRoleAvailable = false;
            $changeStatusAvailable = false;
            if (in_array($roleId, [5, 6])) {
                $changeRoleAvailable = true;
            }

            if (in_array($roleId, [2, 3, 4, 6])) {
                $changeStatusAvailable = true;
            }

            $data = [
                'post' => $this->postService,
                'tickets' => $tickets,
                'roles' => $roles,
                'changeRoleAvailable' => $changeRoleAvailable,
                'changeStatusAvailable' => $changeStatusAvailable,
            ];
            $menu = new MenuController();
            $this->layoutService->setModule('navBar', $menu->index());
            $this->layoutService->view('tickets/list', $data);
        } catch (\Exception $e) {
            print_r($e);
        }
    }

    private function updateTicketRole($data)
    {
        try {
            $ticket = $this->ticketModel->updateRole($data['ticket_id'], $data['role_id']);
            if (!$ticket) {
                throw new Exception('Datos de ticket inválidos');
            }

            return (object)[
                'success' => true,
                'ticket' => $ticket,
            ];
        } catch (\Exception $e) {
            return (object)[
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function updateTicketStatus($data)
    {
        try {
            $userId = $this->aclService->getUser()->id;
            $employee = $this->employeeModel->findByUserId($userId);
            $ticket = $this->ticketModel->updateStatus($data['ticket_id'], $employee->id);
            if (!$ticket) {
                throw new Exception('Datos de ticket inválidos');
            }

            return (object)[
                'success' => true,
                'ticket' => $ticket,
            ];
        } catch (\Exception $e) {
            return (object)[
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
