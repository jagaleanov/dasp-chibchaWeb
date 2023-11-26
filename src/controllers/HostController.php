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
            // print "<pre>"; print_r($data); print "</pre>";
            $this->layoutService->view('hosts/details', $data);
        } catch (\Exception $e) {
            print_r($e);
        }
    }
}
