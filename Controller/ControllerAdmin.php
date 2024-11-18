<?php

namespace Controller;

use GenerarGraficos;

class ControllerAdmin
{
    private $model;
    private $presenter;

    public function __construct($model, $presenter){
        $this->model = $model;
        $this->presenter = $presenter;
    }


    private function checkAdministrador() {
        if (!(isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] == 'administrador')) {
            header("Location: /PW2-JuegoPreguntasYRespuestas/ControllerLogin/get");
            exit();
        }
    }


    public function get()
    {
        $this->checkAdministrador();
        $fechaInicio = '2024-11-11';
        $fechaFin = '2024-11-18';
        $data = [
            'cantidadJugadores' => $this->model->getCantidadJugadores(),
            'cantidadPartidas' => $this->model->getCantidadPartidas(),
            'cantidadPreguntasJuego' => $this->model->getCantidadPreguntasJuego(),
            'cantidadPreguntasCreadas' => $this->model->getCantidadPreguntasCreadas(),
            'cantidadUsuariosNuevos' => $this->model->getCantidadUsuariosNuevos($fechaInicio, $fechaFin),
            'porcentajeCorrectas' => $this->model->getRatioPorUsuario(),
            'usuariosPorPais' => $this->model->getUsuariosPorPais(),
            'usuariosPorSexo' => $this->model->getUsuariosPorSexo(),
            'usuariosPorEdad' => $this->model->getUsuariosPorEdad()
        ];

        $this->presenter->render('view/admin.mustache', $data); // Enviando los datos al presentador
        $grafico = new GenerarGraficos();
        $grafico->obtenerGrafico();
    }


    // Método para obtener el gráfico generado y enviarlo como respuesta
    public function getGrafico()
        {

        $filtro = $_GET['filtroFecha'] ?? 'dia';


        $grafico = new GenerarGraficos();
        $filePath = $grafico->crearGraficoUsuariosPorPais($filtro);


        header('Content-Type: image/png');
        readfile($filePath);
        exit();
    }
}

