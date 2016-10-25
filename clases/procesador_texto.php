<?php
require_once 'sitios_temperatura.php';
require_once 'historial_temperaturas.php';
require_once 'tipo_rectificador.php';
//require_once 'vista/vw_home.php';

class Procesador_texto {

    public function __construct() {
    }

    public static function LeeArchivo($archivo){
        if (!file($archivo)){
            die("No se pudo abrir el archivo ".$archivo);
        }
        else {
            return file($archivo);
        }
    }

    public static function inserta_registros_test($archivo,$arreglo_alarmas){
        $lineas = self::LeeArchivo($archivo);
        $linea = '';
        $temp1_aux = '';
        $temp3_aux = '';
        $temp4_aux = 0;
        $mi_cod_sitio_aux = '';
        $sitio_aux = '';
        $orden_aux = '';

        // Para traer la OID de la WISE - en tabla es codigo 3 --- IMPORTANTE
        $mi_tipo_rectificador_WISE = 3;
        $mi_clase_rectificador_WISE = tipo_rectificador::traer_tipo_rectificador($mi_tipo_rectificador_WISE);
        $mi_oid_temperatura_WISE = $mi_clase_rectificador_WISE->getOIDTemperatura();
        // Fin de busqueda

        foreach ($lineas as $linea_num => $contenido)
        {
            $datos1 = preg_split('/\s+/', $lineas[$linea_num]);
            $sitio = trim(substr($datos1[0],strlen($datos1[0])-11,strlen($datos1[0])),'.log:');
            $temp1 = trim(trim($datos1[11],'C'),'+');
            $orden = $datos1[2];
            $mi_sitio = sitios_temperatura::traer_sitio_nemotecnico($sitio);
            $mi_ip = $mi_sitio->getIP();
            $mi_cod_sitio = $mi_sitio->getCod_Sitio();
            // Para traer la OID del tipo de rectificador
            $mi_tipo_rectificador = $mi_sitio->getCod_tipo_rectificador();
            $mi_clase_rectificador = tipo_rectificador::traer_tipo_rectificador($mi_tipo_rectificador);
            $mi_oid_temperatura = $mi_clase_rectificador->getOIDTemperatura();
            // Fin de busqueda
            $device = snmpget($mi_ip, "public", $mi_oid_temperatura);
            $temp3 = intval(preg_replace('/[^0-9]+/', '', $device));

            $mi_wise = $mi_sitio->getWISE();
            if ($mi_wise <> ''){
                $device2 = snmpget($mi_wise,'public',$mi_oid_temperatura_WISE);
                $temp4 = intval(preg_replace('/[^0-9]+/', '', $device2));
            }
            else {
                $temp4 = 0;

            }
            if ($temp1 == ''){
                 $temp1 = 0;
             }
            if ($sitio <> $sitio_aux && $orden == 1 && $orden_aux == 1){
                //$linea .= " Sitio: ".$sitio_aux." Temp1: ".$temp1_aux." Temp2: 0  Temp3: ".$temp3_aux."<br> ";
                if (!sitios_temperatura::actualizar_temperaturas($mi_cod_sitio_aux,$temp1_aux,0,$temp3_aux,$temp4_aux,0)){
                    $MiFTP = new FTPClient();
                    $MiFTP->logMessage("Error de actualizacion de sitio ".$sitio_aux." Temp1:".$temp1_aux." Temp2:".$temp1." TempRect:".$temp3_aux." TempWise:".$temp4_aux."\r\n");
                }
                else {
                    if (!historial_temperaturas::agregar_historial($mi_cod_sitio_aux,$temp1_aux,0,$temp3_aux,$temp4_aux)){
                        $MiFTP = new FTPClient();
                        $MiFTP->logMessage("Error de actualizacion del historial de temperaturas de sitio ".$sitio_aux."\r\n");
                    }
                    else {
                        $MiFTP = new FTPClient();
                        $MiFTP->logMessage("Ingreso exitoso de sitio ".$sitio_aux."\r\n");
                        }
                    }
                }
            if ($sitio == $sitio_aux && $orden <> 1 && $orden_aux == 1){
                //$linea .= " Sitio: ".$sitio_aux." Temp1: ".$temp1_aux." Temp2:".$temp1." Temp3: ".$temp3_aux."<br> ";
                if (!sitios_temperatura::actualizar_temperaturas($mi_cod_sitio_aux,$temp1_aux,$temp1,$temp3_aux,$temp4_aux,0)){
                    $MiFTP = new FTPClient();
                    $MiFTP->logMessage("Error de actualizacion de sitio ".$sitio_aux." Temp1:".$temp1_aux." Temp2:".$temp1." TempRect:".$temp3_aux." TempWise:".$temp4_aux."\r\n");
                }
                else {
                    if ($temp1 == ''){
                        $temp1 = 0;
                    }
                    if (!historial_temperaturas::agregar_historial($mi_cod_sitio_aux,$temp1_aux,$temp1,$temp3_aux,$temp4_aux)){
                        $MiFTP = new FTPClient();
                        $MiFTP->logMessage("Error de actualizacion del historial de temperaturas de sitio ".$sitio_aux."\r\n");
                    }
                    else {
                        $MiFTP = new FTPClient();
                        $MiFTP->logMessage("Ingreso exitoso de sitio ".$sitio_aux."\r\n");
                        }
                    }
                }
            $sitio_aux = $sitio;
            $orden_aux = $orden;
            $temp1_aux = $temp1;
            $mi_sitio_aux = sitios_temperatura::traer_sitio_nemotecnico($sitio_aux);
            $mi_ip_aux = $mi_sitio_aux->getIP();
            $mi_cod_sitio_aux = $mi_sitio_aux->getCod_Sitio();
            // Para traer la OID del tipo de rectificador
            $mi_tipo_rectificador_aux = $mi_sitio_aux->getCod_tipo_rectificador();
            $mi_clase_rectificador_aux = tipo_rectificador::traer_tipo_rectificador($mi_tipo_rectificador_aux);
            $mi_oid_temperatura_aux = $mi_clase_rectificador_aux->getOIDTemperatura();
            // Fin de busqueda
            $device_aux = snmpget($mi_ip_aux, "public", $mi_oid_temperatura_aux);
            $temp3_aux = intval(preg_replace('/[^0-9]+/', '', $device_aux));
            $mi_wise_aux = $mi_sitio_aux->getWISE();
            if ($mi_wise_aux <> ''){
                $device2_aux = snmpget($mi_wise_aux,'public',$mi_oid_temperatura_WISE);
                $temp4_aux = intval(preg_replace('/[^0-9]+/', '', $device2_aux));
            }
            else {
                $temp4_aux = 0;

            }
            if ($arreglo_alarmas <> ''){
                foreach ($arreglo_alarmas as $rows){
                    if ($sitio_aux == $rows[0]){
                        // Actualizar sitio con sus alarmas
                        if (!sitios_temperatura::actualizar_alarmado($mi_cod_sitio_aux,1)){
                            $MiFTP = new FTPClient();
                            $MiFTP->logMessage("Error de actualizacion de alarmado de sitio ".$sitio_aux."\r\n");
                        }
                        else {
                            $cod_historial = historial_temperaturas::traer_cod_historial_sitio($mi_cod_sitio_aux);
                            // Actualizar historial del sitio y sus temperaturas --> alarma
                            if (!historial_temperaturas::actualizar_alarmado($cod_historial,1)){
                                $MiFTP = new FTPClient();
                                $MiFTP->logMessage("Error de actualizacion de alarmado de historial de sitio ".$sitio_aux."\r\n");
                            }
                            else {
                                $MiFTP = new FTPClient();
                                $MiFTP->logMessage("Ingreso exitoso de sitio alarmado ".$sitio_aux."\r\n");
                            }
                        }
                    }
                }
            }
        }
        echo $linea;
    }

    public static function inserta_registros($archivo){
        $lineas = self::LeeArchivo($archivo);
        $linea = '';
        foreach ($lineas as $linea_num => $contenido)
        {
            $datos1 = preg_split('/\s+/', $lineas[$linea_num]);
            $sitio = trim(substr($datos1[0],strlen($datos1[0])-11,strlen($datos1[0])),'.log:');
            $temp1 = trim(trim($datos1[11],'C'),'+');
            $orden = $datos1[2];

            $linea .= " Sitio: ".$sitio." Temp1: ".$temp1." Orden: ".$orden." <br> ";
        }
        echo $linea;
    }

    public static function Procesa_archivos(){
        foreach (glob("archivos/*.txt") as $nombre_archivo) {
            $linea = Procesador_texto::inserta_registros($nombre_archivo);
            //vw_home::ver_lineas($linea);
            //unlink($nombre_archivo);
        }
        return $linea;
    }

    public static function arreglo_registros_alarmas_temperaturas($archivo){
        date_default_timezone_set('Chile/Continental');
        $lineas = self::LeeArchivo($archivo);
        $linea = '';
        $data_alarmas = '';
        $dt_condicion= date('Y-m-d H:i:s', strtotime('-90 minutes')) ; // resta n intervalo
        foreach ($lineas as $linea_num => $contenido)
        {
            $datos1 = preg_split('/\s+/', $lineas[$linea_num]);
            $fecha = trim(substr($datos1[0],strlen($datos1[0])-11,strlen($datos1[0])),'.log:');
            $sitio = substr(trim(substr($datos1[0],strlen($datos1[0])-21,strlen($datos1[0])),'.log:'),0,6);
            $hora = $datos1[1];
            $alarma = $datos1[3];
            $fecha_alarma = $fecha." ".$hora;
            if ($fecha_alarma >= $dt_condicion ){
                if (strpos($alarma, 'Temperature') <> 0){
                    //$linea .= " Sitio: ".$sitio."  Fecha: ".$fecha_alarma." Alarma: ".$alarma." Condicion tiempo: ".$dt_condicion." Fecha actual: ".date('Y-m-d H:i:s')."<br> ";
                    $data_alarmas[] = array($sitio);
                }
            }
        }
        return $data_alarmas;
    }

    public static function Procesa_correo($from, $to){
        $arreglo_sitios = sitios_temperatura::traer_sitios_correo();
        $cantidad = count($arreglo_sitios);
        $texto_from = "From: ".$from;
        $texto_to = "To: ".$to;
        $texto_subject = "Subject: Alarma de temperatura bateria (".$cantidad." sitios)";
        $texto_footer = "Mas detalles en http://172.16.100.123/itemp ";
        $linea = "";
        if ($cantidad > 0){
            foreach ($arreglo_sitios as $rows) {
                //TEMPREATURA DUW2
                if ($rows["TEMP_DUW_2"] == 0) {
                    $temp_duw2 = " -";
                } else {
                    $temp_duw2 = " - DUW2: ".$rows["TEMP_DUW_2"]."ºC";
                }

                //TEMPERATURA WISE
                if ($rows["TEMP_WISE"] == 0) {
                    $temp_wise = " -";
                } else {
                    $temp_wise =  " - WISE: ".$rows["TEMP_WISE"]."ºC";
                }
                $linea .= " Sitio (".$rows["SITIO"].") - (".$rows["NOMBRE_SITIO"].")\r\nTemperaturas\r\n DUW1: ".$rows["TEMP_DUW_1"]."ºC".$temp_duw2." - Batería: ".$rows["TEMP_RECTIFICADOR"]."ºC".$temp_wise."\r\nDC Control: http://".$rows["IP"]."\r\n \r\n";
            }
            $body_correo = $texto_from."\r\n".$texto_to."\r\n".$texto_subject."\r\n".$linea."\r\n".$texto_footer;
            $fp = fopen("salida.txt","w");
            fwrite($fp, $body_correo.PHP_EOL);
            fclose($fp);
        }

    }
}
