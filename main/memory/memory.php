<?php
// memory.php — Control de sesión y protección de la página
session_start();
require '../pagina-principal/db.php';

$user = $_SESSION['usuario'] ?? null;
if (!$user) {
    header("Location: ../pagina-principal/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Memory - Zentryx</title>
    <link rel="icon" href="../navbar/imagenes/logo.jpg" type="img/jpeg" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="./memory.css" />
    <link rel="stylesheet" href="../navbar/navbar.css" />
</head>

<body>
    <!-- ======== PRELOADER ======== -->
    <div id="preloader">
        <img src="../navbar/imagenes/logo.jpg" alt="Logo Zentryx" id="preloader-logo">
    </div>
    <!-- ======= FIN PRELOADER ===== -->
    
    <!-- ======== NAVBAR ======== -->
    <nav class="navbar navbar-expand-lg shadow-sm py-3">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold fs-4" href="../pagina-principal/Inicio.php#inicio">
                <img src="../navbar/imagenes/logo.jpg" alt="Logo" width="30" height="30"
                    class="d-inline-block align-text-top">
                &nbsp;Zentryx
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <!-- Botón “Inicio” -->
                    <li class="nav-item">
                        <a class="nav-link" href="../pagina-principal/Inicio.php#inicio" id="linkInicio">Inicio</a>
                    </li>
                    <li class="nav-item separator"></li>
                    <!-- Botón “Juegos” -->
                    <li class="nav-item">
                        <a class="nav-link" href="../pagina-principal/Inicio.php#juegos" id="linkJuegos">Juegos</a>
                    </li>
                </ul>

                <!-- PERFIL -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" id="userDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="../navbar/imagenes/usuario2.jpg" alt="User" class="user-avatar shadow-sm" />
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end fade-menu">
                            <li>
                                <a class="dropdown-item" href="#">
                                    Perfil (<?php echo htmlspecialchars($user); ?>)
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="../pagina-principal/logout.php">
                                    Cerrar sesión
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- CONTENIDO GENERAL (sin login) -->
        <div class="Contenido" id="Contenido">
            <div id="mainContent" class="container mt-5" style="display: none;"></div>
            <div class="container mt-5 position-relative" id="juegosContent" style="display: none;"></div>
        </div>
    <!-- FIN NAVBAR -->


    <!-- header ==> h1, estadisticas y boton reiniciar -->
    <header>
        <div class="title-box">
            <h1>Memory</h1>
            <div class="game-desc">
                <h3>Memory es un juego de cartas por parejas:<br>
                -Se colocan todas boca abajo y por turnos se giran dos cartas.<br>
                -Si coinciden, se quedan dadas vuelta.<br>
                -Si no coinciden, se vuelven a voltear.<br>
                -Se gana cuando se tiene todas las parejas.</h3>
            </div>
        </div>
        <div class="scoreboard">
            <span id="matches" class="score-item">Pares: 0</span>
            <span id="intenos" class="score-item">Intentos: 0</span>
            <span id="timer" class="score-item">Tiempo: 00:00</span>
            <!-- cuando este la base de datos llamar los datos del usuario y poner su mejor tiempo -->
            <span id="mejor-tiempo" class="score-item">Mejor tiempo: --:--</span>
        </div>
        <button id="restart-btn">Reiniciar</button>
    </header>
    <!-- fin header -->


    <!-- area de juego -->
    <main>
        <section class="game-board">
        <!-- se generarán 3 columnas × 8 filas de cartas -->
        </section>
    </main>
    <!-- fin area de juego -->


    <!-- Popup al ganar -->
    <div id="win-modal" class="modal hidden">
        <div class="modal-content">
            <h2>¡Has ganado!</h2>
            <p>Tiempo: <span id="final-time"></span></p>
            <!-- Aquí se insertará dinámicamente el número de intentos -->
            <button id="play-again-btn">Jugar de nuevo</button>
            <!-- Placeholder para volver a página principal -->
            <button id="home-btn">Volver al inicio</button>
        </div>
    </div>
    <!-- fin popup al ganar -->
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat iste excepturi quae assumenda. Incidunt velit error perspiciatis culpa quos id blanditiis eaque libero ab. Excepturi quibusdam at debitis eveniet non?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, in molestias est, eius dolorem quia fuga nihil optio doloribus tempore nesciunt officiis debitis earum eos suscipit aspernatur minus sint consectetur.
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, deserunt. Eveniet repudiandae dolor doloremque facilis veniam excepturi, iste fugit accusantium voluptates ab incidunt esse! Veniam beatae corrupti atque maiores unde!
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae animi dolorem error, debitis veritatis nemo cum dignissimos sint eveniet dicta placeat temporibus nisi minima perferendis iste ratione quam et ipsa.
     Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magnam, vitae sit doloremque est debitis aut praesentium corporis inventore nostrum fugit nesciunt reiciendis officia culpa ea aperiam incidunt obcaecati optio cumque?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat iste excepturi quae assumenda. Incidunt velit error perspiciatis culpa quos id blanditiis eaque libero ab. Excepturi quibusdam at debitis eveniet non?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, in molestias est, eius dolorem quia fuga nihil optio doloribus tempore nesciunt officiis debitis earum eos suscipit aspernatur minus sint consectetur.
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, deserunt. Eveniet repudiandae dolor doloremque facilis veniam excepturi, iste fugit accusantium voluptates ab incidunt esse! Veniam beatae corrupti atque maiores unde!
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae animi dolorem error, debitis veritatis nemo cum dignissimos sint eveniet dicta placeat temporibus nisi minima perferendis iste ratione quam et ipsa.
     Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magnam, vitae sit doloremque est debitis aut praesentium corporis inventore nostrum fugit nesciunt reiciendis officia culpa ea aperiam incidunt obcaecati optio cumque?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat iste excepturi quae assumenda. Incidunt velit error perspiciatis culpa quos id blanditiis eaque libero ab. Excepturi quibusdam at debitis eveniet non?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, in molestias est, eius dolorem quia fuga nihil optio doloribus tempore nesciunt officiis debitis earum eos suscipit aspernatur minus sint consectetur.
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, deserunt. Eveniet repudiandae dolor doloremque facilis veniam excepturi, iste fugit accusantium voluptates ab incidunt esse! Veniam beatae corrupti atque maiores unde!
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae animi dolorem error, debitis veritatis nemo cum dignissimos sint eveniet dicta placeat temporibus nisi minima perferendis iste ratione quam et ipsa.
     Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magnam, vitae sit doloremque est debitis aut praesentium corporis inventore nostrum fugit nesciunt reiciendis officia culpa ea aperiam incidunt obcaecati optio cumque?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat iste excepturi quae assumenda. Incidunt velit error perspiciatis culpa quos id blanditiis eaque libero ab. Excepturi quibusdam at debitis eveniet non?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, in molestias est, eius dolorem quia fuga nihil optio doloribus tempore nesciunt officiis debitis earum eos suscipit aspernatur minus sint consectetur.
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, deserunt. Eveniet repudiandae dolor doloremque facilis veniam excepturi, iste fugit accusantium voluptates ab incidunt esse! Veniam beatae corrupti atque maiores unde!
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae animi dolorem error, debitis veritatis nemo cum dignissimos sint eveniet dicta placeat temporibus nisi minima perferendis iste ratione quam et ipsa.
     Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magnam, vitae sit doloremque est debitis aut praesentium corporis inventore nostrum fugit nesciunt reiciendis officia culpa ea aperiam incidunt obcaecati optio cumque?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat iste excepturi quae assumenda. Incidunt velit error perspiciatis culpa quos id blanditiis eaque libero ab. Excepturi quibusdam at debitis eveniet non?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, in molestias est, eius dolorem quia fuga nihil optio doloribus tempore nesciunt officiis debitis earum eos suscipit aspernatur minus sint consectetur.
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, deserunt. Eveniet repudiandae dolor doloremque facilis veniam excepturi, iste fugit accusantium voluptates ab incidunt esse! Veniam beatae corrupti atque maiores unde!
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae animi dolorem error, debitis veritatis nemo cum dignissimos sint eveniet dicta placeat temporibus nisi minima perferendis iste ratione quam et ipsa.
     Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magnam, vitae sit doloremque est debitis aut praesentium corporis inventore nostrum fugit nesciunt reiciendis officia culpa ea aperiam incidunt obcaecati optio cumque?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat iste excepturi quae assumenda. Incidunt velit error perspiciatis culpa quos id blanditiis eaque libero ab. Excepturi quibusdam at debitis eveniet non?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, in molestias est, eius dolorem quia fuga nihil optio doloribus tempore nesciunt officiis debitis earum eos suscipit aspernatur minus sint consectetur.
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, deserunt. Eveniet repudiandae dolor doloremque facilis veniam excepturi, iste fugit accusantium voluptates ab incidunt esse! Veniam beatae corrupti atque maiores unde!
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae animi dolorem error, debitis veritatis nemo cum dignissimos sint eveniet dicta placeat temporibus nisi minima perferendis iste ratione quam et ipsa.
     Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magnam, vitae sit doloremque est debitis aut praesentium corporis inventore nostrum fugit nesciunt reiciendis officia culpa ea aperiam incidunt obcaecati optio cumque?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat iste excepturi quae assumenda. Incidunt velit error perspiciatis culpa quos id blanditiis eaque libero ab. Excepturi quibusdam at debitis eveniet non?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, in molestias est, eius dolorem quia fuga nihil optio doloribus tempore nesciunt officiis debitis earum eos suscipit aspernatur minus sint consectetur.
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, deserunt. Eveniet repudiandae dolor doloremque facilis veniam excepturi, iste fugit accusantium voluptates ab incidunt esse! Veniam beatae corrupti atque maiores unde!
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae animi dolorem error, debitis veritatis nemo cum dignissimos sint eveniet dicta placeat temporibus nisi minima perferendis iste ratione quam et ipsa.
     Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magnam, vitae sit doloremque est debitis aut praesentium corporis inventore nostrum fugit nesciunt reiciendis officia culpa ea aperiam incidunt obcaecati optio cumque?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat iste excepturi quae assumenda. Incidunt velit error perspiciatis culpa quos id blanditiis eaque libero ab. Excepturi quibusdam at debitis eveniet non?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, in molestias est, eius dolorem quia fuga nihil optio doloribus tempore nesciunt officiis debitis earum eos suscipit aspernatur minus sint consectetur.
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, deserunt. Eveniet repudiandae dolor doloremque facilis veniam excepturi, iste fugit accusantium voluptates ab incidunt esse! Veniam beatae corrupti atque maiores unde!
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae animi dolorem error, debitis veritatis nemo cum dignissimos sint eveniet dicta placeat temporibus nisi minima perferendis iste ratione quam et ipsa.
     Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magnam, vitae sit doloremque est debitis aut praesentium corporis inventore nostrum fugit nesciunt reiciendis officia culpa ea aperiam incidunt obcaecati optio cumque?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat iste excepturi quae assumenda. Incidunt velit error perspiciatis culpa quos id blanditiis eaque libero ab. Excepturi quibusdam at debitis eveniet non?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, in molestias est, eius dolorem quia fuga nihil optio doloribus tempore nesciunt officiis debitis earum eos suscipit aspernatur minus sint consectetur.
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, deserunt. Eveniet repudiandae dolor doloremque facilis veniam excepturi, iste fugit accusantium voluptates ab incidunt esse! Veniam beatae corrupti atque maiores unde!
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae animi dolorem error, debitis veritatis nemo cum dignissimos sint eveniet dicta placeat temporibus nisi minima perferendis iste ratione quam et ipsa.
     Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magnam, vitae sit doloremque est debitis aut praesentium corporis inventore nostrum fugit nesciunt reiciendis officia culpa ea aperiam incidunt obcaecati optio cumque?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat iste excepturi quae assumenda. Incidunt velit error perspiciatis culpa quos id blanditiis eaque libero ab. Excepturi quibusdam at debitis eveniet non?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, in molestias est, eius dolorem quia fuga nihil optio doloribus tempore nesciunt officiis debitis earum eos suscipit aspernatur minus sint consectetur.
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, deserunt. Eveniet repudiandae dolor doloremque facilis veniam excepturi, iste fugit accusantium voluptates ab incidunt esse! Veniam beatae corrupti atque maiores unde!
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae animi dolorem error, debitis veritatis nemo cum dignissimos sint eveniet dicta placeat temporibus nisi minima perferendis iste ratione quam et ipsa.
     Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magnam, vitae sit doloremque est debitis aut praesentium corporis inventore nostrum fugit nesciunt reiciendis officia culpa ea aperiam incidunt obcaecati optio cumque?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat iste excepturi quae assumenda. Incidunt velit error perspiciatis culpa quos id blanditiis eaque libero ab. Excepturi quibusdam at debitis eveniet non?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, in molestias est, eius dolorem quia fuga nihil optio doloribus tempore nesciunt officiis debitis earum eos suscipit aspernatur minus sint consectetur.
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, deserunt. Eveniet repudiandae dolor doloremque facilis veniam excepturi, iste fugit accusantium voluptates ab incidunt esse! Veniam beatae corrupti atque maiores unde!
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae animi dolorem error, debitis veritatis nemo cum dignissimos sint eveniet dicta placeat temporibus nisi minima perferendis iste ratione quam et ipsa.
     Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magnam, vitae sit doloremque est debitis aut praesentium corporis inventore nostrum fugit nesciunt reiciendis officia culpa ea aperiam incidunt obcaecati optio cumque?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat iste excepturi quae assumenda. Incidunt velit error perspiciatis culpa quos id blanditiis eaque libero ab. Excepturi quibusdam at debitis eveniet non?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, in molestias est, eius dolorem quia fuga nihil optio doloribus tempore nesciunt officiis debitis earum eos suscipit aspernatur minus sint consectetur.
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, deserunt. Eveniet repudiandae dolor doloremque facilis veniam excepturi, iste fugit accusantium voluptates ab incidunt esse! Veniam beatae corrupti atque maiores unde!
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae animi dolorem error, debitis veritatis nemo cum dignissimos sint eveniet dicta placeat temporibus nisi minima perferendis iste ratione quam et ipsa.
     Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magnam, vitae sit doloremque est debitis aut praesentium corporis inventore nostrum fugit nesciunt reiciendis officia culpa ea aperiam incidunt obcaecati optio cumque?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat iste excepturi quae assumenda. Incidunt velit error perspiciatis culpa quos id blanditiis eaque libero ab. Excepturi quibusdam at debitis eveniet non?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, in molestias est, eius dolorem quia fuga nihil optio doloribus tempore nesciunt officiis debitis earum eos suscipit aspernatur minus sint consectetur.
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, deserunt. Eveniet repudiandae dolor doloremque facilis veniam excepturi, iste fugit accusantium voluptates ab incidunt esse! Veniam beatae corrupti atque maiores unde!
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae animi dolorem error, debitis veritatis nemo cum dignissimos sint eveniet dicta placeat temporibus nisi minima perferendis iste ratione quam et ipsa.
     Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magnam, vitae sit doloremque est debitis aut praesentium corporis inventore nostrum fugit nesciunt reiciendis officia culpa ea aperiam incidunt obcaecati optio cumque?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat iste excepturi quae assumenda. Incidunt velit error perspiciatis culpa quos id blanditiis eaque libero ab. Excepturi quibusdam at debitis eveniet non?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, in molestias est, eius dolorem quia fuga nihil optio doloribus tempore nesciunt officiis debitis earum eos suscipit aspernatur minus sint consectetur.
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, deserunt. Eveniet repudiandae dolor doloremque facilis veniam excepturi, iste fugit accusantium voluptates ab incidunt esse! Veniam beatae corrupti atque maiores unde!
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae animi dolorem error, debitis veritatis nemo cum dignissimos sint eveniet dicta placeat temporibus nisi minima perferendis iste ratione quam et ipsa.
     Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magnam, vitae sit doloremque est debitis aut praesentium corporis inventore nostrum fugit nesciunt reiciendis officia culpa ea aperiam incidunt obcaecati optio cumque?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat iste excepturi quae assumenda. Incidunt velit error perspiciatis culpa quos id blanditiis eaque libero ab. Excepturi quibusdam at debitis eveniet non?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, in molestias est, eius dolorem quia fuga nihil optio doloribus tempore nesciunt officiis debitis earum eos suscipit aspernatur minus sint consectetur.
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, deserunt. Eveniet repudiandae dolor doloremque facilis veniam excepturi, iste fugit accusantium voluptates ab incidunt esse! Veniam beatae corrupti atque maiores unde!
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae animi dolorem error, debitis veritatis nemo cum dignissimos sint eveniet dicta placeat temporibus nisi minima perferendis iste ratione quam et ipsa.
     Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magnam, vitae sit doloremque est debitis aut praesentium corporis inventore nostrum fugit nesciunt reiciendis officia culpa ea aperiam incidunt obcaecati optio cumque?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat iste excepturi quae assumenda. Incidunt velit error perspiciatis culpa quos id blanditiis eaque libero ab. Excepturi quibusdam at debitis eveniet non?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, in molestias est, eius dolorem quia fuga nihil optio doloribus tempore nesciunt officiis debitis earum eos suscipit aspernatur minus sint consectetur.
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, deserunt. Eveniet repudiandae dolor doloremque facilis veniam excepturi, iste fugit accusantium voluptates ab incidunt esse! Veniam beatae corrupti atque maiores unde!
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae animi dolorem error, debitis veritatis nemo cum dignissimos sint eveniet dicta placeat temporibus nisi minima perferendis iste ratione quam et ipsa.
     Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magnam, vitae sit doloremque est debitis aut praesentium corporis inventore nostrum fugit nesciunt reiciendis officia culpa ea aperiam incidunt obcaecati optio cumque?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat iste excepturi quae assumenda. Incidunt velit error perspiciatis culpa quos id blanditiis eaque libero ab. Excepturi quibusdam at debitis eveniet non?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, in molestias est, eius dolorem quia fuga nihil optio doloribus tempore nesciunt officiis debitis earum eos suscipit aspernatur minus sint consectetur.
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, deserunt. Eveniet repudiandae dolor doloremque facilis veniam excepturi, iste fugit accusantium voluptates ab incidunt esse! Veniam beatae corrupti atque maiores unde!
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae animi dolorem error, debitis veritatis nemo cum dignissimos sint eveniet dicta placeat temporibus nisi minima perferendis iste ratione quam et ipsa.
     Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magnam, vitae sit doloremque est debitis aut praesentium corporis inventore nostrum fugit nesciunt reiciendis officia culpa ea aperiam incidunt obcaecati optio cumque?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat iste excepturi quae assumenda. Incidunt velit error perspiciatis culpa quos id blanditiis eaque libero ab. Excepturi quibusdam at debitis eveniet non?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, in molestias est, eius dolorem quia fuga nihil optio doloribus tempore nesciunt officiis debitis earum eos suscipit aspernatur minus sint consectetur.
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, deserunt. Eveniet repudiandae dolor doloremque facilis veniam excepturi, iste fugit accusantium voluptates ab incidunt esse! Veniam beatae corrupti atque maiores unde!
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae animi dolorem error, debitis veritatis nemo cum dignissimos sint eveniet dicta placeat temporibus nisi minima perferendis iste ratione quam et ipsa.
     Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magnam, vitae sit doloremque est debitis aut praesentium corporis inventore nostrum fugit nesciunt reiciendis officia culpa ea aperiam incidunt obcaecati optio cumque?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat iste excepturi quae assumenda. Incidunt velit error perspiciatis culpa quos id blanditiis eaque libero ab. Excepturi quibusdam at debitis eveniet non?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, in molestias est, eius dolorem quia fuga nihil optio doloribus tempore nesciunt officiis debitis earum eos suscipit aspernatur minus sint consectetur.
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, deserunt. Eveniet repudiandae dolor doloremque facilis veniam excepturi, iste fugit accusantium voluptates ab incidunt esse! Veniam beatae corrupti atque maiores unde!
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae animi dolorem error, debitis veritatis nemo cum dignissimos sint eveniet dicta placeat temporibus nisi minima perferendis iste ratione quam et ipsa.
     Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magnam, vitae sit doloremque est debitis aut praesentium corporis inventore nostrum fugit nesciunt reiciendis officia culpa ea aperiam incidunt obcaecati optio cumque?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat iste excepturi quae assumenda. Incidunt velit error perspiciatis culpa quos id blanditiis eaque libero ab. Excepturi quibusdam at debitis eveniet non?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, in molestias est, eius dolorem quia fuga nihil optio doloribus tempore nesciunt officiis debitis earum eos suscipit aspernatur minus sint consectetur.
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, deserunt. Eveniet repudiandae dolor doloremque facilis veniam excepturi, iste fugit accusantium voluptates ab incidunt esse! Veniam beatae corrupti atque maiores unde!
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae animi dolorem error, debitis veritatis nemo cum dignissimos sint eveniet dicta placeat temporibus nisi minima perferendis iste ratione quam et ipsa.
     Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magnam, vitae sit doloremque est debitis aut praesentium corporis inventore nostrum fugit nesciunt reiciendis officia culpa ea aperiam incidunt obcaecati optio cumque?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat iste excepturi quae assumenda. Incidunt velit error perspiciatis culpa quos id blanditiis eaque libero ab. Excepturi quibusdam at debitis eveniet non?
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, in molestias est, eius dolorem quia fuga nihil optio doloribus tempore nesciunt officiis debitis earum eos suscipit aspernatur minus sint consectetur.
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, deserunt. Eveniet repudiandae dolor doloremque facilis veniam excepturi, iste fugit accusantium voluptates ab incidunt esse! Veniam beatae corrupti atque maiores unde!
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae animi dolorem error, debitis veritatis nemo cum dignissimos sint eveniet dicta placeat temporibus nisi minima perferendis iste ratione quam et ipsa.
     Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magnam, vitae sit doloremque est debitis aut praesentium corporis inventore nostrum fugit nesciunt reiciendis officia culpa ea aperiam incidunt obcaecati optio cumque?v



     
    <script src="/memory/memory.css echo time(); ?>"></script>
    <script src="/memory/memory.js echo time(); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./memory.js"></script>
    <script src="../navbar/script.js"></script>

</body>

</html>