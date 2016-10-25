<?php
require_once 'conexion.php';

class sitios_temperatura {
    private $cod_sitio;
    private $sitio;
    private $nombre;
    private $ip;
    private $wise;
    private $cod_tipo_rectificador;
    private $temp_duw_1;
    private $temp_duw_2;
    private $temp_rectificador;
    private $temp_wise;
    private $alarmado;
    private $en_estudio;

    const TABLA = 'itemp.sitios_temperatura';
    const TEMPERATURA_MAX = 28;

    public function getCod_Sitio() {
        return $this->cod_sitio;
    }
    public function getSitio() {
        return $this->sitio;
    }
    public function getNombre() {
        return $this->nombre;
    }
    public function getIP() {
        return $this->ip;
    }
    public function getWISE() {
        return $this->wise;
    }
    public function getCod_tipo_rectificador() {
        return $this->cod_tipo_rectificador;
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
    public function getEnEstudio() {
        return $this->en_estudio;
    }

    public function __construct($cod_sitio, $sitio, $nombre, $ip, $wise, $cod_tipo_rectificador, $temp_duw_1, $temp_duw_2,
                                $temp_rectificador, $temp_wise, $alarmado, $en_estudio) {
        $this->cod_sitio = $cod_sitio;
        $this->sitio = $sitio;
        $this->nombre = $nombre;
        $this->ip = $ip;
        $this->wise = $wise;
        $this->cod_tipo_rectificador = $cod_tipo_rectificador;
        $this->temp_duw_1 = $temp_duw_1;
        $this->temp_duw_2 = $temp_duw_2;
        $this->temp_rectificador = $temp_rectificador;
        $this->temp_wise = $temp_wise;
        $this->alarmado = $alarmado;
        $this->en_estudio = $en_estudio;
    }

    public static function traer_sitio($cod_sitio){
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT COD_SITIO, SITIO, NOMBRE_SITIO, IP, WISE, COD_TIPO_RECTIFICADOR, TEMP_DUW_1, TEMP_DUW_2,
                                TEMP_RECTIFICADOR, TEMP_WISE, EN_ESTUDIO
								FROM
								' . self::TABLA .'
								WHERE
								COD_SITIO = :cod_sitio ');
        $consulta->bindParam(':cod_sitio', $cod_sitio, PDO::PARAM_INT);
        $consulta->execute();
        $registro = $consulta->fetch();
        if($registro){
            return new self($registro['COD_SITIO'], $registro['SITIO'], $registro['NOMBRE_SITIO'],$registro['IP'],$registro['WISE'],$registro['COD_TIPO_RECTIFICADOR'],
                $registro['TEMP_DWU_1'], $registro['TEMP_DWU_2'], $registro['TEMP_RECTIFICADOR'],$registro['TEMP_WISE'],$registro['EN_ESTUDIO']);
        }else{
            return false;
        }
    }

    public static function  traer_sitios_totales(){
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT * FROM '.self::TABLA.' WHERE ACTIVO = 1');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        return $registros;
    }

    public static function traer_sitio_nemotecnico($sitio){
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT COD_SITIO, SITIO, NOMBRE_SITIO, IP, WISE, COD_TIPO_RECTIFICADOR, TEMP_DUW_1, TEMP_DUW_2,
                                TEMP_RECTIFICADOR, TEMP_WISE, EN_ESTUDIO
								FROM
								'.self::TABLA .'
								WHERE
								SITIO = :sitio ');
        $consulta->bindParam(':sitio', $sitio, PDO::PARAM_STR);
        $consulta->execute();
        $registro = $consulta->fetch();
        if($registro){
            return new self($registro['COD_SITIO'], $registro['SITIO'], $registro['NOMBRE_SITIO'],$registro['IP'],$registro['WISE'],$registro['COD_TIPO_RECTIFICADOR'],
                $registro['TEMP_DUW_1'], $registro['TEMP_DUW_2'], $registro['TEMP_RECTIFICADOR'],$registro['TEMP_WISE'],$registro['EN_ESTUDIO']);
        }else{
            return false;
        }
    }

    public static function actualizar_temperaturas($cod_sitio,$temp_duw1,$temp_duw2,$temp_rectif,$temp_wise,$b_alarmado){
        $conexion = new Conexion();
        $consulta = $conexion->prepare('UPDATE ' . self::TABLA .'
								SET TEMP_DUW_1 = :temp_duw1,
								TEMP_DUW_2 = :temp_duw2,
								TEMP_RECTIFICADOR = :temp_rectif,
								TEMP_WISE = :temp_wise,
								ALARMADO = :b_alarmado
								WHERE COD_SITIO = :cod_sitio ');
        $consulta->bindParam(':cod_sitio', $cod_sitio);
        $consulta->bindParam(':temp_duw1', $temp_duw1);
        $consulta->bindParam(':temp_duw2', $temp_duw2);
        $consulta->bindParam(':temp_rectif', $temp_rectif);
        $consulta->bindParam(':temp_wise', $temp_wise);
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

    public static function actualizar_alarmado($cod_sitio,$b_alarmado){
        $conexion = new Conexion();
        $consulta = $conexion->prepare('UPDATE ' . self::TABLA .'
								SET ALARMADO = :b_alarmado
                                WHERE COD_SITIO = :cod_sitio ');
        $consulta->bindParam(':cod_sitio', $cod_sitio);
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

    public static function  traer_sitios_correo($max_temp = self::TEMPERATURA_MAX){
        $conexion = new Conexion();
        $consulta = $conexion->prepare(' SELECT * FROM ' . self::TABLA . ' WHERE EN_ESTUDIO = 1 AND
                                        TEMP_RECTIFICADOR > :temp_max ');
        $consulta->bindParam(':temp_max', $max_temp);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        return $registros;
    }

}

