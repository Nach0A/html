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
$ini = $bd->getIni();
if ($ini === "1") {
    // --- LOGIN ---
    $input = trim($_POST['input'] ?? '');
    $pass  = "liceo";

    if ($input === '' || $pass === '') {
        echo '<script>alert("Completa usuario/contraseña."); window.location.href="login.php";</script>';
        exit();
    }

    if ($bd->inicio()) {
            // Login correcto
            $_SESSION['usuario'] = $bd->getNombre();
            $_SESSION['id_usuario'] = $bd->getIdUsuario();
            // Asignar foto en sesión — con ruta web consistente
            if (!empty($user['imagen_perfil']) && file_exists($UPLOAD_DIR . $user['imagen_perfil'])) {
                $_SESSION['foto'] = $UPLOAD_WEB . $user['imagen_perfil'];
            } else {
                $_SESSION['foto'] = $BASE_URL . "navbar/imagenes/usuario.png";
            }

            // Cerrar y redirigir
            $bd->cerrarConexion();
            header("Location: Inicio.php");
            exit();
    } else {
        echo '<script>alert("Nombre de usuario o contraseña incorrectos."); window.location.href="login.php";</script>';
        exit();
    }
} elseif ($bd->getIni() === "0") {
    $bd->registro();
    $bd->cerrarConexion();
    echo '<script>alert("Usuario registrado con éxito, puedes iniciar sesión."); window.location.href="login.php";</script>';
    exit();
}