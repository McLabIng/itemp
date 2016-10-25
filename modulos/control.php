<?php 
session_start();
require_once '../clases/usuario.php';

$usuario = $_POST["usuario"];
$contrasena = $_POST["contrasena"];

$usuario_reg = cl_usuario::autenticar_usuario($usuario,$contrasena);

/************* Para debug
$_SESSION["autentificado"]= "SI";
$_SESSION["username"]= $usuario_reg->getUsername();
$_SESSION["rol"]= $usuario_reg->getCod_rol();
$_SESSION["area"]= $usuario_reg->getCod_area();

echo $_SESSION["autentificado"];
echo $_SESSION["username"];
echo $_SESSION["rol"];
echo $_SESSION["area"];

/*/
if ($usuario_reg){
    $_SESSION["autentificado"]= "SI";
    $_SESSION["username"]= $usuario_reg->getUsername();
    $_SESSION["rol"]= $usuario_reg->getCod_rol();
    $_SESSION["area"]= $usuario_reg->getCod_area();

    /*/************* Para debug
    echo $_SESSION["autentificado"];
    echo $_SESSION["username"];
    echo $_SESSION["rol"];
    echo $_SESSION["area"];
    //echo "<script type=\"text/javascript\">window.location=\"../index.php?mod=home\";</script>";
    /**************************/
    if ($usuario_reg->getCod_rol()<>4){
    echo "<script type=\"text/javascript\">window.location=\"../index.php?mod=home\";</script>";
    }
    else {
        echo "<script type=\"text/javascript\">window.location=\"../index.php?mod=buscar_ot_edit\";</script>";
    }
}
else {
    //si no existe le mando otra vez a la portada
    $_SESSION["autentificado"]= "NO";
    echo "<script type=\"text/javascript\">window.location=\"../index.php?mod=autenticacion\";</script>";
}
/*/************* Para debug
$_SESSION["autentificado"]= "SI";
$_SESSION["username"]= "rleiva";
$_SESSION["rol"]= 1;
$_SESSION["area"]= 1;
echo "<script type=\"text/javascript\">window.location=\"../index.php?mod=home\";</script>";
//*************/
