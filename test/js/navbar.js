/* scripts login */
const userMenu = document.getElementById("userMenu");
const loginForm = document.getElementById("loginForm");
const mainContent = document.getElementById("mainContent");
const loginError = document.getElementById("loginError");

function renderMenu() {
                const loggedInUser = localStorage.getItem("loggedInUser");

                loginForm.style.display = "none";
                mainContent.style.display = "none";
                document.getElementById("juegosContent").style.display = "none";

                if (loggedInUser) {
                    userMenu.innerHTML = `
            <li><a class="dropdown-item" href="#">Perfil (${loggedInUser})</a></li>
            <li><a class="dropdown-item" href="#">Configuración</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#" onclick="logout()">Cerrar sesión</a></li>
        `;
                    loginForm.style.display = "none";
                    mainContent.style.display = "block";
                } else {
                    userMenu.innerHTML = `
            <li><a class="dropdown-item" href="#" onclick="showLogin()">Iniciar sesión / Registrarse</a></li>
        `;
                    mainContent.style.display = "block";
                }
            }

/* mostrar login y registrar */
function showLogin() {
    loginError.style.display = "none";
    loginForm.classList.add("fade-in");
    loginForm.style.display = "block";
    mainContent.style.display = "none";
    document.getElementById("juegosContent").style.display = "none";
}

function login() {
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value;

    const users = JSON.parse(localStorage.getItem("users")) || {};
    const storedPassword = users[username];

    if (storedPassword && storedPassword === password) {
        localStorage.setItem("loggedInUser", username);
        loginError.style.display = "none";
        renderMenu();
    } else {
        loginError.textContent = "Nombre de usuario o contraseña incorrectos.";
        loginError.style.display = "block";
    }
}

function register() {
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value;

    if (!username || !password) {
        loginError.textContent = "Por favor, completa ambos campos para registrarte.";
        loginError.style.display = "block";
        return;
    }

    const users = JSON.parse(localStorage.getItem("users")) || {};

    if (users[username]) {
        loginError.textContent = "El nombre de usuario ya está registrado.";
        loginError.style.display = "block";
    } else {
        users[username] = password;
        localStorage.setItem("users", JSON.stringify(users));
        localStorage.setItem("loggedInUser", username);
        loginError.style.display = "none";
        renderMenu();
    }
}
/* cierre de sesión */
function logout() {
    localStorage.removeItem("loggedInUser");
    renderMenu();
}

function mostrarInicio() {
    mainContent.classList.add("fade-in");
    loginForm.style.display = "none";
    mainContent.style.display = "block";
    document.getElementById("juegosContent").style.display = "none";
}
function mostrarJuegos() {
    loginForm.style.display = "none";
    mainContent.style.display = "none";
    document.getElementById("juegosContent").classList.add("fade-in");
    document.getElementById("juegosContent").style.display = "block";
}

renderMenu();

/* Esconder el icono de carga */
window.addEventListener("load", () => {
    const preloader = document.getElementById("preloader");
    preloader.style.opacity = "0";
    preloader.style.visibility = "hidden";
    preloader.style.pointerEvents = "none";
});