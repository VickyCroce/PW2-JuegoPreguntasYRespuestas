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
/**
    // 2. Obtener la cantidad de partidas
    public function getCantidadPartidas()
    {
        $query = "SELECT COUNT(*) FROM Partida";
        return $this->db->query($query)->fetchColumn();
    }

    // 3. Obtener la cantidad de preguntas en juego (válidas)
    public function getCantidadPreguntasJuego()
    {
        $query = "SELECT COUNT(*) FROM Pregunta WHERE esValido = 1";
        return $this->db->query($query)->fetchColumn();
    }

    // 4. Obtener la cantidad de preguntas creadas
    public function getCantidadPreguntasCreadas()
    {
        $query = "SELECT COUNT(*) FROM Pregunta";
        return $this->db->query($query)->fetchColumn();
    }

    // 5. Obtener la cantidad de usuarios nuevos dentro de un rango de fechas
    public function getCantidadUsuariosNuevos($fechaInicio, $fechaFin)
    {
        $query = "SELECT COUNT(*) FROM users WHERE fecha_registro BETWEEN :fechaInicio AND :fechaFin";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['fechaInicio' => $fechaInicio, 'fechaFin' => $fechaFin]);
        return $stmt->fetchColumn();
    }

    // 6. Obtener usuarios agrupados por país
    public function getUsuariosPorPais()
    {
        $query = "SELECT pais, COUNT(*) AS cantidad FROM users GROUP BY pais";
        return $this->db->query($query)->fetchAll();
    }

    // 7. Obtener usuarios agrupados por sexo
    public function getUsuariosPorSexo()
    {
        $query = "SELECT sexo, COUNT(*) AS cantidad FROM users GROUP BY sexo";
        return $this->db->query($query)->fetchAll();
    }

    // 8. Obtener usuarios agrupados por edad
    public function getUsuariosPorEdad()
    {
        $query = "SELECT FLOOR(DATEDIFF(CURDATE(), anio_nacimiento) / 365) AS edad, COUNT(*) AS cantidad 
                  FROM users GROUP BY edad";
        return $this->db->query($query)->fetchAll();
    }**/
}
