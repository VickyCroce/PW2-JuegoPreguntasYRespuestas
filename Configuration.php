<?php

use Controller\ControllerHome;
use Controller\ControllerJuego;
use Controller\ControllerLogin;
use Controller\ControllerPartida;
use Controller\ControllerPerfil;
use Controller\ControllerRegistro;
use Controller\ControllerRanking;
use Controller\ControllerEditor;
use Controller\ControllerAdmin;
use Model\ModelHome;
use Model\ModelJuego;
use Model\ModelLogin;
use Model\ModelPartida;
use Model\ModelRegistro;
use Model\ModelRanking;
use Model\ModelEditor;
use Model\ModelAdmin;


include_once "controller/ControllerRegistro.php";
include_once "controller/ControllerLogin.php";
include_once "controller/ControllerPerfil.php";
include_once "controller/ControllerHome.php";
include_once "controller/ControllerJuego.php";
include_once "controller/ControllerPartida.php";
include_once "controller/ControllerRanking.php";
include_once "controller/ControllerEditor.php";
include_once "controller/ControllerAdmin.php";

include_once "model/ModelRegistro.php";
include_once "model/ModelLogin.php";
include_once "model/ModelPerfil.php";
include_once "model/ModelHome.php";
include_once "model/ModelJuego.php";
include_once "model/ModelPartida.php";
include_once "model/ModelRanking.php";
include_once "model/ModelEditor.php";
include_once "model/ModelAdmin.php";

include_once "helper/Database.php";
include_once "helper/Presenter.php";
include_once "helper/MustachePresenter.php";
include_once "helper/Router.php";
include_once("vendor\mustache\mustache\src\Mustache\Autoloader.php");
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

    private static function getPresenter2()
    {

        return new MustachePresenter("view/template");
    }
    // CONTROLADOR REGISTRO
    public static function getControllerRegistro()
    {
        return new ControllerRegistro(self::getModelRegistro(),self::getPresenter2());
    }

    //CONTROLADOR LOGIN

    public static function getControllerLogin(){
        return new ControllerLogin(self::getModelLogin(),self::getPresenter2());
    }

    //CONTROLADOR PERFIL

    public static function getControllerPerfil() {
        return new ControllerPerfil(self::getModelPerfil(), self::getPresenter2());
    }

    //CONTROLADOR HOME

    public static function getControllerHome() {
        return new ControllerHome(self::getModelHome(), self::getPresenter2());
    }

    //CONTROLADOR JUEGO

    public static function getControllerJuego() {
        return new ControllerJuego(self::getModelJuego(), self::getPresenter2());
    }

    //CONTROLADOR PARTIDA
    public static function getControllerPartida() {
        return new ControllerPartida(self::getModelPartida(), self::getPresenter2());
    }

    //CONTROLADOR RANKING
    public static function getControllerRanking() {
        return new ControllerRanking(self::getModelRanking(), self::getPresenter2());
    }

    //CONTROLADOR EDITOR

    public static function getControllerEditor() {
        return new ControllerEditor(self::getModelEditor(), self::getPresenter2());
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

    // MODELO HOME

    public static function getModelHome()
    {
        return new ModelHome(self::getDatabase());
    }

    //MODELO JUEGO

    public static function getModelJuego()
    {
        return new ModelJuego(self::getDatabase());
    }

    //MODELO PARTIDA

    public static function getModelPartida()
    {
        return new ModelPartida(self::getDatabase());
    }

    //MODELO RANKING

    public static function getModelRanking()
    {
        return new ModelRanking(self::getDatabase());
    }

    //MODELO EDITOS
    public static function getModelEditor()
    {
        return new ModelEditor(self::getDatabase());
    }

    public static function getRouter()
    {
        return new Router(self::class,"getControllerLogin", "get");
    }

    private static function getPresenter()
    {
        return new Presenter();
    }

    public static function getControllerAdmin() {
        return new ControllerAdmin(self::getModelAdmin(), self::getPresenter2());
    }
    public static function getModelAdmin() {
        return new ModelAdmin(self::getDatabase());
    }


}