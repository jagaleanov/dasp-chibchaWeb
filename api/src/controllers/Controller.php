<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador
use src\traits\ApiResponse;

class Controller
{
    // Uso del trait ApiResponse para manejar respuestas de la API
    use ApiResponse;

    // Definición de constantes para códigos de estado HTTP
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_NOT_FOUND = 404;
    const HTTP_INTERNAL_SERVER_ERROR = 500;

    // Método privado para obtener datos de entrada en formato JSON
    protected function getInputData()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
}
