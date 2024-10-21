<?php

namespace model;
class ModelLogin
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;


    }

    // Método para buscar un usuario por su nombre de usuario
    public function findUserByUsername($username)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function validarUsuario($email, $password)
    {
        $sql = "SELECT * FROM users WHERE email = ? AND verificado = 1";
        $stmt = $this->db->prepare($sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);

            if ($user) {
                // Comparar directamente la contraseña en texto plano
                if ($password === $user['password']) {
                    return $user; // Usuario verificado y contraseña correcta
                } else {
                    return ['error' => 'Contraseña incorrecta.'];
                }
            } else {
                return ['error' => 'El usuario no existe o no ha sido verificado.'];
            }
        }
        return ['error' => 'Error al conectar con la base de datos.'];
    }


}