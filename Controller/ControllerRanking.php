<?php

namespace Controller;

class ControllerRanking
{
    private $model;

    private $presenter;

    public function __construct($model, $presenter){
        $this->model = $model;
        $this->presenter = $presenter;
    }

    private function checkJugador() {
        if (!(isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] == 'jugador')) {
            header("Location: /PW2-JuegoPreguntasYRespuestas/ControllerLogin/cerrarSesion");
            exit();
        }
    }
    public function mostrarRanking()
    {   $this->checkJugador();
        $jugadores = $this->model->getRankingJugadores();

        $this->presenter->render('view/ranking.mustache', [
            'jugadores' => $jugadores
        ]);
    }



}