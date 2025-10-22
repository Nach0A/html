<?php
require_once "../pagina-principal/Conexion_BD.php";
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../pagina-principal/login.php");
    exit();
}
$bd = new conexion_BD();
$conexion = $bd->conectar("localhost", "root", "", "zentryx");  
if ($conexion->connect_error) die("Error al conectar: " . $conexion->connect_error);

// Filtros de búsqueda
$buscarId = isset($_GET['buscarId']) ? trim($_GET['buscarId']) : '';
$buscarNombre = isset($_GET['buscarNombre']) ? trim($conexion->real_escape_string($_GET['buscarNombre'])) : '';

$sql = "SELECT id_usuario, nom_usuario, imagen_perfil FROM usuarios WHERE 1";
if ($buscarId !== '') $sql .= " AND id_usuario = " . (int)$buscarId;
if ($buscarNombre !== '') $sql .= " AND nom_usuario LIKE '%$buscarNombre%'";

$resultado = $conexion->query($sql);


// Edición
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_id'])) {
    $editarId = (int)$_POST['editar_id'];
    $nombre = $conexion->real_escape_string($_POST['editar_nombre']);
    $password = trim($_POST['editar_passwd'] ?? '');

    if ($password !== '') {
    // Encriptar con SHA-256 (igual que en registro)
    $hash = trim(hash('sha256', $password));
    $conexion->query("UPDATE usuarios SET nom_usuario='$nombre', passwd='$hash' WHERE id_usuario=$editarId");
} else {
    // Solo se cambia el nombre
    $conexion->query("UPDATE usuarios SET nom_usuario='$nombre' WHERE id_usuario=$editarId");
}


    // actualizar sesión si el usuario editado es el actual
    $usuarioActual = $_SESSION['usuario'] ?? '';
    $resultado = $conexion->query("SELECT id_usuario FROM usuarios WHERE nom_usuario='$usuarioActual' LIMIT 1");
    $fila = $resultado ? $resultado->fetch_assoc() : null;

    if ($fila && $fila['id_usuario'] == $editarId) {
        $_SESSION['usuario'] = $nombre;
    }

    header("Location: inicio_admin.php?seccion=admin");
    exit;
}




// Eliminación
if (isset($_GET['eliminar'])) {
    $eliminarId = (int)$_GET['eliminar'];
    $conexion->query("DELETE FROM usuarios WHERE id_usuario=$eliminarId");
    header("Location: inicio_admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - Zentryx</title>
    <link rel="icon" href="../navbar/imagenes/logo.jpg" type="image/jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="estilo.css">
    <style>
        body {
            padding-top: 90px;
        }

        .admin-container {
            padding: 2rem;
            background-color: rgba(15, 15, 15, 0.9);
            color: white;
            border-radius: 15px;
            margin: 2rem auto;
            max-width: 1200px;
            box-shadow: 0 0 15px #00ffff55;
        }

        .admin-container h1 {
            font-family: 'Orbitron', sans-serif;
            text-align: center;
            margin-bottom: 1.5rem;
            color: #00ffff;
            text-shadow: 0 0 10px #00ffff;
        }

        .buscador {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .buscador input {
            width: 250px;
            border-radius: 10px;
            padding: 0.5rem;
            border: none;
            outline: none;
            background: #111;
            color: white;
            box-shadow: 0 0 5px #00ffff;
        }

        .buscador button {
            background-color: #00ffff;
            color: black;
            font-weight: bold;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            transition: 0.3s;
        }

        .buscador button:hover {
            background-color: #00cccc;
            transform: scale(1.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            color: white;
        }

        th,
        td {
            border-bottom: 1px solid #00ffff55;
            padding: 0.75rem;
            text-align: center;
        }

        th {
            color: #00ffff;
            text-transform: uppercase;
        }

        .acciones button {
            border: none;
            padding: 0.4rem 0.8rem;
            margin: 0.2rem;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-editar,
        .btn-eliminar {
            border: none;
            padding: 0.4rem 0.8rem;
            margin: 0.2rem;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s ease;
            box-shadow: 0 0 10px #00ffff33;
        }

        .btn-editar {
            background-color: #0099ff;
            color: white;
        }

        .btn-editar:hover {
            background-color: #007acc;
            transform: scale(1.05);
        }

        .btn-eliminar {
            background-color: #ff0055;
            color: white;
        }

        .btn-eliminar:hover {
            background-color: #cc0044;
            transform: scale(1.05);
        }

        /* Animaciones de secciones */
        .seccion {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
            display: none;
        }

        .seccion.activa {
            opacity: 1;
            transform: translateY(0);
            display: block;
        }

        /* Preloader */
        #preloader {
            position: fixed;
            width: 100%;
            height: 100%;
            background: black;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }

        #preloader-logo {
            width: 100px;
            border-radius: 50%;
            box-shadow: 0 0 20px #00ffff;
            animation: pulse 1.5s infinite alternate;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            100% {
                transform: scale(1.1);
                opacity: 0.7;
            }
        }
    </style>
</head>

<body>
    <!-- PRELOADER -->
    <div id="preloader">
        <img src="../navbar/imagenes/logo.jpg" alt="Logo Zentryx" id="preloader-logo">
    </div>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg shadow-sm py-3" style="background-color: rgb(20,20,20);">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold fs-4 text-white" href="#inicio" id="linkLogo">
                <img src="../navbar/imagenes/logo.jpg" width="30" height="30" class="d-inline-block align-text-top">
                &nbsp;Zentryx (Admin)
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link text-white" href="#" id="linkInicio">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#" id="linkJuegos">Juegos</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#" id="linkAdmin">Usuarios</a></li>
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center text-white" id="userDropdown" data-bs-toggle="dropdown">
                            <img src="<?php echo htmlspecialchars('uploads/perfiles/juan_titocaldern-zq4a.png'); ?>" class="user-avatar shadow-sm" alt="Usuario">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end fade-menu">
                            <li><a class="dropdown-item" href="../pagina-principal/perfil.php">Perfil (<?php echo htmlspecialchars($_SESSION['usuario']); ?>)</a></li>
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

    <!-- SECCIÓN INICIO -->
    <section id="mainContent" class="fade-in seccion">
        <div class="bienvenida-box">
            <h1>¡Hola, <span class="usuario"><?php echo htmlspecialchars($_SESSION['usuario']); ?></span>!</h1>
            <h2>Bienvenido a <span class="resaltado">Zentryx</span></h2>
            <p class="descripcion-inicio">
                En esta plataforma podés explorar juegos, competir en rankings y desafiar tu mente. <br>
                ¡Demostrá tus habilidades y subí en la tabla!
            </p>
            <a href="#juegos" class="btn-inicio-jugar">🎮 Ver Juegos</a>
        </div>
    </section>

    <!-- SECCIÓN JUEGOS -->
    <section id="juegosContent" class="fade-in seccion">
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
                <p>Intenta seguir el ritmo de los vasos sin perder de vista la pelota</p>
                <button class="play-btn" onclick="location.href='../mosqueta/mosqueta.php'">Jugar</button>
            </div>

            <div class="tarjeta-juego">
                <h2>Juego de Monty</h2>
                <p>Pon a prueba tu suerte y astucia para encontrar el premio</p>
                <button class="play-btn" onclick="location.href='../juego-de-monti/juego-de-monti.php'">Jugar</button>
            </div>
    </section>

    <!-- SECCIÓN ADMINISTRAR USUARIOS -->
    <section id="adminContent" class="fade-in seccion">
        <div class="admin-container">
            <h1>Administrar Usuarios</h1>
            <div class="buscador">
                <form method="GET" class="d-flex gap-2 justify-content-center">
                    <input type="text" name="buscarId" placeholder="Buscar por ID..." value="<?php echo $_GET['buscarId'] ?? ''; ?>">
                    <input type="text" name="buscarNombre" placeholder="Buscar por nombre..." value="<?php echo $_GET['buscarNombre'] ?? ''; ?>">
                    <button type="submit">🔍 Buscar</button>
                </form>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Foto</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = $resultado->fetch_assoc()):
                            $imagen = !empty($fila['imagen_perfil']) ? $fila['imagen_perfil'] : 'usuario.png';
                            if (!file_exists(__DIR__ . '/uploads/perfiles/' . $imagen)) $imagen = 'usuario.png';
                            $rutaImagen = 'uploads/perfiles/' . $imagen;
                        ?>
                            <tr>
                                <td><?php echo $fila['id_usuario']; ?></td>
                                <td><?php echo htmlspecialchars($fila['nom_usuario']); ?></td>
                                <td>
                                    <img src="<?php echo htmlspecialchars($rutaImagen); ?>" width="60" height="60" class="rounded-circle border border-info shadow-sm" alt="avatar">
                                </td>
                                <td class="acciones">
                                    <button type="button"
                                        class="btn btn-warning btn-sm btn-editar"
                                        data-id="<?= $fila['id_usuario'] ?>"
                                        data-nombre="<?= htmlspecialchars($fila['nom_usuario'], ENT_QUOTES) ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editarModal">
                                        Editar
                                    </button>

                                    <a href="?eliminar=<?php echo $fila['id_usuario']; ?>"
                                        class="btn btn-danger btn-sm btn-eliminar"
                                        onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">
                                        Eliminar
                                    </a>

                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div> <!-- .table-responsive cerrada -->
            

        </div>
    </section>
    <!-- Modal único de edición (UNA SOLA COPIA) -->
            <div class="modal fade" id="editarModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="background:#111; color:white;">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Usuario</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <form id="formEditarUsuario" method="POST" action="inicio_admin.php">
                                <input type="hidden" name="editar_id" id="editId">
                                <div class="mb-3">
                                    <label>Nombre</label>
                                    <input type="text" name="editar_nombre" id="editNombre" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Contraseña (opcional)</label>
                                    <input type="password" name="editar_passwd" id="editPass" class="form-control">
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>      

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // listener para rellenar el modal cuando se abre
        document.addEventListener("DOMContentLoaded", function() {
            // delegación: si en el futuro las filas cambian dinámicamente, sigue funcionando
            document.querySelector('table').addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-editar');
                if (!btn) return;
                // rellenar inputs del modal
                const id = btn.dataset.id;
                const nombre = btn.dataset.nombre || '';
                document.getElementById('editId').value = id;
                document.getElementById('editNombre').value = nombre;
                document.getElementById('editPass').value = ''; // por seguridad
            });

            // Asegurar que si por algún motivo quedan backdrops huérfanos, se limpien al cerrar
            const editarModalEl = document.getElementById('editarModal');
            if (editarModalEl) {
                editarModalEl.addEventListener('hidden.bs.modal', function() {
                    // eliminar cualquier backdrop sobrante accidental
                    document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
                    document.body.classList.remove('modal-open'); // quitar bloqueo de scroll si quedó
                });
            }
        });

        const secciones = {
            mainContent: document.getElementById("mainContent"),
            juegosContent: document.getElementById("juegosContent"),
            adminContent: document.getElementById("adminContent")
        };

        const linkInicio = document.getElementById("linkInicio");
        const linkJuegos = document.getElementById("linkJuegos");
        const linkAdmin = document.getElementById("linkAdmin");
        const linkLogo = document.getElementById("linkLogo");
        const btnVerJuegos = document.querySelector(".btn-inicio-jugar");


        function mostrarSeccion(id) {
            Object.values(secciones).forEach(sec => sec.classList.remove("activa"));
            secciones[id].classList.add("activa");
        }




        linkInicio.addEventListener("click", e => {
            e.preventDefault();
            mostrarSeccion("mainContent");
        });
        linkJuegos.addEventListener("click", e => {
            e.preventDefault();
            mostrarSeccion("juegosContent");
        });
        linkAdmin.addEventListener("click", e => {
            e.preventDefault();
            mostrarSeccion("adminContent");
        });
        linkLogo.addEventListener("click", e => {
            e.preventDefault();
            mostrarSeccion("mainContent");
        });
        btnVerJuegos.addEventListener("click", e => {
            e.preventDefault();
            mostrarSeccion("juegosContent");
        });


        // Preloader y carga inicial
        window.addEventListener("load", () => {
            const pre = document.getElementById("preloader");
            pre.style.opacity = "0";
            setTimeout(() => pre.style.display = "none", 400);
            mostrarSeccion("mainContent");
        });
        // Mantener en sección de administración si se usó búsqueda o acciones
        if (window.location.search.includes("buscar") || window.location.search.includes("eliminar")) {
            mostrarSeccion("adminContent");
        }
        if (window.location.search.includes("seccion=admin")) mostrarSeccion("adminContent");
    </script>
</body>

</html>