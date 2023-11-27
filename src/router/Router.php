<?php

namespace src\router;

use src\modules\menu\MenuController;
use src\services\LayoutService;

// Clase Router que gestiona las rutas y su correspondencia con los controladores y métodos
class Router
{

    // Array que contendrá todas las rutas definidas
    private $routes = [];

    // Método para añadir rutas al router
    public function add($uri, $action)
    {
        $this->routes[] = [
            'uri' => $uri,
            'action' => $action,
        ];
    }

    // Método que maneja las solicitudes entrantes y las despacha al controlador y método correspondiente
    public function dispatch($requestUri)
    {
        foreach ($this->routes as $route) {
            // Convertimos la URI definida en una expresión regular para hacer coincidir con la URI solicitada
            $pattern = $this->generatePattern($route['uri']);

            if ( preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches);  // Removemos la primera coincidencia que es la URI completa

                list($class, $method) = explode("@", $route['action']);
                $classWithNamespace = "src\\controllers\\" . $class;

                // Verificamos que el controlador y el método existan
                if (class_exists($classWithNamespace) && method_exists($classWithNamespace, $method)) {
                    // Si el controlador es "UserController", proporcionamos la dependencia del repositorio
                    if ($classWithNamespace === "src\\controllers\\AuthController") {
                        $model = new \src\models\UserModel();
                        $controller = new $classWithNamespace($model);
                    } else {
                        $controller = new $classWithNamespace();
                    }
                    // Ejecutamos el método del controlador con los parámetros extraídos de la URI
                    return $controller->$method(...$matches);
                }
            }
        }

        // Si no se encuentra ninguna ruta que coincida, devolvemos un error 404
        $layoutService = LayoutService::getInstance();
        $menu = new MenuController();
        $layoutService->setModule('navBar', $menu->index());
        $layoutService->view('error404');
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
