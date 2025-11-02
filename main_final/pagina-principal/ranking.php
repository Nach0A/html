<?php
require_once "../pagina-principal/Conexion_BD.php";
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../pagina-principal/login.php");
    exit;
}

$bd = new conexion_BD();
$conn = $bd->getConexion();

// Lista de juegos
$juegos = [
    1 => "Memory",
    2 => "Buscaminas",
    3 => "Mosqueta"
];

// Validar id_juego
$id_juego = isset($_GET['id_juego']) && array_key_exists(intval($_GET['id_juego']), $juegos)
    ? intval($_GET['id_juego'])
    : 1;

// Consulta
$stmt = $conn->prepare("
    SELECT nom_usuario, MAX(puntos) AS mejor_puntaje
    FROM juega
    WHERE id_juego = ?
    GROUP BY nom_usuario
    ORDER BY mejor_puntaje DESC
    LIMIT 20
");

if (!$stmt) {
    die("Error al preparar consulta: " . $conn->error);
}

$stmt->bind_param("i", $id_juego);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ranking Zentryx</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../pagina-principal/estilo.css">
    <style>
        .ranking-container { margin-top: 30px; padding: 20px; }
        .game-select {
            width: 250px; margin: 20px auto;
            background: #1a0026; border: 2px solid #ff00cc;
            color: #ff99ff; border-radius: 10px; padding: 10px;
            text-align: center; box-shadow: 0 0 10px #660066;
        }
        .table {
            border-radius: 15px; overflow: hidden;
            box-shadow: 0 0 15px #660066;
        }
        .table th {
            background-color: #1a0026; color: #ff99ff;
        }
        @media (max-width: 768px) {
            h1 { font-size: 1.5rem; }
            .table th, .table td { font-size: 0.9rem; padding: 8px; }
            .game-select { width: 90%; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg shadow-sm py-3" style="background-color: rgb(20,20,20);">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold fs-4 text-white" href="#inicio" id="linkLogo">
                <img src="../navbar/imagenes/logo.jpg" width="30" height="30" class="d-inline-block align-text-top">
                &nbsp;Zentryx
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link text-white" href="../pagina-principal/Inicio.php#inicio">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="../pagina-principal/Inicio.php#juegos">Juegos</a></li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center text-white"
                           id="userDropdown" role="button" data-bs-toggle="dropdown">
                           <img src="<?php echo htmlspecialchars($_SESSION['foto'] ?? '../navbar/imagenes/usuario.png'); ?>"
                                class="user-avatar shadow-sm" alt="Usuario">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end fade-menu">
                            <li><a class="dropdown-item" href="../pagina-principal/perfil.php">
                                Perfil (<?php echo htmlspecialchars($_SESSION['usuario']); ?>)
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <h1 class="text-center mt-4 text-light">🏆 Ranking de Zentryx 🕹️</h1>

    <form method="GET" action="ranking.php" class="text-center">
        <select name="id_juego" class="game-select" onchange="this.form.submit()">
            <?php
            foreach ($juegos as $id => $nombre) {
                $selected = ($id == $id_juego) ? 'selected' : '';
                echo "<option value='$id' $selected>$nombre</option>";
            }
            ?>
        </select>
    </form>

    <div class="ranking-container container">
        <div class="table-responsive">
            <table class="table table-dark table-striped table-bordered align-middle">
                <thead>
                    <tr><th>Posición</th><th>Usuario</th><th>Mejor Puntaje</th></tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        $pos = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$pos}</td>
                                    <td>" . htmlspecialchars($row['nom_usuario']) . "</td>
                                    <td>{$row['mejor_puntaje']}</td>
                                  </tr>";
                            $pos++;
                        }
                    } else {
                        echo "<tr><td colspan='3'>Aún no hay puntajes registrados para este juego.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
