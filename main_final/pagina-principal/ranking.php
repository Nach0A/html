<?php
require_once "../pagina-principal/Conexion_BD.php";
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../pagina-principal/login.php");
    exit;
}

$bd = new conexion_BD();
$conn = $bd->getConexion();

// Lista de juegos (puedes ampliar en el futuro)
$juegos = [
    1 => "Memory",
    2 => "Buscaminas",
    3 => "Mosqueta"
];

// Obtener el ID del juego actual desde la URL (por defecto: 1)
$id_juego = isset($_GET['id_juego']) ? intval($_GET['id_juego']) : 1;

// Consulta de ranking según juego
$sql = "
SELECT nom_usuario, MAX(puntos) AS mejor_puntaje
FROM juega
WHERE id_juego = $id_juego
GROUP BY nom_usuario
ORDER BY mejor_puntaje DESC
LIMIT 20;
";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <head>
    <meta charset="UTF-8">
    <title>Ranking Zentryx</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tu estilo -->
    <link rel="stylesheet" href="../pagina-principal/estilo.css">
</head>

    <style>
        .ranking-container {
            margin-top: 30px;
            padding: 20px;
        }

        .game-select {
            width: 250px;
            margin: 20px auto;
            background: #1a0026;
            border: 2px solid #ff00cc;
            color: #ff99ff;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            box-shadow: 0 0 10px #660066;
        }

        .table {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 0 15px #660066;
        }

        .table th {
            background-color: #1a0026;
            color: #ff99ff;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 1.5rem;
            }
            .table th, .table td {
                font-size: 0.9rem;
                padding: 8px;
            }
            .game-select {
                width: 90%;
            }
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Botones Inicio / Juegos -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../pagina-principal/Inicio.php#inicio" id="linkInicio">Inicio</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" href="../pagina-principal/Inicio.php#juegos" id="linkJuegos">Juegos</a>
                    </li>
                </ul>

                <!-- Dropdown perfil -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center text-white"
                            id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <img src="<?php echo htmlspecialchars($_SESSION['foto'] ?? '../navbar/imagenes/usuario.png'); ?>"
                                class="user-avatar shadow-sm"
                                alt="Usuario">

                        </a>
                        <ul class="dropdown-menu dropdown-menu-end fade-menu">
                            <li>
                                <a class="dropdown-item" href="../pagina-principal/perfil.php">Perfil (<?php echo htmlspecialchars($_SESSION['usuario']);     ?>)</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="logout.php">Cerrar sesión</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <h1>🏆 Ranking de Zentryx 🕹️</h1>

    <form method="GET" action="ranking.php">
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
                    <tr>
                        <th>Posición</th>
                        <th>Usuario</th>
                        <th>Mejor Puntaje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        $pos = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>{$pos}</td>
                                    <td>{$row['nom_usuario']}</td>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
