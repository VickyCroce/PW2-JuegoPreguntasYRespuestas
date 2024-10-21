<?php

namespace model;
class ModelRegistro
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

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

    public function saveUser($data)
    {
        // Asegúrate de que estamos incluyendo todos los parámetros y manejando el campo `foto_perfil` correctamente
        $sql = "INSERT INTO users (nombre_completo, anio_nacimiento, sexo, pais, ciudad, email, password, nombre_usuario, foto_perfil, verificado) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";  // Aquí agregamos 0 por defecto para 'verificado'

        // Verificamos si 'foto_perfil' es NULL o tiene un valor
        $fotoPerfil = isset($data['foto_perfil']) ? $data['foto_perfil'] : NULL;  // Esto manejará el caso en que no haya foto

        // Preparamos la consulta
        $stmt = $this->db->prepare($sql);

        if ($stmt) {
            // Establecemos los parámetros correctamente
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

}
