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
        $query = "SELECT COUNT(*) as cantidad FROM users WHERE verificado = 1 AND rol = 'jugador'";
        return $this->prepareQuery($query);
    }



    // 2. Obtener la cantidad de partidas
    public function getCantidadPartidas()
    {
        $query = "SELECT COUNT(*) as cantidad FROM Partida";
        return $this->prepareQuery($query);
    }

    // 3. Obtener la cantidad de preguntas en juego (válidas)
    public function getCantidadPreguntasJuego()
    {
        $query = "SELECT COUNT(*) as cantidad FROM Pregunta WHERE esValido = 1";
        return $this->prepareQuery($query);
    }

    // 4. Obtener la cantidad de preguntas creadas
    public function getCantidadPreguntasCreadas()
    {
        $query = "SELECT COUNT(*) as cantidad FROM Pregunta";
        return $this->prepareQuery($query);
    }

    // 5. Obtener la cantidad de usuarios nuevos dentro de un rango de fechas
    public function getRatioPorUsuario()
    {
        $query = "SELECT id, nombre_usuario, ratio FROM users WHERE verificado = 1 AND rol NOT IN ('administrador', 'editor')";

        $resultado = $this->ejecutarConsultaAgrupada($query);

        if ($resultado) {
            return $resultado;
        }

        return false;
    }


    public function getCantidadUsuariosNuevos($fechaInicio, $fechaFin)
    {
        $query = "SELECT COUNT(*) as cantidad FROM users WHERE fecha_registro BETWEEN ? AND ? AND verificado = 1 AND rol NOT IN ('administrador', 'editor')";
        $stmt = $this->db->prepare($query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $fechaInicio, $fechaFin);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);
                return $row['cantidad'];
            }

            mysqli_stmt_close($stmt);
            return false;
        }
        return false;
    }


    // 6. Obtener usuarios agrupados por país
    public function getUsuariosPorPais()
    {
        $query = "SELECT pais, COUNT(*) AS cantidad FROM users WHERE verificado = 1  AND rol NOT IN ('administrador', 'editor') GROUP BY pais ";
        return $this->ejecutarConsultaAgrupada($query);
    }

    public function getUsuariosPorPaisConFiltro($fechaInicio, $fechaFin)
    {
        $query = "SELECT pais, COUNT(*) AS cantidad 
              FROM users 
              WHERE verificado = 1 AND fecha_registro BETWEEN ? AND ?
              GROUP BY pais";
        $stmt = $this->db->prepare($query);

        if ($stmt) {
            $stmt->bind_param("ss", $fechaInicio, $fechaFin);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }



    // 7. Obtener usuarios agrupados por sexo
    public function getUsuariosPorSexo()
    {
        $query = "SELECT sexo, COUNT(*) AS cantidad FROM users WHERE verificado = 1 AND rol NOT IN ('administrador', 'editor') GROUP BY sexo";
        return $this->ejecutarConsultaAgrupada($query);
    }


    // 8. Obtener usuarios agrupados por edad
    public function getUsuariosPorEdad()
    {
        $query = "SELECT CASE 
        WHEN anio_nacimiento IS NULL THEN 'Sin Datos'
        WHEN YEAR(CURDATE()) - anio_nacimiento < 18 THEN 'Menores'
        WHEN YEAR(CURDATE()) - anio_nacimiento > 60 THEN 'Jubilados'
        ELSE 'Medios'
        END AS rango_edad,
        COUNT(*) AS cantidad
        FROM users
        WHERE verificado = 1 AND rol NOT IN ('administrador', 'editor')
        GROUP BY rango_edad;";
        return $this->ejecutarConsultaAgrupada($query);
    }



    public function prepareQuery($query){
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $stmt->bind_result($cantidad);
        if ($stmt->fetch()) {
            return $cantidad;
        } else {
    return "Error al ejecutar la consulta.";
         }
    }


    public function ejecutarConsultaAgrupada($query, $params = []){
        $stmt = $this->db->prepare($query);
        if ($stmt) {
            if (!empty($params)) {
                $types = str_repeat('s', count($params));
                mysqli_stmt_bind_param($stmt, $types, ...$params);
            }
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            if ($result) {
                $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
                mysqli_stmt_close($stmt);
                return $data;
            }
            mysqli_stmt_close($stmt);
        }
        return false;
    }

}
