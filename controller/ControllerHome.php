<?php

class ControllerHome
{
    private $model;
    private $presenter;

    public function __construct($model, $presenter){
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function get(){
        // Verifica si hay un usuario en la sesión
        $nombre_usuario = isset($_SESSION['usuario']['nombre_usuario']) ? $_SESSION['usuario']['nombre_usuario'] : 'Invitado';

        // Pasa el nombre a la vista
        $this->presenter->render('view/home.mustache', ['nombre' => $nombre_usuario]);
    }
}
