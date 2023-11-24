<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador

use src\services\ModelService;

// Controlador para gestionar dominios
class PaymentController extends Controller
{
    // Propiedad para el repositorio de dominios
    private $paymentModel;

    // Constructor que inyecta el repositorio de usuarios
    public function __construct()
    {
        $this->paymentModel = ModelService::getInstance()->get('PaymentModel');
    }

    // Método para obtener todos los dominios
    public function getAllPayments()
    {
        try {
            $payments = $this->paymentModel->findAll();
            return $this->successResponse($payments);
        } catch (\Exception $e) {
            // En caso de error, se retorna un mensaje de error
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para obtener un dominio por su ID
    public function getPayment($id)
    {
        try {
            $payment = $this->paymentModel->find($id);
            return $this->successResponse($payment);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para crear un nuevo dominio
    public function createPayment()
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
            if (empty($data['host_id']) || empty($data['credit_card_customer_id']) || empty($data['credit_card_number']) || empty($data['amount'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $payment = new Payment();
            $payment->host_id = $data['host_id'];
            $payment->credit_card_customer_id = $data['credit_card_customer_id'];
            $payment->credit_card_number = $data['credit_card_number'];
            $payment->amount = $data['amount'];
            $payment = $this->paymentModel->save($payment);
            
            return $this->successResponse(['payment' => $payment]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
            
        }
    }

    // Método para actualizar un dominio por su ID
    public function updatePayment($id)
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
            if (empty($data['host_id']) || empty($data['credit_card_customer_id']) || empty($data['credit_card_number']) || empty($data['amount'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $payment = $this->paymentModel->find($id);

            if (!$payment) {
                return $this->notFoundResponse();
            }

            $payment->host_id = $data['host_id'];
            $payment->credit_card_customer_id = $data['credit_card_customer_id'];
            $payment->credit_card_number = $data['credit_card_number'];
            $payment->amount = $data['amount'];
            $this->paymentModel->update($payment);
            
            return $this->successResponse(['payment' => $payment]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para eliminar un dominio por su ID
    public function deletePayment($id)
    {
        try {
            $payment = $this->paymentModel->find($id);

            if (!$payment) {
                return $this->notFoundResponse();
            }

            $this->paymentModel->delete($payment);
            return $this->successResponse(['message' => 'Cliente eliminado exitosamente']);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}