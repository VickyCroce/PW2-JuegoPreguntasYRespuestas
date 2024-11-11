<?php

namespace Model;
class ModelRegistro
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    //metodo para buscar usuario por mail
    public function findUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            return $user;
        }
        return false;
    }

    // metodo guardar usuario
    public function saveUser($data)
    {

        $sql = "INSERT INTO users (nombre_completo, anio_nacimiento, sexo, pais, ciudad, email, password, nombre_usuario, foto_perfil, verificado,rol) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0,'jugador')";


        $fotoPerfil = isset($data['foto_perfil']) ? $data['foto_perfil'] : NULL;


        $stmt = $this->db->prepare($sql);

        if ($stmt) {

            mysqli_stmt_bind_param($stmt, "sssssssss",
                $data['nombre_completo'],
                $data['anio_nacimiento'],
                $data['sexo'],
                $data['pais'],
                $data['ciudad'],
                $data['email'],
                $data['password'],
                $data['nombre_usuario'],
                $fotoPerfil
            );


            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return true;
        }
        return false;
    }

    //metodo para verificar usuario
    public function verifyUser($email)
    {
        $sql = "UPDATE users SET verificado = 1 WHERE email = ?";
        $stmt = $this->db->prepare($sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return true;
        }

        return false;
    }

    public function getLastInsertedId()
    {
        $this->db->getLastInsertId();
    }

}
