<?php
include_once 'credenciales_oss.php';
require_once 'procesador_texto.php';
require_once 'conexion_ftp.php';

class conexion_oss {

    public static function conectar_ssh_amosbatch($OSS, $comando, $sitio)
    {
        $MiFTP = new FTPClient();

        if ($OSS == "OSS13") {
            $credenciales_oss = new credenciales_OSS13();

        } else {
            $credenciales_oss = new credenciales_OSS14();
        }
        $server = $credenciales_oss->getHost();
        $prompt = $credenciales_oss->getPrompt();
        $port =$credenciales_oss->getPort();
        $user = $credenciales_oss->getUsuario();
        $pass = $credenciales_oss->getPassword();

        if (count($sitio) > 21){
            $formula = "amosbatch -p 20 -t 0 " . $sitio . " 'lt all;".$comando.";q'";
        }
        else {
            $formula = "amosbatch " . $sitio . " 'lt all;".$comando.";q'";
        }

        //$formula = "amosbatch " . $sitio . " 'lt all;".$comando.";q'";
        //$formula = "isql -Usa -Psybase11 -w300 -Dfmadb_1_1; 'select Event_time,Object_of_reference,Problem_text,SP_text from FMA_alarm_list where Object_of_reference like '%MeContext=M%' && go' ";

        // *******************   REVISAR FORMULA PARA OSS
        //echo $formula;
        //***********************************************

        $data = "";
        if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");

        if(!($con = ssh2_connect($server, $port))){
            $MiFTP->logMessage("fail_amosbatch_1: unable to establish connection\n");
        }else{
            if(!ssh2_auth_password($con,$user,$pass)) {
                $MiFTP->logMessage("fail_amosbatch_2: unable to authenticate\n");
            }
            else {
                if(!($stream = ssh2_exec($con, $formula )) ){
                    $MiFTP->logMessage("fail_amosbatch_3: unable to execute command\n");
                }
                else {
                    stream_set_blocking( $stream, true );
                    $data = "";
                    while( $buf = fread($stream,4096) ){
                        $data .= $buf;
                    }
                    fclose($stream);

                }
            }
        }
        return $data;
    }

    public static function conectar_ssh_amos($OSS, $comando, $sitio)
    {
        if ($OSS == "OSS13") {
            $credenciales_oss = new credenciales_OSS13();

        } else {
            $credenciales_oss = new credenciales_OSS14();
        }
        $server = $credenciales_oss->getHost();
        $prompt = $credenciales_oss->getPrompt();
        $port =$credenciales_oss->getPort();
        $user = $credenciales_oss->getUsuario();
        $pass = $credenciales_oss->getPassword();

        $formula = "amos " . $sitio . " 'lt all;".$comando.";q'";
        //$formula = "isql -Usa -Psybase11 -w300 -Dfmadb_1_1; 'select Event_time,Object_of_reference,Problem_text,SP_text from FMA_alarm_list where Object_of_reference like '%MeContext=M%' && go' ";

        // *******************   REVISAR FORMULA PARA OSS
        //echo $formula;
        //***********************************************

        $data = "";
        if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");

        if(!($con = ssh2_connect($server, $port))){
            echo "fail: unable to establish connection\n";
        }else{
            if(!ssh2_auth_password($con,$user,$pass)) {
                echo "fail: unable to authenticate\n";
            }
            else {
                if(!($stream = ssh2_exec($con, $formula )) ){
                    echo "fail: unable to execute command\n";
                }
                else {
                    stream_set_blocking( $stream, true );
                    $data = "";
                    while( $buf = fread($stream,4096) ){
                        $data .= $buf;
                    }
                    fclose($stream);

                }
            }
        }
        return $data;
    }

    public static function conectar_ssh_grep($OSS, $dir_inicial, $dir_final)
    {
        $MiFTP = new FTPClient();
        if ($OSS == "OSS13") {
            $credenciales_oss = new credenciales_OSS13();

        } else {
            $credenciales_oss = new credenciales_OSS14();
        }
        $server = $credenciales_oss->getHost();
        $prompt = $credenciales_oss->getPrompt();
        $port =$credenciales_oss->getPort();
        $user = $credenciales_oss->getUsuario();
        $pass = $credenciales_oss->getPassword();

        $formula = 'rm '.$dir_final.'; grep "0   *" ' . $dir_inicial . "/*.log > ".$dir_final;
        //$formula = "isql -Usa -Psybase11 -w300 -Dfmadb_1_1; 'select Event_time,Object_of_reference,Problem_text,SP_text from FMA_alarm_list where Object_of_reference like '%MeContext=M%' && go' ";

        // *******************   REVISAR FORMULA PARA OSS
        //echo $formula;
        //***********************************************

        $data = "";
        if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");

        if(!($con = ssh2_connect($server, $port))){
            $MiFTP->logMessage("fail_grep_1: unable to establish connection\r\n");
        }else{
            if(!ssh2_auth_password($con,$user,$pass)) {
                $MiFTP->logMessage("fail_grep_2: unable to authenticate\r\n");
            }
            else {
                if(!($stream = ssh2_exec($con, $formula )) ){
                    $MiFTP->logMessage("fail_grep_3: unable to execute command\r\n");
                }
                else {
                    stream_set_blocking( $stream, true );
                    $data = "";
                    while( $buf = fread($stream,4096) ){
                        $data .= $buf;
                    }
                    fclose($stream);

                }
            }
        }
        return $data;
    }

    public static function transforma_en_arreglo($OSS, $comando, $sitio){
        ini_set('max_execution_time', 3600); //300 seconds = 5 minutes --> 40 minutos
        $resultado = self::conectar_ssh_amosbatch($OSS, $comando, $sitio);

        $data = trim($resultado);
        $informe[] = "";
        foreach (explode("\n", $data) as $linea) {
            //$linea = trim($linea);
            $linea = preg_replace('/\s+/', ' ', $linea);
            $informe[] = "$linea";
        }
        self::inserta_datos_temp($OSS, $informe);

    }


    public static function inserta_datos_temp($OSS, $arreglo){

        $linea = end($arreglo);
        $datos1 = preg_split('/\s+/', $linea);
        $temp1 = trim($datos1[3]);

        self::conectar_ssh_grep($OSS, $temp1,'/home/excsleiv/Documents/temperaturas.txt');
    }

    public static function inserta_datos_alarm($OSS, $arreglo){

        $linea = end($arreglo);
        $datos1 = preg_split('/\s+/', $linea);
        $temp1 = trim($datos1[3]);

        self::conectar_ssh_grep_alarm($OSS, '[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9] [0-9][0-9]:[0-9][0-9]:[0-9][0-9]', $temp1,'/home/excsleiv/Documents/alarmas_temperaturas.txt');
    }

    public static function transforma_en_arreglo_alarma($OSS, $comando, $sitio){
        ini_set('max_execution_time', 3600); //300 seconds = 5 minutes --> 40 minutos
        $resultado = self::conectar_ssh_amosbatch($OSS, $comando, $sitio);

        $data = trim($resultado);
        $informe[] = "";
        foreach (explode("\n", $data) as $linea) {
            //$linea = trim($linea);
            $linea = preg_replace('/\s+/', ' ', $linea);
            $informe[] = "$linea";
        }
        self::inserta_datos_alarm($OSS,$informe);

    }

    public static function conectar_ssh_grep_alarm($OSS, $expresion, $dir_inicial, $dir_final)
    {
        $MiFTP = new FTPClient();
        if ($OSS == "OSS13") {
            $credenciales_oss = new credenciales_OSS13();

        } else {
            $credenciales_oss = new credenciales_OSS14();
        }
        $server = $credenciales_oss->getHost();
        $prompt = $credenciales_oss->getPrompt();
        $port =$credenciales_oss->getPort();
        $user = $credenciales_oss->getUsuario();
        $pass = $credenciales_oss->getPassword();

        $formula = 'rm '.$dir_final.'; grep "'.$expresion.'" ' . $dir_inicial . "/*.log > ".$dir_final;
        //$formula = "isql -Usa -Psybase11 -w300 -Dfmadb_1_1; 'select Event_time,Object_of_reference,Problem_text,SP_text from FMA_alarm_list where Object_of_reference like '%MeContext=M%' && go' ";

        // *******************   REVISAR FORMULA PARA OSS
        //echo $formula;
        //***********************************************

        $data = "";
        if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");

        if(!($con = ssh2_connect($server, $port))){
            $MiFTP->logMessage("fail_grep_1: unable to establish connection\r\n");
        }else{
            if(!ssh2_auth_password($con,$user,$pass)) {
                $MiFTP->logMessage("fail_grep_2: unable to authenticate\r\n");
            }
            else {
                if(!($stream = ssh2_exec($con, $formula )) ){
                    $MiFTP->logMessage("fail_grep_3: unable to execute command\r\n");
                }
                else {
                    stream_set_blocking( $stream, true );
                    $data = "";
                    while( $buf = fread($stream,4096) ){
                        $data .= $buf;
                    }
                    fclose($stream);

                }
            }
        }
        return $data;
    }


}
