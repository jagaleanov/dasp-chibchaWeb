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

    // Método para crear un nuevo tarjeta de crédito
    public function createCreditCard()
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
            if (empty($data['customer_id']) || empty($data['number']) || empty($data['expiration_year']) || empty($data['expiration_month']) || empty($data['security_code']) || empty($data['name'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            // Asegúrate de que el número de tarjeta sea un string y esté limpio de espacios o guiones
            $data['number'] = str_replace(array('-', ' '), '', $data['number']);

            // Verifica la marca de la tarjeta basándose en los primeros dígitos
            if (preg_match("/^4[0-9]{12}(?:[0-9]{3})?$/", $data['number'])) {
                $type = 'Visa';
            } elseif (preg_match("/^5[1-5][0-9]{14}$/", $data['number'])) {
                $type = 'MasterCard';
            } elseif (preg_match("/^3[47][0-9]{13}$/", $data['number'])) {
                $type = 'American Express';
            } elseif (preg_match("/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/", $data['number'])) {
                $type = 'Diners Club';
            } elseif (preg_match("/^6(?:011|5[0-9]{2})[0-9]{12}$/", $data['number'])) {
                $type = 'Discover';
            } elseif (preg_match("/^(?:2131|1800|35\d{3})\d{11}$/", $data['number'])) {
                $type = 'JCB';
            }

            $creditCard = new CreditCard();
            $creditCard->customer_id = $data['customer_id'];
            $creditCard->number = $data['number'];
            $creditCard->type = $type;
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
