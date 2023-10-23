<?php

namespace src\services;

use src\libraries\jwt\JWT;
use src\libraries\jwt\Key;
use stdClass;

class JwtService
{
    private static $key = "your_secret_key"; // Cambia esto por una clave secreta única
    private static $expiryTime = 3600; // Token válido por 1 hora (3600 segundos)

    public static function encode($data)
    {
        // Agregar la reclamación 'exp' al payload del token
        $data['exp'] = time() + self::$expiryTime; // Tiempo actual + tiempo de validez del token

        return JWT::encode($data, self::$key, 'HS256');
    }

    public static function decode($token)
    {
        try {
            return JWT::decode($token, new Key(self::$key, 'HS256'), new stdClass());
        } catch (\UnexpectedValueException $e) {
            // Esta excepción puede ser causada por varios problemas con el token,
            // uno de ellos es que el token haya expirado.
            // Puedes verificar específicamente el mensaje de la excepción para determinar
            // si fue causado por la expiración, pero por simplicidad, aquí simplemente
            // relanzamos la excepción.
            throw $e;
        }
    }
}
