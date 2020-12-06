<?php
session_start();
class conexion {
    //datos de conexión
    private $server, $user, $password, $database, $port, $conexion;

    function __construct(){
        $datos = $this->datosConexion();
        $this->server = $datos['conexion']['server'];
        $this->user = $datos['conexion']['user'];
        $this->password = $datos['conexion']['password'];
        $this->database = $datos['conexion']['database'];
        $this->port = $datos['conexion']['port'];

        $this->conectarBD();


        //$this->conexion->close();

        
        //var_dump($datosQuery);


    }

    //función para obtener los datos de conexión del archivo config
    private function datosConexion(){
        $path = dirname(__FILE__);
        $jsondata = file_get_contents($path . "/config");
        return json_decode($jsondata,true);
    }

    //funcion para conectar a la BD
    private function conectarBD() {
        $this->conexion = mysqli_connect($this->server.":".$this->port, $this->user, $this->password, $this->database);

        if(!$this->conexion){
            die("Connection failed: ". mysqli_connect_error());
        }else{

            /* cambiar el conjunto de caracteres a utf8 */
            if (!$this->conexion->set_charset("utf8")) {
                printf("Error codificando en utf8: %s\n", $this->conexion->error);
                exit();
            } 
        }
    }

    //función para ejecutar query select y devuelve array con resultados
    public function obtenerDatos($sql, $paciente=null){
        if ($resultados = $this->conexion->query($sql)) {
            $resultadosArray = array();
            foreach($resultados as $key){
                $resultadosArray[] = $key;
            }

            /* liberar el conjunto de resultados */
            $resultados->close();

            //Registramos las acciones
            $accion = 'select';
            if($paciente === null){
                $accion = 'select all'; //cuando se hace petición de muestra todos, no le pasamos un paciente en concreto
                $paciente = 'null';  //lo ponemos así porque en el insert lo ponemos sin comillas, significa que va un numero (id) o la palabra null sin comillas
            }else{
                $accion = "select";
            }
            $this->registrarAccion($accion, $paciente);

            //devolvemos el resultado
            return $resultadosArray;
        }else{
            return null;
        }
    }

    //funcion para ejecutar sql de update o delete en la BD.
    public function accionQuery($sql, $paciente=null, $accion=null){
        $resultados = $this->conexion->query($sql);
        /* liberar el conjunto de resultados */
        //$resultados->close();
        $filasActualizadas = $this->conexion->affected_rows;
        if($filasActualizadas >= 1){
            //Registramos las acciones
            $this->registrarAccion($accion, $paciente);
            
        }
        return $filasActualizadas;
 
    }

    //funcion para ejecutar sql de insert, que devuelve id creado.
    public function accionQueryId($sql){
        $resultados = $this->conexion->query($sql);
        /* liberar el conjunto de resultados */
        //$resultados->close();
        $filas = $this->conexion->affected_rows;
        if($filas >= 1){
            //Registramos las acciones
            $accion = 'insert';
            $paciente = $this->conexion->insert_id;
            $this->registrarAccion($accion, $paciente);
            return $paciente;
        }else{
            return 0;
        }
    }

    //funcion para ejecutar sql de insert, que devuelve id creado.
    private function registrarAccion($accion, $paciente){
        $doctor = $_SESSION['doctor'];
        $sql = "INSERT INTO hospital.registro (doctor, paciente, action) VALUES ($doctor, $paciente, '$accion');";
        $resultados = $this->conexion->query($sql);
        $filas = $this->conexion->affected_rows;
        if($filas >= 1){
            return $this->conexion->insert_id;
        }else{
            return 0;
        }
    }


}


?>