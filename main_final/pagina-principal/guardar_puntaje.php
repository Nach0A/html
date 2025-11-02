<?php
require_once "../pagina-principal/Conexion_BD.php";
session_start();

if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo "Error: usuario no autenticado.";
    exit;
}

if (!isset($_POST['puntos'], $_POST['id_juego'])) {
    http_response_code(400);
    echo "Error: datos incompletos.";
    exit;
}

$puntos = intval($_POST['puntos']);
$id_juego = intval($_POST['id_juego']);
$usuario = $_SESSION['usuario'];

$bd = new conexion_BD();
$conn = $bd->getConexion();
$id_usuario = $bd->getIdUsuario($usuario);
$correo = $bd->obtenerCorreo($id_usuario);

// Verificar si ya existe puntaje para ese usuario/juego
$sql_check = "SELECT puntos FROM juega WHERE id_usuario = ? AND id_juego = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("ii", $id_usuario, $id_juego);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($puntos > $row['puntos']) {
        $sql_update = "UPDATE juega SET puntos = ? WHERE id_usuario = ? AND id_juego = ?";
        $stmt2 = $conn->prepare($sql_update);
        $stmt2->bind_param("iii", $puntos, $id_usuario, $id_juego);
        $stmt2->execute();
        echo "✅ Puntaje actualizado correctamente.";
    } else {
        echo "⚠️ El nuevo puntaje no supera el anterior.";
    }
} else {
    $sql_insert = "INSERT INTO juega (gmail_usuario, id_juego, id_usuario, nom_usuario, puntos)
                   VALUES (?, ?, ?, ?, ?)";
    $stmt3 = $conn->prepare($sql_insert);
    $stmt3->bind_param("sii si", $correo, $id_juego, $id_usuario, $usuario, $puntos);
    $stmt3->execute();
    echo "✅ Nuevo puntaje guardado.";
}

$stmt->close();
$conn->close();
?>
