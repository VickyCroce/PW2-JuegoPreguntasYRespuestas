<?php

class ControllerPerfil
{
    private $modelo;
    private $presenter;

    public function __construct($modelo, $presenter)
    {
        $this->modelo = $modelo;
        $this->presenter = $presenter;
    }


    public function get()
    {
        echo $this->presenter->show('view/perfil.mustache');
    }


    public function profile($userId) {
        $user = $this->modelo->getUserById($userId);
        $games = $this->modelo->getUserGames($userId);
        $ranking = $this->modelo->getRankingPosition($userId);

        include 'views/profile.php'; // Aquí mostrarías los datos del perfil en una vista
    }

}