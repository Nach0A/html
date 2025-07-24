<?php
require_once "Conexion_BD.php";
session_start();
$nombre = $_POST['nombre'];
$contrasenia = $_POST['contrasenia'];
$bd = new Conexion_BD();
if ($bd->getIni() == 1) {
    if ($bd->inicio($bd->getConexion(), md5($bd->getNombre()), md5($bd->getContrasenia()))) {
        $_SESSION['usuario'] = $bd->getNombre();
        $bd->cerrarConexion();
        header("Location: Inicio.php");
        exit();
    } else {
        echo '<script type="text/javascript">
        window.location.href = "login.php";
        alert("Nombre o contrasenia incorrectos.");
        </script>';
        $bd->cerrarConexion();
        exit();
    }
} else {
        $bd->registro($bd->getConexion(), md5($bd->getNombre()), md5($bd->getContrasenia()));
        $bd->cerrarConexion();
        header("Location: login.php");
        exit();
    }