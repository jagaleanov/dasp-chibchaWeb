<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador

use src\services\ModelService;

// Controlador para gestionar dominios
class DomainController extends Controller
{
    // Propiedad para el repositorio de dominios
    private $domainModel;

    // Constructor que inyecta el repositorio de usuarios
    public function __construct()
    {
        $this->domainModel = ModelService::getInstance()->get('DomainModel');
    }

    // Método para obtener todos los dominios
    public function getAllDomains()
    {
        try {
            $domains = $this->domainModel->findAll();
            return $this->successResponse($domains);
        } catch (\Exception $e) {
            // En caso de error, se retorna un mensaje de error
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para obtener un dominio por su ID
    public function getDomain($id)
    {
        try {
            $domain = $this->domainModel->find($id);
            return $this->successResponse($domain);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para crear un nuevo dominio
    public function createDomain()
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
            if (empty($data['customer_id']) || empty($data['provider_id']) || empty($data['domain']) || empty($data['status'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $domain = new Domain();
            $domain->customer_id = $data['customer_id'];
            $domain->provider_id = $data['provider_id'];
            $domain->domain = $data['domain'];
            $domain->status = $data['status'];
            $domain = $this->domainModel->save($domain);
            
            return $this->successResponse(['domain' => $domain]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
            
        }
    }

    // Método para actualizar un dominio por su ID
    public function updateDomain($id)
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
            if (empty($data['customer_id']) || empty($data['provider_id']) || empty($data['domain']) || empty($data['status'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $domain = $this->domainModel->find($id);

            if (!$domain) {
                return $this->notFoundResponse();
            }

            $domain->customer_id = $data['customer_id'];
            $domain->provider_id = $data['provider_id'];
            $domain->domain = $data['domain'];
            $domain->status = $data['status'];
            $this->domainModel->update($domain);
            
            return $this->successResponse(['domain' => $domain]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para eliminar un dominio por su ID
    public function deleteDomain($id)
    {
        try {
            $domain = $this->domainModel->find($id);

            if (!$domain) {
                return $this->notFoundResponse();
            }

            $this->domainModel->delete($domain);
            return $this->successResponse(['message' => 'Cliente eliminado exitosamente']);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
