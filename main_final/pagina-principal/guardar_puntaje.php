<?php
require_once "../pagina-principal/Conexion_BD.php";
session_start();

// ==== VALIDACIONES ====
if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo "Error: usuario no autenticado.";
    exit;
}

if (!isset($_POST['puntos'], $_POST['id_juego'], $_POST['tiempo'])) {
    http_response_code(400);
    echo "Error: datos incompletos.";
    exit;
}

// ==== VARIABLES ====
$puntos = intval($_POST['puntos']);
$id_juego = intval($_POST['id_juego']);
$tiempo = intval($_POST['tiempo']); // tiempo en segundos
$usuario = $_SESSION['usuario'];

$bd = new conexion_BD();
$conn = $bd->getConexion();
$id_usuario = $bd->getIdUsuario($usuario);
$correo = $bd->obtenerCorreo($id_usuario);

// ==== VERIFICAR EXISTENCIA DE REGISTRO ====
$sql_check = "SELECT puntos, tiempo FROM juega WHERE id_usuario = ? AND id_juego = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("ii", $id_usuario, $id_juego);
$stmt->execute();
$result = $stmt->get_result();

// ==== ACTUALIZAR O INSERTAR ====
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nuevoPuntaje = false;
    $nuevoTiempo = false;

    // Si el nuevo puntaje es mayor, se actualiza
    if ($puntos > $row['puntos']) {
        $nuevoPuntaje = true;
    }

    // Si el nuevo tiempo es menor (mejor), se actualiza
    if ($row['tiempo'] === null || $tiempo < $row['tiempo']) {
        $nuevoTiempo = true;
    }

    if ($nuevoPuntaje || $nuevoTiempo) {
        $sql_update = "UPDATE juega SET puntos = ?, tiempo = ? WHERE id_usuario = ? AND id_juego = ?";
        $stmt2 = $conn->prepare($sql_update);
        $stmt2->bind_param("iiii", $puntos, $tiempo, $id_usuario, $id_juego);
        $stmt2->execute();
        echo " Puntaje/tiempo actualizado correctamente.";
    } else {
        echo " No se superó el puntaje ni el tiempo anterior.";
    }

} else {
    // INSERTAR NUEVO REGISTRO
    $sql_insert = "INSERT INTO juega (gmail_usuario, id_juego, id_usuario, nom_usuario, puntos, tiempo)
                   VALUES (?, ?, ?, ?, ?, ?)";
    $stmt3 = $conn->prepare($sql_insert);
    $stmt3->bind_param("siisii", $correo, $id_juego, $id_usuario, $usuario, $puntos, $tiempo);
    $stmt3->execute();
    echo " Nuevo puntaje y tiempo guardados.";
}

// ==== CIERRE ====
$stmt->close();
$conn->close();

