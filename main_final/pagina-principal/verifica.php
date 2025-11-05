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
if ($bd->getIni() === "1") {
    if ($bd->inicio()) {
            // Login correcto
            $_SESSION['id_usuario'] = $bd->getIdUsuario($bd->getNombre());
            // Obtener datos del usuario para la foto
            $_SESSION['usuario'] = $bd->getNombreUsuario($bd->getNombre());
            $_SESSION['id_usuario'] = $bd->getIdUsuario($bd->getNombre());
            $_SESSION['correo'] = $bd->obtenerCorreo($_SESSION['id_usuario']);
            // $bd->agregarAdmin("calle1", "departamento1", "12345", $_SESSION['correo']); // Agregar admin por defecto
            // Asignar foto en sesión — con ruta web consistente
            if (!empty($bd->obtenerFoto($_SESSION['usuario'])) && file_exists($UPLOAD_DIR .$bd->obtenerFoto($_SESSION['usuario']))) {
                $_SESSION['foto'] = $UPLOAD_WEB . $bd->obtenerFoto($_SESSION['usuario']);
            } else {
                $_SESSION['foto'] = $BASE_URL . "navbar/imagenes/usuario.png";
            }
            if($bd->esAdmin($_SESSION['correo'])){
                $_SESSION['admin'] = true;
                header("Location: Inicio_admin.php");
                exit();
            } else {
                $_SESSION['admin'] = false;
            }

            // Cerrar y redirigir
            $bd->cerrarConexion();
            header("Location: Inicio.php");
            exit();
    } else {
        if ($bd->obtenerIniciar($_POST['input']??$_POST['input'])) {
            echo '<script>alert("Estás baneado."); window.location.href="login.php";</script>';
            exit();
        }
        echo '<script>alert("Nombre de usuario o contraseña incorrectos."); window.location.href="login.php";</script>';
        exit();
    }
} elseif ($bd->getIni() === "0") {
    $bd->registro();
    $bd->cerrarConexion();
    echo '<script>alert("Usuario registrado con éxito, puedes iniciar sesión."); window.location.href="login.php";</script>';
    exit();
}
