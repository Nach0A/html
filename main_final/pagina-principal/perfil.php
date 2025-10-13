<?php
session_start();
include("Conexion_BD.php");

// Verificar si hay sesión activa
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

$db = new conexion_BD();
$conexion = $db->getConexion();

$usuario_actual = $_SESSION["usuario"];
// Evitar warnings: inicializar variables usadas por los modales
$accion_post = '';
$mensaje = '';
$tipo_alerta = '';


// Definir BASE_URL
$BASE_URL = "/PlataformaLudica/main_final/";

// Rutas de archivo físico y ruta web para uploads
$upload_dir = __DIR__ . "/uploads/perfiles/";
$upload_web = $BASE_URL . "pagina-principal/uploads/perfiles/"; 

// Obtener datos del usuario
$sql = "SELECT nom_usuario, gmail_usuario, imagen_perfil, passwd FROM usuarios WHERE nom_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $usuario_actual);
$stmt->execute();
$resultado = $stmt->get_result();
$datos = $resultado->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $accion = $_POST["accion"] ?? "";

    // Cambiar nombre usuario
    if ($accion === "usuario") {
        $nuevo_usuario = $_POST["nuevo_usuario"] ?? "";
        if (!empty($nuevo_usuario) && $nuevo_usuario !== $usuario_actual) {
            $update_sql = "UPDATE usuarios SET nom_usuario=? WHERE nom_usuario=?";
            $stmt = $conexion->prepare($update_sql);
            $stmt->bind_param("ss", $nuevo_usuario, $usuario_actual);
            $stmt->execute();
            $_SESSION["usuario"] = $nuevo_usuario;
            $usuario_actual = $nuevo_usuario;
        }
        // Para estas acciones SÍ redirigimos
        header("Location: perfil.php");
        exit();
    }

    // ... luego, en el bloque de cambiar imagen:
if ($accion === "imagen") {
    if (!empty($_FILES["imagen"]["name"])) {
        $nombre_imagen = $usuario_actual . "_" . basename($_FILES["imagen"]["name"]);

        // Aseguramos carpeta
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0775, true);
        }

        $destino_fs = $upload_dir . $nombre_imagen; // ruta física para move_uploaded_file

        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $destino_fs)) {
            // Guardamos solo el nombre en la BD
            $update_img = "UPDATE usuarios SET imagen_perfil=? WHERE nom_usuario=?";
            $stmt = $conexion->prepare($update_img);
            $stmt->bind_param("ss", $nombre_imagen, $usuario_actual);
            $stmt->execute();

            // Guardar en sesión la RUTA WEB (no la ruta física)
            $_SESSION["foto"] = $upload_web . $nombre_imagen;
        } else {
            // opcional: mensaje de error
            $mensaje = "Error al subir la imagen.";
            $tipo_alerta = "danger";
        }
    }
    header("Location: perfil.php");
    exit(); 
}

    // Cambiar contraseña —> NO redirigimos (mostramos mensaje en el modal)
    if ($accion === "password") {
        $accion_post = "password";

        $contrasenia_actual   = $_POST["contrasenia_actual"]   ?? "";
        $nueva_contrasenia    = $_POST["nueva_contrasenia"]    ?? "";
        $confirmar_contrasenia = $_POST["confirmar_contrasenia"] ?? "";

        $contraHash = hash("sha256", $contrasenia_actual);

        // 1. Verificar contraseña actual
        if ($datos["passwd"] !== $contraHash) {
            $mensaje = " La contraseña actual no es correcta";
            $tipo_alerta = "danger";
        }
        // 2. Verificar vacíos
        elseif ($nueva_contrasenia === "" || $confirmar_contrasenia === "") {
            $mensaje = " Debes completar todos los campos";
            $tipo_alerta = "danger";
        }
        // 3. Coincidencia nueva/confirmar
        elseif ($nueva_contrasenia !== $confirmar_contrasenia) {
            $mensaje = " La nueva contraseña y la confirmación no coinciden";
            $tipo_alerta = "danger";
        }
        // 4. Que no sea igual a la actual
        elseif (hash("sha256", $nueva_contrasenia) === $contraHash) {
            $mensaje = " La nueva contraseña no puede ser igual a la actual";
            $tipo_alerta = "danger";
        }
        // 5. Todo bien → actualizar
        else {
            $nuevaHash = hash("sha256", $nueva_contrasenia);
            $update_pass = "UPDATE usuarios SET passwd=? WHERE nom_usuario=?";
            $stmt = $conexion->prepare($update_pass);
            $stmt->bind_param("ss", $nuevaHash, $usuario_actual);
            if ($stmt->execute()) {
                $mensaje = " Contraseña actualizada correctamente";
                $tipo_alerta = "success";
                // Actualizamos el dato en memoria para siguientes validaciones en esta carga
                $datos["passwd"] = $nuevaHash;
            } else {
                $mensaje = " Ocurrió un error al actualizar la contraseña";
                $tipo_alerta = "danger";
            }
        }
        // IMPORTANTE: NO redirigimos aquí
    }
    // Eliminar cuenta
    if ($accion === "eliminar") {
        $delete_sql = "DELETE FROM usuarios WHERE nom_usuario=?";
        $stmt = $conexion->prepare($delete_sql);
        $stmt->bind_param("s", $usuario_actual);
        if ($stmt->execute()) {
            session_destroy();
            header("Location: login.php");
            exit();
        } else {
            $mensaje = "No se pudo eliminar la cuenta. Intenta de nuevo.";
            $tipo_alerta = "danger";
        }
    }
}
// ================== FIN Procesar cambios ==================



if (!empty($datos['imagen_perfil']) && file_exists($upload_dir . $datos['imagen_perfil'])) {
    $foto = $upload_web . $datos['imagen_perfil'];
} else {
    $foto = $BASE_URL . "navbar/imagenes/usuario.png";
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Perfil - Zentryx</title>
    <link rel="icon" href="../navbar/imagenes/logo.jpg" type="image/jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilo.css">
    <style>
        .titulo-perfil {
            font-family: 'Ethnocentric', sans-serif;
            font-size: 2rem;
            text-align: center;
            color: rgba(255, 0, 200, 0.75);
            text-shadow: 0 0 8px rgba(0, 0, 0, 0.75), 0 0 16px rgba(204, 0, 255, 0.75), 0 0 24px rgba(110, 0, 92, 0.75);
            margin-bottom: 2rem;
            position: relative;
        }

        .titulo-perfil::after {
            content: "";
            display: block;
            width: 80px;
            height: 3px;
            margin: 10px auto 0;
            background: rgba(206, 2, 162, 0.86);
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.86), 0 0 16px rgba(114, 0, 108, 0.86);
            border-radius: 2px;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            display: inline-block;
            flex: 0 0 auto;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
        }

        .avatar--lg {
            width: 100px;
            height: 100px;
        }

        .avatar--sm {
            width: 36px;
            height: 36px;
        }

        .avatar>img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }

        .card {
            background: linear-gradient(145deg, #141414, #1f1f1f);
            border: 1px solid rgba(0, 255, 255, 0.2);
            border-radius: 15px;
            transition: all 0.3s ease-in-out;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
        }

        .card:hover {
            border-color: rgba(235, 9, 186, 0.88);
            box-shadow: 0 0 15px rgba(206, 2, 162, 0.64);
        }

        .btn-outline-light {
            border: 1px solid rgba(252, 6, 198, 0.86);
            color: rgba(238, 32, 193, 0.86);
            transition: all 0.3s;
        }

        .btn-outline-light:hover {
            background-color: rgba(219, 13, 174, 0.83);
            color: #111;
            box-shadow: 0 0 10px rgba(206, 2, 162, 0.86), 0 0 20px rgba(170, 4, 247, 0.64);
        }

        .btn-outline-danger {
            border: 1px solid #ff4444;
            color: #ff4444;
        }

        .btn-outline-danger:hover {
            background-color: #ff4444;
            color: #111;
            box-shadow: 0 0 10px #ff4444, 0 0 20px #ff4444;
        }

        .avatar--lg {
            border: 2px rgba(255, 10, 222, 0.82);
            box-shadow: 0 0 10px rgba(206, 2, 162, 0.64);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 0 10px rgba(255, 10, 222, 0.82);
            }

            50% {
                box-shadow: 0 0 25px rgba(245, 12, 194, 0.84);
            }
        }

        .modal-content {
            border-radius: 15px;
            border: 1px solid rgba(255, 0, 255, 0.86);
            box-shadow: 0 0 20px rgba(255, 7, 152, 0.8);
        }

        .modal-header {
            border-bottom: 1px solid rgba(255, 0, 200, 0.75);
        }

        .modal-title {
            font-family: 'Ethnocentric', sans-serif;
            color: rgba(235, 9, 186, 0.88);
        }

        /* Eliminar cuenta modal */
        .modal-header.border-danger {
            border-bottom: 1px solid #ff4444 !important;
        }

        .modal-title.text-danger {
            text-shadow: 0 0 10px #ff4444;
        }
    </style>

</head>

<body>

    <?php
    ?>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg shadow-sm py-3" style="background-color: rgb(20,20,20);">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold fs-4 text-white" href="../pagina-principal/Inicio.php#inicio" id="linkLogo">
                <img src="../navbar/imagenes/logo.jpg" width="30" height="30" class="d-inline-block align-text-top">
                &nbsp;Zentryx
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Botones Inicio / Juegos -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../pagina-principal/Inicio.php#inicio" id="linkInicio">Inicio</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" href="../pagina-principal/Inicio.php#juegos" id="linkJuegos">Juegos</a>
                    </li>
                </ul>

                <!-- Dropdown perfil -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center text-white"
                            id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <img src="<?php echo htmlspecialchars($_SESSION['foto'] ?? '../navbar/imagenes/usuario.png'); ?>"
                                class="user-avatar shadow-sm"
                                alt="Usuario">

                        </a>
                        <ul class="dropdown-menu dropdown-menu-end fade-menu">
                            <li>
                                <a class="dropdown-item" href="../pagina-principal/perfil.php">Perfil (<?php echo htmlspecialchars($_SESSION['usuario']);     ?>)</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="logout.php">Cerrar sesión</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>



    <div class="container mt-5 text-white">
        <h2 class="titulo-perfil">Configuración de Perfil</h2>



        <!-- Imagen de perfil -->
        <div class="card bg-dark mb-3 p-3 shadow-lg testNachoDiego">
            <h5 class="text-white">Imagen de Perfil</h5>
            <div class="d-flex align-items-center">
                <div class="avatar avatar--lg me-3">
                    <img src="<?php echo $foto; ?>" alt="Foto de perfil">
                </div>

                <div>
                    <p class="mb-1 text-secondary">Sube una nueva foto para tu perfil</p>
                    <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#modalImagen">Cambiar</button>
                </div>
            </div>

        </div>

        <!-- Nombre de usuario -->
        <div class="card bg-dark mb-3 p-3 shadow-lg">
            <h5 class="text-white">Nombre de Usuario</h5>
            <p class="mb-1 text-secondary"><?php echo htmlspecialchars($datos['nom_usuario']); ?></p>
            <p class="mb-1 text-secondary">Modifica el nombre con el que apareces en Zentryx</p>
            <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#modalUsuario">Editar</button>
        </div>


        <!-- Contraseña -->
        <div class="card bg-dark mb-3 p-3 shadow-lg">
            <h5 class="text-white">Contraseña</h5>
            <p class="mb-1 text-secondary">Cambia tu clave de acceso de manera segura</p>
            <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#modalPassword">Cambiar</button>
        </div>

        <!-- Eliminar cuenta -->
        <div class="card bg-dark mb-3 p-3 shadow-lg border border-danger">
            <h5 class="text-danger">Eliminar Cuenta</h5>
            <p class="mb-1 text-secondary">Borra permanentemente tu cuenta y todos tus datos</p>
            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalEliminar">
                Eliminar Cuenta
            </button>
        </div>


        <!-- MODALS -->
        <!-- Imagen -->
        <div class="modal fade" id="modalImagen" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content bg-dark text-white">
                    <div class="modal-header">
                        <h5 class="modal-title">Cambiar Imagen</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="accion" value="imagen">
                        <div class="modal-body">
                            <input type="file" class="form-control" name="imagen" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Usuario -->
        <div class="modal fade" id="modalUsuario" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content bg-dark text-white">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Usuario</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="accion" value="usuario">
                        <div class="modal-body">
                            <input type="text" class="form-control" name="nuevo_usuario" value="<?php echo htmlspecialchars($datos['nom_usuario']); ?>" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



        <!-- Password -->
        <div class="modal fade" id="modalPassword" tabindex="-1" aria-labelledby="modalPasswordLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content bg-dark text-white">
                    <form method="POST" action="">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalPasswordLabel">Cambiar Contraseña</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="accion" value="password">

                            <div class="mb-3">
                                <label class="form-label">Contraseña actual</label>
                                <input type="password" class="form-control" name="contrasenia_actual" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nueva contraseña</label>
                                <input type="password" class="form-control" name="nueva_contrasenia" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirmar nueva contraseña</label>
                                <input type="password" class="form-control" name="confirmar_contrasenia" required>
                            </div>

                            <?php if ($accion_post === 'password' && !empty($mensaje)): ?>
                                <div class="alert alert-<?php echo $tipo_alerta; ?> mt-2 mb-0">
                                    <?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Eliminar cuenta -->
        <div class="modal fade" id="modalEliminar" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content bg-dark text-white">
                    <div class="modal-header border-danger">
                        <h5 class="modal-title text-danger">Confirmar Eliminación</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="accion" value="eliminar">
                        <div class="modal-body">
                            <p class="mb-2">¿Estás seguro de que quieres <strong class="text-danger">eliminar tu cuenta</strong>?</p>
                            <p class="mb-0">Esta acción es <strong>irreversible</strong> y perderás todo tu progreso.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>




        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
        <?php if ($accion_post === 'password' && !empty($mensaje)): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var modal = new bootstrap.Modal(document.getElementById('modalPassword'));
                    modal.show();
                });
            </script>
        <?php endif; ?>

</body>

</html>