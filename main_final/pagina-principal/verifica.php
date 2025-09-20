<?php
///////////////////////////////////////////////////////
#######################################################
# PROHIBIDO EL USO DE IA. PROPIEDAD DE JUAN BAUTISTA  #
#######################################################
///////////////////////////////////////////////////////
session_start();
require_once "Conexion_BD.php"; 

// Instancia de DB
$bd = new conexion_BD();
$conexion = $bd->getConexion();

// Config (ajusta si tu estructura cambia)
$BASE_URL   = "/PlataformaLudica/main_final/";
$UPLOAD_DIR = __DIR__ . "/uploads/perfiles/";                  // ruta en disco donde se guardan archivos
$UPLOAD_WEB = $BASE_URL . "pagina-principal/uploads/perfiles/"; // ruta pública para <img> (consistente con perfil.php)

if ($bd->getIni() === "1") {
    // --- LOGIN ---
    $input = trim($_POST['input'] ?? '');
    $pass  = $bd->getContrasenia() ?? '';

    if ($input === '' || $pass === '') {
        echo '<script>alert("Completa usuario/contraseña."); window.location.href="login.php";</script>';
        exit();
    }

    $sql = "SELECT nom_usuario, passwd, imagen_perfil FROM usuarios WHERE nom_usuario = ? OR gmail_usuario = ? LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($user = $res->fetch_assoc()) {
        $hash = hash("sha256", $pass);
        if ($user['passwd'] === $hash) {
            // Login correcto
            $_SESSION['usuario'] = $user['nom_usuario'];

            // Asignar foto en sesión — con ruta web consistente
            if (!empty($user['imagen_perfil']) && file_exists($UPLOAD_DIR . $user['imagen_perfil'])) {
                $_SESSION['foto'] = $UPLOAD_WEB . $user['imagen_perfil'];
            } else {
                $_SESSION['foto'] = $BASE_URL . "navbar/imagenes/usuario.png";
            }

            // Cerrar y redirigir
            $stmt->close();
            $conexion->close();
            header("Location: Inicio.php");
            exit();
        } else {
            echo '<script>alert("Contraseña incorrecta."); window.location.href="login.php";</script>';
            exit();
        }
    } else {
        echo '<script>alert("Usuario no encontrado."); window.location.href="login.php";</script>';
        exit();
    }
} elseif ($bd->getIni() === "0") {
    $bd->registro();
    $bd->cerrarConexion();
    echo '<script>alert("Usuario registrado con éxito, puedes iniciar sesión."); window.location.href="login.php";</script>';
    exit();
}