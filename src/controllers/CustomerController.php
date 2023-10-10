<?php

namespace src\controllers;

use src\models\Customer;
use src\repositories\CustomerRepository;
use src\traits\ApiResponse;

class CustomerController
{
    use ApiResponse;

    // Definición de constantes HTTP
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_NOT_FOUND = 404;
    const HTTP_INTERNAL_SERVER_ERROR = 500;

    private $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    private function getInputData()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    public function getAllCustomers()
    {
        try {
            $customers = $this->customerRepository->findAll();

            return $this->successResponse($customers);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createCustomer()
    {
        try {
            $data = $this->getInputData();

            if (empty($data['name']) || empty($data['email'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $customer = new Customer();

            $customer->name = $data['name'];
            $customer->email = $data['email'];

            $this->customerRepository->save($customer);

            return $this->successResponse(['message' => 'Cliente creado exitosamente']);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function updateCustomer($id)
    {
        try {
            $data = $this->getInputData();

            if (empty($data['name']) || empty($data['email'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $customer = $this->customerRepository->find($id);

            if (!$customer) {
                return $this->notFoundResponse();
            }
            $customer->name = $data['name'];
            $customer->email = $data['email'];

            $this->customerRepository->save($customer);

            return $this->successResponse(['message' => 'Cliente actualizado exitosamente']);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteCustomer($id)
    {
        try {
            $customer = $this->customerRepository->find($id);

            if (!$customer) {
                return $this->notFoundResponse();
            }

            $this->customerRepository->delete($customer);

            return $this->successResponse(['message' => 'Cliente eliminado exitosamente']);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
