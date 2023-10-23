<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

use src\models\User;
use src\repositories\UserRepository;
use src\services\ContainerService;
use src\services\JwtService;

// Controlador para gestionar clientes
class AuthController extends Controller
{
    private $userRepository;

    // Constructor que inyecta el repositorio de clientes
    public function __construct()
    {
        $this->userRepository = ContainerService::getInstance()->get('UserRepository');
    }

    // Método para obtener un cliente por su ID
    public function login()
    {
        try {
            $data = $this->getInputData();
            // print_r($data);

            // Validación de datos de entrada
            if (empty($data['email']) || empty($data['password'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $user = $this->userRepository->getByEmail($data['email']);
            if (!$user) {
                return $this->errorResponse('Usuario inexistente', self::HTTP_BAD_REQUEST);
            }

            if (!password_verify($data['password'], $user->password)) {
                return $this->errorResponse('Password inválido', self::HTTP_BAD_REQUEST);
            }

            $payload = [
                "iss" => "tu_dominio.com", // emisor
                "aud" => "tu_dominio.com", // audiencia
                "iat" => time(), // tiempo de emitido
                "data" => [ // datos personalizados
                    "userId" => $user->id,
                    "userEmail" => $user->email
                ]
            ];

            $token = JwtService::encode($payload);

            return $this->successResponse(['token' => $token, 'user' => $user]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Método para crear un nuevo cliente
    public function register()
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
            if (empty($data['name']) || empty($data['last_name']) || empty($data['email']) || empty($data['password'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $user = new User();
            $user->name = $data['name'];
            $user->last_name = $data['last_name'];
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = password_hash($data['password'], PASSWORD_DEFAULT);

            $this->userRepository->save($user);
            return $this->successResponse(['message' => 'Cliente creado exitosamente']);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
