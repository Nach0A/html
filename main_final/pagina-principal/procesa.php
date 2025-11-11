<?php
session_start();
require_once "Correo.php";
require_once "Conexion_BD.php"; // Lo usás solo para validar que el correo exista y luego cambiar la contraseña

$bd = new conexion_BD();

if (!isset($_POST['gmail']) || empty($_POST['gmail'])) {
    echo '<script>alert("Falta el correo."); window.location.href="login.php";</script>';
    exit();
}

$correo = $_POST['gmail'];

if (!$bd->existeCorreo($correo)) {
    echo '<script>alert("El correo no está registrado."); window.location.href="login.php";</script>';
    exit();
}

// Guardamos datos base en sesión
$_SESSION['correo_ing'] = $correo;
// Opcional: podés no setear usuario aquí si no lo necesitás para el envío
$_SESSION['usuario'] = $bd->getNombreUsuario($correo);

// Enviar primer código
$mail = new Correo();
$mail->enviarCorreo(); // esto setea $_SESSION['codigo']

// Config de expiración de código y cooldown de reenvío
$_SESSION['codigo_expires_at'] = time() + 10 * 60; 
$_SESSION['last_code_sent_at'] = time();
$_SESSION['resend_wait_seconds'] = 60; 
$_SESSION['resend_count'] = 0;
$_SESSION['resend_limit'] = 5; 

header("Location: recuperacion.php");
exit();
