Flujo de la aplicación

    Al cargar la página, se genera un deck mezclado de 24 cartas.
    El temporizador comienza en 00:00 (mm:ss).
    El jugador hace clic en cartas para voltearlas.
    Si dos cartas coinciden, quedan boca arriba y se incrementa el contador de pares.
    Si no coinciden, se giran pasados 1s.
    Al encontrar los 12 pares, se detiene el temporizador y aparece un modal con estadísticas.

Explicación de módulos

    HTML (index.html)

        Estructura semántica.
        Contenedor de juego y botones.
        Modal para victoria.

    CSS (style.css)

        Grid responsivo: 3 columnas × 8 filas (1 columna en móviles).
        Transiciones y transformaciones 3D para volteo.
        Modal de victoria.

    JavaScript (script.js)

        Generación del deck y mezcla (Fisher–Yates).
        Gestión de eventos click.
        Control de estado (primer/segunda carta, número de pares).
        Temporizador en formato mm:ss.
        Popup al ganar.

    PHP (php.php)

        Archivo de ejemplo con comentarios para futura persistencia de resultados.