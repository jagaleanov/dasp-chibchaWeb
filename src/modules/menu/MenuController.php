<?php

// Espacio de nombres utilizado por el controlador
namespace src\modules\menu;

use src\controllers\Controller;
use src\services\ModelService;

// Importaciones de otras clases que se usarán en el controlador

// Controlador para gestionar tarjetas de crédito
class MenuController extends Controller
{
    // Propiedad para el repositorio de tarjetas de crédito
    private $menuModel;

    // Constructor que inyecta el repositorio de usuarios
    public function __construct()
    {
        parent::__construct();
        $this->menuModel = ModelService::getInstance()->get('MenuModel');
    }

    public function index()
    {
        try {

            $links = [
                (object)[
                    'label' => 'Usuarios',
                    'url' => BASE_URL . '/users/list',
                    'icon' => 'person-square',
                    'roles' => [2],
                ],
                (object)[
                    'label' => 'Clientes',
                    'url' => BASE_URL . '/customers/list',
                    'icon' => 'people',
                    'roles' => [2],
                ],
                (object)[
                    'label' => 'Empleados',
                    'url' => BASE_URL . '/employees/list',
                    'icon' => 'buildings',
                    'roles' => [2],
                ],
                (object)[
                    'label' => 'Proveedores',
                    'url' => BASE_URL . '/providers/list',
                    'icon' => 'person-square',
                    'roles' => [2],
                ],
                (object)[
                    'label' => 'Pagos',
                    'url' => BASE_URL . '/payments/list',
                    'icon' => 'cash',
                    'roles' => [2],
                ],
                (object)[
                    'label' => 'Tickets',
                    'url' => BASE_URL . '/tickets/list',
                    'icon' => 'question-diamond',
                    'roles' => [2],
                ],
                (object)[
                    'label' => 'Dominios',
                    'url' => BASE_URL . '/domains/list',
                    'icon' => 'braces-asterisk',
                    'roles' => [2],
                ],
                (object)[
                    'label' => 'Comisiones',
                    'url' => BASE_URL,
                    'icon' => 'coin',
                    'roles' => [2],
                ],
            ];

            $roleId = $this->aclService->getUser()->role_id;

            $res = [];
            foreach ($links as $i => $link) {
                if (in_array($roleId, $link->roles)) {
                    $res[] = $link;
                }
            }

            $login = true;
            if ($roleId > 0) {
                $login = false;
            }

            $data = [
                'links' => $res,
                'login' => $login,
            ];

            $this->layoutService->view('../modules/menu/menuView', $data);
        } catch (\Exception $e) {
            print_r($e);
        }
    }
}
