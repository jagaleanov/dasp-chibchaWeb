<?php

namespace src\controllers;

use Exception;
use src\modules\menu\MenuController;
use src\services\ModelService;
use stdClass;

// Controlador para gestionar dominios
class DomainController extends Controller
{
    private $domainModel, $hostModel, $providerModel;

    public function __construct()
    {
        parent::__construct();
        $this->hostModel = ModelService::getInstance()->get('HostModel');
        $this->domainModel = ModelService::getInstance()->get('DomainModel');
        $this->providerModel = ModelService::getInstance()->get('ProviderModel');
    }

    public function newDomain($hostId)
    {
        if (!$this->aclService->isRoleIn([1])) {
            $_SESSION['systemMessages'] = [
                'danger' => 'Acceso restringido.'
            ];
            header('Location:' . BASE_URL . '/home');
            exit;
        }

        try {

            if ($this->postService->get('submit')) {
                // Validación de datos de entrada
                $rules = [
                    [
                        'field' => 'domain',
                        'label' => 'dominio',
                        'rules' => ['required', 'domain']
                    ],
                    [
                        'field' => 'provider_id',
                        'label' => 'proveedor',
                        'rules' => ['required', 'int']
                    ],
                ];

                $validate = $this->validationService->validate($this->postService->get(), $rules);

                if ($validate->valid === true) {
                    $validatedData = $validate->sanitizedData;
                    $validatedData['host_id'] = $hostId;
                    $res = $this->createDomain($validatedData);

                    if ($res->success) {
                        $_SESSION['systemMessages'] = [
                            'success' => 'Solicitud de dominio registrada.'
                        ];
                        header('Location:' . BASE_URL . '/customers/details');
                        exit;
                    } else {
                        $this->layoutService->setMessages([
                            'danger' => [$res->message],
                        ]);
                    }
                } else {
                    $this->layoutService->setMessages([
                        'danger' => $validate->errors,
                    ]);
                }
            }

            $host = $this->hostModel->find($hostId);
            $providers = $this->providerModel->findAll();

            $data = [
                'post' => $this->postService,
                'host' => $host,
                'providers' => $providers,
            ];
            $menu = new MenuController();
            $this->layoutService->setModule('navBar', $menu->index());

            $this->layoutService->view('domains/new', $data);
        } catch (\Exception $e) {
            print_r($e);
        }
    }

    private function createDomain($data)
    {
        try {
            if ($this->domainModel->issetDomain($data['domain'])) {
                throw new Exception('El dominio ya se encuentra registrado.');
            }

            $domain = new stdClass();
            $domain->domain = $data['domain'];
            $domain->host_id = $data['host_id'];
            $domain->provider_id = $data['provider_id'];

            $domain = $this->domainModel->save($domain);
            if (!$domain) {
                throw new Exception('Datos de dominio inválidos');
            }

            return (object)[
                'success' => true,
                'data' => $domain,
            ];
        } catch (\Exception $e) {
            return (object)[
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function getAllDomains()
    {
        if (!$this->aclService->isRoleIn([4, 6])) {
            $_SESSION['systemMessages'] = [
                'danger' => 'Acceso restringido.'
            ];
            header('Location:' . BASE_URL . '/home');
            exit;
        }

        try {

            if ($this->postService->get('submit')) {
                $rules = [
                    [
                        'field' => 'domain_id',
                        'label' => 'dominio id',
                        'rules' => ['required']
                    ],
                    [
                        'field' => 'status',
                        'label' => 'estado',
                        'rules' => ['required']
                    ],
                ];

                $validate = $this->validationService->validate($this->postService->get(), $rules);

                if ($validate->valid === true) {
                    $validatedData = $validate->sanitizedData;
                    $res = $this->updateDomainStatus($validatedData);

                    if ($res->success) {
                        $_SESSION['systemMessages'] = [
                            'success' => 'Cambio de estado registrado.'
                        ];
                        header('Location:' . BASE_URL . '/domains/list');
                        exit;
                    } else {
                        $this->layoutService->setMessages([
                            'danger' => [$res->message],
                        ]);
                    }
                } else {
                    $this->layoutService->setMessages([
                        'danger' => $validate->errors,
                    ]);
                }
            }

            $domains = $this->domainModel->findAll();

            $data = [
                'post' => $this->postService,
                'domains' => $domains,
            ];
            $menu = new MenuController();
            $this->layoutService->setModule('navBar', $menu->index());
            $this->layoutService->view('domains/list', $data);
        } catch (\Exception $e) {
            print_r($e);
        }
    }

    private function updateDomainStatus($data)
    {
        try {

            $domain = $this->domainModel->updateStatus($data['domain_id'], $data['status']);
            if (!$domain) {
                throw new Exception('Datos de dominio inválidos');
            }

            return (object)[
                'success' => true,
                'domain' => $domain,
            ];
        } catch (\Exception $e) {
            return (object)[
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
