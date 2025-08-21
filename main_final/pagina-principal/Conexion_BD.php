<?php
class conexion_BD
{
    private $servidor;
    private $usuario;
    private $pass;
    private $base;
    private $conexion;
    private $nombre;
    private $nombre_grupo;
    private $contrasenia;
    private $gmail;
    private $ini;

    public function __construct()
    {
        $this->servidor = "localhost";
        $this->usuario = "root";
        $this->pass = "";
        $this->base = "zentryx";
        $this->nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : (isset($_POST["mail"]) ? $_POST["mail"] : "");
        $this->contrasenia = $_POST["contrasenia"] ?? null;
        $this->gmail = isset($_POST["gmail"]) ? $_POST["gmail"] : "";

        $this->ini = $_POST["ini"] ?? null;

        $this->conexion = $this->conectar($this->servidor, $this->usuario, $this->pass, $this->base);
    }

    public function conectar($servidor, $usuario, $pass, $base)
    {
        $conexion = new mysqli($servidor, $usuario, $pass, $base);
        if ($conexion->connect_error) {
            die("No se pudo conectar a la base: " . $conexion->connect_error);
        }
        return $conexion;
    }

    public function cerrarConexion()
    {
        if ($this->conexion) {
            $this->conexion->close();
        }
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setNombreGrupo($nombre_grupo)
    {
        $this->nombre_grupo = $nombre_grupo;
    }

    public function setContrasenia($contrasenia)
    {
        $this->contrasenia = $contrasenia;
    }

    public function getConexion()
    {
        return $this->conexion;
    }

    public function getServidor()
    {
        return $this->servidor;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function getPass()
    {
        return $this->pass;
    }


    public function getBase()
    {
        return $this->base;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getNombreGrupo()
    {
        return $this->nombre_grupo;
    }

    public function getContrasenia()
    {
        return $this->contrasenia;
    }

    public function getIni()
    {
        return $this->ini;
    }

    public function inicio()
    {
        $input = $this->nombre; // puede ser usuario o mail
        $contra = trim(hash('sha256', $this->contrasenia));
        $gmailHash = hash('sha256', $input);

        $sql = "SELECT nom_usuario 
            FROM usuarios 
            WHERE (nom_usuario='{$input}' OR gmail_usuario='{$gmailHash}')
              AND passwd='{$contra}'
            LIMIT 1";
        $consulta = mysqli_query($this->conexion, $sql);

        if ($consulta && mysqli_num_rows($consulta) === 1) {
            $row = mysqli_fetch_assoc($consulta);
            // guardar SIEMPRE el nom_usuario real
            $this->nombre = $row['nom_usuario'];
            return true;
        }
        return false;
    }


    public function registro()
    {
        if ($this->nombreUsado()) {
            echo '<script type="text/javascript">
        alert("El nombre de usuario ya está en uso.");
        window.location.href = "login.php";
        </script>';
            $this->cerrarConexion();
            exit();
        } else {
            $nombre = $this->nombre; // nom_usuario en claro
            $contrasenia = trim(hash('sha256', $this->contrasenia));
            $gmailHash = trim(hash('sha256', $this->gmail)); // gmail encriptado

            mysqli_query($this->conexion, "INSERT INTO `usuarios` (`nom_usuario`, `passwd`, `gmail_usuario`) VALUES ('{$nombre}', '{$contrasenia}', '{$gmailHash}')");
        }
    }

    public function nombreUsado()
    {
        $nom = $this->nombre; // sin hash
        $consulta = mysqli_query($this->conexion, "SELECT 1 FROM usuarios WHERE nom_usuario='{$nom}' LIMIT 1");
        return $consulta && mysqli_num_rows($consulta) > 0;
    }


    public function grupo($conexion, $nombre, $nombre_grupo)
    {
        /*Agregar un nombre del grupo */
        $consulta = mysqli_query($conexion, "INSERT INTO 'grupos'(id_grupo, nom_grupo, nom_usuario) VALUES ('1', '{$nombre}', '{$nombre_grupo}')");
    }

    public function listarUsuarios()
    {
        $consulta = mysqli_query($this->conexion, "SELECT nom_usuario FROM usuarios");
        if ($consulta) {
            while ($row = mysqli_fetch_assoc($consulta)) {
                echo $row['nom_usuario'] . "<br>";
            }
        }
    }
}
