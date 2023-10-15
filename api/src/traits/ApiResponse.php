<?php

namespace src\traits;

// Trait ApiResponse para proporcionar respuestas estandarizadas para la API
trait ApiResponse
{
    // Definición de constantes para códigos de estado HTTP
    private static $HTTP_OK = 200;
    private static $HTTP_CREATED = 201;
    private static $HTTP_ACCEPTED = 202;
    private static $HTTP_NO_CONTENT = 204;
    private static $HTTP_BAD_REQUEST = 400;
    private static $HTTP_UNAUTHORIZED = 401;
    private static $HTTP_FORBIDDEN = 403;
    private static $HTTP_NOT_FOUND = 404;
    private static $HTTP_INTERNAL_SERVER_ERROR = 500;

    // Método para devolver una respuesta exitosa
    protected function successResponse($data, $code = null)
    {
        // Si no se proporciona un código, se utiliza 200 OK por defecto
        if ($code === null) {
            $code = self::$HTTP_OK;
        }
        http_response_code($code);
        return [
            'status' => 'success',
            'data' => $data
        ];
    }

    // Método para devolver una respuesta de error
    protected function errorResponse($message, $code)
    {
        http_response_code($code);
        return [
            'status' => 'error',
            'message' => $message
        ];
    }

    // Método para devolver una respuesta de "No encontrado"
    protected function notFoundResponse()
    {
        return $this->errorResponse('Recurso no encontrado', self::$HTTP_NOT_FOUND);
    }
}
