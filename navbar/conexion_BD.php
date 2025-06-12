<?php
header("Location: login.html");
$servidor = "localhost";
$usuario = "root";
$pass = "";
$base = "zentryx";
$nombre = $_POST["nombre"];
$contrasenia = $_POST["contrasenia"];
$ini = $_POST["ini"];
echo "Nombre: {$nombre} ";
echo "Passwd: {$contrasenia}<br>";
$conexion = new mysqli($servidor, $usuario, $pass, $base);
if ($conexion->connect_error) {
    die("No se pudo conectar a la base: " . $conexion->connect_error);
  }

  function inicio($conexion,$nombre, $contrasenia) {
    $consulta = mysqli_query($conexion, "SELECT * FROM usuarios WHERE nom_usuario='{$nombre}' AND passwd='{$contrasenia}'");
    return mysqli_data_seek($consulta, 0) == true;
}
 function registro($conexion, $nombre, $contrasenia) {
  $consulta = mysqli_query($conexion, "INSERT INTO `usuarios` (`nom_usuario`, `passwd`) VALUES ('{$nombre}', '{$contrasenia}');" );
  echo "Usuario {$nombre} fue registrado";
 }
if ($ini == 1) {
  inicio($conexion, $nombre, $contrasenia);
  if (inicio($conexion,$nombre, $contrasenia) == true) {
    echo "hola";
    header("Location: pagina-principal/Inicio.html#inicio");
  }
} else {
  registro($conexion, $nombre, $contrasenia);
}