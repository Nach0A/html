<?php
session_start();
if (!isset($_SESSION['correo_ing'])) {
    header("Location: recuperar.php");
    exit();
}

// Calcular segundos restantes del cooldown
$now = time();
$last = $_SESSION['last_code_sent_at'] ?? 0;
$wait = $_SESSION['resend_wait_seconds'] ?? 60;
$remaining = max(0, ($last + $wait) - $now);

// Opcional: mascar el correo para mostrarlo
function mask_email($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return $email;
    list($user, $domain) = explode("@", $email);
    $u = strlen($user) > 2 ? substr($user,0,2) . str_repeat("*", max(1, strlen($user)-2)) : $user[0] . "*";
    return $u . "@" . $domain;
}
$correo_mask = mask_email($_SESSION['correo_ing']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar código - Zentryx</title>

    <link rel="icon" href="../navbar/imagenes/logo.jpg" type="image/jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

<!-- preloader -->
<div id="preloader">
    <img src="../navbar/imagenes/logo.jpg" alt="Logo Zentryx" id="preloader-logo">
</div>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg shadow-sm py-3" style="background-color: rgb(20,20,20);">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold fs-4 text-white" href="login.php">
            <img src="../navbar/imagenes/logo.jpg" width="30" height="30" class="d-inline-block align-text-top">
            &nbsp;Zentryx
        </a>
    </div>
</nav>

<!-- CONTENEDOR PRINCIPAL -->
<div class="login-wrapper">
    <!-- LADO IZQUIERDO -->
    <div class="login-left">
        <div class="neon-circle"></div>
        <div class="neon-glow"></div>
        <h1 class="neon-title">ZENTRYX</h1>
        <p class="login-slogan">Verifica tu identidad</p>
    </div>

    <!-- LADO DERECHO -->
    <div class="login-right fade-in">
        <div class="card p-4" id="recoverForm">
            <h4 class="text-center mb-2">Código de recuperación</h4>
            <p class="text-center text-white-50 mb-3" style="font-size: 0.9rem;">
                Te enviamos un código a <span class="text-white"><?php echo htmlspecialchars($correo_mask); ?></span>.
            </p>

            <form action="verifica_codigo.php" method="post" autocomplete="off">
                <input type="text" name="entrada" class="textwhite" placeholder="Código de verificación (6 dígitos)" maxlength="6" required>
                <input type="password" name="nueva_contra" class="textwhite mt-3" placeholder="Nueva contraseña" required>
                <button type="submit" class="btn btn-primary w-100 mt-3">Cambiar contraseña</button>
            </form>

            <!-- Reenviar código -->
            <div class="d-flex align-items-center justify-content-between mt-4">
                <span class="text-white-50" style="font-size:0.9rem;">¿No recibiste el correo?</span>
                <button id="btnReenviar" class="btn btn-outline-light btn-sm" <?php echo $remaining>0?'disabled':''; ?>>
                    Reenviar código <span id="countdown"><?php echo $remaining>0 ? "($remaining s)" : ""; ?></span>
                </button>
            </div>

            <div class="text-center mt-3">
                <a href="recuperar.php" class="forgot-password-link">Usar otro correo</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

<!-- script -->
<script>
window.addEventListener("load", () => {
    const pre = document.getElementById("preloader");
    pre.style.opacity = "0";
    pre.style.visibility = "hidden";
    pre.style.pointerEvents = "none";
});

// Countdown y reenvío
(function(){
    let remaining = <?php echo (int)$remaining; ?>;
    const btn = document.getElementById('btnReenviar');
    const cd  = document.getElementById('countdown');

    function tick(){
        if (remaining > 0) {
            cd.textContent = `(${remaining} s)`;
            btn.disabled = true;
            remaining--;
            setTimeout(tick, 1000);
        } else {
            cd.textContent = '';
            btn.disabled = false;
        }
    }
    tick();

    btn?.addEventListener('click', async () => {
        btn.disabled = true;
        cd.textContent = '...';
        try {
            const res = await fetch('reenviar.php', { method: 'POST' });
            const data = await res.json();
            if (data.ok) {
                remaining = data.cooldown;
                tick();
            } else {
                alert(data.msg || 'No se pudo reenviar el código.');
                // Si viene tiempo restante, lo aplicamos
                if (typeof data.remaining !== 'undefined') {
                    remaining = data.remaining;
                    tick();
                } else {
                    btn.disabled = false;
                    cd.textContent = '';
                }
            }
        } catch (e) {
            alert('Error de red al reenviar.');
            btn.disabled = false;
            cd.textContent = '';
        }
    });
})();
</script>

</body>
</html>
