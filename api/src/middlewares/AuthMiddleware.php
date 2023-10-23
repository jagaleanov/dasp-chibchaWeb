<?php

namespace src\middlewares;

use src\services\JwtService;
use src\traits\ApiResponse;
use src\router\Router;

class AuthMiddleware
{
    use ApiResponse; // Usamos el trait ApiResponse
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function handle()
    {
        $requestPath = /*"/api" .*/ parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if ($this->router->isRoutePublic($_SERVER["REQUEST_METHOD"], $requestPath)) {
            return; // Si la ruta es pública, no hacemos nada
        }
        
        // Verificamos si el token está presente en el encabezado
        if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
            // Usamos el método errorResponse del trait para enviar una respuesta de error
            http_response_code(self::$HTTP_UNAUTHORIZED);
            echo json_encode($this->errorResponse('Token no proporcionado.', self::$HTTP_UNAUTHORIZED));
            exit(); // Termina la ejecución del script
        }

        $token = $_SERVER['HTTP_AUTHORIZATION'];

        try {
            $decoded = JwtService::decode($token);
            // Aquí puedes guardar el payload decodificado en una variable global o de sesión si lo necesitas más tarde
        } catch (\Exception $e) {
            // Usamos el método errorResponse del trait para enviar una respuesta de error
            http_response_code(self::$HTTP_UNAUTHORIZED);
            echo json_encode($this->errorResponse('Token inválido.', self::$HTTP_UNAUTHORIZED));
            exit(); // Termina la ejecución del script
        }
    }
}
