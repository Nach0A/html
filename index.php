<?php
require_once 'Conexion_BD.php';
require_once __DIR__ . '/main_final/pagina-principal/Conexion_BD.php';

// Crear instancia de conexión
$conexion = new conexion_BD();

// Si todo funciona, redirigir a la página principal
header("Location: main_final/pagina-principal/Inicio.php");
exit();
?>
