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
        $_SESSION['preguntas_mostradas'] = [];
        $this->puntuacion = 0;

        $_SESSION['tiempo_limite'] = time() + 30;
        $_SESSION['tiempo_restante'] = 30;
        $this->mostrarPregunta();
    }


    public function mostrarPregunta()
    {
        $usuarioId = $_SESSION['usuario']['id'];
        if (isset($_SESSION['pregunta_actual'])) {
            $pregunta = $_SESSION['pregunta_actual'];
        } else {
            $preguntasMostradas = $this->model->obtenerPreguntasMostradas($usuarioId);

            $pregunta = $this->model->getPreguntaSegunDificultad($preguntasMostradas, $usuarioId);
            if ($pregunta) {
                $this->model->registrarPreguntaMostrada($usuarioId, $pregunta['id']);
                $this->model->incrementarCantidadDadasPregunta($pregunta['id']);
                $_SESSION['pregunta_actual'] = $pregunta;
                $_SESSION['pregunta_token'] = bin2hex(random_bytes(16));
                $_SESSION['tiempo_limite'] = time() + 30;
                $_SESSION['hora_mostrar_pregunta'] = (new \DateTime())->format('Y-m-d H:i:s'); // Guardar la hora, minuto y segundo en la sesión
            } else {
                $this->model->limpiarPreguntasMostradas($usuarioId);
                return $this->mostrarPregunta();
            }
        }

        $tiempoRestante = max($_SESSION['tiempo_limite'] - time(), 0);

        $respuestas = $this->model->getRespuestasPorPregunta($pregunta['id']);

        $this->presenter->render('view/pregunta.mustache', [
            'pregunta' => $pregunta,
            'respuestas' => $respuestas,
            'categoriaColor' => $pregunta['color'],
            'puntuacion' => $this->puntuacion,
            'tiempoRestante' => $tiempoRestante,
            'pregunta_token' => $_SESSION['pregunta_token']
        ]);
    }

    public function verificarRespuesta()
    {
        if (!isset($_SESSION['pregunta_token']) || $_SESSION['pregunta_token'] !== $_POST['pregunta_token']) {
            $this->finalizarPorRecarga();
            return;
        }

        unset($_SESSION['pregunta_token']);

        $preguntaId = $_POST['pregunta_id'];
        $letraSeleccionada = $_POST['letraSeleccionada'] ?? null;
        $partidaId = $_SESSION['partida_id'];

        // Obtener marca de tiempo cuando se mostró la pregunta y cuando se envió la respuesta
        $tiempoMostrarPregunta = new \DateTime($_SESSION['hora_mostrar_pregunta']);
        $tiempoEnvioRespuesta = new \DateTime();

        // Calcular la diferencia en segundos
        $interval = $tiempoMostrarPregunta->diff($tiempoEnvioRespuesta);
        $diferenciaEnSegundos = ($interval->days * 24 * 3600) + ($interval->h * 3600) + ($interval->i * 60) + $interval->s;

        if ($diferenciaEnSegundos > 30) {
            $this->finalizarPorRecarga();
            return;
        }

        $tiempoRestante = $_SESSION['tiempo_limite'] - time();
        $_SESSION['tiempo_restante'] = max($tiempoRestante, 0);

        $respuestas = $this->model->getRespuestasPorPregunta($preguntaId);
        $correcta = false;

        if (empty($letraSeleccionada)) {
            $this->model->recalcularPorcentajePregunta($preguntaId);
            $this->letraSinSeleccionar();
            return;
        }

        foreach ($respuestas as $respuesta) {
            if ($respuesta['letra'] === $letraSeleccionada) {
                $correcta = $respuesta['correcta'];
            }
        }

        if ($correcta) {
            $this->puntuacion++;
            $_SESSION['puntuacion'] = $this->puntuacion;

            $this->model->incrementarCantidadAcertadasPregunta($preguntaId);
            $this->model->recalcularPorcentajePregunta($preguntaId);
            $this->model->actualizarContadoresUsuario($_SESSION['usuario']['id'], true);
            $this->model->actualizarPuntosPartida($partidaId, $this->puntuacion);
            $this->model->actualizarRatio($_SESSION['usuario']['id']);

            unset($_SESSION['pregunta_actual']);
            unset($_SESSION['tiempo_restante']);

            $this->mostrarPregunta();
        } else {
            $this->model->actualizarContadoresUsuario($_SESSION['usuario']['id'], false);
            $this->model->actualizarRatio($_SESSION['usuario']['id']);
            $this->model->recalcularPorcentajePregunta($preguntaId);

            unset($_SESSION['pregunta_actual']);
            unset($_SESSION['tiempo_restante']);

            $this->presenter->render('view/resultado.mustache', [
                'correcta' => false,
                'puntuacion' => $this->puntuacion
            ]);
        }
    }


    private function finalizarPorRecarga()
    {
        $this->model->actualizarContadoresUsuario($_SESSION['usuario']['id'], false);
        $this->presenter->render('view/resultado.mustache', [
            'correta' => false,
            'mensaje' => "Has perdido por refrescar la página o intentar retroceder.",
            'puntuacion' => $this->puntuacion
        ]);
    }

    private function letraSinSeleccionar()
    {
        $this->model->actualizarContadoresUsuario($_SESSION['usuario']['id'], false);
        $this->presenter->render('view/resultado.mustache', [
            'correta' => false,
            'mensaje' => "debes seleccionar una respuesta",
            'puntuacion' => $this->puntuacion
        ]);
    }

    public function reiniciarJuego()
    {
        $_SESSION['puntuacion'] = 0;
        $this->puntuacion = 0;
        $_SESSION['pregunta_token'] = bin2hex(random_bytes(16));
        $this->iniciarPartida();
    }

}



