<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador

use src\services\ModelService;

// Controlador para gestionar planes de hosting
class HostPlanController extends Controller
{
    // Propiedad para el repositorio de planes de hosting
    private $hostPlanModel;

    // Constructor que inyecta el repositorio de usuarios
    public function __construct()
    {
        $this->hostPlanModel = ModelService::getInstance()->get('HostPlanModel');
    }

    // Método para obtener todos los planes de hosting
    public function getAllHostPlans()
    {
        try {
            $hostPlans = $this->hostPlanModel->findAll();
            return $this->successResponse($hostPlans);
        } catch (\Exception $e) {
            // En caso de error, se retorna un mensaje de error
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para obtener un plan de hosting por su ID
    public function getHostPlan($id)
    {
        try {
            $hostPlan = $this->hostPlanModel->find($id);
            return $this->successResponse($hostPlan);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para crear un nuevo plan de hosting
    public function createHostPlan()
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
            if (empty($data['name'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $hostPlan = new HostPlan();
            $hostPlan->name = $data['name'];
            $hostPlan = $this->hostPlanModel->save($hostPlan);
            
            return $this->successResponse(['hostPlan' => $hostPlan]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
            
        }
    }

    // Método para actualizar un plan de hosting por su ID
    public function updateHostPlan($id)
    {
        try {
            $data = $this->getInputData();

            if (empty($data['name'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $hostPlan = $this->hostPlanModel->find($id);

            if (!$hostPlan) {
                return $this->notFoundResponse();
            }

            $hostPlan->name = $data['name'];
            $this->hostPlanModel->update($hostPlan);
            
            return $this->successResponse(['hostPlan' => $hostPlan]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para eliminar un plan de hosting por su ID
    public function deleteHostPlan($id)
    {
        try {
            $hostPlan = $this->hostPlanModel->find($id);

            if (!$hostPlan) {
                return $this->notFoundResponse();
            }

            $this->hostPlanModel->delete($hostPlan);
            return $this->successResponse(['message' => 'Cliente eliminado exitosamente']);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
