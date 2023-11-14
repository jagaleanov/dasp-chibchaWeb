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
            // print_r('OK');
            // $data = new stdClass();

            // print_r($token);
            // $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjaGliY2hhd2ViLmNvbSIsImF1ZCI6ImNoaWJjaGF3ZWIuY29tIiwiaWF0IjoxNjk5OTI2NjQ3LCJkYXRhIjp7InVzZXJJZCI6bnVsbCwidXNlckVtYWlsIjoiZW1haWxvcTlAZW1haWwuY29tIn0sImV4cCI6MTY5OTkzMDI0N30.te7tva4H91hb7kBsp-YYOVSZJdwE-WgOraURNMgiDcQ';

            $pattern = '/^Bearer\s/';

            // Reemplaza 'Bearer ' con un string vacío si se encuentra al inicio
            $token = preg_replace($pattern, '', $token);

            $res = JWT::decode($token, new Key(self::$key, 'HS256'));
            // print_r(JWT::decode($token, $key, $leeway));
            // print_r($res);
            // return JWT::decode($token, $key, $leeway); // Pasar la variable
            // return JWT::decode($token, $key, new stdClass()); // Pasar la variable $key
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
