<?php
const $servidor = "localhost";
const $usuario = "root";
const $pass = "";
const $base = "zentryx";
$nombre_usuario = $_POST["nombre_usuario"];
$passwd = $_POST["passwd"];

function nueva($servidor,$usuario,$pass,$base) {
    try {
        $conectar = mysqli_connect($servidor,$usuario,$pass,$base);
    } catch (Exception $ex) {
        die($ex->getMessage());
    }
    return $conectar;
}	
function seleccionarUsuarios() {
    $resultado = mysqli_query($this->conexion, "select * from usuarios");
    $arreglo = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
    return $arreglo;
}
function ingresarUsuario($nombre_usuario, $passwd) {
    $insertar = "insert into cuenta values('$nombre_usuario','$passwd')";
    return mysqli_query($this->conexion, $insertar);
}
public function validar($nombre_usuario, $passwd) {
    $consulta = mysqli_prepare{$this->conexion,
    "SELECT * FROM usuarios WHERE nombre_usuario = '$nombre_usuario' AND passwd = '$passwd'"}
    mysqli_start_bind_param(consulta, "ss", $numero, $nip);
    mysqli_stat_execute($consulta);
    $resultado = mysqli_stat_get_result($consulta);
    return (mysqli_num_rows($resultado) > 0);
}
?>