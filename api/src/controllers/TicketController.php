<?php

namespace src\controllers;

use Exception;
use src\modules\menu\MenuController;
use src\services\ModelService;
use stdClass;

class TicketController extends Controller
{
    private $ticketModel,$hostModel;

    public function __construct()
    {
        parent::__construct();
        $this->ticketModel = ModelService::getInstance()->get('TicketModel');
        $this->hostModel = ModelService::getInstance()->get('HostModel');
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
            $this->layoutService->setModule('navBar',$menu->index());

            $this->layoutService->view('tickets/new', $data);
        } catch (\Exception $e) {
            print_r($e);
        }
    }

    private function createTicket($data)
    {
        try {
            // Iniciar una transacción
            $this->ticketModel->beginTransaction();

            $ticket = new stdClass();
            $ticket->description = $data['description'];
            $ticket->host_id = $data['host_id'];

            $ticket = $this->ticketModel->save($ticket);
            if (!$ticket) {
                $this->ticketModel->rollback();
                throw new Exception('Datos de cliente inválidos');
            }

            // Confirmar la transacción
            $this->ticketModel->commit();

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









    // Método para obtener todos los dominios
    public function getAllTickets()
    {
        try {
            $tickets = $this->ticketModel->findAll();
            return $this->successResponse($tickets);
        } catch (\Exception $e) {
            // En caso de error, se retorna un mensaje de error
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para obtener un dominio por su ID
    public function getTicket($id)
    {
        try {
            $ticket = $this->ticketModel->find($id);
            return $this->successResponse($ticket);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // // Método para crear un nuevo dominio
    // public function createTicket()
    // {
    //     try {
    //         $data = $this->getInputData();

    //         // Validación de datos de entrada
    //         if (empty($data['host_id']) || empty($data['description'])) {
    //             return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
    //         }

    //         $ticket = new Ticket();
    //         $ticket->host_id = $data['host_id'];
    //         $ticket->description = $data['description'];
    //         $ticket = $this->ticketModel->save($ticket);

    //         return $this->successResponse(['ticket' => $ticket]);
    //     } catch (\Exception $e) {
    //         return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);

    //     }
    // }

    // // Método para actualizar un dominio por su ID
    // public function updateTicketRole($id)
    // {
    //     try {
    //         $data = $this->getInputData();

    //         // Validación de datos de entrada
    //         if (empty($data['role_id'])) {
    //             return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
    //         }

    //         $ticket = $this->ticketModel->find($id);

    //         if (!$ticket) {
    //             return $this->notFoundResponse();
    //         }

    //         $role_id = $data['role_id'];
    //         $this->ticketModel->updateRole($id, $role_id);

    //         return $this->successResponse(['ticket' => $ticket]);
    //     } catch (\Exception $e) {
    //         return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString(), self::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    // Método para actualizar un dominio por su ID
    public function updateTicketStatus($id)
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
            if (empty($data['status'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $ticket = $this->ticketModel->find($id);

            if (!$ticket) {
                return $this->notFoundResponse();
            }

            $status = $data['status'];
            $this->ticketModel->updateStatus($id, $status);

            return $this->successResponse(['ticket' => $ticket]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // // Método para eliminar un dominio por su ID
    // public function deleteTicket($id)
    // {
    //     try {
    //         $ticket = $this->ticketModel->find($id);

    //         if (!$ticket) {
    //             return $this->notFoundResponse();
    //         }

    //         $this->ticketModel->delete($ticket);
    //         return $this->successResponse(['message' => 'Cliente eliminado exitosamente']);
    //     } catch (\Exception $e) {
    //         return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString(), self::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }
}
