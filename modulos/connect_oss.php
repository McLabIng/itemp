<?php 
require_once '../clases/conexion_ftp.php';
require_once '../clases/conexion_oss.php';
require_once '../clases/sitios_temperatura.php';
require_once '../clases/procesador_texto.php';

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

//echo $data_alarmas;

Procesador_texto::inserta_registros_test('archivos/temperaturas.txt',$data_alarmas);