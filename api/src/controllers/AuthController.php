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
    private $roleRepository;

    // Constructor que inyecta el repositorio de usuarios
    public function __construct()
    {
        $this->userRepository = ContainerService::getInstance()->get('UserRepository');
        $this->roleRepository = ContainerService::getInstance()->get('RoleRepository');
    }

    // Método para obtener un usuario por su ID
    public function login()
    {
        try {
            $data = $this->getInputData();

            // Validación de datos de entrada
            if (empty($data['email']) || empty($data['password'])) {
                return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
            }

            $user = $this->userRepository->getByEmail($data['email']);
            if (!$user) {
                return $this->errorResponse('Usuario inexistente', self::HTTP_BAD_REQUEST);
            }
            $role = $this->roleRepository->find($user->role_id);

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

            return $this->successResponse(['token' => $token, 'user' => $user, 'role' => $role]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
