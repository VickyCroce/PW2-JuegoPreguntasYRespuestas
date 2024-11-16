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

    public function actualizarRatio($usuario_id) {
        $sql = "
    UPDATE users 
    SET ratio = (cantidad_acertadas / cantidad_dadas) * 100
    WHERE id = ?";

        $stmt = $this->db->getConexion()->prepare($sql);
        $stmt->bind_param("i", $usuario_id); // Solo un parámetro
        $stmt->execute();
        $stmt->close();
    }

    public function getUserRatio($userId)
    {
        $sql = "SELECT cantidad_acertadas, cantidad_dadas FROM users WHERE id = ?";
        $stmt = $this->db->getConexion()->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($cantidadAcertadas, $cantidadDadas);
        $stmt->fetch();
        $stmt->close();

        return $cantidadDadas > 0 ? ($cantidadAcertadas / $cantidadDadas) * 100 : 0;
    }

    public function getPreguntaSegunDificultad($preguntasMostradas, $userId) {

        $numPartidas = $this->contarPartidasUsuario($userId);

        if ($numPartidas < 5) {
            return $this->getPreguntaAleatoria($preguntasMostradas);
        }

        $userRatio = $this->getUserRatio($userId);

        if ($userRatio >= 80) {
            // Ratio alto -> dificultad alta (preguntas con porcentaje bajo)
            $minPorcentaje = 0;
            $maxPorcentaje = 40;
        } elseif ($userRatio >= 50) {
            // Ratio medio -> dificultad media
            $minPorcentaje = 41;
            $maxPorcentaje = 70;
        } else {
            // Ratio bajo -> preguntas fáciles (porcentaje alto)
            $minPorcentaje = 71;
            $maxPorcentaje = 100;
        }


        $sql = "
        SELECT p.id, p.descripcion, p.punto, p.categoria_id, c.nombre AS categoria, c.color
        FROM Pregunta p
        JOIN Categoria c ON p.categoria_id = c.id
        WHERE p.porcentaje BETWEEN ? AND ?
    ";

        $types = "ii";
        $params = [$minPorcentaje, $maxPorcentaje];
        if (!empty($preguntasMostradas)) {
            $placeholders = implode(',', array_fill(0, count($preguntasMostradas), '?'));
            $sql .= " AND p.id NOT IN ($placeholders)";
            $types .= str_repeat('i', count($preguntasMostradas));
            $params = array_merge($params, $preguntasMostradas);
        }

        $sql .= " ORDER BY RAND() LIMIT 1";
        $stmt = $this->db->getConexion()->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $pregunta = $result->fetch_assoc();
        $stmt->close();

        return $pregunta;
    }



    public function actualizarContadoresUsuario($userId, $esCorrecta)
    {
        $sql = "UPDATE users SET cantidad_dadas = cantidad_dadas + 1" .
            ($esCorrecta ? ", cantidad_acertadas = cantidad_acertadas + 1" : "") .
            " WHERE id = ?";
        $stmt = $this->db->getConexion()->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
    }

    public function contarPartidasUsuario($usuario_id) {
        $sql = "SELECT COUNT(*) FROM Partida WHERE usuario_id = ?";
        $stmt = $this->db->getConexion()->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $stmt->bind_result($conteo);
        $stmt->fetch();
        $stmt->close();
        return $conteo;
    }

    public function registrarPreguntaMostrada($usuarioId, $preguntaId)
    {
        $sql = "INSERT INTO preguntas_mostradas (usuario_id, pregunta_id) VALUES (?, ?)";
        $stmt = $this->db->getConexion()->prepare($sql);
        $stmt->bind_param("ii", $usuarioId, $preguntaId);
        $stmt->execute();
        $stmt->close();
    }

    public function obtenerPreguntasMostradas($usuarioId)
    {
        $sql = "SELECT pregunta_id FROM preguntas_mostradas WHERE usuario_id = ?";
        $stmt = $this->db->getConexion()->prepare($sql);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();
        $preguntas = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return array_column($preguntas, 'pregunta_id');
    }

    public function limpiarPreguntasMostradas($usuarioId)
    {
        $sql = "DELETE FROM preguntas_mostradas WHERE usuario_id = ?";
        $stmt = $this->db->getConexion()->prepare($sql);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $stmt->close();
    }

}


