<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header("Location: Inicio.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Zentryx</title>

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
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link text-white" href="login.php">Inicio</a></li>
                <li class="nav-item separator"></li>
                <li class="nav-item"><a class="nav-link text-white" href="login.php">Juegos</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- FORMULARIO -->
<div class="container mt-5" id="form-container">
    <div class="card p-4 shadow" id="loginForm">
        <div class="text-center mb-4">
            <button id="btnLogin" class="btn btn-outline-light me-2">Iniciar sesión</button>
            <button id="btnRegister" class="btn btn-outline-light">Registrarse</button>
        </div>

        <!-- LOGIN CON MAIL -->
        <form id="loginSection" action="verifica.php" method="post">
            <input type="text" name="input" class="form-control mb-3" placeholder="Usuario o correo" required>
            <input type="password" name="contrasenia" class="form-control mb-3" placeholder="Contraseña" required>
            <input type="hidden" name="ini" value="1">
            <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
        </form>

        <!-- REGISTRO -->
        <form id="registerSection" action="verifica.php" method="post" style="display: none;">
            <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre de usuario" required>
            <input type="email" name="gmail" class="form-control mb-2" placeholder="Correo electrónico" required>
            <input type="password" name="contrasenia" class="form-control mb-2" placeholder ="Contraseña" required>
            <input type="hidden" name="ini" value="0">
            <button type="submit" class="btn btn-secondary w-100">Registrarse</button>
        </form>
    </div>
</div>


<!-- Bootstrap JS (funcionamiento del navbar y componentes) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script para ocultar el preloader al cargar la página -->
<script>
window.addEventListener("load", () => {
    const pre = document.getElementById("preloader");
    pre.style.opacity = "0";
    pre.style.visibility = "hidden";
    pre.style.pointerEvents = "none";
});
</script>
<script src="scriptLogin.js"></script>
</body>
</html>
