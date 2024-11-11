<?php

namespace Controller;
class ControllerHome
{
    private $model;
    private $presenter;

    public function __construct($model, $presenter)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function get()
    {
        $nombre_usuario = isset($_SESSION['usuario']['nombre_usuario']) ? $_SESSION['usuario']['nombre_usuario'] : 'Invitado';
        $usuario_id = $_SESSION['usuario']['id'];

        $maxPuntaje = $this->model->getMaxPuntajeUsuario($usuario_id);

        $this->presenter->render('view/home.mustache', [
            'nombre' => $nombre_usuario,
            'maxPuntaje' => $maxPuntaje,
            'usuario_id' => $usuario_id

        ]);
    }


}
