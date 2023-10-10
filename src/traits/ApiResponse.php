<?php

namespace src\traits;

trait ApiResponse
{
    private static $HTTP_OK = 200;
    private static $HTTP_CREATED = 201;
    private static $HTTP_ACCEPTED = 202;
    private static $HTTP_NO_CONTENT = 204;
    private static $HTTP_BAD_REQUEST = 400;
    private static $HTTP_UNAUTHORIZED = 401;
    private static $HTTP_FORBIDDEN = 403;
    private static $HTTP_NOT_FOUND = 404;
    private static $HTTP_INTERNAL_SERVER_ERROR = 500;

    protected function successResponse($data, $code = null)
    {
        if ($code === null) {
            $code = self::$HTTP_OK;
        }
        http_response_code($code);
        return [
            'status' => 'success',
            'data' => $data
        ];
    }

    protected function errorResponse($message, $code)
    {
        http_response_code($code);
        return [
            'status' => 'error',
            'message' => $message
        ];
    }

    protected function notFoundResponse()
    {
        return $this->errorResponse('Recurso no encontrado', self::$HTTP_NOT_FOUND);
    }
}
