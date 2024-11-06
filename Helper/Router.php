<?php
class Router
{
    private $defaultController;
    private $defaultMethod;
    private $configuration;

    public function __construct($configuration, $defaultController, $defaultMethod) {
        $this->defaultController = $defaultController;
        $this->defaultMethod = $defaultMethod;
        $this->configuration = $configuration;
    }

    public function route($controllerName, $methodName, $param = null) {
        if ($controllerName === "admin") {
            if (isset($_SESSION['usuario']) && $_SESSION['usuario']['email'] === 'administrador@admin.com') {
                $controller = $this->getControllerFrom('admin');
            } else {
                $controller = $this->getControllerFrom('home');
                $methodName = 'get';
            }
        } else {
            $controller = $this->getControllerFrom($controllerName);
        }

        // Ejecutar el método en el controlador
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            if (isset($_FILES['foto_perfil'])) {
                $data['foto_perfil'] = $_FILES['foto_perfil'];
            }
            $this->executeMethodFromController($controller, $methodName, $data);
        } else {
            $this->executeMethodFromController($controller, $methodName, $param);
        }
    }


    private function getControllerFrom($module) {
        $controllerName = 'get' . ucfirst($module);

        if (method_exists($this->configuration, $controllerName)) {
            return call_user_func(array($this->configuration, $controllerName));
        } else {
            return call_user_func(array($this->configuration, $this->defaultController));
        }
    }

    private function executeMethodFromController($controller, $method, $param = null) {
        $validMethod = method_exists($controller, $method) ? $method : $this->defaultMethod;
        if ($param) {
            call_user_func(array($controller, $validMethod), $param);
        } else {
            call_user_func(array($controller, $validMethod));
        }
    }
}
