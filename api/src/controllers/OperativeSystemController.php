<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador

use src\models\OperativeSystem;
use src\services\ContainerService;

// Controlador para gestionar sistemas operativos
class OperativeSystemController extends Controller
{
    // Propiedad para el repositorio de sistemas operativos
    private $operativeSystemRepository;

    // Constructor que inyecta el repositorio de sistemas operativos
    public function __construct()
    {
        $this->operativeSystemRepository = ContainerService::getInstance()->get('OperativeSystemRepository');
    }

    // Método para obtener todos los sistemas operativos
    public function getAllOperativeSystems()
    {
        try {
            $operativeSystems = $this->operativeSystemRepository->findAll();
            return $this->successResponse($operativeSystems);
        } catch (\Exception $e) {
            // En caso de error, se retorna un mensaje de error
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para obtener un sistema operativo por su ID
    public function getOperativeSystem($id)
    {
        try {
            $operativeSystem = $this->operativeSystemRepository->find($id);
            return $this->successResponse($operativeSystem);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para crear un nuevo sistema operativo
    public function createOperativeSystem()
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
            if (empty($data['name'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $operativeSystem = new OperativeSystem();
            $operativeSystem->name = $data['name'];
            $operativeSystem = $this->operativeSystemRepository->save($operativeSystem);
            
            return $this->successResponse(['operativeSystem' => $operativeSystem]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
            
        }
    }

    // Método para actualizar un sistema operativo por su ID
    public function updateOperativeSystem($id)
    {
        try {
            $data = $this->getInputData();

            if (empty($data['name'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $operativeSystem = $this->operativeSystemRepository->find($id);

            if (!$operativeSystem) {
                return $this->notFoundResponse();
            }

            $operativeSystem->name = $data['name'];
            $this->operativeSystemRepository->update($operativeSystem);
            
            return $this->successResponse(['operativeSystem' => $operativeSystem]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para eliminar un sistema operativo por su ID
    public function deleteOperativeSystem($id)
    {
        try {
            $operativeSystem = $this->operativeSystemRepository->find($id);

            if (!$operativeSystem) {
                return $this->notFoundResponse();
            }

            $this->operativeSystemRepository->delete($operativeSystem);
            return $this->successResponse(['message' => 'Cliente eliminado exitosamente']);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
