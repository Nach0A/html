<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['correo_ing'])) {
    echo json_encode(['ok' => false, 'msg' => 'Sesión inválida.']);
    exit();
}

$now   = time();
$last  = $_SESSION['last_code_sent_at'] ?? 0;
$wait  = $_SESSION['resend_wait_seconds'] ?? 60;
$count = $_SESSION['resend_count'] ?? 0;
$limit = $_SESSION['resend_limit'] ?? 5;

$remaining = ($last + $wait) - $now;
if ($remaining > 0) {
    echo json_encode(['ok' => false, 'msg' => 'Esperá antes de reenviar.', 'remaining' => $remaining]);
    exit();
}

if ($count >= $limit) {
    echo json_encode(['ok' => false, 'msg' => 'Alcanzaste el límite de reenvíos.']);
    exit();
}

// Enviar un nuevo código
require_once "Correo.php";
$mail = new Correo();
$mail->enviarCorreo(); // re-genera $_SESSION['codigo']

// Actualizar timers (opcional: backoff exponencial hasta 5 min)
$_SESSION['last_code_sent_at']  = $now;
$_SESSION['resend_count']       = $count + 1;
$_SESSION['resend_wait_seconds']= min($wait * 2, 300); // 60 -> 120 -> 240 -> 300...

// Opcional: renovar expiración del código al reenviar (si querés):
$_SESSION['codigo_expires_at']  = time() + 10 * 60;

echo json_encode(['ok' => true, 'cooldown' => $_SESSION['resend_wait_seconds']]);
