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


if (isset($_SESSION["usuario"])) {
    $userRole = $_SESSION["usuario"]["rol"];

    if ($userRole != "Editor" && in_array($controller, ["lobbyeditor", "questionmanagement"])) {
        header("Location: /PW2-JuegoPreguntasYRespuestas/ControllerIndex/get");
        exit();
    }

    if ($userRole != "Administrador" && $controller == "ControllerAdmin") {
        header("Location: /PW2-JuegoPreguntasYRespuestas/ControllerHome/get");
        exit();
    }
}


$router = Configuration::getRouter();
$router->route($controller, $action);
