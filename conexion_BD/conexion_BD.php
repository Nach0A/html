<?php
$servidor = "localhost";
$usuario = "root";
$pass = "";
$base = "zentryx";
$nombre = $_POST["nombre"];
$contrasenia = $_POST["contrasenia"];
$conexion = new mysqli($servidor, $usuario, $pass);
if ($conexion->connect_error) {
    die("No se pudo conectar a la base: " . $conexion->connect_error);
  }
  function inicio($conexion, $base, $nombre, $contrasenia) {
  if (mysqli_select_db($conexion, $base) == true) {
    $consulta = mysqli_query($conexion, "SELECT * FROM usuarios WHERE nom_usuario={$nombre} AND passwd={$contrasenia}");
    if (mysqli_data_seek($consulta, 0) == true) {
      echo "Bienvenido";
    } else {
      echo "Nombre o contrasenia incorrectos";
    }
  }
}

function registro($conexion, $base, $nombre, $contrasenia): void {
  $consulta = mysqli_query($conexion, "INSERT INTO usuarios('nom_usuario', 'passwd') VALUES('{$nombre}', '{$contrasenia}')" );
  echo "Usuario {$nombre} fue registrado";
}
