<?php
require_once "Conexion_BD.php";
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
$bd = new conexion_BD();
$_SESSION['foto'] = $bd->obtenerFoto($_SESSION['usuario']);