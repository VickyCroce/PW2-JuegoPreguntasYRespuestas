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
    public function findById($id)
    {
        $sql = "
    SELECT u.nombre_completo AS nombre, u.foto_perfil, u.ciudad, u.pais, COALESCE(SUM(p.puntaje), 0) AS puntaje_total,u.nombre_usuario AS nombre_usuario
    FROM users u
    LEFT JOIN Partida p ON u.id = p.usuario_id
    WHERE u.id = '$id'
    GROUP BY u.id";

        $result = $this->database->query($sql);

        if ($result && is_array($result) && count($result) > 0) {
            return $result[0]; // Retorna el primer resultado con los datos del usuario
        }

        return null;
    }


    // Función para obtener toda la tabla
    public function getAll(){
        return $this->database->query("SELECT * FROM usuario");
    }

    // Función para buscar por correo y contraseña
    public function findUserByEmailandPassword($usuario){
        return $this->database->query("SELECT * FROM usuario WHERE CorreoElectronico='".$usuario->getCorreoElectronico()."' AND contrasena='".$usuario->getContrasena()."'");
    }


    public function getPartidasPorUsuario($usuario_id)
    {
        $sql = "
        SELECT id, puntaje, fechaCreacion
        FROM Partida
        WHERE usuario_id = ?
        ORDER BY id DESC
        LIMIT 5";

        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $partidas = [];

        while ($row = $result->fetch_assoc()) {
            $partidas[] = $row;
        }

        $stmt->close();
        return $partidas;
    }


}