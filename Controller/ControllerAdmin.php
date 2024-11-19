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
            'usuariosPorPais' => $this->model->getUsuariosPorPais(),
            'usuariosPorSexo' => $this->model->getUsuariosPorSexo(),
            'usuariosPorEdad' => $this->model->getUsuariosPorEdad(),
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
        $filePathPais = $grafico->generarGrafico($valuesPais, $labelsPais, 'Usuarios por País');


        $labelsSexo = array_column($usuariosPorSexo, 'sexo');
        $valuesSexo = array_column($usuariosPorSexo, 'cantidad');
        $filePathSexo = $grafico->generarGrafico($valuesSexo, $labelsSexo, 'Usuarios por Sexo');


        $labelsEdad = array_column($usuariosPorEdad, 'rango_edad');
        $valuesEdad = array_column($usuariosPorEdad, 'cantidad');
        $filePathEdad = $grafico->generarGrafico($valuesEdad, $labelsEdad, 'Usuarios por Edad');


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


    public function limpiarCarpeta()
    {
        $this->checkAdministrador();
        // Función para limpiar la carpeta public/img/grafico/
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
}


