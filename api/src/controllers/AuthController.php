<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

use src\models\User;
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
                "iss" => "chibchaweb.com", // emisor
                "aud" => "chibchaweb.com", // audiencia
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
}
