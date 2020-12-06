<?php
session_start();
require_once "conexion.php";
require_once "respuesta.class.php";



class pacientes extends conexion {
    public function listaPacientes(){
        $_respuesta = new respuesta;

        $id_doctor = $_SESSION["doctor"];
        $sql = "SELECT * FROM hospital.pacientes WHERE id_doctor = $id_doctor ;";
        if($pacientes = parent::obtenerDatos($sql)){
                //existen pacientes
                return $pacientes;
            }else{
                //no existen pacientes
                return $_respuesta->error('200',"Todavía no tienes pacientes.");
            }
    }

    public function getPaciente($id){
        $_respuesta = new respuesta;

        $id_doctor = $_SESSION["doctor"];
        $sql = "SELECT * FROM hospital.pacientes WHERE id_doctor = $id_doctor AND id = $id ;";
        if($paciente = parent::obtenerDatos($sql, $id)){
            //existe paciente
            return $paciente;
        }else{
            //no existe paciente
            return $_respuesta->error('403',"Registro no encontrado. ");
        }
    }

    public function insertPaciente($json){
        $_respuesta = new respuesta;

        $datos = json_decode($json,true);
        //validamos si existe doctor logeado
        if(!isset($_SESSION['doctor'])) return $_respuesta->error('403','Debes iniciar sesión para continuar.');
        //validamos los datos obligatorios
        if(isset($datos['name']) && isset($datos['surname']) && isset($datos['birthdate'])){
            $sql = "INSERT INTO hospital.pacientes (name, surname, birthdate, id_doctor) VALUES ('".$datos['name']."', '".$datos['surname']."','".$datos['birthdate']."', ".$_SESSION['doctor'].");";
            if($paciente = parent::accionQueryId($sql)){
                //existe paciente
                return $paciente;
            }else{
                //no existe paciente
                return $_respuesta->error('405',"Error al crear paciente. ");
            }
        }else{
            return $_respuesta->error('405','Datos del paciente incompletos.');
        }

    }
}

?>