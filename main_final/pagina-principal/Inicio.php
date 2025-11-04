<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../pagina-principal/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inicio - Zentryx</title>
    <link rel="icon" href="../navbar/imagenes/logo.jpg" type="image/jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="estilo.css">
</head>

<body>
    <!-- PRELOADER -->
    <div id="preloader" aria-hidden="true">
        <img src="../navbar/imagenes/logo.jpg" alt="Logo Zentryx" id="preloader-logo">
    </div>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm py-3" style="background-color: rgb(20,20,20);">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold fs-4 text-white" href="#inicio" id="linkLogo">
                <img src="../navbar/imagenes/logo.jpg" width="30" height="30" class="d-inline-block align-text-top"
                    alt="Zentryx logo">
                &nbsp;Zentryx
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Abrir menú">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Botones Inicio / Juegos / Ranking -->
                <ul class="navbar-nav me-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#" id="linkInicio">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#" id="linkJuegos">Juegos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../pagina-principal/ranking.php"
                            id="linkRanking">Ranking</a>
                    </li>
                </ul>

                <!-- Dropdown perfil -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center text-white"
                            id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                            aria-label="Perfil">
                            <img src="<?php echo htmlspecialchars($_SESSION['foto'] ?? '../navbar/imagenes/usuario.png'); ?>"
                                class="user-avatar shadow-sm" alt="Avatar de usuario">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end fade-menu" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="../pagina-principal/perfil.php">
                                    Perfil (<?php echo htmlspecialchars($_SESSION['usuario']); ?>)
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- SECCIÓN INICIO: NUEVA PANTALLA DE BIENVENIDA -->
    <div id="mainContent" class="inicio-fullscreen" style="display: none;">
        <div class="overlay-tech"></div>

        <div class="welcome-container">
            <div class="welcome-inner">
                <h1 class="welcome-title">¡Bienvenido,
                    <span class="usuario">
                        <?php echo htmlspecialchars($_SESSION['usuario']); ?>
                    </span>!
                </h1>
                <h2 class="welcome-subtitle">A la experiencia <span class="resaltado">Zentryx</span></h2>
                <p class="welcome-text">
                    Desafiá tu mente con nuestros juegos, subí en el ranking y conquistá el mundo digital.
                    <br>Sumergite en una interfaz inspirada en la tecnología del futuro.
                </p>
                <a href="#juegos" class="btn-inicio-jugar" onclick="mostrarJuegos()">🎮 Empezar a jugar</a>
            </div>
        </div>

        <!-- Fondo tecnológico -->
        <div class="grid-anim" aria-hidden="true"></div>
        <div class="tech-particles" aria-hidden="true"></div>

        <!-- Esquinas con circuitos animados -->
        <svg class="corner-svg corner-tl" viewBox="0 0 140 140" aria-hidden="true">
            <defs>
                <linearGradient id="gradTL" x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0%" stop-color="#ea00ff" />
                    <stop offset="100%" stop-color="#00ffe7" />
                </linearGradient>
            </defs>
            <path d="M10,130 L10,40 Q10,10 40,10 L130,10" fill="none" stroke="url(#gradTL)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="6 8">
                <animate attributeName="stroke-dashoffset" from="0" to="-200" dur="4s" repeatCount="indefinite" />
            </path>
            <circle cx="10" cy="130" r="3" fill="#ea00ff">
                <animate attributeName="r" values="2;4;2" dur="2.2s" repeatCount="indefinite" />
            </circle>
            <circle cx="130" cy="10" r="3" fill="#00ffe7">
                <animate attributeName="r" values="2;4;2" dur="2.2s" begin="0.3s" repeatCount="indefinite" />
            </circle>
        </svg>

        <svg class="corner-svg corner-tr" viewBox="0 0 140 140" aria-hidden="true">
            <defs>
                <linearGradient id="gradTR" x1="1" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#ea00ff" />
                    <stop offset="100%" stop-color="#00ffe7" />
                </linearGradient>
            </defs>
            <path d="M130,130 L130,40 Q130,10 100,10 L10,10" fill="none" stroke="url(#gradTR)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="6 8">
                <animate attributeName="stroke-dashoffset" from="0" to="-200" dur="4s" repeatCount="indefinite" />
            </path>
            <circle cx="130" cy="130" r="3" fill="#ea00ff">
                <animate attributeName="r" values="2;4;2" dur="2.2s" repeatCount="indefinite" />
            </circle>
            <circle cx="10" cy="10" r="3" fill="#00ffe7">
                <animate attributeName="r" values="2;4;2" dur="2.2s" begin="0.3s" repeatCount="indefinite" />
            </circle>
        </svg>

        <svg class="corner-svg corner-bl" viewBox="0 0 140 140" aria-hidden="true">
            <defs>
                <linearGradient id="gradBL" x1="0" y1="1" x2="1" y2="0">
                    <stop offset="0%" stop-color="#ea00ff" />
                    <stop offset="100%" stop-color="#00ffe7" />
                </linearGradient>
            </defs>
            <path d="M10,10 L10,100 Q10,130 40,130 L130,130" fill="none" stroke="url(#gradBL)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="6 8">
                <animate attributeName="stroke-dashoffset" from="0" to="-200" dur="4s" repeatCount="indefinite" />
            </path>
            <circle cx="10" cy="10" r="3" fill="#ea00ff">
                <animate attributeName="r" values="2;4;2" dur="2.2s" repeatCount="indefinite" />
            </circle>
            <circle cx="130" cy="130" r="3" fill="#00ffe7">
                <animate attributeName="r" values="2;4;2" dur="2.2s" begin="0.3s" repeatCount="indefinite" />
            </circle>
        </svg>

        <svg class="corner-svg corner-br" viewBox="0 0 140 140" aria-hidden="true">
            <defs>
                <linearGradient id="gradBR" x1="1" y1="1" x2="0" y2="0">
                    <stop offset="0%" stop-color="#ea00ff" />
                    <stop offset="100%" stop-color="#00ffe7" />
                </linearGradient>
            </defs>
            <path d="M130,10 L130,100 Q130,130 100,130 L10,130" fill="none" stroke="url(#gradBR)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="6 8">
                <animate attributeName="stroke-dashoffset" from="0" to="-200" dur="4s" repeatCount="indefinite" />
            </path>
            <circle cx="130" cy="10" r="3" fill="#ea00ff">
                <animate attributeName="r" values="2;4;2" dur="2.2s" repeatCount="indefinite" />
            </circle>
            <circle cx="10" cy="130" r="3" fill="#00ffe7">
                <animate attributeName="r" values="2;4;2" dur="2.2s" begin="0.3s" repeatCount="indefinite" />
            </circle>
        </svg>


    </div>


    <!-- SECCIÓN JUEGOS: lista de juegos -->
    <div class="container-fluid juegos-wrapper" id="juegosContent" style="display: none;">
        <h1 class="titulo-juegos text-center">Juegos Disponibles</h1>

        <div class="grid-juegos">
            <div class="tarjeta-juego">
                <h2>Memory</h2>
                <p>Pon a prueba tu memoria encontrando pares en el menor tiempo posible.</p>
                <button class="play-btn" onclick="location.href='../memory/memory.php'">Jugar</button>
            </div>

            <div class="tarjeta-juego">
                <h2>Buscaminas</h2>
                <p>Intenta identificar el lugar de todas las minas lo más rápido posible.<br>
                    Cuenta con 3 niveles de dificultad: fácil, medio y difícil.</p>
                <button class="play-btn" onclick="location.href='../buscaminas/buscaminas.php'">Jugar</button>
            </div>

            <div class="tarjeta-juego">
                <h2>Mosqueta</h2>
                <p>Intenta seguir el ritmo de los vasos sin perder de vista la pelota.</p>
                <button class="play-btn" onclick="location.href='../mosqueta/mosqueta.php'">Jugar</button>
            </div>

            <div class="tarjeta-juego">
                <h2>Juego de Monty</h2>
                <p>Pon a prueba tu suerte y astucia para encontrar el premio.</p>
                <button class="play-btn" onclick="location.href='../juego-de-monti/juego-de-monti.php'">Jugar</button>
            </div>
            <!-- Más tarjetas a futuro -->
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Referencias
        const mainContent = document.getElementById("mainContent");
        const juegosContent = document.getElementById("juegosContent");
        const linkInicio = document.getElementById("linkInicio");
        const linkJuegos = document.getElementById("linkJuegos");
        const linkLogo = document.getElementById("linkLogo");

        function mostrarInicio() {
            mainContent.classList.add("fade-in");
            mainContent.style.display = "block";
            juegosContent.style.display = "none";
            history.replaceState(null, "", "#inicio");
        }

        function mostrarJuegos() {
            juegosContent.classList.add("fade-in");
            juegosContent.style.display = "block";
            mainContent.style.display = "none";
            history.replaceState(null, "", "#juegos");
        }

        // Eventos
        linkInicio.addEventListener("click", e => {
            e.preventDefault();
            mostrarInicio();
        });
        linkJuegos.addEventListener("click", e => {
            e.preventDefault();
            mostrarJuegos();
        });
        linkLogo.addEventListener("click", e => {
            e.preventDefault();
            linkLogo.classList.add("animate-click");
            setTimeout(() => linkLogo.classList.remove("animate-click"), 600);
            mostrarInicio();
        });

        // Carga inicial
        window.addEventListener("load", () => {
            const pre = document.getElementById("preloader");
            pre.style.opacity = "0";
            pre.style.visibility = "hidden";
            pre.style.pointerEvents = "none";

            if (location.hash === "#juegos") {
                mostrarJuegos();
            } else {
                mostrarInicio();
                if (location.hash === "#inicioAnimado") {
                    linkLogo.classList.add("animate-click");
                    setTimeout(() => linkLogo.classList.remove("animate-click"), 400);
                }
            }
        });
    </script>
    <script src="../navbar/script.js"></script>
    <script>
        // Parallax sutil (solo partículas) + bloqueo de scroll en Inicio
        const particles = document.querySelector(".tech-particles");

        document.addEventListener("mousemove", (e) => {
            if (!particles) return;
            const {
                innerWidth,
                innerHeight
            } = window;
            const moveX = (e.clientX / innerWidth - 0.5) * 10;
            const moveY = (e.clientY / innerHeight - 0.5) * 10;
            particles.style.transform = `translate(${moveX}px, ${moveY}px)`;
        });

        // Forzamos no-scroll cuando está el inicio visible
        function bloquearScrollInicio(activo) {
            if (activo) {
                document.body.classList.add("no-scroll");
            } else {
                document.body.classList.remove("no-scroll");
            }
        }

        // Sobrescribimos mostrarInicio / mostrarJuegos para manejar scroll y centrar
        const _mostrarInicio = window.mostrarInicio;
        const _mostrarJuegos = window.mostrarJuegos;

        window.mostrarInicio = function() {
            if (typeof _mostrarInicio === "function") _mostrarInicio();
            bloquearScrollInicio(true);
        };
        window.mostrarJuegos = function() {
            if (typeof _mostrarJuegos === "function") _mostrarJuegos();
            bloquearScrollInicio(false);
        };

        // En carga inicial, si estás en #inicio, bloqueá scroll
        window.addEventListener("load", () => {
            if (location.hash !== "#juegos") bloquearScrollInicio(true);
        });
    </script>


</body>

</html>