<?php
require_once 'conexion.php';

class menu {
    private $cod_menu;
    private $cod_area;
    private $cod_rol;
    private $menu;
    private $referencia;
    private $grupo;
    private $icono;

    const TABLA = 'menu';

    public function getCod_menu() {
        return $this->cod_menu;
    }
    public function getCod_area() {
        return $this->cod_area;
    }
    public function getCod_rol() {
        return $this->cod_rol;
    }
    public function getMenu() {
        return $this->menu;
    }
    public function getReferencia() {
        return $this->referencia;
    }
    public function getGrupo() {
        return $this->grupo;
    }
    public function getIcono() {
        return $this->icono;
    }
    public function setCod_menu($cod_menu) {
        $this->cod_menu = $cod_menu;
    }
    public function setCod_area($cod_area) {
        $this->cod_area = $cod_area;
    }
    public function setCod_rol($cod_rol) {
        $this->cod_rol = $cod_rol;
    }
    public function setMenu($menu) {
        $this->menu = $menu;
    }
    public function setReferencia($referencia) {
        $this->referencia = $referencia;
    }
    public function __construct($cod_menu, $cod_area, $cod_rol, $menu, $referencia, $grupo, $icono) {
        $this->cod_menu = $cod_menu;
        $this->cod_area = $cod_area;
        $this->cod_rol = $cod_rol;
        $this->menu = $menu;
        $this->referencia = $referencia;
        $this->grupo = $grupo;
        $this->icono = $icono;
    }

    public static function traer_menu($area, $rol){
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA .' WHERE COD_AREA = :area and COD_ROL= :rol ORDER BY ORDEN ASC ');
        $consulta->bindParam(':area', $area);
        $consulta->bindParam(':rol', $rol);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        return $registros;
    }

}

