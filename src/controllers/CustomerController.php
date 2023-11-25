<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador

use src\modules\menu\MenuController;
use src\services\ModelService;

// Controlador para gestionar clientes
class CustomerController extends Controller
{
    // Propiedad para el repositorio de clientes
    private $customerModel, $hostModel, $paymentModel, $domainModel, $ticketModel, $creditCardModel;

    // Constructor que inyecta el repositorio de clientes
    public function __construct()
    {
        parent::__construct();
        $this->customerModel = ModelService::getInstance()->get('CustomerModel');
        $this->hostModel = ModelService::getInstance()->get('HostModel');
        $this->paymentModel = ModelService::getInstance()->get('PaymentModel');
        $this->domainModel = ModelService::getInstance()->get('DomainModel');
        $this->ticketModel = ModelService::getInstance()->get('TicketModel');
        $this->creditCardModel = ModelService::getInstance()->get('CreditCardModel');
        
    }

    // Método para obtener todos los clientes
    public function getAllCustomers()
    {
        try {
            $users = $this->customerModel->findAll();
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
            if ($id == null) {
                $id = $this->aclService->getUser()->id;
            }
            $customer = $this->customerModel->findByUserId($id);
            $creditCard = $this->creditCardModel->findByCustomerId($customer->id);
            $domains = $this->domainModel->findAll([
                'customer_id' => ['value' => $customer->id, 'operator' => '=']
            ]);
            $tickets = $this->ticketModel->findAll([
                'customer_id' => ['value' => $customer->id, 'operator' => '=']
            ]);
            // print "<pre>";print_r($tickets);print "<pre>";
            $hosts = $this->hostModel->findAll([
                'customer_id' => ['value' => $customer->id, 'operator' => '='],
                // 'status' => ['value' => 1, 'operator' => '=']
            ]);
            foreach ($hosts as $id => $host) {
                $hosts[$id]->domains = $this->domainModel->findAll([
                    'host_id' => ['value' => $host->id, 'operator' => '='],
                    'status' => ['value' => 1, 'operator' => '=']
                ]);
            }

            $data = [
                'customer' => $customer,
                'creditCard' => $creditCard,
                'hosts' => $hosts,
                'domains' => $domains,
                'tickets' => $tickets,
            ];
            $menu = new MenuController();
            $this->layoutService->setModule('navBar',$menu->index());
            $this->layoutService->view('customer/details', $data);
        } catch (\Exception $e) {
            print_r($e);
        }
    }
}
