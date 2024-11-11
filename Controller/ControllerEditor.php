<?php

namespace Controller;

namespace Controller;

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
            header("Location: /PW2-JuegoPreguntasYRespuestas/ControllerLogin/get");
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

            // Llamar al modelo para guardar la pregunta
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
            // Obtener las respuestas relacionadas
            $respuestas = $this->model->getRespuestasByPreguntaId($preguntaId);

            $this->presenter->render('view/editarPregunta.mustache', [
                'id' => $pregunta['id'],
                'descripcion' => $pregunta['descripcion'],
                'categoria' => $pregunta['categoria_nombre'],
                'respuestas' => $respuestas
            ]);
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

            // Llamar al modelo para actualizar la pregunta
            $this->model->actualizarPregunta($id, $descripcion, $categoriaId, $respuestas, $correcta);

            // Redirigir a la gestión de preguntas
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
}