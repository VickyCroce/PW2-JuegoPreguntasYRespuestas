<?php

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
        echo $this->presenter->show('view/login.mustache');
    }

    // Funcion para iniciar sesion
    public function iniciarSesion()
    {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $resultado = $this->modelo->validarUsuario($email, $password);

            if (isset($resultado['error'])) {

                $this->presenter->show("view/login.mustache", ['error' => $resultado['error']]);
            } else {
                $_SESSION['usuario'] = $resultado;
                header('Location: /ControllerHome/get');
                // header('Location: /ControllerPerfil/showProfile?username=' . urlencode($resultado['nombre_usuario']));
                exit();
            }
        } else {
            $this->presenter->show("view/login.mustache", ['error' => 'Por favor, ingresa tus credenciales.']);
        }

    }

}