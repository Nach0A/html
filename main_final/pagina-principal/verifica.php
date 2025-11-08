<?php
session_start();
require_once "Conexion_BD.php"; 

$bd = new conexion_BD();
$conexion = $bd->getConexion();

// Configuración
$BASE_URL   = "/PlataformaLudica/main_final/";
$UPLOAD_DIR = __DIR__ . "/uploads/perfiles/";                  // ruta en disco donde se guardan archivos
$UPLOAD_WEB = $BASE_URL . "pagina-principal/uploads/perfiles/"; // ruta pública para <img> (consistente con perfil.php)

$ini = $bd->getIni();

if ($ini === "1") {
    if ($bd->inicio()) {
        // Login correcto
        $_SESSION['id_usuario'] = $bd->getIdUsuario($bd->getNombre());
        $_SESSION['usuario'] = $bd->getNombreUsuario($bd->getNombre());
        $_SESSION['correo'] = $bd->obtenerCorreo($_SESSION['id_usuario']);
        // $bd->agregarAdmin("calle1", "departamento1", "1234", $_SESSION['correo']);
        
        if (!empty($bd->obtenerFoto($_SESSION['usuario'])) && file_exists($UPLOAD_DIR . $bd->obtenerFoto($_SESSION['usuario']))) {
            $_SESSION['foto'] = $UPLOAD_WEB . $bd->obtenerFoto($_SESSION['usuario']);
        } else {
            $_SESSION['foto'] = $BASE_URL . "navbar/imagenes/usuario.png";
        }

        if ($bd->esAdmin($_SESSION['correo'])) {
            $_SESSION['admin'] = true;
            header("Location: Inicio_admin.php");
            exit();
        } else {
            $_SESSION['admin'] = false;
        }

        $bd->cerrarConexion();
        header("Location: Inicio.php");
        exit();
    } else {
        // ❌ Error de login → SweetAlert con estilo Zentryx (violeta)
        echo '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Acceso denegado - Zentryx</title>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <link href="https://fonts.googleapis.com/css2?family=Ethnocentric&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="../css/sweetalert.css">
            <script src="../js/alert.js"></script>
        </head>
        <body>
        <script>
            zentryxAlert({
                icon: "error",
                title: "Acceso denegado",
                text: "Nombre de usuario o contraseña incorrectos.",
                confirmText: "Reintentar",
                goto: "login.php"
            });
        </script>
        </body>
        </html>';
        exit();
    }
} else {
    // ✅ Registro exitoso → SweetAlert con el mismo estilo violeta
    $bd->registro();
    $bd->cerrarConexion();

    echo '
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registro exitoso - Zentryx</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link href="https://fonts.googleapis.com/css2?family=Ethnocentric&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../css/sweetalert.css">
        <script src="../js/alert.js"></script>
    </head>
    <body>
    <script>
        zentryxAlert({
            icon: "success",
            title: "¡Registro completado!",
            text: "Tu cuenta fue creada con éxito. Ahora puedes iniciar sesión.",
            confirmText: "Iniciar sesión",
            goto: "login.php"
        });
    </script>
    </body>
    </html>';
    exit();
}
?>
