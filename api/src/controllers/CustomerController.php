<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador
use src\models\Customer;
use src\repositories\CustomerRepository;
use src\traits\ApiResponse;

// Controlador para gestionar clientes
class CustomerController
{
    // Uso del trait ApiResponse para manejar respuestas de la API
    use ApiResponse;

    // Definición de constantes para códigos de estado HTTP
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_NOT_FOUND = 404;
    const HTTP_INTERNAL_SERVER_ERROR = 500;

    // Propiedad para el repositorio de clientes
    private $customerRepository;

    // Constructor que inyecta el repositorio de clientes
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    // Método privado para obtener datos de entrada en formato JSON
    private function getInputData()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    // Método para obtener todos los clientes
    public function getAllCustomers()
    {
        try {
            $customers = $this->customerRepository->findAll();
            return $this->successResponse($customers);
        } catch (\Exception $e) {
            // En caso de error, se retorna un mensaje de error
            return $this->errorResponse($e->getMessage(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para obtener un cliente por su ID
    public function getCustomer($id)
    {
        try {
            $customers = $this->customerRepository->find($id);
            return $this->successResponse($customers);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para crear un nuevo cliente
    public function createCustomer()
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
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

    // Método para actualizar un cliente por su ID
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

    // Método para eliminar un cliente por su ID
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
