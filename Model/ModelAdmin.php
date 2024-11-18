<?php

namespace Model;

class ModelAdmin
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // 1. Obtener la cantidad de jugadores
    public function getCantidadJugadores() {
        $query = "SELECT COUNT(*) as cantidad FROM users";
        $result = $this->db->query($query);

        if ($result && $result instanceof mysqli_result) {
            $row = $result->fetch_assoc();
            return $row['cantidad'];
        } else {
            return false;
        }
    }

    // 2. Obtener la cantidad de partidas
    public function getCantidadPartidas()
    {
        $query = "SELECT COUNT(*) as cantidad FROM Partida";
        $result = $this->db->query($query);

        if ($result && $result instanceof mysqli_result) {
            $row = $result->fetch_assoc();
            return $row['cantidad'];
        } else {
            return false;
        }
    }

    // 3. Obtener la cantidad de preguntas en juego (válidas)
    public function getCantidadPreguntasJuego()
    {
        $query = "SELECT COUNT(*) as cantidad FROM Pregunta WHERE esValido = 1";
        $result = $this->db->query($query);

        if ($result && $result instanceof mysqli_result) {
            $row = $result->fetch_assoc();
            return $row['cantidad'];
        } else {
            return false;
        }
    }

    // 4. Obtener la cantidad de preguntas creadas
    public function getCantidadPreguntasCreadas()
    {
        $query = "SELECT COUNT(*) as cantidad FROM Pregunta";
        $result = $this->db->query($query);

        if ($result && $result instanceof mysqli_result) {
            $row = $result->fetch_assoc();
            return $row['cantidad'];
        } else {
            return false;
        }
    }

    // 5. Obtener la cantidad de usuarios nuevos dentro de un rango de fechas
    public function getCantidadUsuariosNuevos($fechaInicio, $fechaFin)
    {
        $query = "SELECT COUNT(*) as cantidad FROM users WHERE fecha_registro BETWEEN ? AND ?";
        $stmt = $this->db->prepare($query);

        if ($stmt) {
            $stmt->bind_param('ss', $fechaInicio, $fechaFin);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result instanceof mysqli_result) {
                $row = $result->fetch_assoc();
                return $row['cantidad'];
            }
        }

        return false;
    }

    // 6. Obtener usuarios agrupados por país
    public function getUsuariosPorPais()
    {
        $query = "SELECT pais, COUNT(*) AS cantidad FROM users GROUP BY pais";
        $result = $this->db->query($query);

        if ($result && $result instanceof mysqli_result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return false;
        }
    }

    // 7. Obtener usuarios agrupados por sexo
    public function getUsuariosPorSexo()
    {
        $query = "SELECT sexo, COUNT(*) AS cantidad FROM users GROUP BY sexo";
        $result = $this->db->query($query);

        if ($result && $result instanceof mysqli_result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return false;
        }
    }

    // 8. Obtener usuarios agrupados por edad
    public function getUsuariosPorEdad()
    {
        $query = "SELECT FLOOR(DATEDIFF(CURDATE(), anio_nacimiento) / 365) AS edad, COUNT(*) AS cantidad 
                  FROM users GROUP BY edad";
        $result = $this->db->query($query);

        if ($result && $result instanceof mysqli_result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return false;
        }
    }
}
