<?php

namespace Controller;

class ControllerAdmin
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

        // Comprobar si el usuario es el administrador por el correo electrónico
        $usuario = $_SESSION['usuario'];
        if ($usuario['email'] !== 'administrador@admin.com') {
            header("Location: /home"); // Redirigir al home si no es el admin
            exit();
        }


        $data = [
            'cantidadJugadores' => $this->model->getCantidadJugadores(),
            'cantidadPartidas' => $this->model->getCantidadPartidas(),
            'cantidadPreguntasJuego' => $this->model->getCantidadPreguntasJuego(),
            'cantidadPreguntasCreadas' => $this->model->getCantidadPreguntasCreadas(),
            'cantidadUsuariosNuevos' => $this->model->getCantidadUsuariosNuevos(),
           /** 'porcentajeCorrectas' => $this->model->getPorcentajePreguntasCorrectas(), **/
            'usuariosPorPais' => $this->model->getUsuariosPorPais(),
            'usuariosPorSexo' => $this->model->getUsuariosPorSexo(),
            'usuariosPorEdad' => $this->model->getUsuariosPorEdad(),
        ];


        $this->presenter->render('view/admin.mustache', $data);
    }
}
