//////////////////////////////////////////////////////////////
// area de creacion de constants y funciones para el navbar //
//////////////////////////////////////////////////////////////

// Referencias a los elementos principales del DOM usados en el navbar
const userMenu = document.getElementById("userMenu");
const loginForm = document.getElementById("loginForm");
const mainContent = document.getElementById("mainContent");
const loginError = document.getElementById("loginError");

///////////////////////////////////////////////////////////////////
// fina area de creacion de constants y funciones para el navbar //
///////////////////////////////////////////////////////////////////

////////////////////////////////////////
// inicio de funciones para el navbar //
////////////////////////////////////////

// inico renderMenu
//Renderiza el menu de usuario dependiendo si hay un usuario logueado o no
//Muestra u oculta los elementos principales según el estado de autenticacion
function renderMenu() {
    const loggedInUser = localStorage.getItem("loggedInUser");

    // Oculta todos los paneles principales al iniciar
    if (loginForm) loginForm.style.display = "none";
    if (mainContent) mainContent.style.display = "none";
    const juegosContent = document.getElementById("juegosContent");
    if (juegosContent) juegosContent.style.display = "none";

    if (loggedInUser) {
        // Si hay usuario logueado, muestra opciones de perfil y logout
        userMenu.innerHTML = `
            <li><a class="dropdown-item" href="#">Perfil (${loggedInUser})</a></li>
            <li><a class="dropdown-item" href="#">Configuración</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#" onclick="logout()">Cerrar sesión</a></li>
        `;
        if (mainContent) mainContent.style.display = "block";
    } else {
        // Si no hay usuario logueado, muestra opción de login/registro
        userMenu.innerHTML = `
            <li><a class="dropdown-item" href="#" onclick="showLogin()">Iniciar sesión / Registrarse</a></li>
        `;
        if (mainContent) mainContent.style.display = "block";
    }
}
//fin renderMenu

// inico showLogin
//Muestra el formulario de login y oculta el contenido principal
function showLogin() {
    if (loginError) loginError.style.display = "none";
    if (loginForm) {
        loginForm.classList.add("fade-in");
        loginForm.style.display = "block";
    }
    if (mainContent) mainContent.style.display = "none";
    const juegosContent = document.getElementById("juegosContent");
    if (juegosContent) juegosContent.style.display = "none";
}
// fin showLogin

// inico login
//Intenta iniciar sesión con los datos ingresados.
//Si el usuario y contraseña coinciden, inicia sesión; si no, muestra error.
function login() {
    const username = document.getElementById("username")?.value.trim();
    const password = document.getElementById("password")?.value;

    const users = JSON.parse(localStorage.getItem("users")) || {};
    const storedPassword = users[username];

    if (storedPassword && storedPassword === password) {
        localStorage.setItem("loggedInUser", username);
        if (loginError) loginError.style.display = "none";
        renderMenu();
    } else {
        if (loginError) {
            loginError.textContent = "Nombre de usuario o contraseña incorrectos.";
            loginError.style.display = "block";
        }
    }
}
// fin login

// inico register
// Registra un nuevo usuario si el nombre no existe y ambos campos están completos.
// Si el registro es exitoso, inicia sesión automáticamente.
function register() {
    const username = document.getElementById("username")?.value.trim();
    const password = document.getElementById("password")?.value;

    if (!username || !password) {
        if (loginError) {
            loginError.textContent = "Por favor, completa ambos campos para registrarte.";
            loginError.style.display = "block";
        }
        return;
    }

    const users = JSON.parse(localStorage.getItem("users")) || {};

    if (users[username]) {
        if (loginError) {
            loginError.textContent = "El nombre de usuario ya está registrado.";
            loginError.style.display = "block";
        }
    } else {
        users[username] = password;
        localStorage.setItem("users", JSON.stringify(users));
        localStorage.setItem("loggedInUser", username);
        if (loginError) loginError.style.display = "none";
        renderMenu();
    }
}
// fin register


// inico logout
//Cierra la sesión del usuario actual.
function logout() {
    localStorage.removeItem("loggedInUser");
    renderMenu();
}
// fin logout

// inico mostrarInicio
//Muestra el contenido principal (inicio) y oculta los demás paneles.
function mostrarInicio() {
    if (mainContent) {
        mainContent.classList.add("fade-in");
        mainContent.style.display = "block";
    }
    if (loginForm) loginForm.style.display = "none";
    const juegosContent = document.getElementById("juegosContent");
    if (juegosContent) juegosContent.style.display = "none";
}
// fin mostrarInicio


// inico mostrarJuegos
// Muestra el contenido de juegos y oculta los demás paneles.
function mostrarJuegos() {
    if (loginForm) loginForm.style.display = "none";
    if (mainContent) mainContent.style.display = "none";
    const juegosContent = document.getElementById("juegosContent");
    if (juegosContent) {
        juegosContent.classList.add("fade-in");
        juegosContent.style.display = "block";
    }
}
// fin mostrarJuegos