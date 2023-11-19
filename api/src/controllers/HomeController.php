<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador

// Controlador para gestionar usuarios
class HomeController extends Controller
{

    // Constructor que inyecta el repositorio de usuarios
    public function __construct()
    {
        parent::__construct();
    }

    // Método para obtener todos los usuarios
    public function home()
    {
        try {
            $this->layoutService->view('home');
        } catch (\Exception $e) {
            print_r($e);
        }
    }
}
