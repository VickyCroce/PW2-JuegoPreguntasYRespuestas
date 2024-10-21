<?php
include_once "controller/ControllerRegistro.php";
include_once "controller/ControllerLogin.php";
include_once "controller/ControllerPerfil.php";


include_once "model/ModelRegistro.php";
include_once "model/ModelLogin.php";
include_once "model/ModelPerfil.php";

include_once "helper/Database.php";
include_once "helper/Presenter.php";
include_once "helper/Router.php";

class Configuration
{
    public function __construct()
    {
    }

    //BASE DE DATOS
    public static function getConfig()
    {
        $config = parse_ini_file("config/config.ini");
        return $config;
    }

    public static function getDatabase()
    {
        $config = self::getConfig();
        $server = $config["HOST"];
        $user = $config["USUARIO"];
        $password = $config["CONTRASENIA"];
        $database = $config["BASE_DATOS"];

        return new Database($server, $user, $password, $database);
    }

    // CONTROLADOR REGISTRO
    public static function getControllerRegistro()
    {
        return new ControllerRegistro(self::getModelRegistro(),self::getPresenter());
    }

    //CONTROLADOR LOGIN

    public static function getControllerLogin(){
        return new ControllerLogin(self::getModelLogin(),self::getPresenter());
    }

    //CONTROLADOR PERFIL

    public static function getControllerPerfil() {
        return new ControllerPerfil(self::getModelPerfil(), self::getPresenter());
    }




    // MODELO REGISTRO
    public static function getModelRegistro()
    {
        return new ModelRegistro(self::getDatabase());
    }


    // MODELO LOGIN
    public static function getModelLogin()
    {
        return new ModelLogin(self::getDatabase());
    }


    // MODELO PERFIL
    public static function getModelPerfil()
    {
        return new ModelPerfil(self::getDatabase());
    }


    public static function getRouter()
    {
        return new Router(self::class,"getControllerLogin", "get");
    }

    private static function getPresenter()
    {
        return new Presenter();
    }
}