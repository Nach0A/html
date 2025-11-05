<?php
session_start();
require_once "Conexion_BD.php"; 

$bd = new conexion_BD();
$conexion = $bd->getConexion();

// Config
$BASE_URL   = "/PlataformaLudica/main_final/";
$UPLOAD_DIR = __DIR__ . "/uploads/perfiles/";
$UPLOAD_WEB = $BASE_URL . "pagina-principal/uploads/perfiles/";

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
        // ⚠️ Error de login → SweetAlert con estilo Zentryx (violeta)
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
        html, body {
            height: 100%;
            margin: 0;
            background: radial-gradient(circle at center, #1a001f 0%, #000 70%);
            color: #fff;
            font-family: "Ethnocentric", sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        /* 🌌 Popup con tonos violeta neón */
        .swal2-popup.neon-border {
            border: 2px solid #a64bf4;
            background: rgba(20, 0, 40, 0.92);
            box-shadow: 0 0 25px rgba(166, 75, 244, 0.6), 0 0 60px rgba(234, 0, 255, 0.3);
            text-shadow: 0 0 10px #a64bf4;
            animation: glowPulse 2s infinite alternate, fadeIn 0.5s ease-out;
            transition: opacity 0.4s ease-in-out, transform 0.4s ease-in-out;
            backdrop-filter: blur(12px);
        }

        @keyframes glowPulse {
            0% { box-shadow: 0 0 20px #a64bf4, 0 0 40px #ea00ff; }
            100% { box-shadow: 0 0 35px #ea00ff, 0 0 70px #a64bf4; }
        }

        .swal2-title {
            font-size: 1.6rem;
            color: #cbbaff;
            text-shadow: 0 0 15px #a64bf4, 0 0 30px #ea00ff;
            letter-spacing: 1px;
        }

        .swal2-confirm {
            background: linear-gradient(90deg, #a64bf4, #ea00ff) !important;
            color: #000 !important;
            border: none !important;
            font-family: "Ethnocentric", sans-serif !important;
            box-shadow: 0 0 15px #a64bf4 !important;
            animation: neonPulse 3s infinite alternate ease-in-out;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .swal2-confirm:hover {
            transform: scale(1.05);
            box-shadow: 0 0 25px #ea00ff, 0 0 50px #a64bf4 !important;
        }

        @keyframes neonPulse {
            0% { box-shadow: 0 0 10px #a64bf4, 0 0 20px #ea00ff; }
            100% { box-shadow: 0 0 25px #ea00ff, 0 0 45px #a64bf4; }
        }

        /* 🌀 Transiciones entrada / salida */
        .swal2-show {
            animation: fadeInNeon 0.5s ease-out forwards;
        }
        .swal2-hide {
            animation: fadeOutNeon 0.6s ease-in forwards;
        }

        @keyframes fadeInNeon {
            from { opacity: 0; transform: scale(0.85); }
            to { opacity: 1; transform: scale(1); }
        }

        @keyframes fadeOutNeon {
            from { opacity: 1; transform: scale(1); }
            to { opacity: 0; transform: scale(0.9); filter: blur(2px); }
        }
    </style>
</head>
<body>
<script>
Swal.fire({
    icon: "error",
    title: "Acceso denegado",
    text: "Nombre de usuario o contraseña incorrectos.",
    background: "rgba(20, 0, 40, 0.92)",
    color: "#fff",
    confirmButtonText: "Reintentar",
    customClass: { popup: "neon-border" },
    showClass: { popup: "swal2-show" },
    hideClass: { popup: "swal2-hide" },
    allowOutsideClick: false
}).then(() => {
    // Espera para mostrar la animación de salida
    setTimeout(() => {
        window.location.href = "login.php";
    }, 350);
});
</script>
</body>
</html>';
        exit();
    }

} elseif ($ini === "0") {
    // 🟢 Registro exitoso → SweetAlert con el mismo estilo
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
                background: radial-gradient(circle at center, #1a001f 0%, #000 70%);
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
                border: 2px solid #a64bf4;
                background: rgba(20, 0, 40, 0.92);
                box-shadow: 0 0 25px rgba(166, 75, 244, 0.6), 0 0 60px rgba(234, 0, 255, 0.3);
                text-shadow: 0 0 10px #a64bf4;
                animation: glowPulse 2s infinite alternate;
                backdrop-filter: blur(12px);
            }

            @keyframes glowPulse {
                0% { box-shadow: 0 0 20px #a64bf4, 0 0 40px #ea00ff; }
                100% { box-shadow: 0 0 35px #ea00ff, 0 0 70px #a64bf4; }
            }

            .swal2-title {
                font-size: 1.6rem;
                color: #cbbaff;
                text-shadow: 0 0 15px #a64bf4, 0 0 30px #ea00ff;
                letter-spacing: 1px;
            }

            .swal2-confirm {
                background: linear-gradient(90deg, #a64bf4, #ea00ff) !important;
                color: #000 !important;
                border: none !important;
                font-family: "Ethnocentric", sans-serif !important;
                box-shadow: 0 0 15px #a64bf4 !important;
                animation: neonPulse 3s infinite alternate ease-in-out;
                transition: transform 0.25s ease, box-shadow 0.25s ease;
            }

            .swal2-confirm:hover {
                transform: scale(1.05);
                box-shadow: 0 0 25px #ea00ff, 0 0 50px #a64bf4 !important;
            }

            @keyframes neonPulse {
                0% { box-shadow: 0 0 10px #a64bf4, 0 0 20px #ea00ff; }
                100% { box-shadow: 0 0 25px #ea00ff, 0 0 45px #a64bf4; }
            }

            .swal2-show {
                animation: fadeInNeon 0.5s ease-out forwards;
            }
            .swal2-hide {
                animation: fadeOutNeon 0.6s ease-in forwards;
            }

            @keyframes fadeInNeon {
                from { opacity: 0; transform: scale(0.85); }
                to { opacity: 1; transform: scale(1); }
            }

            @keyframes fadeOutNeon {
                from { opacity: 1; transform: scale(1); }
                to { opacity: 0; transform: scale(0.9); filter: blur(2px); }
            }
        </style>
    </head>
    <body>
    <script>
    Swal.fire({
        icon: "success",
        title: "¡Registro completado!",
        text: "Tu cuenta fue creada con éxito. Ahora puedes iniciar sesión.",
        background: "rgba(20, 0, 40, 0.92)",
        color: "#fff",
        confirmButtonText: "Iniciar sesión",
        customClass: { popup: "neon-border" },
        showClass: { popup: "swal2-show" },
        hideClass: { popup: "swal2-hide" }
    }).then(() => {
        setTimeout(() => {
            window.location.href = "login.php";
        }, 350);
    });
    </script>
    </body>
    </html>';
    exit();
}
?>
