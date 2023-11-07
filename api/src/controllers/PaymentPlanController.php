<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador

use src\models\PaymentPlan;
use src\services\ContainerService;

// Controlador para gestionar planes de pago
class PaymentPlanController extends Controller
{
    // Propiedad para el repositorio de usuarios
    private $paymentPlanRepository;

    // Constructor que inyecta el repositorio de planes de pago
    public function __construct()
    {
        $this->paymentPlanRepository = ContainerService::getInstance()->get('PaymentPlanRepository');
    }

    // Método para obtener todos los planes de pago
    public function getAllPaymentPlans()
    {
        try {
            $paymentPlans = $this->paymentPlanRepository->findAll();
            return $this->successResponse($paymentPlans);
        } catch (\Exception $e) {
            // En caso de error, se retorna un mensaje de error
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para obtener un plan de pagos por su ID
    public function getPaymentPlan($id)
    {
        try {
            $paymentPlan = $this->paymentPlanRepository->find($id);
            return $this->successResponse($paymentPlan);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para crear un nuevo plan de pagos
    public function createPaymentPlan()
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
            if (empty($data['name'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $paymentPlan = new PaymentPlan();
            $paymentPlan->name = $data['name'];
            $paymentPlan = $this->paymentPlanRepository->save($paymentPlan);
            
            return $this->successResponse(['paymentPlan' => $paymentPlan]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
            
        }
    }

    // Método para actualizar un plan de pagos por su ID
    public function updatePaymentPlan($id)
    {
        try {
            $data = $this->getInputData();

            if (empty($data['name'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $paymentPlan = $this->paymentPlanRepository->find($id);

            if (!$paymentPlan) {
                return $this->notFoundResponse();
            }

            $paymentPlan->name = $data['name'];
            $this->paymentPlanRepository->update($paymentPlan);
            
            return $this->successResponse(['paymentPlan' => $paymentPlan]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para eliminar un plan de pagos por su ID
    public function deletePaymentPlan($id)
    {
        try {
            $paymentPlan = $this->paymentPlanRepository->find($id);

            if (!$paymentPlan) {
                return $this->notFoundResponse();
            }

            $this->paymentPlanRepository->delete($paymentPlan);
            return $this->successResponse(['message' => 'Cliente eliminado exitosamente']);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
