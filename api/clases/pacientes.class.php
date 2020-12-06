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
                $respuesta = $_respuesta->response;
                $respuesta["result"] = array(
                    "pacienteId" => $paciente
                );
                return $respuesta;
            }else{
                //no existe paciente
                return $_respuesta->error('405',"Error al crear paciente. ");
            }
        }else{
            return $_respuesta->error('405','Datos del paciente incompletos.');
        }

    }

    public function editarPaciente($json){
        $_respuesta = new respuesta;

        $datos = json_decode($json,true);
        //validamos si existe doctor logeado
        if(!isset($_SESSION['doctor'])) return $_respuesta->error('403','Debes iniciar sesión para continuar.');
        //validamos que recibimos el id del paciente
        if(isset($datos['id']) ){
            $sql = "UPDATE hospital.pacientes SET name='".$datos['name']."', surname='".$datos['surname']."', birthdate='".$datos['birthdate']."' where id = ".$datos['id']." AND id_doctor = ".$_SESSION['doctor'].";";
            if($rows = parent::accionQuery($sql, $datos['id'], 'update')){
                if($rows >= 1){
                    //todo correcto
                    $respuesta = $_respuesta->response;
                    $respuesta["result"] = array(
                        "filasModificadas" => $rows
                    );
                    return $respuesta;
                }else{
                    return $_respuesta->error('405',"Error al editar el paciente. ");
                }
                
                
            }else{
                //no existe paciente
                return $_respuesta->error('405',"Error al editar paciente. ");
            }
        }else{
            return $_respuesta->error('405','Datos del paciente incompletos.');
        }

    }

    public function eliminarPaciente($json){
        $_respuesta = new respuesta;

        $datos = json_decode($json,true);
        //validamos si existe doctor logeado
        if(!isset($_SESSION['doctor'])) return $_respuesta->error('403','Debes iniciar sesión para continuar.');
        //validamos que recibimos el id del paciente
        if(isset($datos['id']) ){
            $sql = "DELETE FROM hospital.pacientes WHERE id = ".$datos['id']." AND id_doctor = ".$_SESSION['doctor'].";";
            if($rows = parent::accionQuery($sql, $datos['id'], 'delete')){
                if($rows >= 1){
                    //todo correcto
                    $respuesta = $_respuesta->response;
                    $respuesta["result"] = array(
                        "registrosEliminados" => $rows
                    );
                    return $respuesta;
                }else{
                    return $_respuesta->error('405',"Error al eliminar el paciente. ");
                }
                
                
            }else{
                //no existe paciente
                return $_respuesta->error('405',"Error al eliminar paciente. ");
            }
        }else{
            return $_respuesta->error('405','Datos incorrectos.');
        }
    }
}

?>