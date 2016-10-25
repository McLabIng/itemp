<?php
error_reporting(E_ALL);
error_reporting(~E_NOTICE);
session_start();
// Primero incluimos el archivo de configuracion
include('conf.php');

/** Verificamos que se haya escogido un modulo, sino
* tomamos el valor por defecto de la configuracion.
* Ademas verificamos que el usuario esta autentificado.
*/
if ($_SESSION["autentificado"] == "SI") {
	if (!empty($_GET['mod'])) 
		$modulo = $_GET['mod'];
	else
		$modulo = MODULO_DEFECTO;
	}
else
	$modulo = MODULO_DEFECTO;

/** Tambien debemos verificar que el valor que nos
* pasaron, corresponde a un modulo que existe, caso
* contrario, cargamos el modulo por defecto
*/
if (empty($conf[$modulo]))
		$modulo = MODULO_DEFECTO;


/** Verificamos que se haya escogido un idioma, sino
* tomamos el valor por defecto de la configuracion.
*/
if (!empty($_GET['idioma'])) 
	$idioma = $_GET['idioma'];
else
	$idioma = IDIOMA_DEFECTO;

/** Ahora determinamos que archivo de Layout tendra
* este modulo, si no tiene ninguno asignado, utilizamos
* el que viene por defecto
*/
if (empty($conf[$modulo]['layout'])) 
		$conf[$modulo]['layout'] = LAYOUT_DEFECTO;


/** Finalmente, cargamos el archivo de Layout que a su vez, se
* encargara de incluir al modulo propiamente dicho. si el archivo
* no existiera, cargamos directamente el modulo. Tambien es un
* buen lugar para incluir Headers y Footers comunes.
*/
$path_layout = LAYOUT_PATH.'/'.$conf[$modulo]['layout'];
$path_modulo = MODULO_PATH.'/'.$conf[$modulo]['archivo'];

if (file_exists($path_layout)){
	/** @noinspection PhpIncludeInspection */
	include( $path_layout );
	}
else
	if (file_exists( $path_modulo ))
		echo "modulo: ".$path_modulo. " y layout: ".$path_layout;
	else
		die('Error al cargar el modulo <b>'.$modulo.'</b>. No existe el archivo <b>'.$conf[$modulo]['archivo'].'</b>');
