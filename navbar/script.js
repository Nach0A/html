
    
        // Obtengo referencias a los elementos
        const userMenu = document.getElementById("userMenu");
        const mainContent = document.getElementById("mainContent");
        const juegosContent = document.getElementById("juegosContent");

        // Función para renderizar el dropdown del avatar (perfil) según si hay usuario logueado
        function renderMenu() {
            const loggedInUser = localStorage.getItem("loggedInUser");

            // Oculto siempre ambas secciones; luego mostraré la que corresponda
            mainContent.style.display = "none";
            juegosContent.style.display = "none";

            if (loggedInUser) {
                // Si está logueado, muestro opciones de perfil y Cerrar Sesión
                userMenu.innerHTML = `
                    <li><a class="dropdown-item" href="#">Perfil (${loggedInUser})</a></li>
                    <li><a class="dropdown-item" href="#">Configuración</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" onclick="logout()">Cerrar sesión</a></li>
                `;
                // Si el usuario está logueado, por defecto muestro “Inicio”
                mostrarInicio();
            } else {
                // Si NO está logueado, muestro el enlace a login.html
                userMenu.innerHTML = `
                    <li><a class="dropdown-item" href="login.html">Iniciar sesión / Registrarse</a></li>
                `;
                // Ya que no hay sesión, por defecto muestro “Inicio” (que es el contenido principal)
                mostrarInicio();
            }
        }

        // Funciones para mostrar/ocultar secciones
        function mostrarInicio() {
            mainContent.classList.add("fade-in");
            mainContent.style.display = "block";
            juegosContent.style.display = "none";
            // Actualizo el hash en la URL
            if (location.hash !== "#inicio") {
                history.replaceState(null, "", "#inicio");
            }
        }

        function mostrarJuegos() {
            juegosContent.classList.add("fade-in");
            juegosContent.style.display = "block";
            mainContent.style.display = "none";
            // Actualizo el hash en la URL
            if (location.hash !== "#juegos") {
                history.replaceState(null, "", "#juegos");
            }
        }

        // Evento para los links del navbar
        document.getElementById("linkInicio").addEventListener("click", (e) => {
            e.preventDefault();
            mostrarInicio();
        });
        document.getElementById("linkJuegos").addEventListener("click", (e) => {
            e.preventDefault();
            mostrarJuegos();
        });

        // Función para cerrar sesión
        function logout() {
            localStorage.removeItem("loggedInUser");
            renderMenu();
        }

        // Al cargar la página, oculto el preloader y hago render del menú
        window.addEventListener("load", () => {
            // Ocultar preloader
            const preloader = document.getElementById("preloader");
            preloader.style.opacity = "0";
            preloader.style.visibility = "hidden";
            preloader.style.pointerEvents = "none";

            // Primero renderizo el menú (dropdown)
            renderMenu();

            // Luego, según el hash en la URL, muestro la sección que toque
            const hash = location.hash;
            if (hash === "#juegos") {
                mostrarJuegos();
            } else {
                // Cualquier otro caso (incluyendo #inicio o sin hash), muestro “Inicio”
                mostrarInicio();
            }
        });
    