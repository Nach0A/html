<?php
$saldo = 500;
$mensaje = " ";
$eleccion = $_POST["eleccion"];
$premio = $_POST["premio"];
echo "{$premio}";
if ($eleccion == $premio) {
    $saldo += 200;
    $mensaje = "¡Ganaste!";
    echo "{$mensaje} <br>";
    echo "Saldo: {$saldo}";
} else {
    $saldo -= 200;
    echo "Saldo: {$saldo}";
}

if ($saldo == 0) {
    $mensaje = "Perdiste <br>";
    echo "{$mensaje}";
}
?>