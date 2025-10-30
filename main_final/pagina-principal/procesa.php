<?php
session_start();
require_once "Correo.php";
require_once "Conexion_BD.php";
$bd = new conexion_BD();
if ($bd->existeCorreo($_POST['gmail'])) {
$_SESSION['correo_ing'] = $_POST['gmail'];
$_SESSION['usuario'] = $bd->getNombreUsuario($_SESSION['correo_ing']);
$mail = new Correo();
$mail->enviarCorreo();
header("Location: recuperacion.php");
} else {
    echo '<script>alert("El correo no está registrado."); window.location.href="login.php";</script>';
    exit();
}