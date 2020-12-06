<?php
//clase para devolver las respuestas de las peticiones como array, para devolver el código de error por el status code.
class respuesta{
    public $response = [
        "status" => "ok",
        "result" => array()
    ];

    public function error($id = "400", $msg = "Error"){
        $this->response['status'] = "error";
        $this->response['result'] = array(
            "error_id" => $id,
            "error_msg" => $msg
        );
        return $this->response;
    }
}

?>