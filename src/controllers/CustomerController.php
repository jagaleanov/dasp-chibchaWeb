<?php

namespace src\controllers;

use src\modules\menu\MenuController;
use src\services\ModelService;

class CustomerController extends Controller
{
    private $customerModel, $hostModel, $domainModel, $ticketModel, $creditCardModel;

    public function __construct()
    {
        parent::__construct();
        $this->customerModel = ModelService::getInstance()->get('CustomerModel');
        $this->hostModel = ModelService::getInstance()->get('HostModel');
        $this->domainModel = ModelService::getInstance()->get('DomainModel');
        $this->ticketModel = ModelService::getInstance()->get('TicketModel');
        $this->creditCardModel = ModelService::getInstance()->get('CreditCardModel');
        
    }

    public function getAllCustomers()
    {
        if (!$this->aclService->isRoleIn([2, 3, 4, 5, 6])) {
            $_SESSION['systemMessages'] = [
                'danger'=>'Acceso restringido.'
            ];
            header('Location:' . BASE_URL . '/home');
            exit;
        }

        try {
            if (!$this->aclService->isRoleIn([2, 3, 4, 5, 6])) {
                $_SESSION['systemMessages'] = [
                    'danger'=>'Acceso restringido.'
                ];
                header('Location:' . BASE_URL . '/home');
                exit;
            }

            $customers = $this->customerModel->findAll();

            $data = [
                'post' => $this->postService,
                'customers' => $customers,
            ];
            $menu = new MenuController();
            $this->layoutService->setModule('navBar', $menu->index());

            $this->layoutService->view('customers/list', $data);
        } catch (\Exception $e) {
            print_r($e);
        }
    }

    public function customerDetails($id = null)
    {
        if (!$this->aclService->isRoleIn([1, 2, 3, 4, 5, 6])) {
            $_SESSION['systemMessages'] = [
                'danger'=>'Acceso restringido.'
            ];
            header('Location:' . BASE_URL . '/home');
            exit;
        }

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
            $hosts = $this->hostModel->findAll([
                'customer_id' => ['value' => $customer->id, 'operator' => '='],
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
            $this->layoutService->view('customers/details', $data);
        } catch (\Exception $e) {
            print_r($e);
        }
    }
}
