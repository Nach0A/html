<?php
session_start();

// Si ya hay sesión, vamos a Inicio
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
        </div>
    </nav>

    <!-- LOGIN WRAPPER -->
    <div class="login-wrapper">
        <!-- LADO IZQUIERDO - EFECTO NEÓN -->
        <div class="login-left">
            <div class="neon-circle"></div>
            <div class="neon-glow"></div>
            <h1 class="neon-title">ZENTRYX</h1>
            <p class="login-slogan">Sumérgete en la experiencia del futuro</p>
        </div>

        <!-- LADO DERECHO - FORMULARIO -->
        <div class="login-right fade-in">
            <div class="card p-4" id="loginForm">
                <div class="text-center mb-4 switch-buttons">
                    <button id="btnLogin" class="btn btn-outline-light me-2 active">Iniciar sesión</button>
                    <button id="btnRegister" class="btn btn-outline-light">Registrarse</button>
                </div>

                <!-- LOGIN -->
                <form id="loginSection" action="verifica.php" method="post">
                    <input type="text" name="input" class="textwhite" placeholder="Usuario o correo" required>
                    <input type="password" name="contrasenia" class="textwhite" placeholder="Contraseña" required>
                    <input type="hidden" name="ini" value="1">
                    <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
                    <div class="text-center mt-3">
                        <a href="recuperar_contrasena.php" class="forgot-password-link">¿Olvidaste tu contraseña?</a>
                    </div>
                </form>

                <!-- REGISTRO -->
                <form id="registerSection" action="verifica.php" method="post" style="display: none;">
                    <input type="text" name="nombre" class="textwhite" placeholder="Nombre de usuario" required>
                    <input type="email" name="gmail" class="textwhite" placeholder="Correo electrónico" required>
                    <input type="password" name="contrasenia" class="textwhite" placeholder="Contraseña" required>
                    <input type="hidden" name="ini" value="0">
                    <button type="submit" class="btn btn-secondary w-100">Registrarse</button>
                </form>
            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>