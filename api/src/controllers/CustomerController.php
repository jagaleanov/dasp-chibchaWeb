<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador

use src\models\CreditCard;
use src\models\Customer;
use src\models\Host;
use src\models\Payment;
use src\services\RepositoryService;

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
        parent::__construct();
        $this->customerRepository = RepositoryService::getInstance()->get('CustomerRepository');
        $this->hostRepository = RepositoryService::getInstance()->get('HostRepository');
        $this->paymentRepository = RepositoryService::getInstance()->get('PaymentRepository');
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
    public function customerDetails($id = null)
    {
        try {
            if($id == null){
                $id = $this->aclService->getUser()->id;
            }
            $customer = $this->customerRepository->find($id);
            $hosts = $this->hostRepository->findAll($id);
            $data = [
                'customer' => $customer,
                'hosts' => $hosts,
            ];
            $this->layoutService->view('customer/details', $data);
        } catch (\Exception $e) {
            print_r($e);
        }
    }
}
