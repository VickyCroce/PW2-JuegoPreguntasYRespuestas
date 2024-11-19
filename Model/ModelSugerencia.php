<?php

namespace Model;

class ModelSugerencia
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Método para crear una sugerencia de pregunta
    public function crearSugerenciaPregunta($usuario_id, $pregunta_sugerida, $categoria)
    {
        $sql = "INSERT INTO sugerencias_preguntas (usuario_id, pregunta_sugerida, categoria, estado, fecha) VALUES (?, ?, ?, 'pendiente', NOW())";
        $stmt = $this->db->getConexion()->prepare($sql);
        $stmt->bind_param("iss", $usuario_id, $pregunta_sugerida, $categoria);
        $stmt->execute();

        // Retornar el ID de la nueva sugerencia de pregunta
        $sugerenciaPreguntaId = $this->db->getConexion()->insert_id;
        $stmt->close();

        return $sugerenciaPreguntaId;
    }

    // Método para crear una sugerencia de respuesta
    public function crearSugerenciaRespuesta($sugerencia_pregunta_id, $usuario_id, $respuesta_sugerida, $es_correcta)
    {
        $sql = "INSERT INTO sugerencias_respuestas (sugerencia_pregunta_id, usuario_id, respuesta_sugerida, es_correcta) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->getConexion()->prepare($sql);
        $stmt->bind_param("iisi", $sugerencia_pregunta_id, $usuario_id, $respuesta_sugerida, $es_correcta);
        $stmt->execute();
        $stmt->close();
    }

    // Método para buscar una pregunta sugerida por ID
    public function obtenerSugerenciaPreguntaPorId($id)
    {
        $sql = "SELECT * FROM sugerencias_preguntas WHERE id = ?";
        $stmt = $this->db->getConexion()->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $pregunta = $result->fetch_assoc();
        $stmt->close();

        return $pregunta;
    }

    // Método para buscar respuestas sugeridas por el ID de la pregunta
    public function obtenerSugerenciasRespuestasPorPreguntaId($pregunta_id)
    {
        $sql = "SELECT * FROM sugerencias_respuestas WHERE sugerencia_pregunta_id = ?";
        $stmt = $this->db->getConexion()->prepare($sql);
        $stmt->bind_param("i", $pregunta_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $respuestas = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $respuestas;
    }

    // Método para actualizar el estado de una pregunta sugerida
    public function actualizarEstadoSugerenciaPregunta($id, $estado)
    {
        $sql = "UPDATE sugerencias_preguntas SET estado = ? WHERE id = ?";
        $stmt = $this->db->getConexion()->prepare($sql);
        $stmt->bind_param("si", $estado, $id);
        $stmt->execute();
        $stmt->close();
    }

    // Método para buscar una sugerencia por ID de usuario
    public function findById($id)
    {
        $sql = "
            SELECT u.nombre_completo AS nombre, u.foto_perfil, u.ciudad, u.pais, COALESCE(SUM(p.puntaje), 0) AS puntaje_total
            FROM users u
            LEFT JOIN Partida p ON u.id = p.usuario_id
            WHERE u.id = ?
            GROUP BY u.id";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        $usuario = $result->fetch_assoc();

        $stmt->close();
        return $usuario;
    }
}
