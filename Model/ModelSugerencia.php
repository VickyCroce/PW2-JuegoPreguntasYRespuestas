<?php

namespace Model;

class ModelSugerencia
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function crearSugerenciaPregunta($usuarioId, $preguntaSugerida, $categoria)
    {
        $stmt = $this->db->prepare("INSERT INTO sugerencias_preguntas (usuario_id, pregunta_sugerida, categoria) VALUES (?, ?, ?)");
        $stmt->execute([$usuarioId, $preguntaSugerida, $categoria]);
        return $this->db->lastInsertId();
    }

    public function crearSugerenciaRespuesta($sugerenciaPreguntaId, $usuarioId, $respuestaSugerida)
    {
        $stmt = $this->db->prepare("INSERT INTO sugerencias_respuestas (sugerencia_pregunta_id, usuario_id, respuesta_sugerida) VALUES (?, ?, ?)");
        $stmt->execute([$sugerenciaPreguntaId, $usuarioId, $respuestaSugerida]);
    }

    public function obtenerSugerenciasPorUsuario($usuarioId)
    {
        $stmt = $this->db->prepare("SELECT * FROM sugerencias_preguntas WHERE usuario_id = ?");
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll();
    }

    public function obtenerSugerenciaPorId($sugerenciaPreguntaId)
    {
        $stmt = $this->db->prepare("SELECT * FROM sugerencias_preguntas WHERE id = ?");
        $stmt->execute([$sugerenciaPreguntaId]);
        return $stmt->fetch();
    }

    public function obtenerRespuestasPorSugerencia($sugerenciaPreguntaId)
    {
        $stmt = $this->db->prepare("SELECT * FROM sugerencias_respuestas WHERE sugerencia_pregunta_id = ?");
        $stmt->execute([$sugerenciaPreguntaId]);
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "
    SELECT u.nombre_completo AS nombre, u.foto_perfil, u.ciudad, u.pais, COALESCE(SUM(p.puntaje), 0) AS puntaje_total
    FROM users u
    LEFT JOIN Partida p ON u.id = p.usuario_id
    WHERE u.id = '$id'
    GROUP BY u.id";

        $result = $this->db->query($sql);

        if ($result && is_array($result) && count($result) > 0) {
            return $result[0]; // Retorna el primer resultado con los datos del usuario
        }

        return null;
    }
}
