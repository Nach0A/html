<?php
require_once "conexion_BD.php";
include "login.html.php";
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
    if ($bd->inicio($bd->getConexion(), $nombre, $contrasenia) == false) {
        $bd->registro($bd->getConexion(), $nombre, $contrasenia);
        $bd->cerrarConexion();
        $_SESSION['nombre'] = $nombre;
        header("Location: inicio.html.php");
        exit();
    }elseif($bd->nombreUsado($bd->getConexion(), $nombre)) {
        $bd->cerrarConexion();
        echo '<script type="text/javascript">
        window.location.href = "login.html.php";
        alert("El nombre ya esta usado.");
        </script>';
        exit();
    } else {
        $bd->cerrarConexion();
        echo '<script type="text/javascript">
        window.location.href = "login.html.php";
        alert("El usuario ya esta registrado.");
        </script>';
    }
}