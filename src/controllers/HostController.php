<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador

use src\services\ModelService;

// Controlador para gestionar dominios
class HostController extends Controller
{
    // Propiedad para el repositorio de dominios
    private $hostModel;

    // Constructor que inyecta el repositorio de usuarios
    public function __construct()
    {
        $this->hostModel = ModelService::getInstance()->get('HostModel');
    }

    // Método para obtener todos los dominios
    public function getAllHosts()
    {
        try {
            $hosts = $this->hostModel->findAll();
            return $this->successResponse($hosts);
        } catch (\Exception $e) {
            // En caso de error, se retorna un mensaje de error
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para obtener todos los dominios
    public function getCustomerHosts($customerId)
    {
        try {
            $hosts = $this->hostModel->findAll($customerId);
            return $this->successResponse($hosts);
        } catch (\Exception $e) {
            // En caso de error, se retorna un mensaje de error
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para obtener un dominio por su ID
    public function getHost($id)
    {
        try {
            $host = $this->hostModel->find($id);
            return $this->successResponse($host);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para crear un nuevo dominio
    public function createHost()
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
            if (empty($data['customer_id']) || empty($data['host_plan_id']) || empty($data['payment_plan_id']) || empty($data['operative_system_id'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $host = new Host();
            $host->customer_id = $data['customer_id'];
            $host->host_plan_id = $data['host_plan_id'];
            $host->payment_plan_id = $data['payment_plan_id'];
            $host->operative_system_id = $data['operative_system_id'];
            $host = $this->hostModel->save($host);
            
            return $this->successResponse(['host' => $host]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
            
        }
    }

    // Método para actualizar un dominio por su ID
    public function updateHost($id)
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
            if (empty($data['customer_id']) || empty($data['provider_id']) || empty($data['host']) || empty($data['status'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $host = $this->hostModel->find($id);

            if (!$host) {
                return $this->notFoundResponse();
            }

            $host->customer_id = $data['customer_id'];
            $host->host_plan_id = $data['host_plan_id'];
            $host->payment_plan_id = $data['payment_plan_id'];
            $host->operative_system_id = $data['operative_system_id'];
            $this->hostModel->update($host);
            
            return $this->successResponse(['host' => $host]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para eliminar un dominio por su ID
    public function deleteHost($id)
    {
        try {
            $host = $this->hostModel->find($id);

            if (!$host) {
                return $this->notFoundResponse();
            }

            $this->hostModel->delete($host);
            return $this->successResponse(['message' => 'Cliente eliminado exitosamente']);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
