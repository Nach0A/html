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

$puntos     = intval($_POST['puntos']);
$intentos   = intval($_POST['intentos']);
$dificultad = $_POST['dificultad'];
$id_juego   = intval($_POST['id_juego']);   // debería ser 3
$usuario    = $_SESSION['usuario'];

// (Opcional) Validar dificultad a un set conocido
$validDiffs = ['easy','normal','hard','impossible'];
if (!in_array($dificultad, $validDiffs, true)) {
    http_response_code(400);
    exit("Error: dificultad inválida.");
}

$bd   = new conexion_BD();
$conn = $bd->getConexion();

$id_usuario = $bd->getIdUsuario($usuario);
$correo     = $bd->obtenerCorreo($id_usuario);

// Verificar si tiene registro previo
$sql = "SELECT puntos, intentos FROM juega WHERE id_usuario = ? AND id_juego = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_usuario, $id_juego);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();

    // Mejor puntaje (mayor) o menos intentos (menor) -> actualizar
    if ($puntos > $row['puntos'] || $row['intentos'] === null || $intentos < $row['intentos']) {
        $update = $conn->prepare("UPDATE juega 
            SET puntos=?, intentos=?, dificultad=? 
            WHERE id_usuario=? AND id_juego=?");
        $update->bind_param("iisii", $puntos, $intentos, $dificultad, $id_usuario, $id_juego);
        $update->execute();
        $update->close();
        echo "Actualizado correctamente.";
    } else {
        echo "No se superó el puntaje anterior.";
    }
} else {
    // Insert nuevo
    $ins = $conn->prepare("
        INSERT INTO juega (gmail_usuario, id_juego, id_usuario, nom_usuario, puntos, intentos, dificultad)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $ins->bind_param("siisiss", $correo, $id_juego, $id_usuario, $usuario, $puntos, $intentos, $dificultad);
    $ins->execute();
    $ins->close();
    echo "Puntaje guardado.";
}

$stmt->close();
$conn->close();
