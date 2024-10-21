<?php

SESSION_START();
include_once ("Configuration.php");

$router = Configuration::getRouter();

if ($_GET['action'] === 'profile' && isset($_GET['username'])) {
    $controller = Configuration::getControladorUsuario();
    $controller->showProfile($_GET['username']);
} else {
    echo "Página no encontrada.";
}