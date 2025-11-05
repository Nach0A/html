<?php
session_start();
require_once "Conexion_BD.php";

if (!isset($_POST['entrada'], $_POST['nueva_contra'], $_SESSION['correo_ing'])) {
    echo '<script>alert("Solicitud inválida."); window.location.href="recuperar.php";</script>';
    exit();
}

$codigo_ingresado = trim($_POST['entrada']);
$nueva_pass       = trim($_POST['nueva_contra']);

if (!isset($_SESSION['codigo'])) {
    echo '<script>alert("No hay un código activo. Solicitá uno nuevo."); window.location.href="recuperar.php";</script>';
    exit();
}

// Expiración por sesión
if (isset($_SESSION['codigo_expires_at']) && time() > $_SESSION['codigo_expires_at']) {
    echo '<script>alert("El código expiró. Solicitá uno nuevo."); window.location.href="recuperar.php";</script>';
    exit();
}

if ($codigo_ingresado !== $_SESSION['codigo']) {
    echo '<script>alert("Código incorrecto. Inténtalo de nuevo."); window.location.href="recuperacion.php";</script>';
    exit();
}

// Código correcto: cambiamos contraseña
$bd = new conexion_BD();
$correo = $_SESSION['correo_ing'];
$bd->cambiarContrasenia($correo, $nueva_pass);

// Limpiar datos de recuperación en sesión
unset($_SESSION['codigo'], $_SESSION['codigo_expires_at'], $_SESSION['last_code_sent_at'],
      $_SESSION['resend_wait_seconds'], $_SESSION['resend_count'], $_SESSION['resend_limit']);

echo '<script>alert("Tu contraseña fue cambiada correctamente."); window.location.href="login.php";</script>';
exit();
