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
        $ultimoId = $this->model->getUltimoIdPartida();
        $nombrePartida = "Partida " . ($ultimoId + 1);
        $codigo = rand(1000, 9999);

        $partidaId = $this->model->crearPartida($nombrePartida, 0, $codigo, $usuarioId);

        $_SESSION['partida_id'] = $partidaId;
        $_SESSION['puntuacion'] = 0;
        $_SESSION['preguntas_mostradas'] = []; // Reiniciar preguntas mostradas
        $this->puntuacion = 0;

        $_SESSION['tiempo_limite'] = time() + 30;
        $_SESSION['tiempo_restante'] = 30;
        $this->mostrarPregunta();
    }


    public function mostrarPregunta()
    {
        if (isset($_SESSION['pregunta_actual']) && isset($_SESSION['pregunta_token'])) {
            $pregunta = $_SESSION['pregunta_actual'];
            $respuestas = $this->model->getRespuestasPorPregunta($pregunta['id']);
        } else {

            $pregunta = $this->model->getPreguntaAleatoria($_SESSION['preguntas_mostradas']);
            if ($pregunta) {
                $_SESSION['pregunta_actual'] = $pregunta;
                $_SESSION['pregunta_token'] = bin2hex(random_bytes(16));
                $_SESSION['preguntas_mostradas'][] = $pregunta['id'];
                $_SESSION['tiempo_limite'] = time() + 30;
            }
            $respuestas = $this->model->getRespuestasPorPregunta($pregunta['id']);
        }
        $this->presenter->render('view/pregunta.mustache', [
            'pregunta' => $pregunta,
            'respuestas' => $respuestas,
            'categoriaColor' => $pregunta['color'],
            'puntuacion' => $this->puntuacion,
            'tiempoRestante' => $_SESSION['tiempo_limite'] - time(),
            'pregunta_token' => $_SESSION['pregunta_token']
        ]);
    }



    public function verificarRespuesta()
    {
        if (!isset($_SESSION['pregunta_token']) || $_SESSION['pregunta_token'] !== $_POST['pregunta_token']) {
            $this->finalizarPorRecarga();
            return;
        }

        $preguntaId = $_POST['pregunta_id'];
        $letraSeleccionada = $_POST['letraSeleccionada'];
        $partidaId = $_SESSION['partida_id'];

        // Actualizar el tiempo restante en la sesión
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
            unset($_SESSION['pregunta_token']);


            $this->mostrarPregunta();
        } else {

            $this->presenter->render('view/resultado.mustache', [
                'correcta' => false,
                'puntuacion' => $this->puntuacion
            ]);
        }
    }



    private function finalizarPorRecarga()
    {
        // Acción que realiza cuando detecta recarga o retroceso
        $this->presenter->render('view/resultado.mustache', [
            'correcta' => false,
            'mensaje' => "Has perdido por refrescar la página o intentar retroceder.",
            'puntuacion' => $this->puntuacion
        ]);
    }

    public function reiniciarJuego()
    {
        $_SESSION['puntuacion'] = 0;
        $this->puntuacion = 0;
        $this->iniciarPartida();
    }
}



