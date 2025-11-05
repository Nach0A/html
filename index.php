<?php
require_once 'Conexion_BD.php';

// Crear instancia de conexión
$conexion = new conexion_BD();

// Probar la conexión (opcional durante desarrollo)
$conexion->probarConexion();

// Si todo funciona, redirigir a la página principal
header("Location: main_final/pagina-principal/Inicio.php");
exit();
?>
