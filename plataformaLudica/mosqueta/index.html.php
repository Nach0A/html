<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilo.css">
    <title>Mosqueta</title>
</head>

<body>
    <form id="formulario" action="prueba.php" method="get">
        <input type="text" placeholder="Número de vaso" name="variable">
        <input type="hidden" name="premio" id="premio">
        <br>
        <button type="submit" onclick="mandarPremio()">Enviar</button>
    </form>
    <br>
    <button onclick="jugar()" id="boton">Jugar</button>

    <div class="vaso" id="0" style="left: 0px;">
        <div class="pelota" id="pelota_0"></div>
    </div>
    <div class="vaso" id="1" style="left: 140px;">
        <div class="pelota" id="pelota_1"></div>
    </div>
    <div class="vaso" id="2" style="left: 280px;">
        <div class="pelota" id="pelota_2"></div>
    </div>

    <script type="text/javascript">
        var premio = Math.floor(Math.random() * 3);          
        var vaso_0 = document.getElementById('0');
        var vaso_1 = document.getElementById('1');
        var vaso_2 = document.getElementById('2');
        var pelota = document.getElementById("pelota_" + premio);
        var vasos = [vaso_0, vaso_1, vaso_2];
        var pelotas = ['0', '0', '0']
        pelotas.splice(premio, 1, '1');
        function mandarPremio() {
            for (let i = 0; i < 3; i++) {
                if(pelotas[i] == '1') {
                    premio = i;
                }
            }
            document.getElementById('premio').value = premio;
        }    

        function jugar() {
            let boton = document.getElementById("boton");
            boton.disabled = true;
            pelota.style.display = "block";
            setTimeout(() => {
            pelota.style.display = "none";
            }, 2000);
            setTimeout(() => {
            for (let i=0; i < 5; i++) {                       
            setTimeout(() => {
            let j = Math.floor(Math.random() * 3); // Valor aleatorio de una variable j
            let k;
            do {                                   //
            k = Math.floor(Math.random() * 3);     // Si j es igual a k, k toma otro valor aleatorio
            }while(j == k);                        //
            const leftJ = window.getComputedStyle(vasos[j]).left; // lee la posicion del vaso j 
            const leftK = window.getComputedStyle(vasos[k]).left; // lee la posicion del vaso k
            vasos[j].style.left = leftK; //mueve el vaso j a la posicion del vaso k
            vasos[k].style.left = leftJ; // mueve el vaso k a la posicion del vaso j
            [pelotas[j], pelotas[k]] = [pelotas[k], pelotas[j]]; // las pelotas siguen a su vaso
              }, i*500);//Se multiplica el tiempo de espera por i, para que cada ejecucion espere a que termine la anterior
        }
            }, 2000);
        }
    </script>
</body>

</html>
</html>