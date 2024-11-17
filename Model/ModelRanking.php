<?php

namespace Model;

class ModelRanking
{
    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function getRankingJugadores()
    {
        $sql = "
    SELECT u.nombre_usuario AS nombre, MAX(p.puntaje) AS puntuacion, u.id AS usuario_id
    FROM Partida p
    INNER JOIN users u ON p.usuario_id = u.id
    WHERE u.rol NOT IN ('administrador', 'editor')
    GROUP BY u.id
    ORDER BY puntuacion DESC
    LIMIT 10";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            echo "Error en la preparación de la consulta: " . mysqli_error($this->db->getConexion());
            return [];
        }

        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $nombre, $puntuacion, $usuario_id);

        $jugadores = [];
        $posicion = 1;

        while (mysqli_stmt_fetch($stmt)) {
            $jugadores[] = [
                'posicion' => $posicion++,
                'nombre' => $nombre,
                'puntuacion' => $puntuacion,
                'usuario_id' => $usuario_id
            ];
        }

        mysqli_stmt_close($stmt);

        return $jugadores;
    }







}