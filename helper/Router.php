<?php

namespace helper;
class Router
{
    private $defaultController;
    private $defaultMethod;
    private $configuration;

    public function __construct($configuration, $defaultController, $defaultMethod)
    {
        $this->defaultController = $defaultController;
        $this->defaultMethod = $defaultMethod;
        $this->configuration = $configuration;
    }

    public function route($controllerName, $methodName, $param = null)
    {
        $controller = $this->getControllerFrom($controllerName);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            if (isset($_FILES['foto_perfil'])) {
                $data['foto_perfil'] = $_FILES['foto_perfil'];
            }

            // Ejecuta el método del controlador, pasando los datos del formulario
            $this->executeMethodFromController($controller, $methodName, $data);
        } else {
            // Maneja las solicitudes GET
            $this->executeMethodFromController($controller, $methodName, $param);
        }
    }


    private function getControllerFrom($module)
    {
        $controllerName = 'get' . ucfirst($module); // Por ejemplo, "getControllerLogin" o "getControllerRegistro"

        // Verifica si el método existe en la clase Configuration
        if (method_exists($this->configuration, $controllerName)) {
            return call_user_func(array($this->configuration, $controllerName)); // Llama al método estático
        } else {
            // Si no existe, usa el controlador por defecto
            return call_user_func(array($this->configuration, $this->defaultController));
        }
    }


    private function executeMethodFromController($controller, $method, $param = null)
    {
        $validMethod = method_exists($controller, $method) ? $method : $this->defaultMethod;
        if ($param) {
            call_user_func(array($controller, $validMethod), $param);
        } else {
            call_user_func(array($controller, $validMethod));
        }
    }


}