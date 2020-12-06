<?php 
require_once "clases/conexion.php";
require_once 'clases/auth.class.php';

$conexion = new conexion;
//var_dump($conexion->obtenerDatos("SELECT * FROM hospital.doctores;"));

if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['logout'])){
    if($_GET['logout'] === 'true'){
        $_auth = new auth;
        echo $_auth->logout();
    }
}

?>
index