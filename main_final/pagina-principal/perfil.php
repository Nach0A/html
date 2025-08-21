<?php
session_start();
include("Conexion_BD.php"); // conexión con mysqli

// Verificar si hay sesión activa
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

$db = new conexion_BD();
$conexion = $db->getConexion();

$usuario_actual = $_SESSION["usuario"];

// Obtener datos del usuario
$sql = "SELECT nom_usuario, gmail_usuario, imagen_perfil, passwd FROM usuarios WHERE nom_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $usuario_actual);
$stmt->execute();
$resultado = $stmt->get_result();
$datos = $resultado->fetch_assoc();

// Procesar cambios
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nuevo_usuario = $_POST["nuevo_usuario"];
    $nuevo_mail = $_POST["mail"];
    $contrasenia_actual = $_POST["contrasenia_actual"];
    $nueva_contrasenia = $_POST["nueva_contrasenia"];
    $confirmar_contrasenia = $_POST["confirmar_contrasenia"];

    // Cambiar nombre de usuario
    if (!empty($nuevo_usuario) && $nuevo_usuario !== $usuario_actual) {
        $update_sql = "UPDATE usuarios SET nom_usuario=? WHERE nom_usuario=?";
        $stmt = $conexion->prepare($update_sql);
        $stmt->bind_param("ss", $nuevo_usuario, $usuario_actual);
        $stmt->execute();

        $_SESSION["usuario"] = $nuevo_usuario;
        $usuario_actual = $nuevo_usuario;
    }

    // Cambiar mail
    if (!empty($nuevo_mail) && $nuevo_mail !== $datos["gmail_usuario"]) {
        $update_sql = "UPDATE usuarios SET gmail_usuario=? WHERE nom_usuario=?";
        $stmt = $conexion->prepare($update_sql);
        $stmt->bind_param("ss", $nuevo_mail, $usuario_actual);
        $stmt->execute();
    }

    // Cambiar contraseña
    if (!empty($nueva_contrasenia) && $nueva_contrasenia === $confirmar_contrasenia) {
        $contraHash = hash("sha256", $contrasenia_actual);
        $nuevaHash = hash("sha256", $nueva_contrasenia);

        if ($datos["passwd"] === $contraHash) {
            $update_pass = "UPDATE usuarios SET passwd=? WHERE nom_usuario=?";
            $stmt = $conexion->prepare($update_pass);
            $stmt->bind_param("ss", $nuevaHash, $usuario_actual);
            $stmt->execute();
        } else {
            echo "<p style='color:red; text-align:center'>❌ Contraseña actual incorrecta</p>";
        }
    }

    // Subir imagen
    if (!empty($_FILES["imagen"]["name"])) {
        $nombre_imagen = $usuario_actual . "_" . basename($_FILES["imagen"]["name"]);
        $ruta = "uploads/perfiles/" . $nombre_imagen;

        // Crear carpeta si no existe
        if (!is_dir("uploads/perfiles")) {
            mkdir("uploads/perfiles", 0777, true);
        }

        move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta);

        $update_img = "UPDATE usuarios SET imagen_perfil=? WHERE nom_usuario=?";
        $stmt = $conexion->prepare($update_img);
        $stmt->bind_param("ss", $nombre_imagen, $usuario_actual);
        $stmt->execute();
    }

    header("Location: perfil.php");
    exit();
}

// Determinar foto a mostrar
$foto = !empty($datos['imagen_perfil']) 
    ? "uploads/perfiles/".$datos['imagen_perfil'] 
    : "imagenes/default.png";
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inicio - Zentryx</title>
    <link rel="icon" href="imagenes/logo.jpg" type="image/jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="estilo.css">
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
                            <img src="../navbar/imagenes/usuario.png" class="user-avatar shadow-sm">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end fade-menu">
                            <li>
                                <a class="dropdown-item" href="../pagina-principal/perfil.php">Perfil (<?php echo htmlspecialchars($_SESSION['usuario']); ?>)</a>
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
    <!-- CONTENIDO PERFIL -->
    <div class="container mt-5 text-white">
        <h2 class="mb-4">Editar Perfil</h2>
        <form method="POST" enctype="multipart/form-data" class="bg-dark p-4 rounded shadow-lg neon-border">
            <div class="mb-3 text-center">
                <img src="uploads/perfiles/<?php echo $datos['imagen_perfil']; ?>" width="120" class="rounded-circle shadow">
                <input type="file" class="form-control mt-2" name="imagen">
            </div>

            <div class="mb-3">
                <label>Nombre de usuario</label>
                <input type="text" class="form-control" name="nuevo_usuario" value="<?php echo $datos['nom_usuario']; ?>">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" class="form-control" name="mail" value="<?php echo $datos['gmail_usuario']; ?>">
            </div>

            <div class="mb-3">
                <label>Contraseña actual</label>
                <input type="password" class="form-control" name="contrasenia_actual">
            </div>

            <div class="mb-3">
                <label>Nueva contraseña</label>
                <input type="password" class="form-control" name="nueva_contrasenia">
            </div>

            <div class="mb-3">
                <label>Confirmar nueva contraseña</label>
                <input type="password" class="form-control" name="confirmar_contrasenia">
            </div>

            <button type="submit" class="btn btn-primary w-100">Guardar cambios</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.addEventListener("load", () => {
            const pre = document.getElementById("preloader");
            pre.style.opacity = "0";
            pre.style.visibility = "hidden";
            pre.style.pointerEvents = "none";
        });
    </script>
</body>

</html>