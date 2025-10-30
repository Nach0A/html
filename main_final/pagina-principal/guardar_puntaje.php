<?php
require_once "../pagina-principal/Conexion_BD.php";
session_start();

if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo "No autorizado";
    exit;
}

$usuario = $_SESSION['usuario'];
$puntos = $_POST['puntos'] ?? 0;
$id_juego = $_POST['id_juego'] ?? 0; // ← Se recibe dinámicamente

if ($id_juego == 0) {
    http_response_code(400);
    echo "Falta el ID del juego";
    exit;
}

$bd = new conexion_BD();
$id_usuario = $bd->getIdUsuario($usuario);
$correo = $bd->obtenerCorreo($id_usuario);

$bd->agregarPuntaje($usuario, $puntos, $correo, $id_juego);

echo "Puntaje guardado con éxito";
