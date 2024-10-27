<?php

namespace Controller;
use MustachePresenter;

class ControllerPerfil
{
    private $model;
    private $Mustachepresenter;

    public function __construct($model, MustachePresenter $Mustachepresenter)
    {
        $this->model = $model;
        $this->Mustachepresenter = $Mustachepresenter;
    }

    public function get()
    {
        $this->Mustachepresenter->render('view/perfil.mustache');
    }


    // Funcion para mostrar el perfil del usuario
    public function showProfile()
    {
        if (isset($_GET['username'])) {
            $username = $_GET['username'];
            $user = $this->model->getUserProfile($username);

            if ($user) {
                $this->renderProfileView($user);
            } else {
                echo "Usuario no encontrado.";
            }
        } else {
            echo "Usuario no especificado.";
        }
    }


    // Función para renderizar la vista del perfil
    private function renderProfileView($user)
    {
        echo $this->Mustachepresenter->render('view/perfil.mustache', $user);
    }


}