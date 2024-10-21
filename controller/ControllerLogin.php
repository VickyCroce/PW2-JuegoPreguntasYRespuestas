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

    // Método para mostrar el formulario de login
    public function get()
    {
        echo $this->presenter->show('view/login.mustache');
    }

    public function iniciarSesion()
    {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $resultado = $this->modelo->validarUsuario($email, $password);
            var_dump($resultado);


            if (isset($resultado['error'])) {
                // Si hay un error, mostrarlo al usuario
                $this->presenter->show("view/login.mustache", ['error' => $resultado['error']]);
            } else {
                // Si no hay errores, el usuario es válido, iniciar sesión
                $_SESSION['usuario'] = $resultado; // Guardar la información del usuario en la sesión
                $this->presenter->show("view/perfil.mustache"); // Redirigir a la página de inicio o dashboard
            }
        } else {
            $this->presenter->show("view/login.mustache", ['error' => 'Por favor, ingresa tus credenciales.']);
        }
    }

}
