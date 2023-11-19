<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador

use Exception;
use src\services\ModelService;
use stdClass;

// Controlador para gestionar dominios
class DomainController extends Controller
{
    // Propiedad para el repositorio de dominios
    private $domainModel, $hostModel, $providerModel;

    // Constructor que inyecta el repositorio de usuarios
    public function __construct()
    {
        parent::__construct();
        $this->hostModel = ModelService::getInstance()->get('HostModel');
        $this->domainModel = ModelService::getInstance()->get('DomainModel');
        $this->providerModel = ModelService::getInstance()->get('ProviderModel');
    }

    public function newDomain($hostId)
    {
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
                        header('Location:' . BASE_URL . '/customers/details');
                    } else {
                        $this->layoutService->setMessage([
                            'danger' => [$res->message],
                        ]);
                    }
                } else {
                    $this->layoutService->setMessage([
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

            $this->layoutService->view('domains/new', $data);
        } catch (\Exception $e) {
            print_r($e);
        }
    }

    private function createDomain($data)
    {
        try {
            // Iniciar una transacción
            $this->domainModel->beginTransaction();

            $domain = new stdClass();
            $domain->domain = $data['domain'];
            $domain->host_id = $data['host_id'];
            $domain->provider_id = $data['provider_id'];

            $ticket = $this->domainModel->save($domain);
            if (!$ticket) {
                $this->domainModel->rollback();
                throw new Exception('Datos de cliente inválidos');
            }

            // Confirmar la transacción
            $this->domainModel->commit();

            return (object)[
                'success' => true,
                'data' => $ticket,
            ];
        } catch (\Exception $e) {
            return (object)[
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    // Método para obtener todos los dominios
    public function getAllDomains()
    {
        try {
            $domains = $this->domainModel->findAll();
            return $this->successResponse($domains);
        } catch (\Exception $e) {
            // En caso de error, se retorna un mensaje de error
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para obtener un dominio por su ID
    public function getDomain($id)
    {
        try {
            $domain = $this->domainModel->find($id);
            return $this->successResponse($domain);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // // Método para crear un nuevo dominio
    // public function createDomain()
    // {
    //     try {
    //         $data = $this->getInputData();

    //         // Validación de datos de entrada
    //         if (empty($data['customer_id']) || empty($data['provider_id']) || empty($data['domain']) || empty($data['status'])) {
    //             return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
    //         }

    //         $domain = new Domain();
    //         $domain->customer_id = $data['customer_id'];
    //         $domain->provider_id = $data['provider_id'];
    //         $domain->domain = $data['domain'];
    //         $domain->status = $data['status'];
    //         $domain = $this->domainModel->save($domain);

    //         return $this->successResponse(['domain' => $domain]);
    //     } catch (\Exception $e) {
    //         return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);

    //     }
    // }

    // Método para actualizar un dominio por su ID
    public function updateDomain($id)
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
            if (empty($data['customer_id']) || empty($data['provider_id']) || empty($data['domain']) || empty($data['status'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $domain = $this->domainModel->find($id);

            if (!$domain) {
                return $this->notFoundResponse();
            }

            $domain->customer_id = $data['customer_id'];
            $domain->provider_id = $data['provider_id'];
            $domain->domain = $data['domain'];
            $domain->status = $data['status'];
            $this->domainModel->update($domain);

            return $this->successResponse(['domain' => $domain]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para eliminar un dominio por su ID
    public function deleteDomain($id)
    {
        try {
            $domain = $this->domainModel->find($id);

            if (!$domain) {
                return $this->notFoundResponse();
            }

            $this->domainModel->delete($domain);
            return $this->successResponse(['message' => 'Cliente eliminado exitosamente']);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
