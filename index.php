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

// Verificamos si el usuario es editor y si intenta acceder a las vistas específicas
if (isset($_SESSION["usuario"])) {
    $userRole = $_SESSION["usuario"]["rol"]; // Obtenemos el rol del usuario

    // Verificamos si el usuario no es Editor y está intentando acceder a las vistas del Editor
    if ($userRole != "Editor" && in_array($controller, ["lobbyeditor", "questionmanagement"])) {
        // Redirigimos al usuario a una página no permitida o una vista general
        header("Location: /PW2-JuegoPreguntasYRespuestas/ControllerIndex/get");
        exit();
    }
}


$router = Configuration::getRouter();
$router->route($controller, $action);
