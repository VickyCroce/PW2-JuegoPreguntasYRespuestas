<?php
require_once 'Configuration.php';
require_once 'helper/Router.php';

session_start();

$controller = isset($_GET["controller"]) ? $_GET["controller"] : "";
$action = isset($_GET["action"]) ? $_GET["action"] : "";

if (!isset($_SESSION['usuario']) && !in_array($_GET['controller'], ['ControllerLogin', 'ControllerIndex', 'ControllerRegistro'])) {
    header("Location: /PW2-JuegoPreguntasYRespuestas/ControllerLogin/get");
    exit();
}

$router = Configuration::getRouter();
$router->route($controller, $action);
