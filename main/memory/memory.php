<?php
// memory.php — Control de sesión y protección de la página
session_start();
require '../pagina-principal/conexion_BD.php';
if(!isset($_SESSION['nombre'])) {
    header("Location: ../pagina-principal/login.html.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Memory - Zentryx</title>
  <link rel="icon" href="../navbar/imagenes/logo.jpg" type="image/jpeg">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Estilos del juego -->
  <link rel="stylesheet" href="./memory.css" />
  <!-- Estilos generales del navbar -->
  <link rel="stylesheet" href="../navbar/style.css" />
</head>
<body>
  <!-- PRELOADER -->
  <div id="preloader">
    <img src="../navbar/imagenes/logo.jpg" alt="Logo Zentryx" id="preloader-logo">
  </div>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg shadow-sm py-3" style="background-color: rgb(20,20,20);">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold fs-4 text-white" href="../pagina-principal/inicio.html.php#inicio">
        <img src="../navbar/imagenes/logo.jpg" width="30" height="30" class="d-inline-block align-text-top">
        &nbsp;Zentryx
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <!-- Links Inicio / Juegos -->
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link text-white" href="../pagina-principal/inicio.html.php#inicio">Inicio</a>
          </li>
          <li class="nav-item separator"></li>
          <li class="nav-item">
            <a class="nav-link text-white" href="../pagina-principal/inicio.html.php#juegos">Juegos</a>
          </li>
        </ul>
        <!-- Dropdown Perfil -->
        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle d-flex align-items-center text-white"
               id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="../navbar/imagenes/usuario2.jpg" alt="Avatar" class="user-avatar shadow-sm">
            </a>
            <ul class="dropdown-menu dropdown-menu-end fade-menu">
              <li>
                <a class="dropdown-item" href="#">
                  Perfil (<?php echo htmlspecialchars($_SESSION['nombre']); ?>)
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item" href="../pagina-principal/login.html.php">
                  Cerrar sesión
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- HEADER: título y estadísticas -->
  <header>
    <h1>Memory</h1>
    <div class="scoreboard">
      <span id="matches"     class="score-item">Pares: 0</span>
      <span id="intentos"    class="score-item">Intentos: 0</span>
      <span id="timer"       class="score-item">Tiempo: 00:00</span>
      <span id="mejor-tiempo" class="score-item">Mejor tiempo: --:--</span>
    </div>
    <button id="restart-btn">Reiniciar</button>
  </header>

  <!-- TABLERO -->
  <main>
    <section class="game-board"></section>
  </main>

  <!-- MODAL DE VICTORIA -->
  <div id="win-modal" class="modal hidden">
    <div class="modal-content">
      <h2>¡Has ganado!</h2>
      <p>Tiempo: <span id="final-time"></span></p>
      <button id="play-again-btn">Jugar de nuevo</button>
      <button id="home-btn">Volver al inicio</button>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Lógica del juego -->
  <script src="./memory.js"></script>
  <script>
    // Ocultar preloader al cargar
    window.addEventListener("load", () => {
      const pre = document.getElementById("preloader");
      pre.style.opacity = "0";
      pre.style.visibility = "hidden";
      pre.style.pointerEvents = "none";
    });

    // Volver al inicio desde el modal
    document.getElementById('home-btn').addEventListener('click', () => {
      window.location.href = '../pagina-principal/inicio.html.php#inicio';
    });
  </script>
</body>
</html>
