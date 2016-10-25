<?php

/* Datos para conexion de base de datos interna -----------  IMPORTANTE */
define ('SERVIDOR', "127.0.0.1");
define ('USUARIO', "root");
define ('PASSWORD', "");
define ('DATABASE', "scale");
// Fin de conexion de base de datos interna */


class Conexion extends PDO {
    private $tipo_de_base = 'mysql';
    private $host = SERVIDOR;
    private $nombre_de_base = DATABASE;
    private $usuario = USUARIO;
    private $contrasena = PASSWORD;
    public function __construct() {
        //Sobreescribo el método constructor de la clase PDO.
        try{
            parent::__construct($this->tipo_de_base.':host='.$this->host.';dbname='.$this->nombre_de_base, $this->usuario, $this->contrasena,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        }catch(PDOException $e){
            echo 'Ha surgido un error y no se puede conectar a la base de datos. Detalle: ' . $e->getMessage();
            exit;
        }
    }
}

class MySQL{
    private $conexion;
    private $total_consultas;

    public function MySQL(){
        if(!isset($this->conexion)){
            $this->conexion = (mysql_connect(SERVIDOR,USUARIO,PASSWORD)) or die(mysql_error());
            mysql_select_db(DATABASE,$this->conexion) or die(mysql_error());
            mysql_query ("SET NAMES 'utf8'");
        }
    }

    public function consulta($consulta){
        $this->total_consultas++;
        /*/ Compruebo errores en consulta
        echo $consulta;
        echo "<br>";
        // Fin comprobacion /*/
        $resultado = mysql_query($consulta,$this->conexion);
        if(!$resultado){
            echo 'MySQL Error: ' . mysql_error();
            exit;
        }
        return $resultado;
    }

    public function fetch_array($consulta){
        return mysql_fetch_array($consulta);
    }

    public function num_rows($consulta){
        // Compruebo errores en consulta
        // echo mysql_num_rows($consulta);
        return @mysql_num_rows($consulta);
    }

    public function getTotalConsultas(){
        return $this->total_consultas;
    }
}

//Función auxiliar para errores (solo AJAX)
function mostrar_error($mensaje_error){
    $arr = array("error" => true, "mensaje" => $mensaje_error);
    echo json_encode($arr);
    exit();
};

//Conexión a la base de datos
function abrirConexion(){
    $db_host = SERVIDOR; //La dirección del servidor de BD
    $db_user = USUARIO;
    $db_pw = PASSWORD;
    $db_name = DATABASE;

    $connection = mysqli_connect($db_host, $db_user, $db_pw, $db_name);
    if (!$connection) {
        mostrar_error("No se puede conectar al servidor\\base de datos: $db_host\\$db_name");
    }
    return $connection;
}