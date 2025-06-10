<?php
$servidor = "localhost";
$usuario = "root";
$pass = "";
$base = "zentryx";
$nombre = $_POST["nombre"];
$contrasenia = $_POST["contrasenia"];
$ini = $_POST["ini"];
$reg = $_POST["reg"];
echo "Nombre: {$nombre} ";
echo "Passwd: {$contrasenia}<br>";
$conexion = new mysqli($servidor, $usuario, $pass, $base);
if ($conexion->connect_error) {
    die("No se pudo conectar a la base: " . $conexion->connect_error);
  }

  function inicio($conexion, $nombre, $contrasenia) {
    $consulta = mysqli_query($conexion, "SELECT * FROM usuarios WHERE nom_usuario='{$nombre}' AND passwd='{$contrasenia}'");
    if (mysqli_data_seek($consulta, 0) == true) {
      echo "Bienvenido, usuario";
    } else {
      echo "Nombre o contrasenia incorrectos";
    }
}
 function registro($conexion, $nombre, $contrasenia) {
  $consulta = mysqli_query($conexion, "INSERT INTO `usuarios` (`nom_usuario`, `passwd`) VALUES ('{$nombre}', '{$contrasenia}');" );
  echo "Usuario {$nombre} fue registrado";
 }
if ($ini == 1) {
  inicio($conexion, $nombre, $contrasenia);
}
if ($reg == 0) {
  registro($conexion, $nombre, $contrasenia);
}