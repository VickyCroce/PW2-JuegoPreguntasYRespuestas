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
        
        if (!isset($_SESSION['usuario'])) {
            header("Location: /login");
            exit();
        }

        // Comprobar si el usuario es el administrador
        $usuario = $_SESSION['usuario'];
        if ($usuario['email'] === 'administrador@admin.com') {
            header("Location: /PW2-JuegoPreguntasYRespuestas/ControllerAdmin");
            exit();
        }

        // Si no es administrador, cargar la vista del usuario común
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

