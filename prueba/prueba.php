<?php
$saldo = 500;
$mensaje = " ";
$eleccion = $_GET["variable"];
$premio = $_GET["premio"];
echo "Elegiste: {$eleccion}";
echo "Premio: {$premio}";

if ($eleccion == $premio) {
    $saldo += 200;
    $mensaje = "¡Ganaste! <br>";
    echo "{$mensaje} <br>";
    echo "Saldo: {$saldo}";
} else {
    $saldo -= 200;
    echo "Saldo: {$saldo}";
    echo "<br> Perdiste";
}

if ($saldo == 0) {
    $mensaje = "Perdiste <br>";
    echo "{$mensaje}";
}
