<?php
require_once 'conexion.php';

class cl_usuario {
    private $cod_usuario;
    private $cod_area;
    private $cod_rol;
    private $nombre;
    private $apellido;
    private $username;
    private $password;
    private $estado;

    const TABLA = 'usuario';

    public function getCod_usuario() {
        return $this->cod_usuario;
    }
    public function getCod_area() {
        return $this->cod_area;
    }
    public function getCod_rol() {
        return $this->cod_rol;
    }
    public function getNombre() {
        return $this->nombre;
    }
    public function getApellido() {
        return $this->apellido;
    }
    public function getUsername() {
        return $this->username;
    }
    public function getPassword() {
        return $this->password;
    }
    public function getEstado() {
        return $this->estado;
    }
    public function setCod_usuario($cod_usuario) {
        $this->cod_usuario = $cod_usuario;
    }
    public function setCod_area($cod_area) {
        $this->cod_area = $cod_area;
    }
    public function setCod_rol($cod_rol) {
        $this->cod_rol = $cod_rol;
    }
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }
    public function setUsername($username) {
        $this->username = $username;
    }
    public function setPassword($password) {
        $this->password = $password;
    }
    public function setEstado($estado) {
        $this->estado = $estado;
    }
    public function __construct($cod_usuario, $cod_area, $cod_rol, $nombre, $apellido, $username, $password, $estado) {
        $this->cod_usuario = $cod_usuario;
        $this->cod_area = $cod_area;
        $this->cod_rol = $cod_rol;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->username = $username;
        $this->password = $password;
        $this->estado = $estado;
    }

    public static function traer_usuario($username){
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA .' WHERE USERNAME = :usuario');
        $consulta->bindParam(':usuario', $username, PDO::PARAM_STR);
        $consulta->execute();
        $registro = $consulta->fetch();
        if($registro){
            return new self($registro['COD_USUARIO'], $registro['COD_AREA'], $registro['COD_ROL'], $registro['NOMBRE']
                , $registro['APELLIDO'], $registro['USERNAME'], $registro['PASSWORD'], $registro['ESTADO']);
        }else{
            return false;
        }
    }

    public static function traer_usuario_cb($cod_usuario){
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA .' WHERE COD_USUARIO = :usuario');
        $consulta->bindParam(':usuario', $cod_usuario);
        $consulta->execute();
        $registro = $consulta->fetch();
        if($registro){
            return new self($registro['COD_USUARIO'], $registro['COD_AREA'], $registro['COD_ROL'], $registro['NOMBRE']
                , $registro['APELLIDO'], $registro['USERNAME'], $registro['PASSWORD'], $registro['ESTADO']);
        }else{
            return false;
        }
    }

    public static function autenticar_usuario($username, $contrasena){
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA .' WHERE USERNAME = :username  and PASSWORD = :contrasena and estado = 1 ');
        $consulta->bindParam(':username', $username, PDO::PARAM_STR);
        $consulta->bindParam(':contrasena', $contrasena, PDO::PARAM_STR);
        $consulta->execute();
        $registro = $consulta->fetch();
        if($registro){
            return new self($registro['COD_USUARIO'], $registro['COD_AREA'], $registro['COD_ROL'], $registro['NOMBRE']
                , $registro['APELLIDO'], $registro['USERNAME'], $registro['PASSWORD'], $registro['ESTADO']);
        }else{
            return false;
        }
    }

    public static function comprobar_username($username){
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA .' WHERE USERNAME = :username');
        $consulta->bindParam(':username', $username);
        $consulta->execute();
        $registro = $consulta->fetch();
        if($registro){
            return true;
        }else{
            return false;
        }
    }

    public static function agregar_usuario($us_nombre,$us_apellido,$us_username,$us_password,$us_rol,$us_area){
        $conexion = new Conexion();
        $consulta = $conexion->prepare('insert into ' . self::TABLA .' (NOMBRE, APELLIDO, USERNAME, PASSWORD, COD_AREA, COD_ROL, ESTADO)
								values (:us_nombre, :us_apellido, :us_username, :us_password, :us_area, :us_rol, 1)');
        $consulta->bindParam(':us_nombre', $us_nombre);
        $consulta->bindParam(':us_apellido', $us_apellido);
        $consulta->bindParam(':us_username', $us_username);
        $consulta->bindParam(':us_password', $us_password);
        $consulta->bindParam(':us_area', $us_area);
        $consulta->bindParam(':us_rol', $us_rol);
        if ($consulta->execute()){
            $conexion = null;
            return true;
        }
        else {
            $conexion = null;
            return false;
        }
    }

    public static function actualizar_usuario($cod_usuario,$us_nombre,$us_apellido,$us_username,$us_password,$us_rol,$us_area,$us_activo){
        $conexion = new Conexion();
        $consulta = $conexion->prepare('UPDATE ' . self::TABLA .'
								SET NOMBRE = :us_nombre,
								APELLIDO = :us_apellido,
								USERNAME = :us_username,
								PASSWORD = :us_password,
								COD_AREA = :us_area,
								COD_ROL = :us_rol,
								ESTADO = :us_activo
								WHERE COD_USUARIO = :cod_usuario ');
        $consulta->bindParam(':cod_usuario', $cod_usuario);
        $consulta->bindParam(':us_nombre', $us_nombre);
        $consulta->bindParam(':us_apellido', $us_apellido);
        $consulta->bindParam(':us_username', $us_username);
        $consulta->bindParam(':us_password', $us_password);
        $consulta->bindParam(':us_area', $us_area);
        $consulta->bindParam(':us_rol', $us_rol);
        $consulta->bindParam(':us_activo', $us_activo);
        if ($consulta->execute()){
            $conexion = null;
            return true;
        }
        else {
            $conexion = null;
            return false;
        }
    }

    public static function desactivar_usuario($cod_usuario){
        $conexion = new Conexion();
        $consulta = $conexion->prepare('UPDATE ' . self::TABLA .'
								SET ESTADO = 0
								WHERE COD_USUARIO = :cod_usuario ');
        $consulta->bindParam(':cod_usuario', $cod_usuario);
        if ($consulta->execute()){
            $conexion = null;
            return true;
        }
        else {
            $conexion = null;
            return false;
        }
    }

    public static function despliega_usuarios(){
        $conexion = new Conexion();
        $consulta = $conexion->prepare('SELECT
                                        COD_USUARIO, CONCAT(NOMBRE," ",APELLIDO) AS nombre_usuario
                                        FROM ' . self::TABLA .' WHERE ESTADO = 1 ORDER BY nombre_usuario ');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        return $registros;
    }

}

