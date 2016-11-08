<?php
require_once 'conexion.php';

class tipo_rectificador {
    private $cod_tipo_rectificador;
    private $tipo_rectificador;
    private $oid_temperatura;

    const TABLA = 'itemp.tipo_rectificador';

    public function getCod_tipo_rectificador() {
        return $this->cod_tipo_rectificador;
    }
    public function getTipo_rectificador() {
        return $this->tipo_rectificador;
    }
    public function getOIDTemperatura() {
        return $this->oid_temperatura;
    }

    public function __construct($cod_tipo_rectificador, $tipo_rectificador, $oid_temperatura) {
        $this->cod_tipo_rectificador = $cod_tipo_rectificador;
        $this->tipo_rectificador = $tipo_rectificador;
        $this->oid_temperatura = $oid_temperatura;
    }

    public static function traer_tipo_rectificador($cod_tipo){
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA .' WHERE COD_TIPO_RECTIFICADOR = :cod_tipo ');
        $consulta->bindParam(':cod_tipo', $cod_tipo, PDO::PARAM_INT);
        $consulta->execute();
        $registro = $consulta->fetch();
        if($registro){
            return new self($registro['COD_TIPO_RECTIFICADOR'], $registro['TIPO_RECTIFICADOR'], $registro['OID_TEMPERATURA']);
        }else{
            return false;
        }
    }

    public static function listado_tipo_rectificador(){
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT COD_TIPO_RECTIFICADOR, TIPO_RECTIFICADOR FROM ' . self::TABLA );
        $consulta->execute();
        $registro = $consulta->fetchAll();
        return $registro;
    }
}

