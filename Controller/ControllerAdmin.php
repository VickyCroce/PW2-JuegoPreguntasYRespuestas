<?php

namespace Controller;

use GenerarGraficos;
use Helper\PdfCreator;

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
            header("Location: /PW2-JuegoPreguntasYRespuestas/ControllerLogin/cerrarSesion");
            exit();
        }
    }


    public function get() {
        $this->checkAdministrador();

        $fechaInicio = '2024-11-11';
        $fechaFin = '2024-11-18';

        try {
            $graficos = $this->getGrafico();
        } catch (Exception $e) {
            $graficos = [
                'graficoPais' => '',
                'graficoSexo' => ''
            ];
            error_log($e->getMessage());
        }

        $data = array_merge([
            'cantidadJugadores' => $this->model->getCantidadJugadores(),
            'cantidadPartidas' => $this->model->getCantidadPartidas(),
            'cantidadPreguntasJuego' => $this->model->getCantidadPreguntasJuego(),
            'cantidadPreguntasCreadas' => $this->model->getCantidadPreguntasCreadas(),
            'cantidadUsuariosNuevos' => $this->model->getCantidadUsuariosNuevos($fechaInicio, $fechaFin),
            'porcentajeCorrectas' => $this->model->getRatioPorUsuario(),
        ], $graficos);

        $this->presenter->render('view/admin.mustache', $data);

    }



    public function getGrafico() {
        $this->checkAdministrador();
        $this->limpiarCarpeta();


        $usuariosPorPais = $this->model->getUsuariosPorPais();
        $usuariosPorSexo = $this->model->getUsuariosPorSexo();
        $usuariosPorEdad = $this->model->getUsuariosPorEdad();


        $labelsPais = array_column($usuariosPorPais, 'pais');
        $valuesPais = array_column($usuariosPorPais, 'cantidad');
        $grafico = new GenerarGraficos();
        $filePathPais = $grafico->generarGraficoBarras($valuesPais, $labelsPais, 'Usuarios por País');


        $labelsSexo = array_column($usuariosPorSexo, 'sexo');
        $valuesSexo = array_column($usuariosPorSexo, 'cantidad');
        $filePathSexo = $grafico->generarGraficoTorta($valuesSexo, $labelsSexo, 'Usuarios por Sexo');


        $labelsEdad = array_column($usuariosPorEdad, 'rango_edad');
        $valuesEdad = array_column($usuariosPorEdad, 'cantidad');
        $filePathEdad = $grafico->generarGraficoBarras($valuesEdad, $labelsEdad, 'Usuarios por Edad');


        // Verificar que los gráficos se hayan generado correctamente
        if (file_exists($filePathPais) && file_exists($filePathSexo)&& file_exists($filePathEdad)) {
            return [
                'graficoPais' => $filePathPais,
                'graficoSexo' => $filePathSexo,
                'graficoEdad' => $filePathEdad,
            ];
        } else {
            throw new Exception('No se pudieron generar los gráficos.');
        }
    }

    public function getGraficoPorFiltro($fechaInicio, $fechaFin)
    {
        $this->checkAdministrador();
        $this->limpiarCarpeta();

        $usuariosPorPais = $this->model->getUsuariosPorPaisFiltrados($fechaInicio, $fechaFin);
        $usuariosPorSexo = $this->model->getUsuariosPorSexoFiltrados($fechaInicio, $fechaFin);
        $usuariosPorEdad = $this->model->getUsuariosPorEdadFiltrados($fechaInicio, $fechaFin);


        $grafico = new GenerarGraficos();

        $labelsPais = array_column($usuariosPorPais, 'pais');
        $valuesPais = array_column($usuariosPorPais, 'cantidad');
        $filePathPais = $grafico->generarGraficoBarras($valuesPais, $labelsPais, 'Usuarios por País');

        $labelsSexo = array_column($usuariosPorSexo, 'sexo');
        $valuesSexo = array_column($usuariosPorSexo, 'cantidad');
        $filePathSexo = $grafico->generarGraficoTorta($valuesSexo, $labelsSexo, 'Usuarios por Sexo');

        $labelsEdad = array_column($usuariosPorEdad, 'rango_edad');
        $valuesEdad = array_column($usuariosPorEdad, 'cantidad');
        $filePathEdad = $grafico->generarGraficoBarras($valuesEdad, $labelsEdad, 'Usuarios por Edad');

        if (file_exists($filePathPais) && file_exists($filePathSexo) && file_exists($filePathEdad)) {
            return [
                'graficoPais' => $filePathPais,
                'graficoSexo' => $filePathSexo,
                'graficoEdad' => $filePathEdad,
            ];
        } else {
            throw new Exception('No se pudieron generar los gráficos.');
        }
    }



    public function limpiarCarpeta()
    {
        $this->checkAdministrador();

        $carpeta = 'public/img/Grafico/';

        $carpeta = rtrim($carpeta, '/') . '/';

        if (is_dir($carpeta) && strpos(realpath($carpeta), realpath('public/img/Grafico')) === 0) {
            $archivos = glob($carpeta . '*');

            foreach ($archivos as $archivo) {
                if (is_dir($archivo)) {
                    $this->limpiarCarpeta($archivo);
                    rmdir($archivo);
                } else {
                    unlink($archivo);
                }
            }

        }
    }

    public function datosFiltrados()
    {
        $this->checkAdministrador();

        $filtro = $_GET['filtroFecha'] ?? 'mes'; // Por defecto

        $fechaFin = date('Y-m-d 23:59:59'); // Final del día actual
        switch ($filtro) {
            case 'dia':
                $fechaInicio = date('Y-m-d 00:00:00'); // Inicio del día actual
                break;
            case 'semana':
                $fechaInicio = date('Y-m-d 00:00:00', strtotime('monday this week')); // Inicio de la semana
                break;
            case 'mes':
                $fechaInicio = date('Y-m-d 00:00:00', strtotime('first day of this month')); // Inicio del mes
                break;
            case 'anio':
                $fechaInicio = date('Y-m-d 00:00:00', strtotime('first day of January this year')); // Inicio del año
                break;
            default:
                $fechaInicio = date('Y-m-d 00:00:00', strtotime('-1 month')); // Rango por defecto (último mes)
        }


        $graficos = $this->getGraficoPorFiltro($fechaInicio, $fechaFin);


        $data = array_merge([
            'cantidadJugadores' => $this->model->getCantidadJugadores(),
            'cantidadPartidas' => $this->model->getCantidadPartidas(),
            'cantidadPreguntasJuego' => $this->model->getCantidadPreguntasJuego(),
            'cantidadPreguntasCreadas' => $this->model->getCantidadPreguntasCreadas(),
            'cantidadUsuariosNuevos' => $this->model->getCantidadUsuariosNuevos($fechaInicio, $fechaFin),
            'porcentajeCorrectas' => $this->model->getRatioPorUsuario(),
            'filtroSeleccionado' => [
                'dia' => $filtro === 'dia',
                'semana' => $filtro === 'semana',
                'mes' => $filtro === 'mes',
                'anio' => $filtro === 'anio',
            ]
        ], $graficos);

        $this->presenter->render('view/admin.mustache', $data);
    }

    public function generarPdf()
    {
        $this->checkAdministrador();

        $data = array_merge([
            'cantidadJugadores' => $this->model->getCantidadJugadores(),
            'cantidadPartidas' => $this->model->getCantidadPartidas(),
            'cantidadPreguntasJuego' => $this->model->getCantidadPreguntasJuego(),
            'cantidadPreguntasCreadas' => $this->model->getCantidadPreguntasCreadas(),
            'porcentajeCorrectas' => $this->model->getRatioPorUsuario(),
        ]);

        $pdfCreator = new PdfCreator();
        $html = $this->presenter->render('view/adminPdf.mustache', $data);

        $pdfCreator->create($html);
    }






}


