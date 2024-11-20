<?php

namespace Controller;

use Database;
use Model\ModelReporte;
use MustachePresenter;

class ControllerReporte
{
    private $modelo;
    private $presenter;

    public function __construct($Modelo, $Presentador)
    {
        $this->modelo = $Modelo;
        $this->presenter = $Presentador;
    }

    public function postReportarPregunta($data) {
        $pregunta_id = isset($data['pregunta_id']) ? $data['pregunta_id'] : null;
        $usuario_id = isset($data['usuario_id']) ? $data['usuario_id'] : 1; // Valor predeterminado, como un ID de prueba
        $razon = isset($data['razon']) ? $data['razon'] : '';
        $descripcion = isset($data['descripcion']) ? $data['descripcion'] : '';
        $status = isset($data['reporte_status']) ? $data['reporte_status'] : 'pendiente';

        if ($pregunta_id) {
            $this->modelo->guardarReporte($pregunta_id, $usuario_id, $razon, $descripcion, $status);

            // Redirige a la página de la misma pregunta con un mensaje de confirmación
            header("Location: /PW2-JuegoPreguntasYRespuestas/ControllerJuego/mostrarPregunta?id=$pregunta_id");
            exit;
        } else {
            echo "Faltan datos requeridos para procesar el reporte.";
        }
    }


}