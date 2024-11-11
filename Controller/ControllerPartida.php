<?php

namespace Controller;
class ControllerPartida
{
    private $model;

    private $presenter;

    public function __construct($model, $presenter)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function mostrarPartidas()
    {
        $usuario_id = $_GET['id'];;
        $partidas = $this->model->obtenerPartidasPorUsuario($usuario_id);
        $this->presenter->render('view/partidas.mustache',
            ['partidas' => $partidas]);
    }


}