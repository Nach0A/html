<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "vendor/autoload.php";
class Correo
{
    private $host;
    private $puerto;
    private $nombre;
    private $direccion;
    private $contenido;
    private $asunto;
    private $mail;

    public function __construct()
    {
        $this->host = "192.168.20.215";
        $this->puerto = 25;
        $this->nombre = $_SESSION['usuario'];
        $this->direccion = $_SESSION['correo_ing'];
        $this->contenido = 0;
        $this->asunto = "Recuperación de contraseña";
        $this->mail = new PHPMailer(true);
    }

    public function setHost($host)
    {
        $this->host = $host;
    }
    public function setPuerto($puerto)
    {
        $this->puerto = $puerto;
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
            $this->mail->Port = $this->puerto;
            $this->contenido = rand(100000, 999999); // Generar un código de 6 dígitos
            $this->mail->setFrom('zentryx@correos.local', 'Zentryx');
            $this->mail->addAddress($this->direccion, $this->nombre);
            $this->mail->isHTML(true);
            $this->mail->AltBody = "Tu código de recuperación de contraseña: " . $this->contenido;
            $this->mail->Subject = $this->asunto;
            $this->mail->Body    = $this->contenido;
            $this->mail->send();
            $_SESSION['codigo'] = $this->contenido;
        } catch (Exception $e) {
            echo "Error al enviar el correo: {$this->mail->ErrorInfo}";
        }
    }
}