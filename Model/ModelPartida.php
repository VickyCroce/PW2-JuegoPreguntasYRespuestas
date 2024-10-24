<?php

namespace Model;
class ModelPartida
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function obtenerPartidasPorUsuario($usuario_id)
    {
        $stmt = $this->db->prepare("SELECT id, codigo, puntaje AS resultado FROM Partida WHERE usuario_id = ? ORDER BY fechaCreacion DESC");
        $stmt->bind_param('i', $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $partidas = [];
        while ($row = $result->fetch_assoc()) {
            $partidas[] = $row;
        }

        return $partidas;
    }
}