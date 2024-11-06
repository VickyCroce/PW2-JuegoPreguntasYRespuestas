<?php
require_once 'Configuration.php';
require_once 'helper/Router.php';

session_start();

$controller = isset($_GET["controller"]) ? $_GET["controller"] : "home";
$action = isset($_GET["action"]) ? $_GET["action"] : "get";

$router = Configuration::getRouter();
$router->route($controller, $action);
