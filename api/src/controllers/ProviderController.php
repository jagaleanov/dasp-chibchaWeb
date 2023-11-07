<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador

use src\models\Provider;
use src\services\ContainerService;

// Controlador para gestionar provedores
class ProviderController extends Controller
{
    // Propiedad para el repositorio de provedores
    private $providerRepository;

    // Constructor que inyecta el repositorio de provedores
    public function __construct()
    {
        $this->providerRepository = ContainerService::getInstance()->get('ProviderRepository');
    }

    // Método para obtener todos los provedores
    public function getAllProviders()
    {
        try {
            $providers = $this->providerRepository->findAll();
            return $this->successResponse($providers);
        } catch (\Exception $e) {
            // En caso de error, se retorna un mensaje de error
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para obtener un provedor por su ID
    public function getProvider($id)
    {
        try {
            $provider = $this->providerRepository->find($id);
            return $this->successResponse($provider);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para crear un nuevo provedor
    public function createProvider()
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
            if (empty($data['name'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $provider = new Provider();
            $provider->name = $data['name'];
            $provider = $this->providerRepository->save($provider);
            
            return $this->successResponse(['provider' => $provider]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
            
        }
    }

    // Método para actualizar un provedor por su ID
    public function updateProvider($id)
    {
        try {
            $data = $this->getInputData();

            if (empty($data['name'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $provider = $this->providerRepository->find($id);

            if (!$provider) {
                return $this->notFoundResponse();
            }

            $provider->name = $data['name'];
            $this->providerRepository->update($provider);
            
            return $this->successResponse(['provider' => $provider]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para eliminar un provedor por su ID
    public function deleteProvider($id)
    {
        try {
            $provider = $this->providerRepository->find($id);

            if (!$provider) {
                return $this->notFoundResponse();
            }

            $this->providerRepository->delete($provider);
            return $this->successResponse(['message' => 'Cliente eliminado exitosamente']);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
