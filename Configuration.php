<?php

use Controller\controladorUsuario;
use Helper\Database;
use Model\modeloUsuario;

include_once ("Config/Config.ini");
include_once ("Helper/Database.php");
include_once ("Helper/MustachePresenter.php");
include_once ("Controller/controladorUsuario.php");
include_once ("Model/modeloUsuario.php");


class Configuration
{
    public static function getConfig(){
        $config = parse_ini_file("Config/config.ini");
        return $config;
    }

    public static function getDatabase(){
        $config = self::getConfig();
        $host = $config["HOST"];
        $user = $config["USER"];
        $password = $config["PASSWORD"];
        $database = $config["DATABASE"];

        return new Database($host, $user, $password, $database);
    }

    public static function getPresenter(){
        return new MustachePresenter();
    }

    public static function getModeloUsuario(){
        return new ModeloUsuario(self::getDatabase());
    }

    public static function getControladorUsuario(){
        return new ControladorUsuario(self::getModeloUsuario(), self::getPresenter());
    }

    public static function getRouter(){
        return new Router("getLobbyUsuarioController", "get");
    }
}