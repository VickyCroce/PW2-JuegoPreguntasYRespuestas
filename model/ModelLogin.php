<?php

class ModelLogin
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;


    }

    // metodo para buscar usuario por nombre de usuario
    public function findUserByUsername($username)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // metodo para validar usuario
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
                if ($password === $user['password']) {
                    return $user;
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