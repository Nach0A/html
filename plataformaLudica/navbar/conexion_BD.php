<?php
$servidor = "localhost";
$usuario = "root";
$pass = "";
$base = "zentryx";
$nombre = $_POST["nombre"];
$contrasenia = $_POST["contrasenia"];
$ini = $_POST["ini"];
$conexion = new mysqli($servidor, $usuario, $pass, $base);
if ($conexion->connect_error) {
    die("No se pudo conectar a la base: " . $conexion->connect_error);
  }

  function inicio($conexion,$nombre, $contrasenia) {
    $consulta = mysqli_query($conexion, "SELECT * FROM usuarios WHERE nom_usuario='{$nombre}' AND passwd='{$contrasenia}'");
    return mysqli_data_seek($consulta, 0) == true;
}
 function registro($conexion, $nombre, $contrasenia) {
  if (inicio($conexion, $nombre, $contrasenia) == false) {
  $consulta = mysqli_query($conexion, "INSERT INTO `usuarios` (`nom_usuario`, `passwd`) VALUES ('{$nombre}', '{$contrasenia}');" );
  } else {
    include "login.html";
    echo '<script type="text/javascript">
          alert("El usuario ya existe");
          </script>';
  }
 }
if ($ini == 1) {
  inicio($conexion, $nombre, $contrasenia);
  if (inicio($conexion,$nombre, $contrasenia) == true) {
    include "Inicio.html"; //Es  conexion_BD.php pero se ve como Inicio.html
    include "conexion_BD.php#inicio"; //Cargamos el inicio de conexion_BD.php
  }
} else {
  registro($conexion, $nombre, $contrasenia);
}