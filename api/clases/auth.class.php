<?php
session_start();
require_once "conexion.php";
require_once "respuesta.class.php";

class auth extends conexion {

    public function login($json){
        $_respuesta = new respuesta;
        //obtenemos los datos del post
        $datos = json_decode($json, true);

        //validamos si existe user en bd
        if(isset($datos['usuario']) && isset($datos['password'])){
            $usuario = $datos['usuario'];
            $password = $datos['password'];
            $sql = "SELECT * FROM hospital.doctores WHERE name LIKE '".$usuario."' AND password LIKE '".$password."';";
            if($doctor = $this->obtenerDatos($sql)){
                //existe usuario. Login correcto
                //creamos variable de sesión con el doctor que ha hecho login
                //MERORAR SISTEMA DE LOGIN
                $_SESSION["doctor"]= $doctor[0]['id'];

                return "Bienvenido ".$doctor[0]['name'];
            }else{
                //no existe usuario
                return $_respuesta->error('400',"Usuario o contraseña incorrecto.");
            }
        }else{
            return $_respuesta->error('405',"Datos incompletos o formato incorrecto.");
        }

    }

    public function logout(){
        session_destroy();
        return "Has cerrado sesión.";
    }

}


?>