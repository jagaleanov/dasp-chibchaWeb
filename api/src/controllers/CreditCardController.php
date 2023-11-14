<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador

use src\models\CreditCard;
use src\services\ContainerService;

// Controlador para gestionar tarjetas de crédito
class CreditCardController extends Controller
{
    // Propiedad para el repositorio de tarjetas de crédito
    private $creditCardRepository;

    // Constructor que inyecta el repositorio de usuarios
    public function __construct()
    {
        $this->creditCardRepository = ContainerService::getInstance()->get('CreditCardRepository');
    }

    // Método para obtener todos los tarjetas de crédito
    public function getAllCreditCards()
    {
        try {
            $creditCards = $this->creditCardRepository->findAll();
            return $this->successResponse($creditCards);
        } catch (\Exception $e) {
            // En caso de error, se retorna un mensaje de error
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para obtener un tarjeta de crédito por su ID
    public function getCreditCard($customerId, $number)
    {
        try {
            $creditCard = $this->creditCardRepository->find($customerId, $number);
            return $this->successResponse($creditCard);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para obtener un tarjeta de crédito por su ID
    public function validateCreditCard($number)
    {
        try {
            $creditCard = $this->creditCardRepository->getCreditCardType($number);
            if(!$creditCard){
                return $this->errorResponse('Tarjeta inválida', self::HTTP_BAD_REQUEST);  
            }
            return $this->successResponse($creditCard);
            
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() /*. ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString()*/, self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para crear un nuevo tarjeta de crédito
    public function createCreditCard()
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
            if (empty($data['customer_id']) || empty($data['number']) || empty($data['expiration_year']) || empty($data['expiration_month']) || empty($data['security_code']) || empty($data['name'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $creditCard = new CreditCard();
            $creditCard->customer_id = $data['customer_id'];
            $creditCard->number = $data['number'];
            $creditCard->expiration_year = $data['expiration_year'];
            $creditCard->expiration_month = $data['expiration_month'];
            $creditCard->security_code = $data['security_code'];
            $creditCard->name = $data['name'];
            $creditCard = $this->creditCardRepository->save($creditCard);

            return $this->successResponse(['creditCard' => $creditCard]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para eliminar un tarjeta de crédito por su ID
    public function deleteCreditCard($customerId, $number)
    {
        try {
            $creditCard = $this->creditCardRepository->find($customerId, $number);

            if (!$creditCard) {
                return $this->notFoundResponse();
            }

            $this->creditCardRepository->delete($creditCard);
            return $this->successResponse(['message' => 'Cliente eliminado exitosamente']);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
