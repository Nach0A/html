<?php
session_start();            // Inicia o continúa la sesión del usuario
require 'db.php';           // Conecta a la base de datos (aunque no se usa en esta página, se puede dejar preparado)

$user = $_SESSION['usuario'] ?? null;  // Obtiene el nombre del usuario desde la sesión (si está logueado)

// Si no hay usuario en sesión, redirige al login
if (!$user) {
    header("Location: login.php");
    exit;                   // Termina la ejecución del script
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Zentryx</title>

    <!-- Ícono en la pestaña -->
    <link rel="icon" href="imagenes/logo.jpg" type="image/jpeg">

    <!-- Bootstrap para estilos rápidos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tus estilos personalizados -->
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

<!-- NAVBAR Bootstrap igual en todas las páginas -->
<nav class="navbar navbar-expand-lg shadow-sm py-3" style="background-color: rgb(20,20,20);">
    <div class="container-fluid">
        
        <!-- Logo y nombre de Zentryx que lleva al inicio -->
        <a class="navbar-brand fw-bold fs-4 text-white" href="inicio.php#inicio">
            <img src="../navbar/imagenes/logo.jpg" width="30" height="30">
            &nbsp;Zentryx
        </a>

        <!-- Botón hamburguesa en móviles -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menú de navegación -->
        <div class="collapse navbar-collapse" id="navbarNav">
            
            <!-- Enlaces principales -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="inicio.php#inicio">Inicio</a>
                </li>
                <li class="nav-item separator"></li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="inicio.php#juegos">Juegos</a>
                </li>
            </ul>

            <!-- Icono de usuario y menú desplegable -->
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle d-flex align-items-center text-white" id="userDropdown" data-bs-toggle="dropdown">
                        <img src="../navbar/imagenes/usuario2.jpg" class="user-avatar shadow-sm">
                    </a>

                    <!-- Menú desplegable con opciones -->
                    <ul class="dropdown-menu dropdown-menu-end fade-menu">
                        <li>
                            <a class="dropdown-item" href="#">
                                Perfil (<?php echo htmlspecialchars($user); ?>)
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="logout.php">Cerrar sesión</a>
                        </li>
                    </ul>
                </li>
            </ul>

        </div>
    </div>
</nav>

<!-- Contenido principal -->
<div class="container mt-5">
    <h1 class="text-center">Bienvenido, <?php echo htmlspecialchars($user); ?>, a Zentryx</h1>
    <p class="text-center">Explora nuestros juegos y contenidos exclusivos.</p>
</div>

<!-- Bootstrap JS para el funcionamiento del navbar y componentes -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
