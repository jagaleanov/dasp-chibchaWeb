<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador

use src\models\CreditCard;
use src\models\Customer;
use src\models\Host;
use src\models\Payment;
use src\services\ContainerService;

// Controlador para gestionar clientes
class CustomerController extends Controller
{
    // Propiedad para el repositorio de clientes
    private $customerRepository;
    private $hostRepository;
    private $paymentRepository;

    // Constructor que inyecta el repositorio de clientes
    public function __construct()
    {
        $this->customerRepository = ContainerService::getInstance()->get('CustomerRepository');
        $this->hostRepository = ContainerService::getInstance()->get('HostRepository');
        $this->paymentRepository = ContainerService::getInstance()->get('PaymentRepository');
    }

    // Método para obtener todos los clientes
    public function getAllCustomers()
    {
        try {
            $users = $this->customerRepository->findAll();
            return $this->successResponse($users);
        } catch (\Exception $e) {
            // En caso de error, se retorna un mensaje de error
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para obtener un cliente por su ID
    public function getCustomer($id)
    {
        try {
            $users = $this->customerRepository->find($id);
            return $this->successResponse($users);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para crear un nuevo cliente
    public function createCustomer()
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
            if (
                empty($data['name'])
                || empty($data['last_name'])
                || empty($data['email'])
                || empty($data['password'])
                || empty($data['address'])
                || empty($data['hosting_plan'])
                || empty($data['operative_system'])
                || empty($data['payment_plan'])
                || empty($data['credit_card_number'])
                || empty($data['credit_card_name'])
                || empty($data['credit_card_month'])
                || empty($data['credit_card_year'])
                || empty($data['credit_card_code'])
                || empty($data['amount'])
            ) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            // Iniciar una transacción
            $this->customerRepository->beginTransaction();

            $customer = new Customer();
            $customer->name = $data['name'];
            $customer->last_name = $data['last_name'];
            $customer->email = $data['email'];
            $customer->password = password_hash($data['password'], PASSWORD_DEFAULT);
            $customer->address = $data['address'];

            $customer = $this->customerRepository->save($customer);
            if(!$customer){
                $this->customerRepository->rollback();
                return $this->errorResponse('Datos de cliente inválidos', self::HTTP_BAD_REQUEST);
            }

            $creditCard = new CreditCard();
            $creditCard->customer_id = $customer->id;
            $creditCard->number = $data['last_name'];
            $creditCard->type = $data['email'];
            $creditCard->expiration_year = $data['credit_card_year'];
            $creditCard->expiration_month = $data['credit_card_month'];
            $creditCard->security_code = $data['credit_card_code'];
            $creditCard->name = $data['credit_card_name'];

            $creditCard = $this->customerRepository->save($creditCard);
            if(!$creditCard){
                $this->customerRepository->rollback();
                return $this->errorResponse('Tarjeta de crédito inválida', self::HTTP_BAD_REQUEST);
            }

            $host = new Host();
            $host->customer_id = $customer->id;
            $host->host_plan_id = $data['hosting_plan'];
            $host->payment_plan_id = $data['payment_plan'];
            $host->operative_system_id = $data['operative_system'];

            $host = $this->hostRepository->save($host);
            if(!$customer){
                $this->customerRepository->rollback();
                return $this->errorResponse('Datos de host inválidos', self::HTTP_BAD_REQUEST);
            }

            $payment = new Payment();
            $payment->host_id = $host->id;
            $payment->credit_card_customer_id = $creditCard->id;
            $payment->credit_card_number = $creditCard->number;
            $payment->amount = $data['amount'];

            $payment = $this->paymentRepository->save($payment);
            if(!$payment){
                $this->customerRepository->rollback();
                return $this->errorResponse('Datos de pago inválidos', self::HTTP_BAD_REQUEST);
            }

            // Confirmar la transacción
            $this->customerRepository->commit();

            return $this->successResponse(['customer' => $customer,'creditCard' => $creditCard,'host' => $host,'payment' => $payment]);
        } catch (\Exception $e) {
            // Si hay un error, revertir la transacción
            $this->customerRepository->rollback();
            return $this->errorResponse($e->getMessage() /*. ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString()*/, self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para actualizar un cliente por su ID
    public function updateCustomer($id)
    {
        try {
            $data = $this->getInputData();

            if (empty($data['name']) || empty($data['last_name']) || empty($data['email']) || empty($data['password']) || empty($data['address'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $customer = $this->customerRepository->find($id);

            if (!$customer) {
                return $this->notFoundResponse();
            }

            $customer->name = $data['name'];
            $customer->last_name = $data['last_name'];
            $customer->email = $data['email'];
            $customer->password = password_hash($data['password'], PASSWORD_DEFAULT);
            $customer->address = $data['address'];
            $this->customerRepository->update($customer);

            return $this->successResponse(['customer' => $customer]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para eliminar un cliente por su ID
    public function deleteCustomer($id)
    {
        try {
            $user = $this->customerRepository->find($id);

            if (!$user) {
                return $this->notFoundResponse();
            }

            $this->customerRepository->delete($user);
            return $this->successResponse(['message' => 'Cliente eliminado exitosamente']);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
