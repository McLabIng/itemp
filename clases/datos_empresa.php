<?php
require_once 'conexion.php';

class datos_empresa {
    private $razon_social;
    private $rut;
    private $giro;
    private $direccion;
    private $telefono;
    private $email;
    private $web;
    private $url_logo;

    const TABLA = 'datos_empresa';

    public function getRazon_social() {
        return $this->razon_social;
    }
    public function getRut() {
        return $this->rut;
    }
    public function getGiro() {
        return $this->giro;
    }
    public function getDireccion() {
        return $this->direccion;
    }
    public function getTelefono() {
        return $this->telefono;
    }
    public function getEmail() {
        return $this->email;
    }
    public function getWeb() {
        return $this->web;
    }
    public function getURL_logo() {
        return $this->url_logo;
    }

    public function __construct($razon_social, $rut, $giro, $direccion, $telefono, $email, $web, $url_logo) {
        $this->razon_social = $razon_social;
        $this->rut = $rut;
        $this->giro = $giro;
        $this->direccion = $direccion;
        $this->telefono = $telefono;
        $this->email = $email;
        $this->web = $web;
        $this->url_logo = $url_logo;
    }

    public static function traer_mi_empresa(){
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT *
								FROM
								' . self::TABLA );
        $consulta->execute();
        $registro = $consulta->fetch();
        if($registro){
            return new self($registro['razon_social'], $registro['rut'], $registro['giro'], $registro['direccion'], $registro['telefono'],
            $registro['email'], $registro['web'], $registro['url_logo']);
        }else{
            return false;
        }
    }

    public static function actualizar_datos_empresa($razon_social, $rut, $giro, $direccion, $telefono, $email, $web){
        $conexion = new Conexion();
        $consulta = $conexion->prepare('UPDATE ' . self::TABLA. '
								SET razon_social = :razon_social,
								rut = :rut,
								giro = :giro,
								direccion = :direccion,
								telefono = :telefono,
								email = :email,
								web = :web ');
        $consulta->bindParam(':razon_social', $razon_social);
        $consulta->bindParam(':rut', $rut);
        $consulta->bindParam(':giro', $giro);
        $consulta->bindParam(':direccion', $direccion);
        $consulta->bindParam(':telefono', $telefono);
        $consulta->bindParam(':email', $email);
        $consulta->bindParam(':web', $web);
        if ($consulta->execute()){
            $conexion = null;
            return true;
        }
        else {
            $conexion = null;
            return false;
        }
    }

    public static function actualizar_path_logo($path){
        $conexion = new Conexion();
        $consulta = $conexion->prepare('UPDATE ' . self::TABLA. '
								SET url_logo = :path ');
        $consulta->bindParam(':path', $path);
        if ($consulta->execute()){
            $conexion = null;
            return true;
        }
        else {
            $conexion = null;
            return false;
        }
    }

}

