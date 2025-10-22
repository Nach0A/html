<?php
session_start();
require_once "Conexion_BD.php"; 

// Instancia de DB
$bd = new conexion_BD();
$conexion = $bd->getConexion();

// Config (ajusta si tu estructura cambia)
$BASE_URL   = "/PlataformaLudica/main_final/";
$UPLOAD_DIR = __DIR__ . "/uploads/perfiles/";                  // ruta en disco donde se guardan archivos
$UPLOAD_WEB = $BASE_URL . "pagina-principal/uploads/perfiles/"; // ruta pública para <img> (consistente con perfil.php)

// Valores del formulario
$ini = $_POST['ini'] ?? null;
if($bd->esAdmin("35")) {
    echo '<script>alert("Acceso denegado para administradores."); window.location.href="login.php";</script>';
    exit();
}

if ($ini === "1") {
    // --- LOGIN ---
    $input = trim($_POST['input'] ?? '');
    $pass  = $_POST['contrasenia'] ?? '';

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
} elseif ($ini === "0") {
    // --- REGISTRO ---
    $nombre = trim($_POST['nombre'] ?? '');
    $gmail  = trim($_POST['gmail']  ?? '');
    $pass   = $_POST['contrasenia'] ?? '';

    if ($nombre === '' || $gmail === '' || $pass === '') {
        echo '<script>alert("Completa todos los campos de registro."); window.location.href="login.php";</script>';
        exit();
    }

    // Verificar duplicados 
    $check = "SELECT COUNT(*) AS cnt FROM usuarios WHERE nom_usuario = ? OR gmail_usuario = ?";
    $stmt = $conexion->prepare($check);
    $stmt->bind_param("ss", $nombre, $gmail);
    $stmt->execute();
    $cnt = $stmt->get_result()->fetch_assoc()['cnt'];
    $stmt->close();

    if ($cnt > 0) {
        echo '<script>alert("El nombre de usuario o correo ya existe."); window.location.href="login.php";</script>';
        exit();
    }

    // Insertar nuevo usuario
    $hash = hash("sha256", $pass);
    $insert = "INSERT INTO usuarios (nom_usuario, gmail_usuario, passwd) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($insert);
    $stmt->bind_param("sss", $nombre, $gmail, $hash);
    if ($stmt->execute()) {
        $_SESSION['usuario'] = $nombre;
        $_SESSION['foto'] = $BASE_URL . "navbar/imagenes/usuario.png";
        $stmt->close();
        $conexion->close();
        header("Location: Inicio.php");
        exit();
    } else {
        echo '<script>alert("Error al registrar. Intenta de nuevo."); window.location.href="login.php";</script>';
        exit();
    }
} else {
    
    header("Location: login.php");
    exit();
}
