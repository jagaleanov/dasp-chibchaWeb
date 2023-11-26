<?php

namespace src\controllers;

use src\modules\menu\MenuController;
use src\services\LayoutService;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function home()
    {
        $this->layoutService = LayoutService::getInstance();
        try {
            $menu = new MenuController();
            $this->layoutService->setModule('navBar',$menu->index());
            $this->layoutService->view('home');
        } catch (\Exception $e) {
            print_r($e);
        }
    }
}
