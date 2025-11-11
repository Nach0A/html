// ==== Obtener referencias (pueden NO existir en algunas páginas) ====
const userMenu = document.getElementById("userMenu");
const mainContent = document.getElementById("mainContent");
const juegosContent = document.getElementById("juegosContent");

// ==== Renderiza el menú de usuario ====
function renderMenu() {

    // Si no existe userMenu, significa que esta página NO usa este menú
    if (!userMenu) return;

    const loggedInUser = localStorage.getItem("loggedInUser");

    // Oculta secciones SOLO si existen
    if (mainContent) mainContent.style.display = "none";
    if (juegosContent) juegosContent.style.display = "none";

    if (loggedInUser) {
        userMenu.innerHTML = `
            <li><a class="dropdown-item" href="#">Perfil (${loggedInUser})</a></li>
            <li><a class="dropdown-item" href="#">Configuración</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#" onclick="logout()">Cerrar sesión</a></li>
        `;
        mostrarInicio();
    } else {
        userMenu.innerHTML = `
            <li><a class="dropdown-item" href="../navbar/login.php">Iniciar sesión / Registrarse</a></li>
        `;
        mostrarInicio();
    }
}

// ==== Mostrar Inicio ====
function mostrarInicio() {
    if (mainContent) {
        mainContent.classList.add("fade-in");
        mainContent.style.display = "block";
    }

    if (juegosContent) {
        juegosContent.style.display = "none";
    }

    if (location.hash !== "#inicio") {
        history.replaceState(null, "", "#inicio");
    }
}

// ==== Mostrar Juegos ====
function mostrarJuegos() {
    if (juegosContent) {
        juegosContent.classList.add("fade-in");
        juegosContent.style.display = "block";
    }

    if (mainContent) {
        mainContent.style.display = "none";
    }

    if (location.hash !== "#juegos") {
        history.replaceState(null, "", "#juegos");
    }
}

// ==== Logout ====
function logout() {
    localStorage.removeItem("loggedInUser");
    renderMenu();
}

// ==== LOAD ====
window.addEventListener("load", () => {

    // Preloader (solo si existe)
    const preloader = document.getElementById("preloader");
    if (preloader) {
        preloader.style.opacity = "0";
        preloader.style.visibility = "hidden";
        preloader.style.pointerEvents = "none";
    }

    // Render menu solo si aplica
    renderMenu();

    // Hash (solo si existen las secciones)
    const hash = location.hash;
    if (hash === "#juegos") {
        mostrarJuegos();
    } else {
        mostrarInicio();
    }
});
