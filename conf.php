<?php
/*
 * Archivo de configuracion para nuestra aplicacion modularizada.
 * Definimos valores por defecto y datos para cada uno de nuestros modulos.
*/
define('ESTADO_INICIAL', 1);
define('MODULO_DEFECTO', 'autenticacion');
define('LAYOUT_DEFECTO', 'layout_menu.php');
define('MODULO_PATH', realpath('./modulos/'));
define('LAYOUT_PATH', realpath('./layouts/'));
define('IMAGES_PATH', realpath('./img/'));
define('CSS_PATH', realpath('./css/'));
define('FONT_PATH', realpath('./fonts/'));
define('IDIOMA_DEFECTO', 'EN');
define('AUTOINCREMENTA',1); // Valores [0]: El num de OT se coloca manual; [1]: El num de OT se coloca automatico

// Fechas de prueba
define('FECHA_PRUEBA_INICIO','2016-01-01 00:00:00');
define('FECHA_PRUEBA_FIN','2016-01-01 23:59:00');

// Autenticacion
$conf['autenticacion'] = array(
		'archivo' => 'autenticacion.php',
		'layout' => 'layout_simple.php' );
//Principales
$conf['home'] = array(
		'archivo' => 'home.php',
		'layout' => LAYOUT_DEFECTO );
$conf['historial'] = array(
	'archivo' => 'historial.php',
	'layout' => LAYOUT_DEFECTO );
$conf['admin'] = array(
	'archivo' => 'admin.php',
	'layout' => LAYOUT_DEFECTO );

//Sitios
$conf['sitios'] = array(
	'archivo' => 'sitios.php' );

//Usuarios
$conf['users'] = array(
		'archivo' => 'users.php' );
$conf['buscar_user'] = array(
	'archivo' => 'buscar_user.php' );
//Salir
$conf['logout'] = array(
		'archivo' => 'logout.php' );