<?php
$servidor = "localhost";
$usuario = "root";
$pass = "";
$base = "zentryx";

$conexion = new mysqli($servidor, $usuario, $pass);
if ($conexion->connect_error) {
    die("No se pudo conectar a la base: " . $conexion->connect_error);
  }
 $prueba = mysqli_select_db($conexion, $base);
 $consulta = mysqli_query($conexion, "SELECT * FROM usuarios WHERE nom_usuario='Juan' AND passwd='1234567'");
if (mysqli_data_seek($consulta, 0) == true) {
  echo "Bienvenido";
} else {
  echo "Nombre o contrasenia incorrectos";
}