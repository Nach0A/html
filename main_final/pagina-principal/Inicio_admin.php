<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../pagina-principal/login.php");
    exit();
}

$conexion = new mysqli("localhost", "root", "", "zentryx");
if ($conexion->connect_error) die("Error al conectar: " . $conexion->connect_error);

// Filtros de búsqueda
$buscarId = isset($_GET['buscarId']) ? (int)$_GET['buscarId'] : '';
$buscarNombre = isset($_GET['buscarNombre']) ? $conexion->real_escape_string($_GET['buscarNombre']) : '';

// Consulta
$sql = "SELECT id_usuario, nom_usuario, imagen_perfil FROM usuarios WHERE 1";
if ($buscarId !== '') $sql .= " AND id_usuario = $buscarId";
if ($buscarNombre !== '') $sql .= " AND nom_usuario LIKE '%$buscarNombre%'";
$resultado = $conexion->query($sql);

// Edición
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_id'])) {
    $editarId = (int)$_POST['editar_id'];
    $nombre = $conexion->real_escape_string($_POST['editar_nombre']);
    $conexion->query("UPDATE usuarios SET nom_usuario='$nombre' WHERE id_usuario=$editarId");
    header("Location: inicio_admin.php");
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
        .admin-container {
            padding: 2rem;
            background-color: rgba(15, 15, 15, 0.9);
            color: white;
            border-radius: 15px;
            margin: 2rem auto;
            max-width: 1200px;
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

        .btn-editar {
            background-color: #0099ff;
            color: white;
        }

        .btn-eliminar {
            background-color: #ff0055;
            color: white;
        }

        .btn-editar:hover {
            background-color: #007acc;
        }

        .btn-eliminar:hover {
            background-color: #cc0044;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg shadow-sm py-3" style="background-color: rgb(20,20,20);">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold fs-4 text-white" href="#inicio" id="linkLogo">
                <img src="../navbar/imagenes/logo.jpg" width="30" height="30" class="d-inline-block align-text-top">
                &nbsp;Zentryx (Admin)
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link text-white" href="#" id="linkInicio">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#" id="linkJuegos">Juegos</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#" id="linkAdmin">Administrar usuarios</a></li>
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center text-white"
                            id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <img src="<?php echo htmlspecialchars($_SESSION['foto'] ?? 'uploads/perfiles/usuario.png'); ?>"
                                class="user-avatar shadow-sm" alt="Usuario">
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

    <!-- ADMIN -->
    <div id="adminContent" class="admin-container">
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
                            <td><img src="<?php echo htmlspecialchars($rutaImagen); ?>" width="60" height="60" class="rounded-circle border border-info shadow-sm"></td>
                            <td class="acciones">
                                <button class="btn-editar" data-bs-toggle="modal" data-bs-target="#editarModal<?php echo $fila['id_usuario']; ?>">Editar</button>
                                <a href="?eliminar=<?php echo $fila['id_usuario']; ?>" class="btn-eliminar" onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">Eliminar</a>
                            </td>
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="editarModal<?php echo $fila['id_usuario']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content" style="background:#111; color:white;">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Usuario: <?php echo htmlspecialchars($fila['nom_usuario']); ?></h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST">
                                            <input type="hidden" name="editar_id" value="<?php echo $fila['id_usuario']; ?>">
                                            <div class="mb-3">
                                                <label>Nombre</label>
                                                <input type="text" name="editar_nombre" class="form-control" value="<?php echo htmlspecialchars($fila['nom_usuario']); ?>" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const mainContent = document.getElementById("mainContent");
        const juegosContent = document.getElementById("juegosContent");
        const adminContent = document.getElementById("adminContent");
        const linkInicio = document.getElementById("linkInicio");
        const linkJuegos = document.getElementById("linkJuegos");
        const linkAdmin = document.getElementById("linkAdmin");
        const linkLogo = document.getElementById("linkLogo");

        function mostrarInicio() {
            mainContent.style.display = "block";
            juegosContent.style.display = "none";
            adminContent.style.display = "none";
        }

        function mostrarJuegos() {
            mainContent.style.display = "none";
            juegosContent.style.display = "block";
            adminContent.style.display = "none";
        }

        function mostrarAdmin() {
            mainContent.style.display = "none";
            juegosContent.style.display = "none";
            adminContent.style.display = "block";
        }

        linkInicio.addEventListener("click", e => {
            e.preventDefault();
            mostrarInicio();
        });
        linkJuegos.addEventListener("click", e => {
            e.preventDefault();
            mostrarJuegos();
        });
        linkAdmin.addEventListener("click", e => {
            e.preventDefault();
            mostrarAdmin();
        });
        linkLogo.addEventListener("click", e => {
            e.preventDefault();
            mostrarInicio();
        });

        window.addEventListener("load", () => {
            document.getElementById("preloader")?.style.opacity = "0";
            setTimeout(() => {
                document.getElementById("preloader")?.style.display = "none";
                mostrarInicio();
            }, 400);
        });
    </script>

</body>

</html>