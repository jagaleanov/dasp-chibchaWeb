<?php

namespace src\controllers;

use src\modules\menu\MenuController;
use src\services\ModelService;

class ProviderController extends Controller
{
    private $providerModel, $domainModel;

    public function __construct()
    { 
        parent::__construct();
        $this->providerModel = ModelService::getInstance()->get('ProviderModel');
        $this->domainModel = ModelService::getInstance()->get('DomainModel');

        if (!$this->aclService->isRoleIn([4, 6])) {
            $_SESSION['systemMessages'] = [
                'danger'=>'Acceso restringido.'
            ];
            header('Location:' . BASE_URL . '/home');
            exit;
        }
    }

    public function getAllProviders()
    {
        try {

            $providers = $this->providerModel->findAll();
            foreach($providers as $i => $provider){
                $providers[$i]->approvedDomains = $this->domainModel->countApprovedDomains($provider->id);
                $providers[$i]->commission = $this->calculateCommision($providers[$i]->approvedDomains,$provider->price);
            }

            $data = [
                'post' => $this->postService,
                'providers' => $providers,
            ];
            $menu = new MenuController();
            $this->layoutService->setModule('navBar', $menu->index());
            $this->layoutService->view('providers/list', $data);
        } catch (\Exception $e) {
            print_r($e);
        }
    }

    public function providerDetails($id)
    {
        try {
            $provider = $this->providerModel->find($id);
            $domains = $this->domainModel->findAll([
                'provider_id' => ['value' => $provider->id, 'operator' => '=']
            ]);
            $requestsCounter = count($domains);
            $approvedCounter = $this->domainModel->countApprovedDomains($provider->id);

            $commission = $this->calculateCommision($approvedCounter, $provider->price);

            $data = [
                'provider' => $provider,
                'domains' => $domains,
                'requestsCounter' => $requestsCounter,
                'approvedCounter' => $approvedCounter,
                'commission' => $commission,
            ];
            $menu = new MenuController();
            $this->layoutService->setModule('navBar', $menu->index());
            $this->layoutService->view('providers/details', $data);
        } catch (\Exception $e) {
            print_r($e);
        }
    }

    public function calculateCommision($approvedCounter, $price)
    {
        $totalCost = $approvedCounter * $price;
        $providerType = $approvedCounter <= 100 ? 'BASIC' : 'PREMIUM';
        $commissionValue = $providerType == 'BASIC' ? 0.1 : 0.2;
        $commissionTotal = $totalCost * $commissionValue;
        return [
            'totalCost' => $totalCost,
            'comissionValue' => $commissionValue * 100,
            'comissionTotal' => $commissionTotal,
            'providerType' => $providerType,
        ];
    }
}
