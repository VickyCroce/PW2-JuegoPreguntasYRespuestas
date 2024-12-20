<?php

namespace Controller;
class ControllerLogin
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
        echo $this->presenter->render('view/login.mustache');
    }

    // Funcion para iniciar sesion
    public function iniciarSesion()
    {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $resultado = $this->modelo->validarUsuario($email, $password);

            if (isset($resultado['error'])) {

                $this->presenter->render("view/login.mustache", ['error' => $resultado['error']]);
            } else {
                $_SESSION['usuario_id'] = $resultado['id'];
                $_SESSION['usuario'] = $resultado;


                if($resultado["rol"] == "Editor"){
                    header("Location:/PW2-JuegoPreguntasYRespuestas/ControllerEditor/get");
                    return;
                }

                if($resultado["rol"] == "administrador"){
                    header("Location:/PW2-JuegoPreguntasYRespuestas/ControllerAdmin/get");
                    return;
                }
                header('Location: /PW2-JuegoPreguntasYRespuestas/ControllerHome/get');
                exit();
            }
        }
    }

    public function cerrarSesion()
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /PW2-JuegoPreguntasYRespuestas/ControllerLogin/get');
        exit();
    }

}
