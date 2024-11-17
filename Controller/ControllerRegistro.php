<?php

namespace Controller;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/phpqrcode/qrlib.php';

class ControllerRegistro
{
    private $modelo;
    private $presenter;

    public function __construct($Modelo, $Presentador)
    {
        $this->modelo = $Modelo;
        $this->presenter = $Presentador;
    }

    // Renderiza la página de registro
    public function get()
    {
        $this->presenter->render("view/registro.mustache");
    }

    // Maneja el registro de un nuevo usuario
    public function registrarUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = $this->sanitizarEntradaUsuario($_POST);
            $errores = $this->validarRegistro($datos);

            if (!empty($errores)) {
                $this->presenter->render("view/registro.mustache", ['errores' => $errores]);
                return;
            }

            $datos['foto_perfil'] = $this->manejarFotoPerfil($_FILES['foto_perfil'] ?? null);
            $datos['password'] = $datos['password']; // Sin aplicar hash (puedes modificar esto)

            if ($this->modelo->guardarUsuario($datos)) {
                $_SESSION['usuario_temporal'] = $datos;
                if ($this->enviarCorreoVerificacion($datos['email'])) {
                    error_log('Correo enviado exitosamente');
                    $this->presenter->render("view/registro.mustache", [
                        'exito' => 'Correo de verificación enviado. Revisa tu bandeja de entrada.',
                    ]);
                } else {
                    error_log('Error al enviar el correo');
                    $this->presenter->render("view/registro.mustache", [
                        'errores' => ['Error al enviar el correo de verificación.'],
                    ]);
                }
            } else {
                $this->presenter->render("view/registro.mustache", ['errores' => ['Error al registrar el usuario.']]);
            }
        }
    }

    // Verifica el correo electrónico del usuario
    public function verificarCorreo()
    {
        $correo = $_GET['email'] ?? null;

        if (!$correo) {
            $this->presenter->render("view/login.mustache", [
                'error' => 'Correo no proporcionado para la verificación.'
            ]);
            return;
        }

        $usuario = $this->modelo->buscarUsuarioPorCorreo($correo);

        if (!$usuario || $usuario['verificado']) {
            $this->presenter->render("view/login.mustache", [
                'error' => 'El usuario no existe o ya ha sido verificado.'
            ]);
            return;
        }

        if ($this->modelo->verificarUsuario($correo)) {
            $this->generarCodigoQR($usuario['id']);
            unset($_SESSION['usuario_temporal']);
            $this->presenter->render("view/login.mustache", [
                'error' => 'Registro exitoso. Ya puedes iniciar sesión.'
            ]);
        } else {
            $this->presenter->render("view/login.mustache", [
                'error' => 'No se pudo verificar el usuario.'
            ]);
        }
    }


    // Sanitiza y valida la entrada del usuario
    private function sanitizarEntradaUsuario($entrada)
    {
        return array_map(fn($valor) => htmlspecialchars(trim($valor), ENT_QUOTES, 'UTF-8'), $entrada);
    }

    // Valida los datos del registro
    private function validarRegistro($datos)
    {
        $errores = [];

        if ($this->modelo->buscarUsuarioPorCorreo($datos['email'])) {
            $errores[] = 'El correo electrónico ya está registrado.';
        }

        if ($this->modelo->buscarUsuarioPorNombreUsuario($datos['nombre_usuario'])) {
            $errores[] = 'El nombre de usuario ya está registrado.';
        }

        return $errores;
    }

    // Maneja la foto de perfil
    private function manejarFotoPerfil($archivo)
    {
        $urlDefecto = '/PW2-JuegoPreguntasYRespuestas/public/img/fotoPerfil/fotoPerfil.jpg';

        if (!$archivo || $archivo['error'] !== UPLOAD_ERR_OK) {
            return $urlDefecto;
        }

        $directorioSubida = $_SERVER['DOCUMENT_ROOT'] . '/PW2-JuegoPreguntasYRespuestas/public/img/fotoPerfil/';
        if (!is_dir($directorioSubida)) {
            mkdir($directorioSubida, 0755, true);
        }

        $nombreArchivo = uniqid() . '.' . pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $rutaArchivo = $directorioSubida . $nombreArchivo;

        return move_uploaded_file($archivo['tmp_name'], $rutaArchivo)
            ? '/PW2-JuegoPreguntasYRespuestas/public/img/fotoPerfil/' . $nombreArchivo
            : $urlDefecto;
    }

    // Genera el código QR
    private function generarCodigoQR($idUsuario)
    {
        $url = "http://localhost/PW2-JuegoPreguntasYRespuestas/ControllerPerfil/showPerfilAjeno/?id=$idUsuario";
        $rutaQr = $_SERVER['DOCUMENT_ROOT'] . '/PW2-JuegoPreguntasYRespuestas/public/img/Qr/' . $idUsuario . '.png';

        if (!is_dir(dirname($rutaQr))) {
            mkdir(dirname($rutaQr), 0755, true);
        }

        \QRcode::png($url, $rutaQr, QR_ECLEVEL_H, 3);
        return $rutaQr;
    }

    // Envía el correo de verificación
    private function enviarCorreoVerificacion($correo)
    {
        $asunto = "Verificación de correo electrónico";
        $cuerpo = "<h1>Por favor, verifica tu correo:</h1>";
        $cuerpo .= "<a href='http://localhost/PW2-JuegoPreguntasYRespuestas/ControllerRegistro/verificarCorreo?email=" . urlencode($correo) . "'>Aquí</a>";

        return $this->enviarCorreo($correo, $asunto, $cuerpo);
    }

    // Configuración y envío del correo
    private function enviarCorreo($destinatario, $asunto, $cuerpo)
    {
        require 'vendor/autoload.php';

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'preguntadospw2@gmail.com';
            $mail->Password = 'hbos sinb unvs vvtu';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('preguntadospw2@gmail.com', 'Preguntados');
            $mail->addAddress($destinatario);

            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body = $cuerpo;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Error de PHPMailer: {$mail->ErrorInfo}");
            return false;
        }
    }
}


