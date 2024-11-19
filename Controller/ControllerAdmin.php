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

        // Obtener datos del modelo
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

        // Renderizar la vista con los datos
        $this->presenter->render('view/admin.mustache', $data);



    }


    public function getGrafico() {
        $this->checkAdministrador();
        $this->limpiarCarpeta();

        // Obtener datos para ambos gráficos
        $usuariosPorPais = $this->model->getUsuariosPorPais();
        $usuariosPorSexo = $this->model->getUsuariosPorSexo();

        // Generar el gráfico de usuarios por país
        $labelsPais = array_column($usuariosPorPais, 'pais');
        $valuesPais = array_column($usuariosPorPais, 'cantidad');
        $grafico = new GenerarGraficos();
        $filePathPais = $grafico->generarGrafico($valuesPais, $labelsPais, 'Usuarios por País');

        // Generar el gráfico de usuarios por sexo
        $labelsSexo = array_column($usuariosPorSexo, 'sexo');
        $valuesSexo = array_column($usuariosPorSexo, 'cantidad');
        $filePathSexo = $grafico->generarGrafico($valuesSexo, $labelsSexo, 'Usuarios por Sexo');

        // Responder con los gráficos
        if (file_exists($filePathPais) && file_exists($filePathSexo)) {
            header('Content-Type: application/json');
            echo json_encode([
                'graficoPais' => $filePathPais,
                'graficoSexo' => $filePathSexo
            ]);
            exit();
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'No se pudo generar los gráficos.']);
            exit();
        }
    }


    public function limpiarCarpeta()
    {
        // Función para limpiar la carpeta public/img/grafico/
        $carpeta = 'public/img/Grafico/';

        // Asegurarse de que el camino tenga una barra diagonal al final
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


