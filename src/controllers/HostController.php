<?php

namespace src\controllers;

use src\modules\menu\MenuController;
use src\services\ModelService;

class HostController extends Controller
{
    private $hostModel, $customerModel, $domainModel, $ticketModel;

    public function __construct()
    {
        parent::__construct();
        $this->hostModel = ModelService::getInstance()->get('HostModel');
        $this->customerModel = ModelService::getInstance()->get('CustomerModel');
        $this->domainModel = ModelService::getInstance()->get('DomainModel');
        $this->ticketModel = ModelService::getInstance()->get('TicketModel');

        if (!$this->aclService->isRoleIn([2, 3, 4, 5, 6])) {
            $_SESSION['systemMessages'] = [
                'danger'=>'Acceso restringido.'
            ];
            header('Location:' . BASE_URL . '/home');
            exit;
        }
    }

    public function getAllHosts()
    {
        try {

            $hosts = $this->hostModel->findAll();

            $data = [
                'post' => $this->postService,
                'hosts' => $hosts,
            ];
            $menu = new MenuController();
            $this->layoutService->setModule('navBar', $menu->index());
            $this->layoutService->view('hosts/list', $data);
        } catch (\Exception $e) {
            print_r($e);
        }
    }

    public function hostDetails($id)
    {
        try {
            $host = $this->hostModel->find($id);
            $customer = $this->customerModel->find($host->customer_id);
            $domains = $this->domainModel->findAll([
                'host_id' => ['value' => $host->id, 'operator' => '=']
            ]);
            $tickets = $this->ticketModel->findAll([
                'host_id' => ['value' => $host->id, 'operator' => '=']
            ]);

            $data = [
                'host' => $host,
                'customer' => $customer,
                'domains' => $domains,
                'tickets' => $tickets,
            ];
            $menu = new MenuController();
            $this->layoutService->setModule('navBar', $menu->index());
            $this->layoutService->view('hosts/details', $data);
        } catch (\Exception $e) {
            print_r($e);
        }
    }
}
