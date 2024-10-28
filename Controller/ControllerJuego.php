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

        $_SESSION['tiempo_limite'] = time() + 30; // 30 segundos desde ahora
        $_SESSION['tiempo_restante'] = 30;
        $this->mostrarPregunta();
    }


    public function mostrarPregunta()
    {
        // Intenta obtener la pregunta actual de la sesión
    if (isset($_SESSION['pregunta_actual'])) {
        $pregunta = $_SESSION['pregunta_actual'];
    } else {

        $pregunta = $this->model->getPreguntaAleatoria();
        $_SESSION['pregunta_actual'] = $pregunta; // Guarda la pregunta en la sesión
    }
        $tiempoRestante = $_SESSION['tiempo_limite'] - time();

        $_SESSION['tiempo_restante'] = max($tiempoRestante, 0);

        if ($pregunta) {
            $respuestas = $this->model->getRespuestasPorPregunta($pregunta['id']);

            $this->presenter->render('view/pregunta.mustache', [
                'pregunta' => $pregunta,
                'respuestas' => $respuestas,
                'categoriaColor' => $pregunta['color'],
                'puntuacion' => $this->puntuacion,
                'tiempoRestante' => max($tiempoRestante, 0)
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

        // Calcular el tiempo restante
        $tiempoRestante = $_SESSION['tiempo_limite'] - time();
        $_SESSION['tiempo_restante'] = max($tiempoRestante, 0);

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

            unset($_SESSION['pregunta_actual']);

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

