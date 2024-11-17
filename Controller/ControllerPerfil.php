<?php
namespace Controller;
use MustachePresenter;

class ControllerPerfil
{
    private $model;
    private $presenter;

    public function __construct($model,$presenter)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function get()
    {
        $this->presenter->render('view/perfil.mustache');
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
        echo $this->presenter->render('view/perfil.mustache', $user);
    }

    public function showPerfilAjeno()
    {
        if (isset($_GET['id'])) {
            $usuario_id = $_GET['id'];
            $user = $this->model->findById($usuario_id);
            $partidas = $this->model->getPartidasPorUsuario($usuario_id);

            if ($user) {
                $user['partidas'] = $partidas;
                 $this->presenter->render('view/perfilAjeno.mustache', $user);
            } else {
                echo "Usuario no encontrado.";
            }
        } else {
            echo "Usuario no especificado.";
        }
    }
}