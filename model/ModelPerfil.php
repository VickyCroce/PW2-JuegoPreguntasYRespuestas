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
        $stmt = $this->database->prepare("SELECT * FROM users WHERE nombre_usuario = :username");
        $stmt->execute(['username' => $username]);

        return $stmt->fetch(Database::FETCH_ASSOC);
    }

    public function findById($id){
        return $this->database->query("SELECT * FROM usuario WHERE id = '$id'");
    }

    public function getAll(){
        return $this->database->query("SELECT * FROM usuario");
    }

    public function findUserByEmailandPassword($usuario){
        return $this->database->query("SELECT * FROM usuario WHERE CorreoElectronico='".$usuario->getCorreoElectronico()."' AND contrasena='".$usuario->getContrasena()."'");
    }
}