<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador

use src\models\Customer;
use src\services\ContainerService;

// Controlador para gestionar usuarios
class CustomerController extends Controller
{
    // Propiedad para el repositorio de usuarios
    private $customerRepository;

    // Constructor que inyecta el repositorio de usuarios
    public function __construct()
    {
        $this->customerRepository = ContainerService::getInstance()->get('CustomerRepository');
    }

    // Método para obtener todos los usuarios
    public function getAllCustomers()
    {
        try {
            $users = $this->customerRepository->findAll();
            return $this->successResponse($users);
        } catch (\Exception $e) {
            // En caso de error, se retorna un mensaje de error
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para obtener un usuario por su ID
    public function getCustomer($id)
    {
        try {
            $users = $this->customerRepository->find($id);
            return $this->successResponse($users);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para crear un nuevo usuario
    public function createCustomer()
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
            if (empty($data['name']) || empty($data['last_name']) || empty($data['email']) || empty($data['password']) || empty($data['address'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $customer = new Customer();
            $customer->name = $data['name'];
            $customer->last_name = $data['last_name'];
            $customer->email = $data['email'];
            $customer->password = password_hash($data['password'], PASSWORD_DEFAULT);
            $customer->address = $data['address'];
            $customer->corporation = isset($data['corporation']) ? $data['corporation'] : null;

            $customer = $this->customerRepository->save($customer);
            return $this->successResponse(['customer' => $customer]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
            
        }
    }

    // Método para actualizar un usuario por su ID
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
            $customer->last_name = $data['last_name'];
            $customer->email = $data['email'];
            $customer->password = password_hash($data['password'], PASSWORD_DEFAULT);
            $customer->address = $data['address'];
            $customer->corporation = isset($data['corporation']) ? $data['corporation'] : null;
            $this->customerRepository->update($customer);
            
            return $this->successResponse(['customer' => $customer]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para eliminar un usuario por su ID
    public function deleteCustomer($id)
    {
        try {
            $user = $this->customerRepository->find($id);

            if (!$user) {
                return $this->notFoundResponse();
            }

            $this->customerRepository->delete($user);
            return $this->successResponse(['message' => 'Cliente eliminado exitosamente']);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString() , self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
