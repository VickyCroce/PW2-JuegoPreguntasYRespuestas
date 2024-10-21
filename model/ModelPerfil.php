<?php

namespace model;
class ModelPerfil
{
    private $conn;
    private $table_name = "users"; // Ajustar al nombre de la tabla en la base de datos

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Obtener datos de un usuario por ID
    public function getUserById($userId)
    {
        $query = "SELECT id, username, full_name, score, profile_pic FROM " . $this->table_name . " WHERE id = :userId";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC); // Retorna un array con los datos del usuario
    }

    // Obtener todas las partidas de un usuario
    public function getUserGames($userId)
    {
        $query = "SELECT g.id, g.name, g.score FROM games g WHERE g.user_id = :userId";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna un array de todas las partidas del usuario
    }

    // Obtener la posición en el ranking de un usuario
    public function getRankingPosition($userId)
    {
        // La consulta puede variar según cómo se gestione el ranking en tu base de datos
        $query = "SELECT COUNT(*) AS rank_position 
                  FROM " . $this->table_name . " 
                  WHERE score > (SELECT score FROM " . $this->table_name . " WHERE id = :userId)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['rank_position'] + 1; // La posición es la cantidad de usuarios con más puntaje más 1
    }

    // Actualizar el perfil del usuario
    public function updateUserProfile($userId, $fullName, $profilePic)
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET full_name = :fullName, profile_pic = :profilePic 
                  WHERE id = :userId";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':fullName', $fullName);
        $stmt->bindParam(':profilePic', $profilePic);
        $stmt->bindParam(':userId', $userId);

        return $stmt->execute(); // Retorna true si se actualizó correctamente
    }

    // Borrar un usuario
    public function deleteUser($userId)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :userId";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId);

        return $stmt->execute();
    }

}