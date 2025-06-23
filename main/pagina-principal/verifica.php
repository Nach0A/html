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
  //      $_SESSION['ini'] = 1; prar implementar en la caja de errores mas adelante
        echo '<script type="text/javascript">
        alert("Nombre o contrasenia incorrectos.");
        window.location.href = "login.html.php";
        </script>';
    }
} else {
    if ($bd->inicio($bd->getConexion(), $nombre, $contrasenia) == false) {
        $bd->registro($bd->getConexion(), $nombre, $contrasenia);
        $bd->cerrarConexion();
        $_SESSION['nombre'] = $nombre;
        header("Location: inicio.html.php");
        exit();
    } else {
   //     $_SESSION['reg'] = 1; para implementar en la caja de errores mas adelante
        echo '<script type="text/javascript">
        alert("El usuario ya está registrado.");
        window.location.href = "login.html.php";
        </script>';
        $bd->cerrarConexion();
        exit();
    }
}