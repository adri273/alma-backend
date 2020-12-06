<?php
require_once 'clases/auth.class.php';
require_once 'clases/respuesta.class.php';

$_auth = new auth;
$_respuesta = new respuesta;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //recibimos los datos
    $postBody = file_get_contents("php://input");
    //enviamos los datos a la clase
    $respuestaLogin = $_auth->login($postBody);
    //devolvemos respuesta
    header('Content-Type: application/json');
    if(isset($respuestaLogin["result"]["error_id"])){
        $responseCode = $respuestaLogin["result"]["error_id"];  //enviamos en la cabecera como status el error_id
        http_response_code($responseCode);
    }else{
        http_response_code(200); //todo OK
    }
    echo json_encode($respuestaLogin);
}else{
    //si no accedemos por POST, mostramos error
    header('Content-Type: application/json');
    $respuestaLogin = $_respuesta->error('405','Método no permitido.');
    echo json_encode($respuestaLogin);
}


?>