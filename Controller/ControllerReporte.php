<?php

namespace Controller;

use Database;
use Model\ModelReporte;
use MustachePresenter;

class ControllerReporte
{
    private $db;
    private $modelReporte;

    public function __construct() {
        // Inicializar la conexión a la base de datos
        $this->db = new Database('localhost', 'root', '', 'pregunta');
        $this->modelReporte = new ModelReporte($this->db);
    }

    public function postReportarPregunta($data) {
        $pregunta_id = isset($data['pregunta_id']) ? $data['pregunta_id'] : null;
        $usuario_id = isset($data['usuario_id']) ? $data['usuario_id'] : 1; // Valor predeterminado, como un ID de prueba
        $razon = isset($data['razon']) ? $data['razon'] : '';
        $descripcion = isset($data['descripcion']) ? $data['descripcion'] : '';
        $status = isset($data['reporte_status']) ? $data['reporte_status'] : 'pendiente';

        if ($pregunta_id) {
            $this->modelReporte->guardarReporte($pregunta_id, $usuario_id, $razon, $descripcion, $status);

            // Redirige a la página de la misma pregunta con un mensaje de confirmación
            header("Location: /PW2-JuegoPreguntasYRespuestas/ControllerJuego/mostrarPregunta?id=$pregunta_id");
            exit;
        } else {
            echo "Faltan datos requeridos para procesar el reporte.";
        }
    }
   /* public function postReportarPregunta($data) {
        $pregunta_id = isset($data['pregunta_id']) ? $data['pregunta_id'] : null;
        $usuario_id = isset($data['usuario_id']) ? $data['usuario_id'] : null;
        $razon = isset($data['razon']) ? $data['razon'] : '';
        $descripcion = isset($data['descripcion']) ? $data['descripcion'] : '';
        $status = isset($data['reporte_status']) ? $data['reporte_status'] : 'pendiente';

            $this->modelReporte->guardarReporte($pregunta_id, $usuario_id, $razon, $descripcion, $status);

            // Redirige al usuario al inicio después de reportar la pregunta
            header('Location: /PW2-JuegoPreguntasYRespuestas/ControllerInicio');
            exit;
        /*if ($pregunta_id && $usuario_id) {
            $this->modelReporte->guardarReporte($pregunta_id, $usuario_id, $razon, $descripcion, $status);
        } else {
            echo "Faltan datos requeridos para procesar el reporte.<br>";
        }*/



}