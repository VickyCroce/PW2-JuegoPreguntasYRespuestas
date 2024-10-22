<?php

class ModelPerfil
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    // Función para obtener el perfil del usuario por nombre de usuario
    public function getUserProfile($username)
    {
        $sql = "SELECT * FROM users WHERE nombre_usuario = '$username'";
        $result = $this->database->query($sql);

        if ($result && is_array($result) && count($result) > 0) {
            return isset($result[0]) ? $result[0] : $result;
        }

        return null;
    }



    // Función para buscar por id
    public function findById($id){
        return $this->database->query("SELECT * FROM usuario WHERE id = '$id'");
    }

    // Función para obtener toda la tabla
    public function getAll(){
        return $this->database->query("SELECT * FROM usuario");
    }

    // Función para buscar por correo y contraseña
    public function findUserByEmailandPassword($usuario){
        return $this->database->query("SELECT * FROM usuario WHERE CorreoElectronico='".$usuario->getCorreoElectronico()."' AND contrasena='".$usuario->getContrasena()."'");
    }
}