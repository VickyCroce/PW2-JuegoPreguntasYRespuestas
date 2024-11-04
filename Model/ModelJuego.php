<?php

namespace Model;
class ModelJuego
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function crearPartida($nombre, $puntaje, $codigo, $usuario_id)
    {
        $sql = "INSERT INTO Partida (nombre, puntaje, codigo, usuario_id, fechaCreacion) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->db->getConexion()->prepare($sql);
        $stmt->bind_param("siis", $nombre, $puntaje, $codigo, $usuario_id);
        $stmt->execute();

        // Retornar el ID de la nueva partida
        $partidaId = $this->db->getConexion()->insert_id;
        $stmt->close();

        return $partidaId;
    }

    public function getUltimoIdPartida()
    {
        $sql = "SELECT MAX(id) FROM Partida";
        $stmt = $this->db->getConexion()->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($ultimoId);
        $stmt->fetch();
        $stmt->close();
        return $ultimoId ? $ultimoId : 0;
    }


    public function getPreguntaAleatoria($preguntasMostradas)
    {
        $placeholders = implode(',', array_fill(0, count($preguntasMostradas), '?'));
        $sql = "
        SELECT p.id, p.descripcion, p.punto, p.categoria_id, c.nombre AS categoria, c.color AS color
        FROM Pregunta p
        JOIN Categoria c ON p.categoria_id = c.id
        WHERE p.esValido = TRUE " .
            (count($preguntasMostradas) ? "AND p.id NOT IN ($placeholders)" : "") . "
        ORDER BY RAND()
        LIMIT 1
    ";

        $stmt = $this->db->getConexion()->prepare($sql);
        if (count($preguntasMostradas)) {
            $stmt->bind_param(str_repeat('i', count($preguntasMostradas)), ...$preguntasMostradas);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $pregunta = $result->fetch_assoc();
        $stmt->close();

        return $pregunta;
    }


    public function getRespuestasPorPregunta($pregunta_id)
    {
        $sql = "SELECT * FROM Respuesta WHERE pregunta_id = ?";
        $stmt = $this->db->getConexion()->prepare($sql);
        $stmt->bind_param("i", $pregunta_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $respuestas = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $respuestas;
    }

    public function actualizarPuntosPartida($partidaId, $puntos)
    {
        $sql = "UPDATE Partida SET puntaje = ? WHERE id = ?";
        $stmt = $this->db->getConexion()->prepare($sql);
        $stmt->bind_param("ii", $puntos, $partidaId);
        $stmt->execute();
        $stmt->close();
    }


}


