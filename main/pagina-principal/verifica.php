<?php
require_once "conexion_BD.php";
session_start();
$nombre = $_POST["nombre"];
$contrasenia = $_POST["contrasenia"];
$bd = new conexion_BD();
if ($bd->getIni() == 1) {
    if ($bd->inicio($bd->getConexion(), $bd->getNombre(), $bd->getContrasenia())) {
        $_SESSION['nombre'] = $nombre;
        $bd->cerrarConexion();
        header("Location: inicio.html.php");
        exit();
    }
} else {
    if ($bd->inicio($bd->getConexion(), $nombre, $contrasenia) == false) {
        $bd->registro($bd->getConexion(), $nombre, $contrasenia);
        $bd->cerrarConexion();
        header("Location: login.html.php");
        exit();
    } else {
        echo '<script type="text/javascript">
        alert("El usuario ya está registrado.");
        window.location.href = "login.html.php";
        </script>';
        $bd->cerrarConexion();
        exit();
    }
}