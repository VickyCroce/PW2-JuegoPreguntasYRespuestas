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

    private function checkJugador() {
        if (!(isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] == 'jugador')) {
            header("Location: /PW2-JuegoPreguntasYRespuestas/ControllerLogin/cerrarSesion");
            exit();
        }
    }
    public function mostrarPartidas()
    {   $this->checkJugador();
        $usuario_id = $_GET['id'];;
        $partidas = $this->model->obtenerPartidasPorUsuario($usuario_id);
        $this->presenter->render('view/partidas.mustache',
            ['partidas' => $partidas]);
    }


}