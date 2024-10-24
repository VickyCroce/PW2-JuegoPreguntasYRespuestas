<?php

namespace Model;
class ModelHome
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getMaxPuntajeUsuario($usuarioId)
    {
        $sql = "SELECT MAX(puntaje) as max_puntaje FROM Partida WHERE usuario_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        return $data['max_puntaje'] ? $data['max_puntaje'] : 0;
    }
}