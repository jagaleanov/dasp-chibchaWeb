<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador

use src\models\Domain;
use src\services\ContainerService;

// Controlador para gestionar dominios
class DomainController extends Controller
{
    // Propiedad para el repositorio de dominios
    private $domainRepository;

    // Constructor que inyecta el repositorio de usuarios
    public function __construct()
    {
        $this->domainRepository = ContainerService::getInstance()->get('DomainRepository');
    }

    // Método para obtener todos los dominios
    public function getAllDomains()
    {
        try {
            $domains = $this->domainRepository->findAll();
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
            $domain = $this->domainRepository->find($id);
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
            $domain = $this->domainRepository->save($domain);
            
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

            $domain = $this->domainRepository->find($id);

            if (!$domain) {
                return $this->notFoundResponse();
            }

            $domain->customer_id = $data['customer_id'];
            $domain->provider_id = $data['provider_id'];
            $domain->domain = $data['domain'];
            $domain->status = $data['status'];
            $this->domainRepository->update($domain);
            
            return $this->successResponse(['domain' => $domain]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para eliminar un dominio por su ID
    public function deleteDomain($id)
    {
        try {
            $domain = $this->domainRepository->find($id);

            if (!$domain) {
                return $this->notFoundResponse();
            }

            $this->domainRepository->delete($domain);
            return $this->successResponse(['message' => 'Cliente eliminado exitosamente']);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
