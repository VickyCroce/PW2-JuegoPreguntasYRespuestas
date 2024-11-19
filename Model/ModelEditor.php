<?php

namespace Model;

use Exception;

class ModelEditor
{
    private $db;

    public function __construct($db){
        $this->db = $db;
    }


    public function getPreguntas()
    {
        $sql = "SELECT * FROM Pregunta ORDER BY id ASC";
        $stmt = $this->db->getConexion()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $preguntas = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $preguntas;
    }


    public function getCategoriaId($categoriaNombre)
    {
        $sql = "SELECT id FROM categoria WHERE nombre = ?";
        $stmt = $this->db->prepare($sql);
        mysqli_stmt_bind_param($stmt, "s", $categoriaNombre);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $categoria = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        return $categoria ? $categoria['id'] : null;
    }


    public function getPreguntaById($id)
    {
        $sql = "SELECT pregunta.id, pregunta.descripcion, categoria.nombre AS categoria_nombre
            FROM pregunta
            JOIN categoria ON pregunta.categoria_id = categoria.id
            WHERE pregunta.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getRespuestasByPreguntaId($preguntaId)
    {
        $sql = "SELECT id, descripcion, correcta FROM respuesta WHERE pregunta_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $preguntaId);
        $stmt->execute();

        $result = $stmt->get_result();
        $respuestas = [];
        while ($row = $result->fetch_assoc()) {
            $respuestas[] = $row;
        }
        return $respuestas;
    }



    public function guardarPregunta($descripcion, $categoriaId, $respuestas, $correcta)
    {
        $sql = "INSERT INTO pregunta (descripcion, categoria_id, fechaCreacion, punto, esValido, cantidad_dadas, acertadas, porcentaje) 
            VALUES (?, ?, NOW(), 1, 1, 0, 0, 0)";
        $stmt = $this->db->prepare($sql);
        mysqli_stmt_bind_param($stmt, "si", $descripcion, $categoriaId);
        mysqli_stmt_execute($stmt);

        $preguntaId = $this->db->getConexion()->insert_id;

        foreach ($respuestas as $key => $respuesta) {
            $esCorrecta = ($key + 1 == $correcta) ? 1 : 0;
            $sqlRespuesta = "INSERT INTO respuesta (pregunta_id, descripcion, correcta) VALUES (?, ?, ?)";
            $stmtRespuesta = $this->db->prepare($sqlRespuesta);
            mysqli_stmt_bind_param($stmtRespuesta, "isi", $preguntaId, $respuesta, $esCorrecta);
            mysqli_stmt_execute($stmtRespuesta);
        }

        mysqli_stmt_close($stmt);
        mysqli_stmt_close($stmtRespuesta);
    }


    public function eliminarPregunta($preguntaId)
    {
        // Eliminar las relaciones en la tabla `preguntas_mostradas`
        $sqlPreguntasMostradas = "DELETE FROM preguntas_mostradas WHERE pregunta_id = ?";
        $stmtPreguntasMostradas = $this->db->prepare($sqlPreguntasMostradas);
        mysqli_stmt_bind_param($stmtPreguntasMostradas, "i", $preguntaId);
        mysqli_stmt_execute($stmtPreguntasMostradas);
        mysqli_stmt_close($stmtPreguntasMostradas);

        // Eliminar las respuestas asociadas a la pregunta
        $sqlRespuestas = "DELETE FROM respuesta WHERE pregunta_id = ?";
        $stmtRespuestas = $this->db->prepare($sqlRespuestas);
        mysqli_stmt_bind_param($stmtRespuestas, "i", $preguntaId);
        mysqli_stmt_execute($stmtRespuestas);
        mysqli_stmt_close($stmtRespuestas);

        // Finalmente, eliminar la pregunta
        $sqlPregunta = "DELETE FROM pregunta WHERE id = ?";
        $stmtPregunta = $this->db->prepare($sqlPregunta);
        mysqli_stmt_bind_param($stmtPregunta, "i", $preguntaId);
        mysqli_stmt_execute($stmtPregunta);
        mysqli_stmt_close($stmtPregunta);
    }



    public function actualizarPregunta($id, $descripcion, $categoriaId, $respuestas, $correcta)
    {
        // Actualizar la pregunta en la tabla `pregunta`
        $sqlPregunta = "UPDATE pregunta SET descripcion = ?, categoria_id = ? WHERE id = ?";
        $stmtPregunta = $this->db->prepare($sqlPregunta);
        $stmtPregunta->bind_param("sii", $descripcion, $categoriaId, $id);
        $stmtPregunta->execute();
        $stmtPregunta->close();

        // Obtener IDs de las respuestas para esta pregunta y actualizarlas
        $sqlObtenerRespuestas = "SELECT id FROM respuesta WHERE pregunta_id = ?";
        $stmtObtenerRespuestas = $this->db->prepare($sqlObtenerRespuestas);
        $stmtObtenerRespuestas->bind_param("i", $id);
        $stmtObtenerRespuestas->execute();
        $result = $stmtObtenerRespuestas->get_result();
        $respuestaIds = [];
        while ($row = $result->fetch_assoc()) {
            $respuestaIds[] = $row['id'];
        }
        $stmtObtenerRespuestas->close();

        foreach ($respuestas as $index => $respuesta) {
            $esCorrecta = ($index + 1 == $correcta) ? 1 : 0;
            $sqlRespuesta = "UPDATE respuesta SET descripcion = ?, correcta = ? WHERE id = ?";
            $stmtRespuesta = $this->db->prepare($sqlRespuesta);
            $stmtRespuesta->bind_param("sii", $respuesta, $esCorrecta, $respuestaIds[$index]);
            $stmtRespuesta->execute();
            $stmtRespuesta->close();
        }
    }

    //REPORTE
    public function obtenerReportes() {
        $conexion = $this->db->getConexion();
        $stmt = $conexion->prepare("SELECT r.id, p.descripcion AS pregunta, r.razon, r.descripcion 
                                FROM reportes_preguntas r
                                JOIN Pregunta p ON r.pregunta_id = p.id
                                WHERE r.status = 'pendiente';");

        $stmt->execute();
        $result = $stmt->get_result();
        $reportes = [];

        while ($row = $result->fetch_assoc()) {
            $reportes[] = $row;
        }

        return $reportes;
    }

    public function eliminarPreguntaYReporte($id) {
        $sqlReporte = "UPDATE reportes_preguntas SET status = 'aprobado' WHERE id = ?";
        $stmtReporte = $this->db->prepare($sqlReporte);
        $stmtReporte->bind_param("i", $id);
        $stmtReporte->execute();

        // Actualizar la columna esValido a 0 en la tabla pregunta
        $sqlActualizarPregunta = "UPDATE pregunta SET esValido = 0 WHERE id = (SELECT pregunta_id FROM reportes_preguntas WHERE id = ?)";
        $stmtActualizarPregunta = $this->db->prepare($sqlActualizarPregunta);
        $stmtActualizarPregunta->bind_param("i", $id);
        $stmtActualizarPregunta->execute();
    }

    public function rechazarReporte($id) {
        $sql = "UPDATE reportes_preguntas SET status = 'rechazado' WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
    }

    public function obtenerPreguntasSugeridas() {
        $sql = "SELECT * FROM sugerencias_preguntas";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $preguntas = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $preguntas;
    }

    public function obtenerRespuestasPorPreguntaSugeridaId($preguntaId) {
        $sql = "SELECT * FROM sugerencias_respuestas WHERE sugerencia_pregunta_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $preguntaId);
        $stmt->execute();
        $result = $stmt->get_result();
        $respuestas = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $respuestas;
    }

    public function actualizarEstadoSugerencia($id, $estado) {
        $sql = "UPDATE sugerencias_preguntas SET estado = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $estado, $id);
        $stmt->execute();
        $stmt->close();
    }


    public function getPreguntaSugeridaById($id)
    {
        $sql = "SELECT id, descripcion, categoria FROM sugerencias_preguntas WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function getRespuestasSugeridasByPreguntaId($preguntaId)
    {
        $sql = "SELECT descripcion, es_correcta FROM sugerencias_respuestas WHERE pregunta_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $preguntaId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function eliminarPreguntaSugerida($id)
    {
        $sqlRespuestas = "DELETE FROM respuestas_sugerida WHERE pregunta_id = ?";
        $stmtRespuestas = $this->db->prepare($sqlRespuestas);
        $stmtRespuestas->bind_param("i", $id);
        $stmtRespuestas->execute();
        $stmtRespuestas->close();

        $sqlPregunta = "DELETE FROM preguntas_sugerida WHERE id = ?";
        $stmtPregunta = $this->db->prepare($sqlPregunta);
        $stmtPregunta->bind_param("i", $id);
        $stmtPregunta->execute();
        $stmtPregunta->close();
    }




}