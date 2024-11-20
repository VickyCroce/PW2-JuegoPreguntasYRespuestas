<?php

namespace Controller;

use Model\ModelReporte;


class ControllerEditor
{
    private $model;
    private $presenter;

    public function __construct($model, $presenter){
        $this->model = $model;
        $this->presenter = $presenter;
    }

    private function checkEditor() {
        if (!(isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] == 'Editor')) {
            header("Location: /PW2-JuegoPreguntasYRespuestas/ControllerLogin/cerrarSesion");
            exit();
        }
    }

    public function get(){
        $this->checkEditor();
        $this->presenter->render('view/homeEditor.mustache', []);
    }

    public function gestionarPreguntas()
    {
        $this->checkEditor();
        $preguntas = $this->model->getPreguntas();
        $this->presenter->render('view/gestionarPreguntas.mustache', [
            'preguntas' => $preguntas
        ]);
    }

    public function agregarPregunta()
    {
        $this->checkEditor();
        $this->presenter->render('view/agregarPregunta.mustache');
    }

    public function guardarPregunta()
    {
        if (isset($_POST['descripcion'], $_POST['categoria'], $_POST['respuesta1'], $_POST['respuesta2'], $_POST['respuesta3'], $_POST['respuesta4'], $_POST['correcta'])) {
            $this->checkEditor();
            $descripcion = $_POST['descripcion'];
            $categoriaNombre = $_POST['categoria'];
            $respuestas = [
                $_POST['respuesta1'],
                $_POST['respuesta2'],
                $_POST['respuesta3'],
                $_POST['respuesta4']
            ];
            $correcta = $_POST['correcta'];

            $categoriaId = $this->model->getCategoriaId($categoriaNombre);


            $this->model->guardarPregunta($descripcion, $categoriaId, $respuestas, $correcta);

            header("Location: /PW2-JuegoPreguntasYRespuestas/ControllerEditor/gestionarPreguntas");
            exit();
        }
    }

    public function editarPregunta()
    {
        $this->checkEditor();
        $preguntaId = $_GET['id'];
        $pregunta = $this->model->getPreguntaById($preguntaId);

        if ($pregunta) {

            $respuestas = $this->model->getRespuestasByPreguntaId($preguntaId);

            $categorias = [
                'isHistoria' => $pregunta['categoria_nombre'] === 'Historia',
                'isCiencia' => $pregunta['categoria_nombre'] === 'Ciencia',
                'isEntretenimiento' => $pregunta['categoria_nombre'] === 'Entretenimiento',
                'isDeportes' => $pregunta['categoria_nombre'] === 'Deportes',
                'isGeografia' => $pregunta['categoria_nombre'] === 'Geografía'
            ];

            $correctas = [];
            foreach ($respuestas as $index => $respuesta) {
                $correctas["correcta" . ($index + 1)] = $respuesta['correcta'] == 1;
            }

            $this->presenter->render('view/editarPregunta.mustache', array_merge([
                'id' => $pregunta['id'],
                'descripcion' => $pregunta['descripcion'],
                'categoria' => $pregunta['categoria_nombre'],
                'respuestas' => $respuestas
            ], $categorias, $correctas));
        } else {
            header("Location: /PW2-JuegoPreguntasYRespuestas/ControllerEditor/gestionarPreguntas");
            exit();
        }
    }


    public function actualizarPregunta()
    {
        if (isset($_POST['id'], $_POST['descripcion'], $_POST['categoria'], $_POST['respuesta1'], $_POST['respuesta2'], $_POST['respuesta3'], $_POST['respuesta4'], $_POST['correcta'])) {
            $this->checkEditor();
            $id = $_POST['id'];
            $descripcion = $_POST['descripcion'];
            $categoria = $_POST['categoria'];
            $respuestas = [
                $_POST['respuesta1'],
                $_POST['respuesta2'],
                $_POST['respuesta3'],
                $_POST['respuesta4']
            ];
            $correcta = $_POST['correcta'];

            $categoriaId = $this->model->getCategoriaId($categoria);

            $this->model->actualizarPregunta($id, $descripcion, $categoriaId, $respuestas, $correcta);


            header("Location: /PW2-JuegoPreguntasYRespuestas/ControllerEditor/gestionarPreguntas");
            exit();
        } else {
            echo "Error: faltan datos en el formulario.";
        }
    }

    public function eliminarPregunta()
    {
        if (isset($_GET['id'])) {
            $this->checkEditor();
            $preguntaId = $_GET['id'];
            $this->model->eliminarPregunta($preguntaId);
            header("Location: /PW2-JuegoPreguntasYRespuestas/ControllerEditor/gestionarPreguntas");
            exit();
        } else {
            echo "Error: No se especificó un ID válido para eliminar la pregunta.";
        }
    }

    //REPORTE
    public function verPreguntasReportadas() {
        $reportes = $this->model->obtenerReportes();
        include 'view/reportarForm.mustache';
    }
    public function aceptarReporte($id) {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $this->model->eliminarPreguntaYReporte($id);
            header("Location: /PW2-JuegoPreguntasYRespuestas/ControllerEditor/verPreguntasReportadas");
            exit();
        }else {
            header("Location: /PW2-JuegoPreguntasYRespuestas/ControllerEditor/reportes?error=true");
            exit();
        }
    }

    public function rechazarReporte() {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $this->model->rechazarReporte($id);
            header("Location: /PW2-JuegoPreguntasYRespuestas/ControllerEditor/verPreguntasReportadas");
            exit();
        } else {
            header("Location: /PW2-JuegoPreguntasYRespuestas/ControllerEditor/reportes?error=true");
            exit();
        }
    }

    public function verPreguntasSugeridas() {
        $preguntas = $this->model->obtenerPreguntasSugeridas();

        foreach ($preguntas as &$pregunta) {
            $pregunta['respuestas'] = $this->model->obtenerRespuestasPorPreguntaSugeridaId($pregunta['id']);
        }

        $this->presenter->render('view/mostrarSugerencias.mustache', [
            'preguntas' => $preguntas
        ]);
    }

    public function aceptarPreguntaSugerida()
    {
        $this->checkEditor();

        if (isset($_POST['id'])) {
            $preguntaSugeridaId = $_POST['id'];

            // Obtener la pregunta sugerida y sus respuestas
            $preguntaSugerida = $this->model->getPreguntaSugeridaById($preguntaSugeridaId);
            $respuestasSugeridas = $this->model->getRespuestasSugeridasByPreguntaId($preguntaSugeridaId);

            if ($preguntaSugerida && $respuestasSugeridas) {
                // Guardar la pregunta en la tabla `pregunta`
                $categoriaId = $this->model->getCategoriaId($preguntaSugerida['categoria']);
                $preguntaId = $this->model->guardarPregunta(
                    $preguntaSugerida['descripcion'],
                    $categoriaId,
                    array_column($respuestasSugeridas, 'descripcion'),
                    array_search(true, array_column($respuestasSugeridas, 'es_correcta')) + 1
                );

                // Eliminar la sugerencia de pregunta tras ser aceptada
                $this->model->eliminarPreguntaSugerida($preguntaSugeridaId);

                header("Location: /PW2-JuegoPreguntasYRespuestas/ControllerEditor/verPreguntasSugeridas?success=true");
                exit();
            }
        }

        header("Location: /PW2-JuegoPreguntasYRespuestas/ControllerEditor/verPreguntasSugeridas?error=true");
        exit();
    }






}
