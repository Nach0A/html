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

// Obtener datos del usuario
$sql = "SELECT nom_usuario, gmail_usuario, imagen_perfil, passwd FROM usuarios WHERE nom_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $usuario_actual);
$stmt->execute();
$resultado = $stmt->get_result();
$datos = $resultado->fetch_assoc();

// Procesar cambios (se envía desde modals con inputs ocultos indicando acción)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $accion = $_POST["accion"];

    // Cambiar nombre usuario
    if ($accion === "usuario") {
        $nuevo_usuario = $_POST["nuevo_usuario"];
        if (!empty($nuevo_usuario) && $nuevo_usuario !== $usuario_actual) {
            $update_sql = "UPDATE usuarios SET nom_usuario=? WHERE nom_usuario=?";
            $stmt = $conexion->prepare($update_sql);
            $stmt->bind_param("ss", $nuevo_usuario, $usuario_actual);
            $stmt->execute();
            $_SESSION["usuario"] = $nuevo_usuario;
            $usuario_actual = $nuevo_usuario;
        }
    }

    // Cambiar mail
    if ($accion === "mail") {
        $nuevo_mail = $_POST["mail"];
        if (!empty($nuevo_mail) && $nuevo_mail !== $datos["gmail_usuario"]) {
            $update_sql = "UPDATE usuarios SET gmail_usuario=? WHERE nom_usuario=?";
            $stmt = $conexion->prepare($update_sql);
            $stmt->bind_param("ss", $nuevo_mail, $usuario_actual);
            $stmt->execute();
        }
    }

    // Cambiar contraseña
    if ($accion === "password") {
        $contrasenia_actual = $_POST["contrasenia_actual"];
        $nueva_contrasenia = $_POST["nueva_contrasenia"];
        $confirmar_contrasenia = $_POST["confirmar_contrasenia"];

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
    }

    // Cambiar imagen
    if ($accion === "imagen") {
        if (!empty($_FILES["imagen"]["name"])) {
            $nombre_imagen = $usuario_actual . "_" . basename($_FILES["imagen"]["name"]);
            $ruta = "uploads/perfiles/" . $nombre_imagen;

            if (!is_dir("uploads/perfiles")) {
                mkdir("uploads/perfiles", 0777, true);
            }

            move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta);

            $update_img = "UPDATE usuarios SET imagen_perfil=? WHERE nom_usuario=?";
            $stmt = $conexion->prepare($update_img);
            $stmt->bind_param("ss", $nombre_imagen, $usuario_actual);
            $stmt->execute();
        }
    }

    header("Location: perfil.php");
    exit();
}

// Foto por defecto o personalizada
$foto = (!empty($datos['imagen_perfil']) && file_exists("uploads/perfiles/".$datos['imagen_perfil'])) 
    ? "uploads/perfiles/".$datos['imagen_perfil'] 
    : "../navbar/imagenes/usuario.png";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil - Zentryx</title>
    <link rel="icon" href="imagenes/logo.jpg" type="image/jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <!-- NAVBAR -->
    <?php // Puedes dejar tu navbar igual al que ya tenías ?>
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
        <h2 class="mb-4">Configuración de Perfil</h2>

        <!-- Imagen de perfil -->
        <div class="card bg-dark mb-3 p-3 shadow-lg">
            <h5>Imagen de Perfil</h5>
            <div class="d-flex align-items-center">
                <img src="<?php echo $foto; ?>" width="100" class="rounded-circle shadow me-3">
                <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#modalImagen">Cambiar</button>
            </div>
        </div>

        <!-- Nombre de usuario -->
        <div class="card bg-dark mb-3 p-3 shadow-lg">
            <h5>Nombre de Usuario</h5>
            <p class="mb-1"><?php echo htmlspecialchars($datos['nom_usuario']); ?></p>
            <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#modalUsuario">Editar</button>
        </div>

        <!-- Correo -->
        <div class="card bg-dark mb-3 p-3 shadow-lg">
            <h5>Email</h5>
            <p class="mb-1"><?php echo htmlspecialchars($datos['gmail_usuario']); ?></p>
            <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#modalMail">Editar</button>
        </div>

        <!-- Contraseña -->
        <div class="card bg-dark mb-3 p-3 shadow-lg">
            <h5>Contraseña</h5>
            <p class="mb-1">********</p>
            <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#modalPassword">Cambiar</button>
        </div>
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

    <!-- Mail -->
    <div class="modal fade" id="modalMail" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
          <div class="modal-header">
            <h5 class="modal-title">Editar Email</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <form method="POST">
            <input type="hidden" name="accion" value="mail">
            <div class="modal-body">
                <input type="email" class="form-control" name="mail" value="<?php echo htmlspecialchars($datos['gmail_usuario']); ?>" required>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Password -->
    <div class="modal fade" id="modalPassword" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
          <div class="modal-header">
            <h5 class="modal-title">Cambiar Contraseña</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <form method="POST">
            <input type="hidden" name="accion" value="password">
            <div class="modal-body">
                <input type="password" class="form-control mb-2" name="contrasenia_actual" placeholder="Contraseña actual" required>
                <input type="password" class="form-control mb-2" name="nueva_contrasenia" placeholder="Nueva contraseña" required>
                <input type="password" class="form-control" name="confirmar_contrasenia" placeholder="Confirmar nueva contraseña" required>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
