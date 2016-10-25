<?php
require_once 'conexion.php';

class historial_temperaturas {
    private $cod_historial;
    private $cod_sitio;
    private $fecha;
    private $temp_duw_1;
    private $temp_duw_2;
    private $temp_rectificador;
    private $temp_wise;
    private $alarmado;

    const TABLA = 'itemp.historial_temperaturas';

    public function getCod_Historial() {
        return $this->cod_historial;
    }
    public function getCodSitio() {
        return $this->cod_sitio;
    }
    public function getFecha() {
        return $this->fecha;
    }
    public function getTemp_duw_1() {
        return $this->temp_duw_1;
    }
    public function getTemp_duw_2() {
        return $this->temp_duw_2;
    }
    public function getTemp_rectificador() {
        return $this->temp_rectificador;
    }
    public function getTemp_wise() {
        return $this->temp_wise;
    }
    public function getAlarmado() {
        return $this->alarmado;
    }

    public function __construct($cod_historial, $cod_sitio, $fecha, $temp_duw_1, $temp_duw_2,
                                $temp_rectificador, $temp_wise, $alarmado) {
        $this->cod_historial = $cod_historial;
        $this->cod_sitio = $cod_sitio;
        $this->fecha = $fecha;
        $this->temp_duw_1 = $temp_duw_1;
        $this->temp_duw_2 = $temp_duw_2;
        $this->temp_rectificador = $temp_rectificador;
        $this->temp_wise = $temp_wise;
        $this->alarmado = $alarmado;
    }

    public static function traer_historial_diario(){
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA );
        $consulta->execute();
        $registros = $consulta->fetchAll();
        return $registros;
    }

    public static function agregar_historial($cod_sitio, $temp_duw1, $temp_duw2, $temp_rectif, $temp_wise){
        $conexion = new Conexion();
        $consulta = $conexion->prepare('insert into ' . self::TABLA .' (COD_SITIO, FECHA, TEMP_DUW_1, TEMP_DUW_2, TEMP_RECTIFICADOR, TEMP_WISE)
								values (:cod_sitio, NOW(), :temp_duw1, :temp_duw2, :temp_rectif, :temp_wise)');
        $consulta->bindParam(':cod_sitio', $cod_sitio);
        $consulta->bindParam(':temp_duw1', $temp_duw1);
        $consulta->bindParam(':temp_duw2', $temp_duw2);
        $consulta->bindParam(':temp_rectif', $temp_rectif);
        $consulta->bindParam(':temp_wise', $temp_wise);
        if ($consulta->execute()){
            $conexion = null;
            return true;
        }
        else {
            $conexion = null;
            return false;
        }
    }

    public static function actualizar_alarmado($cod_historial,$b_alarmado){
        $conexion = new Conexion();
        $consulta = $conexion->prepare('UPDATE ' . self::TABLA .'
								SET ALARMADO = :b_alarmado
                                WHERE COD_HISTORIAL = :cod_historial ');
        $consulta->bindParam(':cod_historial', $cod_historial);
        $consulta->bindParam(':b_alarmado', $b_alarmado);
        if ($consulta->execute()){
            $conexion = null;
            return true;
        }
        else {
            $conexion = null;
            return false;
        }
    }

    public static function traer_cod_historial_sitio($cod_sitio){
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT MAX(COD_HISTORIAL) AS cod_hist  FROM '. self::TABLA . ' WHERE COD_SITIO = :cod_sitio ' );
        $consulta->bindParam(':cod_sitio', $cod_sitio);
        $consulta->execute();
        $registro = $consulta->fetch();
        if($registro){
            return $registro['cod_hist'];
        }else{
            return false;
        }
    }

    public static function traer__historial_fechas($cod_sitio, $fecha_ini, $fecha_fin){
        $conexion = new Conexion();
        $consulta = $conexion->prepare( ' SELECT * FROM '. self::TABLA . ' WHERE COD_SITIO = :cod_sitio AND FECHA >= :fecha_ini AND FECHA <= :fecha_fin ORDER BY FECHA ASC  ');
        $consulta->bindParam(':cod_sitio', $cod_sitio);
        $consulta->bindParam(':fecha_ini', $fecha_ini);
        $consulta->bindParam(':fecha_fin', $fecha_fin);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        return $registros;
    }

}

