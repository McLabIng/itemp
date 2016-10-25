<?php
error_reporting(E_ALL);
error_reporting(~E_NOTICE);
session_start();

// Primero incluimos las clases de procesamiento PRODUCCION
require_once 'c:/inetpub/wwwroot/itemp/clases/conexion_ftp.php';
require_once 'c:/inetpub/wwwroot/itemp/clases/conexion_oss.php';
require_once 'c:/inetpub/wwwroot/itemp/clases/sitios_temperatura.php';
require_once 'c:/inetpub/wwwroot/itemp/clases/procesador_texto.php';

/*/ Clases de procesamiento -- PRUEBA LOCAL
require_once '/Users/Blackbird/Sites/itemp/clases/conexion_ftp.php';
require_once '/Users/Blackbird/Sites/itemp/clases/conexion_oss.php';
require_once '/Users/Blackbird/Sites/itemp/clases/sitios_temperatura.php';
require_once '/Users/Blackbird/Sites/itemp/clases/procesador_texto.php';*/

// Vista de conexion FTP
ini_set('max_execution_time', 3000); //300 seconds = 5 minutes

$lista_sitios = sitios_temperatura::traer_sitios_totales();
$linea = '';
foreach($lista_sitios as $rows):
	$linea .= $rows['SITIO'].',';
endforeach;

$string_sitios = trim($linea,',');

//********************* BLOQUE QUE TRAE LAS TEMP Y LAS DEJA EN LA CARPETA DEL SERVIDOR ***********************************

conexion_oss::transforma_en_arreglo("OSS13","cab x",$string_sitios);
conexion_oss::transforma_en_arreglo_alarma("OSS13","alt",$string_sitios);

$Cliente_ftp = new FTPClient();
$Cliente_ftp->Traer_archivo_temperaturas();
$Cliente_ftp->Traer_archivo_alarmas_temperaturas();

//************************************************************************************************************************

$data_alarmas =  Procesador_texto::arreglo_registros_alarmas_temperaturas('archivos/alarmas_temperaturas.txt');

Procesador_texto::inserta_registros_test('archivos/temperaturas.txt',$data_alarmas);

Procesador_texto::Procesa_correo('gestionoym@entel.cl','shjorquera@entel.cl');

// Envia archivo al directorio deseado
date_default_timezone_set('Chile/Continental');
copy("salida.txt","c:/inetpub/mailroot/Pickup/salida_".time().".txt"); // SERVIDOR
//copy("salida.txt","c:/xampp/mailoutput/salida.txt"); // PC de Prueba
unlink("salida.txt");