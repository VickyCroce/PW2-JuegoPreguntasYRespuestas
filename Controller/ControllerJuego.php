<?php

namespace Controller;
class ControllerJuego
{
    private $model;
    private $presenter;
    private $puntuacion;

    public function __construct($model, $presenter)
    {
        $this->model = $model;
        $this->presenter = $presenter;

        if (!isset($_SESSION['puntuacion'])) {
            $_SESSION['puntuacion'] = 0;
        }
        $this->puntuacion = $_SESSION['puntuacion'];
    }

    public function iniciarPartida()
    {
        $usuarioId = $_SESSION['usuario']['id'];

        // Obtener el nombre de la partida basado en el último ID
        $ultimoId = $this->model->getUltimoIdPartida();
        $nombrePartida = "Partida " . ($ultimoId + 1);
        $codigo = rand(1000, 9999);

        $partidaId = $this->model->crearPartida($nombrePartida, 0, $codigo, $usuarioId);


        $_SESSION['partida_id'] = $partidaId;

        $_SESSION['puntuacion'] = 0;
        $this->puntuacion = 0;


        $this->mostrarPregunta();
    }


    public function mostrarPregunta()
    {
        $pregunta = $this->model->getPreguntaAleatoria();
        if ($pregunta) {
            $respuestas = $this->model->getRespuestasPorPregunta($pregunta['id']);

            $this->presenter->render('view/pregunta.mustache', [
                'pregunta' => $pregunta,
                'respuestas' => $respuestas,
                'categoriaColor' => $pregunta['color'],
                'puntuacion' => $this->puntuacion
            ]);
        } else {
            echo "No se pudo obtener una pregunta.";
        }
    }

    public function verificarRespuesta()
    {
        $preguntaId = $_POST['pregunta_id'];
        $letraSeleccionada = $_POST['letraSeleccionada'];
        $partidaId = $_SESSION['partida_id'];

        $respuestas = $this->model->getRespuestasPorPregunta($preguntaId);
        $correcta = false;

        foreach ($respuestas as $respuesta) {
            if ($respuesta['letra'] === $letraSeleccionada) {
                $correcta = $respuesta['esCorreto'];
            }
        }

        if ($correcta) {
            $this->puntuacion++;
            $_SESSION['puntuacion'] = $this->puntuacion;

            $this->model->actualizarPuntosPartida($partidaId, $this->puntuacion);

            $this->mostrarPregunta();
        } else {
            $this->presenter->render('view/resultado.mustache', [
                'correcta' => false,
                'puntuacion' => $this->puntuacion
            ]);
        }
    }


    public function reiniciarJuego()
    {
        $_SESSION['puntuacion'] = 0;
        $this->puntuacion = 0;
        $this->iniciarPartida();
    }
}

