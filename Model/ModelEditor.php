<?php

namespace Model;

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
        $sql = "INSERT INTO pregunta (descripcion, categoria_id, fechaCreacion,punto,esValido) VALUES (?, ?, NOW(),1,1)";
        $stmt = $this->db->prepare($sql);
        mysqli_stmt_bind_param($stmt, "si", $descripcion, $categoriaId);
        mysqli_stmt_execute($stmt);

        $preguntaId = $this->db->getConexion()->insert_id;

        // Insertar las respuestas
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
        // Primero, eliminar las respuestas asociadas a la pregunta
        $sqlRespuestas = "DELETE FROM respuesta WHERE pregunta_id = ?";
        $stmtRespuestas = $this->db->prepare($sqlRespuestas);
        mysqli_stmt_bind_param($stmtRespuestas, "i", $preguntaId);
        mysqli_stmt_execute($stmtRespuestas);
        mysqli_stmt_close($stmtRespuestas);

        // Luego eliminar la pregunta
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

        // Actualizar cada respuesta
        foreach ($respuestas as $index => $respuesta) {
            $esCorrecta = ($index + 1 == $correcta) ? 1 : 0;
            $sqlRespuesta = "UPDATE respuesta SET descripcion = ?, correcta = ? WHERE id = ?";
            $stmtRespuesta = $this->db->prepare($sqlRespuesta);
            $stmtRespuesta->bind_param("sii", $respuesta, $esCorrecta, $respuestaIds[$index]);
            $stmtRespuesta->execute();
            $stmtRespuesta->close();
        }
    }



}