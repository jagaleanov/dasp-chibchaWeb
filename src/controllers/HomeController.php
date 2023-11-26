<?php

namespace src\controllers;

use src\modules\menu\MenuController;
class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function home()
    {
        try {
            $menu = new MenuController();
            $this->layoutService->setModule('navBar',$menu->index());
            $this->layoutService->view('home');
        } catch (\Exception $e) {
            print_r($e);
        }
    }
}
