<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña</title>
</head>
<body>
    <form method="post" action="verifica_codigo.php">
        <input type="text" name="entrada" placeholder="Ingresa el código recibido por correo" required>
        <input type="text" name="nueva_contra" placeholder="Ingresa tu nueva contraseña" required>
        <button type="submit">Enviar código</button>
    </form>
</body>
</html>