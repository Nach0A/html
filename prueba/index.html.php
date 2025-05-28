<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mosqueta</title>
</head>

<body>
    <form id="formulario" action="../prueba/prueba.php" method="get">
        <input type="text" placeholder="Número de vaso" name="variable">
        <input type="hidden" name="premio" id="premio">
        <br>
        <button type="submit" onclick="mandarPremio()">Enviar</button>
    </form>
    <div id="contenedor"></div>
    <br>
    <div id="contenedor1"></div>
    <br>
    <div id="contenedor2"></div>
    <br>
    <div id="contenedor3"></div>

    <script type="text/javascript">
        var premio = Math.floor(Math.random() * 3);
        console.log(premio);
        var vasos = ['*', '*', '*'];
        for (var i = 0; i < 3; i++) {
            if (i == premio) {
                var contenedor = document.getElementById("contenedor");
                var etiqueta = document.createElement("label");
                contenedor.appendChild(etiqueta);
                vasos.splice(i, 1, '[*]');
                etiqueta.textContent = vasos + " ";
                vasos.splice(i, 1, '*');
            }
        }
        console.log(" ");
        premio = Math.floor(Math.random() * 3);
        console.log(premio);
        for (var i = 0; i < 3; i++) {
            if (i == premio) {
                var contenedor = document.getElementById("contenedor1");
                var etiqueta = document.createElement("label");
                vasos.splice(i, 1, '[*]');
                etiqueta.textContent = vasos + " ";
                vasos.splice(i, 1, '*');
                contenedor.appendChild(etiqueta);
            }
        }
        console.log(" ");
        premio = Math.floor(Math.random() * 3);
        console.log(premio);
        for (var i = 0; i < 3; i++) {
            if (i == premio) {
                var contenedor = document.getElementById("contenedor2");
                vasos.splice(i, 1, '[*]');
                var etiqueta = document.createElement("label");
                etiqueta.textContent = vasos + " ";
                vasos.splice(i, 1, '*');
                contenedor.appendChild(etiqueta);
            }
        }
        for (var i = 0; i < 3; i++) {
            var contenedor = document.getElementById("contenedor3");
            var etiqueta = document.createElement("label");
            etiqueta.textContent = " " + vasos[i];
            contenedor.appendChild(etiqueta);
        }
        premio = Math.floor(Math.random() * 3);
        console.log(" ");
        console.log(premio);
        function mandarPremio() {
            document.getElementById('premio').value = premio;
        }
    </script>
</body>

</html>
</html>