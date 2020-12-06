<?php
session_start();
require_once 'clases/respuesta.class.php';
require_once 'clases/pacientes.class.php';

$_respuesta = new respuesta;
$_pacientes = new pacientes;

//validamos si hay inicio de sesión
if(!isset($_SESSION['doctor'])) die(json_encode($_respuesta->error("403", "Acceso denegado.")));


switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if(isset($_GET['id'])){
            $resultData = $_pacientes->getPaciente($_GET['id']);
        }else{
            $resultData = $_pacientes->getPaciente($_GET['id']);
        }

        header('Content-Type: application/json');
        if(isset($resultData["result"]["error_id"])){
            $responseCode = $resultData["result"]["error_id"];  //enviamos en la cabecera como status el error_id
            http_response_code($responseCode);
        }else{
            http_response_code(200); //todo OK
        }
        echo json_encode($resultData);
        break;
    case 'POST':
        //recibimos los datos enviados
        $postBody = file_get_contents('php://input');
        //enviamos los datos a la clase
        echo json_encode($_pacientes->insertPaciente($postBody));
        //devolvemos respuesta al cliente

        break;
    case 'PUT':
        break;
    case 'DELETE':
        break;
    default:
        # code...
        break;
}

?>