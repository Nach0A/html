<?php
require_once "Conexion_BD.php";
session_start();
$bd = new conexion_BD();
if ($bd->getIni() == 1) {
if ($bd->inicio()) {
        $_SESSION['usuario'] = $bd->getNombre();
        $_SESSION['foto'] = $bd->obtenerFoto($bd->getNombre());
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
        $bd->registro();
        $bd->cerrarConexion();
        $_SESSION['usuario'] = $bd->getNombre();
        header("Location: Inicio.php");
        exit();
    }