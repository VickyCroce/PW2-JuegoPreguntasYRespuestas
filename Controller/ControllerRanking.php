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
        // Obtén los jugadores con los puntajes más altos desde el modelo
        $jugadores = $this->model->getRankingJugadores();

        // Renderiza la vista del ranking con los datos de los jugadores
        $this->presenter->render('view/ranking.mustache', [
            'jugadores' => $jugadores
        ]);
    }



}