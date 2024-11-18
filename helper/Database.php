<?php

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

        if (!$result) {

            echo "Error en la consulta: " . mysqli_error($this->conexion) . "<br>";
            return null;
        }

        if (mysqli_num_rows($result) == 1) {
            return [mysqli_fetch_assoc($result)];
        }

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    public function query2($sql){
        $result = mysqli_query($this->conexion, $sql);
        if(mysqli_num_rows($result) == 1)
            return mysqli_fetch_assoc($result);
        return mysqli_fetch_all($result,MYSQLI_ASSOC);
    }
// Si estás usando una clase Database personalizada, verifica si tiene un método getError()

    public function getError() {
        return $this->mysqli->error;
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

    public function getLastInsertId()
    {
        return $this->conexion->insert_id;
    }

}