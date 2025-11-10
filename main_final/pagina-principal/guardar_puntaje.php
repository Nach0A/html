<?php
// guardar_puntaje.php (versión debug) ------------------------------------------------
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "../pagina-principal/Conexion_BD.php";
session_start();

// Log de lo que llegó (fecha, POST, COOKIES, SESSION)
$logfile = __DIR__ . '/guardar_debug.log';
$entry = date('c') . " - REMOTE_ADDR: " . ($_SERVER['REMOTE_ADDR'] ?? 'N/A') . PHP_EOL;
$entry .= "POST: " . json_encode($_POST) . PHP_EOL;
$entry .= "COOKIES: " . json_encode($_COOKIE) . PHP_EOL;
$entry .= "SESSION: " . json_encode($_SESSION) . PHP_EOL;
file_put_contents($logfile, $entry . PHP_EOL, FILE_APPEND);

// Validaciones básicas
if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo "Error: usuario no autenticado. SESSION empty.";
    exit;
}
if (!isset($_POST['puntos'], $_POST['id_juego'], $_POST['tiempo'])) {
    http_response_code(400);
    echo "Error: datos incompletos. POST: " . json_encode($_POST);
    exit;
}

// Variables
$puntos = intval($_POST['puntos']);
$id_juego = intval($_POST['id_juego']);
$tiempo = intval($_POST['tiempo']);
$usuario = $_SESSION['usuario'];

file_put_contents($logfile, date('c') . " -> Validado usuario: $usuario, id_juego: $id_juego, puntos: $puntos, tiempo: $tiempo" . PHP_EOL, FILE_APPEND);

// Conexión DB (adaptá si tu clase usa otro método)
$bd = new conexion_BD();
if (method_exists($bd, 'getConexion')) {
    $conn = $bd->getConexion();
} else if (method_exists($bd, 'conectar')) {
    // ejemplo, adapta host/cred si hace falta
    $conn = $bd->conectar("localhost", "root", "", "zentryx");
} else {
    file_put_contents($logfile, date('c') . " -> ERROR: conexion_BD no tiene getConexion/conectar" . PHP_EOL, FILE_APPEND);
    echo "Error: no hay método de conexión en Conexion_BD.";
    exit;
}

if (!$conn) {
    file_put_contents($logfile, date('c') . " -> ERROR: conexión nula." . PHP_EOL, FILE_APPEND);
    echo "Error: no hay conexión a BD.";
    exit;
}

// Obtener id_usuario y correo (asegurate que estos métodos existan)
$id_usuario = $bd->getIdUsuario($usuario);
$correo = $bd->obtenerCorreo($id_usuario);
file_put_contents($logfile, date('c') . " -> id_usuario: $id_usuario, correo: $correo" . PHP_EOL, FILE_APPEND);

// Verificar existencia
$sql_check = "SELECT puntos, tiempo FROM juega WHERE id_usuario = ? AND id_juego = ?";
$stmt = $conn->prepare($sql_check);
if (!$stmt) {
    file_put_contents($logfile, date('c') . " -> ERROR prepare SELECT: " . $conn->error . PHP_EOL, FILE_APPEND);
    echo "Error prepare SELECT: " . $conn->error;
    exit;
}
$stmt->bind_param("ii", $id_usuario, $id_juego);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nuevoPuntaje = ($puntos > $row['puntos']);
    $nuevoTiempo = ($row['tiempo'] === null || $tiempo < $row['tiempo']);
    if ($nuevoPuntaje || $nuevoTiempo) {
        $sql_update = "UPDATE juega SET puntos = ?, tiempo = ? WHERE id_usuario = ? AND id_juego = ?";
        $stmt2 = $conn->prepare($sql_update);
        if (!$stmt2) {
            file_put_contents($logfile, date('c') . " -> ERROR prepare UPDATE: " . $conn->error . PHP_EOL, FILE_APPEND);
            echo "Error prepare UPDATE: " . $conn->error;
            exit;
        }
        $stmt2->bind_param("iiii", $puntos, $tiempo, $id_usuario, $id_juego);
        if ($stmt2->execute()) {
            echo "Puntaje/tiempo actualizado correctamente.";
            file_put_contents($logfile, date('c') . " -> UPDATE OK" . PHP_EOL, FILE_APPEND);
        } else {
            file_put_contents($logfile, date('c') . " -> ERROR execute UPDATE: " . $stmt2->error . PHP_EOL, FILE_APPEND);
            echo "Error execute UPDATE: " . $stmt2->error;
        }
    } else {
        echo "No se superó el puntaje ni el tiempo anterior.";
        file_put_contents($logfile, date('c') . " -> No superó puntaje/tiempo" . PHP_EOL, FILE_APPEND);
    }
} else {
    $sql_insert = "INSERT INTO juega (gmail_usuario, id_juego, id_usuario, nom_usuario, puntos, tiempo) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt3 = $conn->prepare($sql_insert);
    if (!$stmt3) {
        file_put_contents($logfile, date('c') . " -> ERROR prepare INSERT: " . $conn->error . PHP_EOL, FILE_APPEND);
        echo "Error prepare INSERT: " . $conn->error;
        exit;
    }
    $stmt3->bind_param("siisii", $correo, $id_juego, $id_usuario, $usuario, $puntos, $tiempo);
    if ($stmt3->execute()) {
        echo "Nuevo puntaje y tiempo guardados.";
        file_put_contents($logfile, date('c') . " -> INSERT OK" . PHP_EOL, FILE_APPEND);
    } else {
        file_put_contents($logfile, date('c') . " -> ERROR execute INSERT: " . $stmt3->error . PHP_EOL, FILE_APPEND);
        echo "Error execute INSERT: " . $stmt3->error;
    }
}

if (isset($stmt)) $stmt->close();
if (isset($stmt2)) $stmt2->close();
if (isset($stmt3)) $stmt3->close();
$conn->close();
?>
