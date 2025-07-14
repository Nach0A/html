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
    } else {
        echo '<script type="text/javascript">
        window.location.href = "login.html.php";
        alert("Nombre o contrasenia incorrectos.");
        </script>';
        $bd->cerrarConexion();
        exit();
    }
} else {
    if ($bd->nombreUsado($bd->getConexion(), $nombre)) {
        $bd->cerrarConexion();
        echo "hola"; 
        echo '<script type="text/javascript">
        window.location.href = "login.html.php";
        alert("El usuario ya existe."); 
        </script>';
        exit();
    } else {
        $bd->registro($bd->getConexion(), $nombre, $contrasenia);
        $bd->cerrarConexion();
        $_SESSION['nombre'] = $nombre;
        header("Location: inicio.html.php");
        exit();
    }
}