<?php


namespace Controller;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class ControllerRegistro
{

    private $modelo;
    private $presenter;

    public function __construct($Model, $presenter)
    {
        $this->modelo = $Model;
        $this->presenter = $presenter;
    }

    public function get()
    {
        $this->presenter->render("view/registro.mustache");
    }


    // Función para registrar usuario
    public function registrarUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];

            if ($this->modelo->findUserByEmail($email)) {
                return ['error' => 'El correo electrónico ya está registrado.'];
            }

            if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
                $fotoPerfil = $_FILES['foto_perfil'];


                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/PW2-JuegoPreguntasYRespuestas/public/img/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true); // Crea el directorio si no existe
                }


                $fotoPerfilName = basename(str_replace(' ', '_', $fotoPerfil['name']));
                $fotoPerfilPath = $uploadDir . $fotoPerfilName;


                if (move_uploaded_file($fotoPerfil['tmp_name'], $fotoPerfilPath)) {

                    $fotoPerfilUrl = '/PW2-JuegoPreguntasYRespuestas/public/img/' . $fotoPerfilName;
                } else {
                    echo "Error al cargar la imagen.";
                }

            } else {
                $fotoPerfilPath = null;
            }

            // Guardar el usuario con verificado = 0
            $usuarioData = [
                'nombre_completo' => $_POST['nombre_completo'],
                'anio_nacimiento' => $_POST['anio_nacimiento'],
                'sexo' => $_POST['sexo'],
                'pais' => $_POST['pais'],
                'ciudad' => $_POST['ciudad'],
                'email' => $email,
                'password' => $_POST['password'],
                'nombre_usuario' => $_POST['nombre_usuario'],
                'foto_perfil' => $fotoPerfilUrl
            ];

            if ($this->modelo->saveUser($usuarioData)) {

                $_SESSION['usuario_temporal'] = $usuarioData;

                $asunto = "Verificación de correo electrónico";
                $mensaje = "<h1>Por favor, verifica tu correo haciendo clic en este enlace:</h1>";
                $mensaje .= "<a href='http://localhost/PW2-JuegoPreguntasYRespuestas/ControllerRegistro/verificarCorreo?email=" . urlencode($email) . "'>Aquí</a>";

                if (!$this->sendMail($email, $asunto, $mensaje)) {
                    return ['error' => 'Error al enviar el correo de verificación.'];
                }
            } else {
                return ['error' => 'Error al registrar el usuario en la base de datos.'];
            }
        }
    }

    public function verificarCorreo()
    {
        if (isset($_GET['email'])) {
            $email = $_GET['email'];
            $usuario = $this->modelo->findUserByEmail($email);

            if ($usuario) {
                // Actualizamos el campo 'verificado' a 1
                if ($this->modelo->verifyUser($email)) {
                    unset($_SESSION['usuario_temporal']);
                    $this->presenter->render("view/login.mustache", ['error' => 'Registro exitoso. Ya puedes iniciar sesión.']);
                } else {
                    $this->presenter->render("view/login.mustache", ['error' => 'No se pudo verificar el usuario.']);
                }
            } else {
                $this->presenter->render("view/login.mustache", ['error' => 'El usuario no existe o ya ha sido verificado.']);
            }
        } else {
            $this->presenter->render("view/login.mustache", ['error' => 'Correo no proporcionado para la verificación.']);
        }
    }


    // Función para enviar el correo de verificación
    private function sendMail($to, $subject, $body)
    {

        require 'vendor/autoload.php';

        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Servidor SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'preguntadospw2@gmail.com'; // Tu correo SMTP
            $mail->Password = 'hbos sinb unvs vvtu'; // Tu contraseña SMTP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Destinatarios
            $mail->setFrom('preguntadospw2@gmail.com', 'preguntados');
            $mail->addAddress($to);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            // Enviar el correo
            $mail->send();
            echo 'Correo de verificación enviado. Revisa tu bandeja de entrada.';
        } catch (Exception $e) {
            echo "El mensaje no se pudo enviar. Error de PHPMailer: {$mail->ErrorInfo}";
        }
    }

}
