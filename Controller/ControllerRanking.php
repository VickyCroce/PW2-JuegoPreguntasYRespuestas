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


    public function mostrarRanking()
    {
        $jugadores = $this->model->getRankingJugadores();


        $this->presenter->render('view/ranking.mustache', [
            'jugadores' => $jugadores
        ]);
    }



}