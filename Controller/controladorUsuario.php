<?php

namespace Controller;

class controladorUsuario
{
    private $model;
    private $presenter;

    public function __construct($model, $presenter)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function get(){
        $this->presenter->render("View/perfil.mustache");
    }


    // Función para mostrar el perfil del usuario
    public function showProfile($username)
    {
        // Obtener los datos del usuario del modelo
        $user = $this->model->getUserProfile($username);

        if ($user) {
            // Si el usuario existe, cargar la vista con los datos del perfil
            $this->renderProfileView($user);
        } else {
            echo "Usuario no encontrado.";
        }
    }

    // Función para renderizar la vista del perfil
    private function renderProfileView($user)
    {
        // Cargar Mustache
        $mustache = new Mustache_Engine();
        $template = file_get_contents('View/perfil.mustache');

        // Renderizar la vista con los datos del usuario
        echo $mustache->render($template, $user);
    }
}