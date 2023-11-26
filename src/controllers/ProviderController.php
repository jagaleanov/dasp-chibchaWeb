<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador

use src\modules\menu\MenuController;
use src\services\ModelService;

// Controlador para gestionar provedores
class ProviderController extends Controller
{
    // Propiedad para el repositorio de provedores
    private $providerModel, $domainModel;

    // Constructor que inyecta el repositorio de provedores
    public function __construct()
    {
        parent::__construct();
        $this->providerModel = ModelService::getInstance()->get('ProviderModel');
        $this->domainModel = ModelService::getInstance()->get('DomainModel');
    }

    // Método para obtener todos los provedores
    public function getAllProviders()
    {
        try {

            $providers = $this->providerModel->findAll();

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
            $approvedDomains = $this->domainModel->findAll([
                'provider_id' => ['value' => $provider->id, 'operator' => '='],
                'status' => ['value' => 1, 'operator' => '='],
            ]);
            $approvedCounter = count($approvedDomains);

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
            // print"<pre>";print_r($data);print"</pre>";
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
