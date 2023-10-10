<?php

namespace src\router;

use src\traits\ApiResponse;

class Router {

    use ApiResponse;

    private $routes = [];

    public function add($method, $uri, $action) {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action
        ];
    }

    public function dispatch($requestMethod, $requestUri) {
        // print $requestMethod;
        // print $requestUri;
        foreach ($this->routes as $route) {
            $pattern = $this->generatePattern($route['uri']);
            if ($route['method'] == $requestMethod && preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches);  // Removemos la primera coincidencia que es la URI completa

                list($class, $method) = explode("@", $route['action']);
                $classWithNamespace = "src\\controllers\\" . $class;

                if (class_exists($classWithNamespace) && method_exists($classWithNamespace, $method)) {
                    // Aquí es donde necesitas proporcionar las dependencias al controlador
                    if ($classWithNamespace === "src\\controllers\\CustomerController") {
                        $repository = new \src\repositories\CustomerRepository();
                        $controller = new $classWithNamespace($repository);
                    } else {
                        $controller = new $classWithNamespace();
                    }
                    return $controller->$method(...$matches);
                }
                
            }
        }
        
        // Si ninguna ruta coincide
        header("HTTP/1.1 404 Not Found");
        return $this->notFoundResponse();  // Usamos el método del trait para devolver una respuesta coherente
    }

    private function generatePattern($uri) {
        // Convertimos las variables, por ejemplo: {id} a una expresión regular como ([^/]+)
        // Esto coincide con cualquier carácter que no sea una barra.
        $pattern = preg_replace('/\{\w+\}/', '([^/]+)', $uri);
        return "@^" . $pattern . "$@D";
    }
}
