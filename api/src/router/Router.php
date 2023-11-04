<?php

namespace src\router;

use src\traits\ApiResponse;

// Clase Router que gestiona las rutas y su correspondencia con los controladores y métodos
class Router
{

    // Uso del trait ApiResponse para manejar respuestas de la API
    use ApiResponse;

    // Array que contendrá todas las rutas definidas
    private $routes = [];

    // Método para añadir rutas al router
    public function add($method, $uri, $action, $isPublic = false)
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action,
            'public' => $isPublic
        ];
    }

    public function isRoutePublic($requestMethod, $requestUri)
    {
        foreach ($this->routes as $route) {
            $pattern = $this->generatePattern($route['uri']);
            // print $pattern;
            // print $requestUri;
            // print $route['method'] == $requestMethod;
            // print preg_match($pattern, $requestUri);
            if ($route['method'] == $requestMethod && preg_match($pattern, $requestUri)) {
                return $route['public'];
            }
        }
        return false; // Por defecto, las rutas son privadas
    }

    // Método que maneja las solicitudes entrantes y las despacha al controlador y método correspondiente
    public function dispatch($requestMethod, $requestUri)
    {
        // print $requestMethod;
        // print $requestUri;
        // $requestUri = "/api" . $requestUri;
        foreach ($this->routes as $route) {
            // Convertimos la URI definida en una expresión regular para hacer coincidir con la URI solicitada
            $pattern = $this->generatePattern($route['uri']);
            // exit;

            if ($route['method'] == $requestMethod && preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches);  // Removemos la primera coincidencia que es la URI completa

                list($class, $method) = explode("@", $route['action']);
                $classWithNamespace = "src\\controllers\\" . $class;
                // print_r($classWithNamespace);
                // print_r(class_exists($classWithNamespace));

                // Verificamos que el controlador y el método existan
                if (class_exists($classWithNamespace) && method_exists($classWithNamespace, $method)) {
                    // Si el controlador es "UserController", proporcionamos la dependencia del repositorio
                    if ($classWithNamespace === "src\\controllers\\AuthController") {
                        $repository = new \src\repositories\UserRepository();
                        $controller = new $classWithNamespace($repository);
                    } else {
                        $controller = new $classWithNamespace();
                    }
                    // Ejecutamos el método del controlador con los parámetros extraídos de la URI
                    return $controller->$method(...$matches);
                }
            }
        }

        // Si no se encuentra ninguna ruta que coincida, devolvemos un error 404
        header("HTTP/1.1 404 Not Found");
        return $this->notFoundResponse();  // Usamos el método del trait para devolver una respuesta coherente
    }

    // Método privado que convierte la URI con parámetros (por ej. {id}) en una expresión regular
    private function generatePattern($uri)
    {
        // Convertimos las variables, por ejemplo: {id} a una expresión regular como ([^/]+)
        // Esto coincide con cualquier carácter que no sea una barra.
        $pattern = preg_replace('/\{\w+\}/', '([^/]+)', $uri);
        return "@^" . $pattern . "$@D";
    }
}
