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
    private $correo;
    private $ini;
    private $calle;
    private $departamento;
    private $num_calle;

    public function __construct()
    {
        $this->servidor = "localhost";
        $this->usuario = "root";
        $this->pass = "";
        $this->base = "zentryx";
        $this->nombre = $_POST["nombre"] ?? $_POST["input"] ?? null;
        $this->contrasenia = $_POST["contrasenia"] ?? null;
        $this->correo = $_POST["gmail"] ?? null;
        $this->ini = $_POST["ini"] ?? null;
        $this->calle = $_POST["calle"] ?? null;
        $this->departamento = $_POST["departamento"] ?? null;
        $this->num_calle = $_POST["num_calle"] ?? null;
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
    public function setCorreo($correo)
    {
        $this->correo = $correo;
    }
    public function setCalle($calle)
    {
        $this->calle = $calle;
    }
    public function setDepartamento($departamento)
    {
        $this->departamento = $departamento;
    }
    public function setNumCalle($num_calle)
    {
        $this->num_calle = $num_calle;
    }

    public function getConexion()
    {
        return $this->conexion;
    }

    public function getCorreo()
    {
        return $this->correo;
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
    public function getCalle()
    {
        return $this->calle;
    }
    public function getDepartamento()
    {
        return $this->departamento;
    }
    public function getNumCalle()
    {
        return $this->num_calle;
    }

    public function inicio()
    {
        $input = $this->nombre; // puede ser usuario o mail
        $contra = trim(hash('sha256', $this->contrasenia));
        $gmailHash = hash('sha256', $input);
        $sql = "SELECT nom_usuario 
            FROM usuarios JOIN 
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
        if ($this->nombreUsado() || $this->mailUsado()) {
            echo '<script type="text/javascript">
        alert("El nombre de usuario ya está en uso o el correo ya está registrado.");
        window.location.href = "login.php";
        </script>';
            $this->cerrarConexion();
            exit();
        } else {
            $nombre = $this->nombre; 
            $contrasenia = trim(hash('sha256', $this->contrasenia));
            $gmailHash = trim(hash('sha256', $this->correo)); 
            $stmt = $this->conexion->prepare("INSERT INTO `usuarios` (`nom_usuario`, `passwd`, `gmail_usuario`) VALUES (?, ?, ?)"); //Para que solo sustiyuya los ?
            $stmt->bind_param("sss", $nombre, $contrasenia, $gmailHash); //Indicamos los valores por los que vamos a sustituir los ? por tres datos tipo string
            $stmt->execute(); //Ejecutamos la consulta
            $stmt->close();
        }
    }

    public function mailUsado() {
        $mail = $this->correo; 
        $gmailHash = hash('sha256', $mail);
        $consulta = mysqli_query($this->conexion, "SELECT nom_usuario FROM usuarios WHERE gmail_usuario='{$gmailHash}' LIMIT 1");
        return $consulta && mysqli_num_rows($consulta) > 0;
    }

    public function nombreUsado()
    {
        $nom = $this->nombre; // sin hash
        $consulta = mysqli_query($this->conexion, "SELECT nom_usuario FROM usuarios WHERE nom_usuario='{$nom}' LIMIT 1");

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
    public function obtenerFoto() {
    $nombre = $this->nombre;
    $sql = "SELECT imagen_perfil FROM usuarios WHERE nom_usuario={$nombre}  LIMIT 1";
    $stmt = $this->conexion->prepare($sql);
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    return $row['imagen_perfil'] ?? null;
}

public function agregarAdmin() {
    $calle = trim(hash('sha256',$this->calle));
    $departamento = trim(hash('sha256',$this->departamento));
    $contrasenia = trim(hash('sha256', $this->contrasenia));
    $num_calle = trim(hash('sha256', $this->num_calle));   
    $gmail = trim(hash('sha256', $this->correo));
    $stmt = $this->conexion->prepare("INSERT INTO `administrador` (`calle`, `departamento`, `gmail_admin`, `num_calle`, `passwd_admin`) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $calle, $departamento, $gmail, $num_calle, $contrasenia);
    $stmt->execute();
    $stmt->close();
}
}