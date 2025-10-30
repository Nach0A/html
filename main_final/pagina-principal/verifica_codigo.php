<?php
require_once "Conexion_BD.php";
session_start();
$bd = new conexion_BD();
if (trim($_POST['entrada']) === trim($_SESSION['codigo'])) {
    $_SESSION['usuario'] = $bd->getNombrePorCorreo($_SESSION['correo_ing']); ;
    $bd->cambiarContrasenia($_SESSION['correo_ing'], $_POST['nueva_contra']);
    $bd->cerrarConexion();
    echo '<script>alert("Tu contraseña fue cambiada."); window.location.href="logout.php";</script>';
    exit();
} else {
    echo '<script>alert("Código incorrecto. Inténtalo de nuevo."); window.location.href="recuperacion.php";</script>';
    exit();
}
?>