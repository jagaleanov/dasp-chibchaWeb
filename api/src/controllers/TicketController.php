<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador

use src\models\Ticket;
use src\services\ContainerService;

// Controlador para gestionar dominios
class TicketController extends Controller
{
    // Propiedad para el repositorio de dominios
    private $ticketRepository;

    // Constructor que inyecta el repositorio de usuarios
    public function __construct()
    {
        $this->ticketRepository = ContainerService::getInstance()->get('TicketRepository');
    }

    // Método para obtener todos los dominios
    public function getAllTickets()
    {
        try {
            $tickets = $this->ticketRepository->findAll();
            return $this->successResponse($tickets);
        } catch (\Exception $e) {
            // En caso de error, se retorna un mensaje de error
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para obtener un dominio por su ID
    public function getTicket($id)
    {
        try {
            $ticket = $this->ticketRepository->find($id);
            return $this->successResponse($ticket);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para crear un nuevo dominio
    public function createTicket()
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
            if (empty($data['host_id']) || empty($data['description'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $ticket = new Ticket();
            $ticket->host_id = $data['host_id'];
            $ticket->description = $data['description'];
            $ticket = $this->ticketRepository->save($ticket);
            
            return $this->successResponse(['ticket' => $ticket]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
            
        }
    }

    // Método para actualizar un dominio por su ID
    public function updateTicketRole($id)
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
            if (empty($data['role_id'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $ticket = $this->ticketRepository->find($id);

            if (!$ticket) {
                return $this->notFoundResponse();
            }

            $role_id = $data['role_id'];
            $this->ticketRepository->updateRole($id, $role_id);
            
            return $this->successResponse(['ticket' => $ticket]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para actualizar un dominio por su ID
    public function updateTicketStatus($id)
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
            if (empty($data['status'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $ticket = $this->ticketRepository->find($id);

            if (!$ticket) {
                return $this->notFoundResponse();
            }

            $status = $data['status'];
            $this->ticketRepository->updateStatus($id, $status);
            
            return $this->successResponse(['ticket' => $ticket]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para eliminar un dominio por su ID
    public function deleteTicket($id)
    {
        try {
            $ticket = $this->ticketRepository->find($id);

            if (!$ticket) {
                return $this->notFoundResponse();
            }

            $this->ticketRepository->delete($ticket);
            return $this->successResponse(['message' => 'Cliente eliminado exitosamente']);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
