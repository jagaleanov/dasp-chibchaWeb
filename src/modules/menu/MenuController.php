<?php

// Espacio de nombres utilizado por el controlador
namespace src\modules\menu;

use src\controllers\Controller;
use src\services\ModelService;


class MenuController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        try {

            $links = [
                (object)[
                    'label' => 'Usuarios',
                    'url' => BASE_URL . '/users/list',
                    'icon' => 'person-circle',
                    'roles' => [2,3,4,5,6],
                ],
                (object)[
                    'label' => 'Clientes',
                    'url' => BASE_URL . '/customers/list',
                    'icon' => 'people',
                    'roles' => [2,3,4,5,6],
                ],
                (object)[
                    'label' => 'Empleados',
                    'url' => BASE_URL . '/employees/list',
                    'icon' => 'people-fill',
                    'roles' => [5,6],
                ],
                (object)[
                    'label' => 'Proveedores',
                    'url' => BASE_URL . '/providers/list',
                    'icon' => 'buildings',
                    'roles' => [4,6],
                ],
                (object)[
                    'label' => 'Pagos',
                    'url' => BASE_URL . '/payments/list',
                    'icon' => 'cash',
                    'roles' => [2,6],
                ],
                (object)[
                    'label' => 'Hosts',
                    'url' => BASE_URL . '/hosts/list',
                    'icon' => 'hdd-network',
                    'roles' => [3,6],
                ],
                (object)[
                    'label' => 'Dominios',
                    'url' => BASE_URL . '/domains/list',
                    'icon' => 'braces-asterisk',
                    'roles' => [4,6],
                ],
                (object)[
                    'label' => 'Tickets',
                    'url' => BASE_URL . '/tickets/list',
                    'icon' => 'question-diamond',
                    'roles' => [2,3,4,5,6],
                ],
                (object)[
                    'label' => 'Dashboard',
                    'url' => BASE_URL . '/customers/details',
                    'icon' => 'bricks',
                    'roles' => [1],
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
