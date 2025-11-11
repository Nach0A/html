<?php
require_once "../pagina-principal/Conexion_BD.php";
session_start();

if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    exit("Error: usuario no autenticado.");
}

if (!isset($_POST['puntos'], $_POST['intentos'], $_POST['dificultad'], $_POST['id_juego'])) {
    http_response_code(400);
    exit("Error: datos incompletos.");
}

$puntos     = max(0, intval($_POST['puntos']));
$intentos   = max(0, intval($_POST['intentos']));
$dificultad = $_POST['dificultad'];
$id_juego   = intval($_POST['id_juego']);   // 3
$usuario    = $_SESSION['usuario'];

$validDiffs = ['easy','normal','hard','impossible'];
if (!in_array($dificultad, $validDiffs, true)) {
    http_response_code(400);
    exit("Error: dificultad inválida.");
}

$bd   = new conexion_BD();
$conn = $bd->getConexion();

$id_usuario = $bd->getIdUsuario($usuario);
$correo     = $bd->obtenerCorreo($id_usuario);

// Buscar registro previo del usuario en este juego y dificultad
$sql = "SELECT puntos, intentos FROM juega WHERE id_usuario = ? AND id_juego = ? AND dificultad = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    exit("Prep error SELECT: ".$conn->error);
}
$stmt->bind_param("iis", $id_usuario, $id_juego, $dificultad);
$stmt->execute();
$res = $stmt->get_result();

if ($res && $res->num_rows > 0) {
    $row   = $res->fetch_assoc();
    $pPrev = (int)($row['puntos'] ?? 0);
    $iPrev = isset($row['intentos']) ? (int)$row['intentos'] : null;

    $mejoraPuntos   = ($puntos > $pPrev);
    $mejoraIntentos = ($iPrev === null) || ($intentos < $iPrev);

    if ($mejoraPuntos || $mejoraIntentos) {
        $newP = max($pPrev, $puntos);
        $newI = ($iPrev === null) ? $intentos : min($iPrev, $intentos);

        $update = $conn->prepare("
            UPDATE juega
               SET puntos = ?, intentos = ?, dificultad = ?
             WHERE id_usuario = ? AND id_juego = ? AND dificultad = ?
        ");
        if (!$update) {
            http_response_code(500);
            exit("Prep error UPDATE: ".$conn->error);
        }
        $update->bind_param("iisiss", $newP, $newI, $dificultad, $id_usuario, $id_juego, $dificultad);
        $ok = $update->execute();
        $update->close();

        if (!$ok) {
            http_response_code(500);
            exit("Exec error UPDATE: ".$conn->error);
        }
        echo "Actualizado correctamente.";
    } else {
        echo "Sin mejoras.";
    }

} else {
    // Insert nuevo (Mosqueta no usa 'tiempo')
    $ins = $conn->prepare("
        INSERT INTO juega (gmail_usuario, id_juego, id_usuario, nom_usuario, puntos, intentos, dificultad)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    if (!$ins) {
        http_response_code(500);
        exit("Prep error INSERT: ".$conn->error);
    }
    // FIRMA CORRECTA: s i i s i i s
    $ins->bind_param("siisiis", $correo, $id_juego, $id_usuario, $usuario, $puntos, $intentos, $dificultad);
    $ok = $ins->execute();
    $ins->close();

    if (!$ok) {
        http_response_code(500);
        exit("Exec error INSERT: ".$conn->error);
    }
    echo "Puntaje guardado.";
}

$stmt->close();
$conn->close();
