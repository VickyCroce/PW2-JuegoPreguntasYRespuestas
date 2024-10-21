<?php

namespace helper;
class Database
{
    private $conexion;

    public function __construct($host, $user, $pass, $db)
    {
        $this->conexion = mysqli_connect($host, $user, $pass, $db);

        if (!$this->conexion) {
            die("Error en la conexión: " . mysqli_connect_error());
        }
    }

    public function query($sql)
    {
        $result = mysqli_query($this->conexion, $sql);

        if (mysqli_num_rows($result) == 1) {
            return mysqli_fetch_assoc($result);
        }
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function execute($sql)
    {
        mysqli_query($this->conexion, $sql);
    }


    public function prepare($sql)
    {
        return mysqli_prepare($this->conexion, $sql);
    }

    public function __destruct()
    {
        mysqli_close($this->conexion);
    }

    public function getConexion()
    {
        return $this->conexion;
    }
}
