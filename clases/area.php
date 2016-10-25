<?php
require_once 'conexion.php';

class area {
    private $cod_area;
    private $area;

    const TABLA = 'area';

    public function getCod_area() {
        return $this->cod_area;
    }
    public function getArea() {
        return $this->area;
    }
    public function setCod_area($cod_area) {
        $this->cod_area = $cod_area;
    }
    public function setArea($area) {
        $this->area = $area;
    }
    public function __construct($cod_area, $area) {
        $this->cod_area = $cod_area;
        $this->area = $area;
    }

    public static function traer_area($cod_area){
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA .' WHERE COD_AREA = :cod_area');
        $consulta->bindParam(':cod_area', $cod_area, PDO::PARAM_INT);
        $consulta->execute();
        $registro = $consulta->fetch();
        if($registro){
            return new self($registro['COD_AREA'], $registro['AREA']);
        }else{
            return false;
        }
    }

    public static function traer_areas_usuario(){
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA );
        $consulta->execute();
        $registros = $consulta->fetchAll();
        return $registros;
    }
}

