<?php
session_start();

// Si ya hay sesión, redirigir al inicio
if (isset($_SESSION['usuario'])) {
    header("Location: Inicio.php");
    exit();
}

$BASE_URL = "/PlataformaLudica/main_final/";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña - Zentryx</title>

    <!-- Ícono -->
    <link rel="icon" href="../navbar/imagenes/logo.jpg" type="image/jpeg">
    
    <!-- Bootstrap y fuente -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet">

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

<!-- Preloader -->
<div id="preloader">
    <img src="../navbar/imagenes/logo.jpg" alt="Logo Zentryx" id="preloader-logo">
</div>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg shadow-sm py-3" style="background-color: rgb(20,20,20);">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold fs-4 text-white" href="login.php">
            <img src="../navbar/imagenes/logo.jpg" width="30" height="30" class="d-inline-block align-text-top">
            &nbsp;Zentryx
        </a>
    </div>
</nav>

<!-- CONTENEDOR PRINCIPAL -->
<div class="login-wrapper">
    <!-- LADO IZQUIERDO - EFECTO NEÓN -->
    <div class="login-left">
        <div class="neon-circle"></div>
        <div class="neon-glow"></div>
        <h1 class="neon-title">ZENTRYX</h1>
        <p class="login-slogan">Restablece tu acceso al futuro</p>
    </div>

    <!-- LADO DERECHO - FORMULARIO -->
    <div class="login-right fade-in">
        <div class="card p-4" id="recoverForm">
            <h4 class="text-center mb-3">Recuperar contraseña</h4>
            <p class="text-center text-white-50 mb-4" style="font-size: 0.9rem;">
                Ingresa tu correo electrónico o nombre de usuario y te ayudaremos a recuperar tu cuenta.
            </p>

            <form action="procesar_recuperacion.php" method="post">
                <input type="text" name="usuario_o_email" class="textwhite" placeholder="Usuario o correo electrónico" required>
                <button type="submit" class="btn btn-primary w-100 mt-3">Enviar enlace de recuperación</button>
            </form>

            <div class="text-center mt-4">
                <a href="login.php" class="forgot-password-link">Volver al inicio de sesión</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

<!-- Preloader Script -->
<script>
window.addEventListener("load", () => {
    const pre = document.getElementById("preloader");
    pre.style.opacity = "0";
    pre.style.visibility = "hidden";
    pre.style.pointerEvents = "none";
});
</script>

</body>
</html>
