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

    private function checkJugador() {
        if (!(isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] == 'jugador')) {
            header("Location: /PW2-JuegoPreguntasYRespuestas/ControllerLogin/cerrarSesion");
            exit();
        }
    }
    public function mostrarFormularioSugerencia()
    {   $this->checkJugador();
        // Verificar si el usuario está en sesión
        if (isset($_SESSION['usuario']['id'])) {
            $usuarioId = $_SESSION['usuario']['id'];
            $user = $this->model->findById($usuarioId);
        } else {
            echo "Debes estar logueado para ver el formulario de sugerir una pregunta.";
            return;
        }

        $this->presenter->render('view/sugerenciaForm.mustache', [
            'usuario_id' => $usuarioId
        ]);
    }

    // Guardar una nueva sugerencia de pregunta
    public function guardarSugerenciaPregunta()
    {   $this->checkJugador();
        // Verificar si el usuario está logueado
        if (isset($_POST['usuario_id'])) {
            $usuarioId = $_POST['usuario_id'];
            $user = $this->model->findById($usuarioId);

            // Obtener datos del formulario
            $preguntaSugerida = $_POST['pregunta_sugerida'];
            $categoria = $_POST['categoria'];
            $respuestaCorrecta = $_POST['respuesta_correcta'];
            $respuestasIncorrectas = $_POST['respuestas_incorrectas'];

            // Guardar la pregunta en la tabla `sugerencias_preguntas`
            $sugerenciaPreguntaId = $this->model->crearSugerenciaPregunta($usuarioId, $preguntaSugerida, $categoria);

            // Guardar la respuesta correcta en `sugerencias_respuestas`
            $this->model->crearSugerenciaRespuesta($sugerenciaPreguntaId, $usuarioId, $respuestaCorrecta, 1); // `es_correcta` es 1 para indicar que es la correcta

            // Guardar cada respuesta incorrecta en `sugerencias_respuestas`
            foreach ($respuestasIncorrectas as $respuestaIncorrecta) {
                $this->model->crearSugerenciaRespuesta($sugerenciaPreguntaId, $usuarioId, $respuestaIncorrecta, 0); // `es_correcta` es 0 para incorrectas
            }

            // Renderizar la vista de éxito
            $this->presenter->render('view/sugerenciaExito.mustache', [
                'mensaje' => '¡Tu sugerencia ha sido enviada exitosamente!'
            ]);
        } else {
            echo "Debes estar logueado para sugerir una pregunta.";
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
