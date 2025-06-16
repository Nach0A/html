<?php
$saldo = 500;
$mensaje = " ";
$eleccion = $_GET["variable"];
$premio = $_GET["premio"] + 1;
echo "Elegiste: {$eleccion} <br>";
if ($eleccion == $premio) {
    $saldo += 200;
    $mensaje = "¡Correcto! <br>";
    echo "{$mensaje} <br>";
    echo "Saldo: {$saldo}";
} else {
    $saldo -= 200;
    echo "Incorrecto, la pelota estaba en {$premio}";
    echo "Saldo: {$saldo}";
}

if ($saldo == 0) {
    $mensaje = "Perdiste <br>";
    echo "{$mensaje}";
}
