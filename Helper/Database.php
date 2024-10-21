<?php

namespace Helper;

use mysqli;

class Database
{
    private $conexion;

    public function __construct(
        $host = "localhost 3306",
        $user = "root",
        $password = "",
        $database = "preguntados_bd"
    ){
        $this->conexion = new mysqli($host, $user, $password, $database);
    }

    public function query($sql){
        $resultado = mysqli_query($this->conexion, $sql);
        if(mysqli_num_rows($resultado) == 1)
            return mysqli_fetch_assoc($resultado);
        return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    }

    public function execute($sql){
        mysqli_query($this->conexion, $sql);
    }

    public function __destruct(){
        mysqli_close($this->conexion);
    }
}