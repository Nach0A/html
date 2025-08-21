<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require "autoload.php";
$mail = new PHPMailer(true); 
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Usamos el SMTP de gmail
    $mail->SMTPAuth = true;
    $mail->Username = 'zentryx.correos@gmail.com';
    $mail->Password = 'xsbpenuywjcmmuee';
        //aws contraseña Zentryx=1
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('zentryx.correos@gmail.com', 'Zentryx');
    $mail->addAddress('juanbau.rod2007@gmail.com', 'Juan');
    $mail->isHTML(true);
        $mail->Subject = 'Correo de prueba';
        $mail->Body    = 'Este es un correo de prueba enviado desde PHP usando PHPMailer.';
        $mail->send();
    echo 'Correo enviado correctamente';
} catch (Exception $e) {
    echo "Error al enviar el correo: {$mail->ErrorInfo}";
}