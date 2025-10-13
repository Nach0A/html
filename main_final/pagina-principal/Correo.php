<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require "autoload.php";
class Correo
{
    private $host;
    private $nombre;
    private $direccion;
    private $contenido;
    private $asunto;
    private $mail;

    public function __construct($host, $puerto, $nombre, $direccion, $contenido, $asunto)
    {
        $this->host = $host;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
        $this->contenido = $contenido;
        $this->asunto = $asunto;
        $this->mail = new PHPMailer(true);
    }

    public function setHost($host)
    {
        $this->host = $host;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;
    }
    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;
    }
    public function enviarCorreo()
    {
        try {
            $this->mail->isSMTP();
            $this->mail->Host = $this->host;
            $this->mail->SMTPAuth = false;
            $this->mail->SMTPAutoTLS = false; // Para que no use TLS por defecto, porque el ServidOR no lo soporta :/
            $this->mail->Port = 587;

            $this->mail->setFrom('zentryx@correos.local', 'Zentryx');
            $this->mail->addAddress($this->direccion, $this->nombre);
            $this->mail->isHTML(true);
            $this->mail->AltBody = 'Este es el cuerpo en texto plano para clientes de correo que no soportan HTML';
            $this->mail->Subject = $this->asunto;
            $this->mail->Body    = $this->contenido;
            $this->mail->send();
            echo 'Correo enviado correctamente';
        } catch (Exception $e) {
            echo "Error al enviar el correo: {$this->mail->ErrorInfo}";
        }
    }
}