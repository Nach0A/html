<?php
require_once "./Conexion_BD.php";
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


// Verificar si hay sesión activa
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

$db = new conexion_BD();
$conexion = $db->getConexion();

$usuario_actual = $_SESSION["usuario"];
$accion_post = '';
$mensaje = '';
$tipo_alerta = '';
$eliminar_ok = false;
$eliminar_error = '';

$BASE_URL = "/PlataformaLudica/main_final/";
$upload_dir = __DIR__ . "/uploads/perfiles/";
$upload_web = $BASE_URL . "pagina-principal/uploads/perfiles/";

$sql = "SELECT id_usuario, nom_usuario, gmail_usuario, imagen_perfil, passwd
        FROM usuarios WHERE nom_usuario = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $usuario_actual);
$stmt->execute();
$resultado = $stmt->get_result();
$datos = $resultado->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $accion = $_POST["accion"] ?? "";

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
        header("Location: perfil.php");
        exit();
    }

    if ($accion === "imagen") {
        if (!empty($_FILES["imagen"]["name"])) {
            $nombre_imagen = $usuario_actual . "_" . basename($_FILES["imagen"]["name"]);

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0775, true);
            }

            $destino_fs = $upload_dir . $nombre_imagen; // ruta física para move_uploaded_file

            if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $destino_fs)) {
                $update_img = "UPDATE usuarios SET imagen_perfil=? WHERE nom_usuario=?";
                $stmt = $conexion->prepare($update_img);
                $stmt->bind_param("ss", $nombre_imagen, $usuario_actual);
                $stmt->execute();

                // guardar en sesión la ruta web
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

    if ($accion === "password") {
        $accion_post = "password";
        $contrasenia_actual = $_POST["contrasenia_actual"] ?? "";
        $nueva_contrasenia = $_POST["nueva_contrasenia"] ?? "";
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
        // 5. Todo bien -> actualizar
        else {
            $nuevaHash = hash("sha256", $nueva_contrasenia);
            $update_pass = "UPDATE usuarios SET passwd=? WHERE nom_usuario=?";
            $stmt = $conexion->prepare($update_pass);
            $stmt->bind_param("ss", $nuevaHash, $usuario_actual);
            if ($stmt->execute()) {
                $mensaje = " Contraseña actualizada correctamente";
                $tipo_alerta = "success";
                $datos["passwd"] = $nuevaHash;
            } else {
                $mensaje = " Ocurrió un error al actualizar la contraseña";
                $tipo_alerta = "danger";
            }
        }
    }

    if ($accion === "eliminar") {
        $accion_post = 'eliminar';

        try {
            $conexion->begin_transaction();

            $stmt = $conexion->prepare("DELETE FROM administra WHERE id_usuario = ?");
            $stmt->bind_param("i", $datos['id_usuario']);
            $stmt->execute();

            $stmt = $conexion->prepare("DELETE FROM grupo WHERE id_usuario = ?");
            $stmt->bind_param("i", $datos['id_usuario']);
            $stmt->execute();

            $stmt = $conexion->prepare("DELETE FROM habla WHERE id_usuario_emisor = ? OR id_usuario_receptor = ?");
            $stmt->bind_param("ii", $datos['id_usuario'], $datos['id_usuario']);
            $stmt->execute();

            $stmt = $conexion->prepare("DELETE FROM juega WHERE id_usuario = ?");
            $stmt->bind_param("i", $datos['id_usuario']);
            $stmt->execute();

            $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
            $stmt->bind_param("i", $datos['id_usuario']);
            $stmt->execute();

            if ($stmt->affected_rows < 1) {
                throw new Exception("No se pudo eliminar el usuario (0 filas).");
            }

            if (!empty($datos['imagen_perfil'])) {
                $path_img = $upload_dir . $datos['imagen_perfil'];
                if (is_file($path_img)) {
                    @unlink($path_img);
                }
            }

            $conexion->commit();

            session_unset();
            session_destroy();
            $eliminar_ok = true;

        } catch (Throwable $e) {
            if ($conexion->errno) {
                $conexion->rollback();
            }
            $eliminar_error = 'Error al eliminar la cuenta: ' . $e->getMessage();
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        /* ---------- Avatar responsive ---------- */
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            display: inline-block;
            flex: 0 0 auto;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
        }

        .avatar>img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }

        .avatar--lg {
            width: var(--avatar-size, 100px);
            height: var(--avatar-size, 100px);
            border: 2px rgba(255, 10, 222, 0.82);
            box-shadow: 0 0 10px rgba(206, 2, 162, 0.64);
            animation: pulse 2s infinite;
        }

        @media (max-width: 575.98px) {
            .avatar--lg {
                --avatar-size: 84px;
            }
        }

        @media (min-width: 576px) and (max-width: 991.98px) {
            .avatar--lg {
                --avatar-size: 96px;
            }
        }

        @media (min-width: 992px) {
            .avatar--lg {
                --avatar-size: 112px;
            }
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

        /* ---------- carta ---------- */
        .card {
            background: linear-gradient(145deg, #141414, #1f1f1f);
            border: 1px solid rgba(0, 255, 255, 0.2);
            border-radius: 15px;
            transition: all .3s ease-in-out;
        }

        .card:hover {
            border-color: rgba(235, 9, 186, 0.88);
            box-shadow: 0 0 15px rgba(206, 2, 162, 0.64);
        }

        /* ---------- botones ---------- */
        .btn-outline-light {
            border: 1px solid rgba(252, 6, 198, 0.86);
            color: rgba(238, 32, 193, 0.86);
            transition: all .3s;
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

        /* ---------- modals ---------- */
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

        .modal-header.border-danger {
            border-bottom: 1px solid #ff4444 !important;
        }

        .modal-title.text-danger {
            text-shadow: 0 0 10px #ff4444;
        }

        /* ---------- navbar ---------- */
        .navbar-dark .navbar-toggler {
            border-color: rgba(255, 255, 255, .2);
        }

        .navbar-dark .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255,255,255,0.75)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .stack-gap>* {
            margin-bottom: 1rem;
        }

        @media (min-width: 768px) {
            .stack-gap>* {
                margin-bottom: 1.25rem;
            }
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
                <img src="../navbar/imagenes/logo.jpg" width="30" height="30" class="d-inline-block align-text-top"
                    alt="Logo">
                &nbsp;Zentryx
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Abrir menú">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Botones Inicio / Juegos -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link text-white" href="../pagina-principal/Inicio.php#inicio"
                            id="linkInicio">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="../pagina-principal/Inicio.php#juegos"
                            id="linkJuegos">Juegos</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="../pagina-principal/ranking.php"
                            id="linkRanking">Ranking</a></li>
                </ul>

                <!-- Dropdown perfil -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center text-white"
                            id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="<?php echo htmlspecialchars($_SESSION['foto'] ?? '../navbar/imagenes/usuario.png'); ?>"
                                class="user-avatar shadow-sm rounded-circle" width="36" height="36" alt="Usuario">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end fade-menu">
                            <li><a class="dropdown-item" href="../pagina-principal/perfil.php">Perfil
                                    (<?php echo htmlspecialchars($_SESSION['usuario']); ?>)</a></li>
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



    <div class="container mt-5 text-white">
        <h2 class="titulo-perfil">Configuración de Perfil</h2>

        <!-- grid -->
        <div class="row g-4 align-items-stretch">
            <div class="col-12 col-md-5 col-lg-4">
                <div class="card h-100 p-3 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="text-white mb-3">Imagen de Perfil</h5>
                        <div class="d-flex flex-column align-items-center text-center">
                            <div class="avatar avatar--lg mb-3">
                                <img src="<?php echo $foto; ?>" alt="Foto de perfil">
                            </div>
                            <p class="mb-3 text-secondary small">Sube una nueva foto para tu perfil</p>
                            <button class="btn btn-outline-light w-100" data-bs-toggle="modal"
                                data-bs-target="#modalImagen">Cambiar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-7 col-lg-8">
                <div class="stack-gap">
                    <!-- nombre de usuario -->
                    <div class="card p-3 h-100">
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
                            <div class="me-sm-3">
                                <h5 class="text-white mb-1">Nombre de Usuario</h5>
                                <p class="mb-1 text-secondary fw-semibold">
                                    <?php echo htmlspecialchars($datos['nom_usuario']); ?>
                                </p>
                                <p class="mb-0 text-secondary small">Modifica el nombre con el que apareces en Zentryx
                                </p>
                            </div>
                            <div class="w-100 w-sm-auto">
                                <button class="btn btn-outline-light w-100 w-sm-auto" data-bs-toggle="modal"
                                    data-bs-target="#modalUsuario">Editar</button>
                            </div>
                        </div>
                    </div>

                    <!-- contraseña -->
                    <div class="card p-3 h-100">
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
                            <div class="me-sm-3">
                                <h5 class="text-white mb-1">Contraseña</h5>
                                <p class="mb-0 text-secondary small">Cambia tu clave de acceso de manera segura</p>
                            </div>
                            <div class="w-100 w-sm-auto">
                                <button class="btn btn-outline-light w-100 w-sm-auto" data-bs-toggle="modal"
                                    data-bs-target="#modalPassword">Cambiar</button>
                            </div>
                        </div>
                    </div>

                    <!-- eliminar cuenta -->
                    <div class="card p-3 border border-danger h-100">
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
                            <div class="me-sm-3">
                                <h5 class="text-danger mb-1">Eliminar Cuenta</h5>
                                <p class="mb-0 text-secondary small">Borra permanentemente tu cuenta y todos tus datos
                                </p>
                            </div>
                            <div class="w-100 w-sm-auto">
                                <button class="btn btn-outline-danger w-100 w-sm-auto" data-bs-toggle="modal"
                                    data-bs-target="#modalEliminar">Eliminar Cuenta</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- MODALS -->
    <!-- Imagen -->
    <div class="modal fade" id="modalImagen" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm modal-md">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Cambiar Imagen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="accion" value="imagen">
                    <div class="modal-body">
                        <input type="file" class="form-control" name="imagen" accept="image/*" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

    <!-- Usuario -->
    <div class="modal fade" id="modalUsuario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Usuario</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>
                <form method="POST" autocomplete="off">
                    <input type="hidden" name="accion" value="usuario">
                    <div class="modal-body">
                        <label class="form-label small">Nuevo usuario</label>
                        <input type="text" class="form-control" name="nuevo_usuario"
                            value="<?php echo htmlspecialchars($datos['nom_usuario']); ?>" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100">Guardar</button>
                    </div>
                </form>
            </div>
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
                        <h5 class="modal-title" id="modalPasswordLabel">Cambiar Contraseña</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="accion" value="imagen">
                        <div class="modal-body">
                            <input type="file" class="form-control" name="imagen" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirmar nueva contraseña</label>
                            <input type="password" class="form-control" name="confirmar_contrasenia" required>
                        </div>

                        <?php if ($accion_post === 'password' && !empty($mensaje)): ?>
                            <div class="alert alert-<?php echo $tipo_alerta; ?> mt-2 mb-0" role="alert">
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
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-danger">
                    <h5 class="modal-title text-danger">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="accion" value="eliminar">
                    <div class="modal-body">
                        <p class="mb-2">¿Estás seguro de que quieres <strong class="text-danger">eliminar tu
                                cuenta</strong>?</p>
                        <p class="mb-0">Esta acción es <strong>irreversible</strong> y perderás todo tu progreso.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                    </div>
                </form>
            </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <?php if ($accion_post === 'password' && !empty($mensaje)): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var modal = new bootstrap.Modal(document.getElementById('modalPassword'));
                modal.show();
            });
        </script>
    <?php endif; ?>
    <?php if ($accion_post === 'eliminar'): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                <?php if ($eliminar_ok): ?>
                    Swal.fire({
                        title: 'Cuenta eliminada',
                        text: 'Tu cuenta ha sido eliminada correctamente.',
                        icon: 'success',
                        background: '#0d0d0d',
                        color: '#fff',
                        confirmButtonColor: '#e600e6',
                        confirmButtonText: 'Aceptar',
                        timer: 1800,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        willClose: () => { window.location.href = 'login.php'; }
                    });
                    setTimeout(function () { window.location.href = 'login.php'; }, 2200); // fallback
                <?php else: ?>
                    Swal.fire({
                        title: 'No se pudo eliminar',
                        text: '<?php echo addslashes($eliminar_error); ?>',
                        icon: 'error'
                    });
                <?php endif; ?>
            });
        </script>
    <?php endif; ?>

</body>

</html>