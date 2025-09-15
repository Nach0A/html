document.getElementById("btnLogin").addEventListener("click", () => {
    document.getElementById("loginSection").style.display = "block";
    document.getElementById("registerSection").style.display = "none";
    btnLogin.classList.add("active");
    btnRegister.classList.remove("active");
});

document.getElementById("btnRegister").addEventListener("click", () => {
    document.getElementById("loginSection").style.display = "none";
    document.getElementById("registerSection").style.display = "block";
    btnRegister.classList.add("active");
    btnLogin.classList.remove("active");
});
