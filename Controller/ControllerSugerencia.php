<?php

namespace Controller;
use MustachePresenter;

class ControllerSugerencia
{
    private $model;
    private $presenter;

    public function __construct($model, MustachePresenter $presenter)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    // Mostrar formulario para crear una sugerencia de pregunta
    public function mostrarFormularioSugerencia()
    {
        // Verificar si el usuario está en sesión
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $user = $this->model->findById($id);
        } else {
            echo "Debes estar logueado para sugerir una pregunta.";
            return;
        }

        $this->presenter->render('view/sugerenciaForm.mustache');
    }

    // Guardar una nueva sugerencia de pregunta
    public function guardarSugerenciaPregunta()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $user = $this->model->findById($id);

            $preguntaSugerida = $_POST['pregunta_sugerida'];
            $categoria = $_POST['categoria'];

            // Guardar la pregunta en la base de datos
            $sugerenciaPreguntaId = $this->model->crearSugerenciaPregunta($id, $preguntaSugerida, $categoria);

            // Guardar las respuestas asociadas a la pregunta
            if (isset($_POST['respuestas'])) {
                foreach ($_POST['respuestas'] as $respuestaSugerida) {
                    $this->model->crearSugerenciaRespuesta($sugerenciaPreguntaId, $id, $respuestaSugerida);
                }
            }

            // Mostrar mensaje de éxito
            $this->presenter->render('view/sugerenciaExito.mustache', [
                'mensaje' => '¡Tu sugerencia ha sido enviada exitosamente!'
            ]);
        } else {
            echo "Debes estar logueado para sugerir una pregunta.";
            return;
        }

    }
//
//    // Obtener todas las sugerencias de preguntas de un usuario
//    public function obtenerSugerenciasPorUsuario()
//    {
//        $usuarioId = $_SESSION['usuario']['id'];
//        $sugerencias = $this->model->obtenerSugerenciasPorUsuario($usuarioId);
//
//        $this->presenter->render('view/sugerenciasUsuario.mustache', [
//            'sugerencias' => $sugerencias
//        ]);
//    }
//
//    // Ver detalles de una sugerencia de pregunta específica, incluyendo respuestas
//    public function verDetallesSugerencia($sugerenciaPreguntaId)
//    {
//        $sugerencia = $this->model->obtenerSugerenciaPorId($sugerenciaPreguntaId);
//        $respuestas = $this->model->obtenerRespuestasPorSugerencia($sugerenciaPreguntaId);
//
//        $this->presenter->render('view/detalleSugerencia.mustache', [
//            'sugerencia' => $sugerencia,
//            'respuestas' => $respuestas
//        ]);
//    }
}
