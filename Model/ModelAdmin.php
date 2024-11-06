<?php

namespace Model;

class ModelAdmin
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Obtener la cantidad de jugadores
    public function getCantidadJugadores($filtro = 'DAY')
    {
        $sql = "SELECT COUNT(*) as cantidad FROM users WHERE fecha_registro >= DATE_SUB(CURDATE(), INTERVAL 1 $filtro)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        return $data['cantidad'] ?? 0;
    }

    // Obtener la cantidad de partidas jugadas
    public function getCantidadPartidas($filtro = 'DAY')
    {
        $sql = "SELECT COUNT(*) as cantidad FROM Partida WHERE fechaCreacion >= DATE_SUB(CURDATE(), INTERVAL 1 $filtro)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        return $data['cantidad'];
    }


    // Obtener la cantidad total de preguntas en el juego
    public function getCantidadPreguntasJuego()
    {
        $sql = "SELECT COUNT(*) as cantidad FROM Pregunta";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        return $data['cantidad'];
    }

    // Obtener la cantidad de preguntas creadas en el periodo
    public function getCantidadPreguntasCreadas($filtro = 'DAY')
    {
        $sql = "SELECT COUNT(*) as cantidad FROM Pregunta WHERE fechaCreacion >= DATE_SUB(CURDATE(), INTERVAL 1 $filtro)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        return $data['cantidad'];
    }

    // Obtener la cantidad de usuarios nuevos en el periodo
    public function getCantidadUsuariosNuevos($filtro = 'DAY')
    {
        $sql = "SELECT COUNT(*) as cantidad FROM users WHERE fecha_registro >= DATE_SUB(CURDATE(), INTERVAL 1 $filtro)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        return $data['cantidad'];
    }

    // Obtener el porcentaje de preguntas respondidas correctamente por usuario
    /**public function getPorcentajePreguntasCorrectas($filtro = 'DAY')
    {
        $sql = "
            SELECT users_id,
                   (SUM(CASE WHEN respuesta_correcta = 1 THEN 1 ELSE 0 END) / COUNT(*)) * 100 as porcentaje_correcto
            FROM Respuesta
            WHERE fecha_respuesta >= DATE_SUB(CURDATE(), INTERVAL 1 $filtro)
            GROUP BY users_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $porcentajes = [];
        while ($row = $result->fetch_assoc()) {
            $porcentajes[] = $row;
        }
        return $porcentajes;
    } **/

    // Obtener la cantidad de usuarios por país
    public function getUsuariosPorPais()
    {
        $sql = "SELECT pais, COUNT(*) as cantidad FROM users GROUP BY pais";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuariosPorPais = [];
        while ($row = $result->fetch_assoc()) {
            $usuariosPorPais[] = $row;
        }
        return $usuariosPorPais;
    }

    // Obtener la cantidad de usuarios por sexo
    public function getUsuariosPorSexo()
    {
        $sql = "SELECT sexo, COUNT(*) as cantidad FROM users GROUP BY sexo";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuariosPorSexo = [];
        while ($row = $result->fetch_assoc()) {
            $usuariosPorSexo[] = $row;
        }
        return $usuariosPorSexo;
    }

    // Obtener la cantidad de usuarios por grupo de edad
    public function getUsuariosPorEdad()
    {
        $sql = "
            SELECT 
                CASE 
                    WHEN anio_nacimiento < 2006 THEN 'Menores'
                    WHEN anio_nacimiento >= 1959 THEN 'Jubilados'
                    ELSE 'Medio'
                END as grupo_edad,
                COUNT(*) as cantidad
            FROM users
            GROUP BY grupo_edad";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuariosPorGrupoEdad = [];
        while ($row = $result->fetch_assoc()) {
            $usuariosPorGrupoEdad[] = $row;
        }
        return $usuariosPorGrupoEdad;
    }
}
