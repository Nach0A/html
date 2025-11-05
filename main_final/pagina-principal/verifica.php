<?php
session_start();
require_once "Conexion_BD.php"; 

$bd = new conexion_BD();
$conexion = $bd->getConexion();

// Config
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

$ini = $bd->getIni();

if ($ini === "1") {
    if ($bd->inicio()) {
        // Login correcto
        $_SESSION['id_usuario'] = $bd->getIdUsuario($bd->getNombre());
        $_SESSION['usuario'] = $bd->getNombreUsuario($bd->getNombre());
        $_SESSION['correo'] = $bd->obtenerCorreo($_SESSION['id_usuario']);
        
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
        // ⚠️ Error de login → SweetAlert con estilo Zentryx
        echo '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Acceso denegado - Zentryx</title>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <link href="https://fonts.googleapis.com/css2?family=Ethnocentric&display=swap" rel="stylesheet">
            <style>
                body {
                    background: radial-gradient(circle at 50% 40%, #120015, #050007 70%);
                    color: #fff;
                    font-family: "Ethnocentric", sans-serif;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                    overflow: hidden;
                }

                .swal2-popup.neon-border {
                    border: 2px solid #ff00cc;
                    box-shadow: 0 0 25px #ff0077, inset 0 0 15px #ff00cc;
                    text-shadow: 0 0 10px #ff00cc;
                    background: rgba(15, 0, 25, 0.95);
                    animation: glowPulse 2s infinite alternate;
                }

                @keyframes glowPulse {
                    from { box-shadow: 0 0 20px #ff0077; }
                    to   { box-shadow: 0 0 40px #ff00cc; }
                }

                .swal2-title {
                    font-size: 1.6rem;
                    color: #ff4de3;
                    letter-spacing: 1px;
                }

                .swal2-confirm {
                    background-color: #ff0077 !important;
                    border: none !important;
                    box-shadow: 0 0 10px #ff0077 !important;
                    font-family: "Ethnocentric", sans-serif !important;
                    transition: all 0.3s ease;
                }
                .swal2-confirm:hover {
                    background-color: #ff00cc !important;
                    box-shadow: 0 0 20px #ff00cc !important;
                }
            </style>
        </head>
        <body>
        <script>
        Swal.fire({
            icon: "error",
            title: "Acceso denegado",
            text: "Nombre de usuario o contraseña incorrectos.",
            background: "rgba(15, 0, 25, 0.95)",
            color: "#fff",
            confirmButtonColor: "#ff0077",
            confirmButtonText: "Reintentar",
            customClass: { popup: "neon-border" }
        }).then(() => {
            window.location.href = "login.php";
        });
        </script>
        </body>
        </html>';
        exit();
    }

} elseif ($ini === "0") {
    // 🟢 Registro exitoso → SweetAlert con el mismo estilo Zentryx
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
        <style>
            body {
                background: radial-gradient(circle at 50% 40%, #120015, #050007 70%);
                color: #fff;
                font-family: "Ethnocentric", sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                overflow: hidden;
            }

            .swal2-popup.neon-border {
                border: 2px solid #ff00cc;
                box-shadow: 0 0 25px #ff0077, inset 0 0 15px #ff00cc;
                text-shadow: 0 0 10px #ff00cc;
                background: rgba(15, 0, 25, 0.95);
                animation: glowPulse 2s infinite alternate;
            }

            @keyframes glowPulse {
                from { box-shadow: 0 0 20px #ff0077; }
                to   { box-shadow: 0 0 40px #ff00cc; }
            }

            .swal2-title {
                font-size: 1.6rem;
                color: #ff4de3;
                letter-spacing: 1px;
            }

            .swal2-confirm {
                background-color: #ff0077 !important;
                border: none !important;
                box-shadow: 0 0 10px #ff0077 !important;
                font-family: "Ethnocentric", sans-serif !important;
                transition: all 0.3s ease;
            }
            .swal2-confirm:hover {
                background-color: #ff00cc !important;
                box-shadow: 0 0 20px #ff00cc !important;
            }
        </style>
    </head>
    <body>
    <script>
    Swal.fire({
        icon: "success",
        title: "¡Registro completado!",
        text: "Tu cuenta fue creada con éxito. Ahora puedes iniciar sesión.",
        background: "rgba(15, 0, 25, 0.95)",
        color: "#fff",
        confirmButtonColor: "#ff0077",
        confirmButtonText: "Iniciar sesión",
        customClass: { popup: "neon-border" }
    }).then(() => {
        window.location.href = "login.php";
    });
    </script>
    </body>
    </html>';
    exit();
}
?>
