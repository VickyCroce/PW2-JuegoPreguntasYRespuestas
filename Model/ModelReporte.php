<?php

namespace Model;

use Database;
use PDO;

class ModelReporte
{
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function guardarReporte($pregunta_id, $usuario_id, $razon, $descripcion, $status) {
        $conexion = $this->db->getConexion();
        $stmt = $conexion->prepare("INSERT INTO reportes_preguntas (pregunta_id, usuario_id, razon, descripcion, status) 
                                VALUES (?, ?, ?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param('iisss', $pregunta_id, $usuario_id, $razon, $descripcion, $status);
            if (!$stmt->execute()) {
                echo "Error en la ejecución: " . $stmt->error . "<br>";
            }
            $stmt->close();
        } else {
            echo "Error al preparar la consulta: " . $conexion->error . "<br>";
        }
    }


   /* public function obtenerReportesPendientes() {
        $conexion = $this->db->getConexion();
        $sql = "SELECT * FROM reportes_preguntas WHERE status = 'pendiente'";
        $result = mysqli_query($conexion, $sql);

        $reportes = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reportes[] = $row;
        }
        return $reportes;
    }

    // Método para cambiar el estado del reporte (aceptar o rechazar)
    public function cambiarEstadoReporte($reporte_id, $nuevo_estado) {
        // Usar la conexión de la base de datos
        $conexion = $this->db->getConexion();  // Usamos la conexión mysqli desde la clase Database

        // Actualizar el estado del reporte
        $sql = "UPDATE reportes_preguntas SET status = '$nuevo_estado' WHERE id = $reporte_id";  // Cambié 'reportes' por 'reportes_preguntas'

        // Ejecutar la consulta
        mysqli_query($conexion, $sql);  // Ejecutamos la consulta directamente
    }
*/
}