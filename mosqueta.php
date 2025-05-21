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
/*
$vasos = ['*', '*', '*'];
$premio = rand(0, 2);
for ($i=0; $i < 3; $i++) {
    if ($i != $premio) {
        echo "* <br>";
    } else {
        echo "[{$vasos[$premio]}] <br>";
     }

}
echo "<br>";

$premio = rand(0, 2);
for ($i=0; $i < 3; $i++) {
    if ($i != $premio) {
        echo "* <br>";
    } else {
        echo "[{$vasos[$premio]}] <br>";
    }
}
echo "<br>";

$premio = rand(0, 2);
for ($i=0; $i < 3; $i++) {
    if ($i != $premio) {
        echo "* <br>";
    } else {
        echo "[{$vasos[$premio]}] <br>";
    }
}

echo "<br>";
for ($i=0; $i < 3; $i++) {
    if ($i != $premio) {
        echo "* <br>";
    } else {
        echo "{$vasos[$premio]} <br>";
    }
}

$eleccion = $_GET["eleccion"];

echo "{$premio}";
if (is_integer($eleccion) == 1) {
        if ($eleccion == $premio) {
            $mensaje = "Ganaste";
            echo "{$mensaje} <br>";
            $saldo += 200;
            echo "Saldo: {$saldo}";
    } else {
            $mensaje = "Perdiste";
            echo "{$mensaje} <br>";
            $saldo -= 200;
            echo "Saldo: {$saldo}";
    }
}
*/
?>
