<?php
require_once "./Conexion_BD.php";
session_start();
session_unset();
session_destroy();
header("Location: login.php");