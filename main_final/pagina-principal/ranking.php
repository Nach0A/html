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

// ==========================
// CONSULTA PRINCIPAL
// ==========================
if ($id_juego == 3) {

    // MOSQUETA → dificultad + intentos
    $stmt = $conn->prepare("
        SELECT nom_usuario, 
               MAX(puntos) AS mejor_puntaje,
               MIN(intentos) AS mejor_intentos,
               dificultad
        FROM juega
        WHERE id_juego = 3 AND dificultad IS NOT NULL
        GROUP BY nom_usuario, dificultad
        ORDER BY mejor_puntaje DESC, mejor_intentos ASC
        LIMIT 20
    ");

} else {

    // Memory / Buscaminas → puntos + tiempo
    $stmt = $conn->prepare("
        SELECT nom_usuario, 
               MAX(puntos) AS mejor_puntaje,
               MIN(tiempo) AS mejor_tiempo
        FROM juega
        WHERE id_juego = ?
        GROUP BY nom_usuario
        ORDER BY mejor_puntaje DESC, mejor_tiempo ASC
        LIMIT 20
    ");
}

if (!$stmt) {
    die("Error al preparar consulta: " . $conn->error);
}

if ($id_juego != 3) {
    $stmt->bind_param("i", $id_juego);
}

$stmt->execute();
$result = $stmt->get_result();

// ==========================
// CONSULTA RÉCORD GENERAL
// ==========================
if ($id_juego == 3) {

    $stmt2 = $conn->prepare("
        SELECT nom_usuario, puntos, intentos, dificultad
        FROM juega
        WHERE id_juego = 3 AND dificultad IS NOT NULL
        ORDER BY puntos DESC, intentos ASC
        LIMIT 1
    ");

} else {

    $stmt2 = $conn->prepare("
        SELECT nom_usuario, puntos, tiempo
        FROM juega
        WHERE id_juego = ?
        ORDER BY puntos DESC, tiempo ASC
        LIMIT 1
    ");

    $stmt2->bind_param("i", $id_juego);
}

$stmt2->execute();
$record = $stmt2->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ranking Zentryx</title>
    <link rel="icon" href="../navbar/imagenes/logo.jpg" type="image/jpeg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../pagina-principal/estilo.css">
</head>

<body>
    <!-- PRELOADER -->
    <div id="preloader">
        <img src="../navbar/imagenes/logo.jpg" alt="Logo Zentryx" id="preloader-logo">
    </div>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg shadow-sm py-3" style="background-color: rgb(20,20,20);">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold fs-4 text-white" href="../pagina-principal/inicio.php#inicio" id="linkLogo">
                <img src="../navbar/imagenes/logo.jpg" width="30" height="30" class="d-inline-block align-text-top">
                &nbsp;Zentryx
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../pagina-principal/inicio.php#inicio">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../pagina-principal/inicio.php#juegos">Juegos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white active" href="../pagina-principal/ranking.php">Ranking</a>
                    </li>
                </ul>

                <!-- Perfil -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center text-white" id="userDropdown"
                            role="button" data-bs-toggle="dropdown">
                            <img src="<?php echo htmlspecialchars($_SESSION['foto'] ?? '../navbar/imagenes/usuario.png'); ?>"
                                class="user-avatar shadow-sm" alt="Usuario">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end fade-menu">
                            <li>
                                <a class="dropdown-item" href="../pagina-principal/perfil.php">
                                    Perfil (<?php echo htmlspecialchars($_SESSION['usuario']); ?>)
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="../pagina-principal/logout.php">Cerrar sesión</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- MAIN -->
    <main class="ranking-main fade-in-up">
        <div class="ranking-header text-center">
            <h1 class="ranking-title">RANKING ZENTRYX</h1>

            <form method="GET" action="ranking.php">
                <select name="id_juego" class="ranking-select" onchange="this.form.submit()">
                    <?php
                    foreach ($juegos as $id => $nombre) {
                        $selected = ($id == $id_juego) ? 'selected' : '';
                        echo "<option value='$id' $selected>$nombre</option>";
                    }
                    ?>
                </select>
            </form>
        </div>

        <div class="ranking-board container">

            <!-- RÉCORD GENERAL -->
            <?php if ($record): ?>
                <div class="text-center my-4">
                    <?php if ($id_juego == 3): ?>
                        <h4 class="text-warning fw-bold">
                            🏆 Récord general de Mosqueta:
                            <?php echo htmlspecialchars($record['nom_usuario']); ?> —
                            <?php echo $record['puntos']; ?> pts ·
                            dificultad: <?php echo htmlspecialchars($record['dificultad']); ?> ·
                            intentos: <?php echo htmlspecialchars($record['intentos']); ?>
                        </h4>
                    <?php else: ?>
                        <h4 class="text-warning fw-bold">
                            🏆 Récord general de <?php echo htmlspecialchars($juegos[$id_juego]); ?>:
                            <?php echo htmlspecialchars($record['nom_usuario']); ?> —
                            <?php echo $record['puntos']; ?> pts en
                            <?php echo $record['tiempo']; ?> seg
                        </h4>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- TABLA -->
            <div class="ranking-table-wrapper">
                <table class="ranking-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jugador</th>
                            <th>Juego</th>

                            <?php if ($id_juego == 3): ?>
                                <th>Dificultad</th>
                                <th>Intentos</th>
                                <th>Puntos</th>
                            <?php else: ?>
                                <th>Tiempo (s)</th>
                                <th>Puntos</th>
                            <?php endif; ?>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        if ($result && $result->num_rows > 0) {
                            $pos = 1;

                            while ($row = $result->fetch_assoc()) {

                                $clase = ($pos == 1) ? "gold" :
                                        (($pos == 2) ? "silver" :
                                        (($pos == 3) ? "bronze" : ""));

                                if ($id_juego == 3) {
                                    // MOSQUETA
                                    echo "<tr class='{$clase}'>
                                            <td>{$pos}</td>
                                            <td>".htmlspecialchars($row['nom_usuario'])."</td>
                                            <td>Mosqueta</td>
                                            <td>".htmlspecialchars($row['dificultad'])."</td>
                                            <td>".htmlspecialchars($row['mejor_intentos'])."</td>
                                            <td>".htmlspecialchars($row['mejor_puntaje'])."</td>
                                          </tr>";
                                } else {
                                    // MEMORY / BUSCAMINAS
                                    echo "<tr class='{$clase}'>
                                            <td>{$pos}</td>
                                            <td>".htmlspecialchars($row['nom_usuario'])."</td>
                                            <td>".htmlspecialchars($juegos[$id_juego])."</td>
                                            <td>".htmlspecialchars($row['mejor_tiempo'])."</td>
                                            <td>".htmlspecialchars($row['mejor_puntaje'])."</td>
                                          </tr>";
                                }

                                $pos++;
                            }
                        } else {
                            echo "<tr><td colspan='6'>Aún no hay puntajes registrados.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../navbar/script.js"></script>
</body>

</html>
