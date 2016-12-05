<?php
require_once 'conexion.php';

class vm_grafico_temperaturas {

    const TABLA_1 = 'itemp.sitios_temperatura';
    const TABLA_2 = 'itemp.historial_temperaturas';
    const TABLA_3 = 'itemp.tipo_rectificador';

// QUERYS PARA DASHBOARD
    public static function traer_temperaturas(){
        $conexion = new Conexion();

        $consulta = $conexion->prepare(' SELECT
                    S.COD_SITIO, S.SITIO, S.NOMBRE_SITIO, S.IP, S.TEMP_DUW_1, S.TEMP_DUW_2, S.TEMP_RECTIFICADOR, S.TEMP_WISE
                    FROM '.self::TABLA_1.' S
                    WHERE ACTIVO = 1
                    ORDER BY
                    S.TEMP_RECTIFICADOR DESC,
                    S.TEMP_DUW_1 DESC');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        return $registros;
    }

    public static function traer_temperaturas_grafico($cod_sitio,$cantidad){
        $conexion = new Conexion();

        $consulta = $conexion->prepare(' SELECT
                    H.COD_HISTORIAL, H.COD_SITIO, H.FECHA, H.TEMP_DUW_1, H.TEMP_DUW_2, H.TEMP_RECTIFICADOR, H.TEMP_WISE, H.ALARMADO
                    FROM '.self::TABLA_2.' H
                    WHERE
                    H.COD_SITIO = :cod_sitio
                    ORDER BY
                    H.FECHA DESC
                    limit :cantidad ');
        $consulta->bindParam(':cod_sitio', $cod_sitio, PDO::PARAM_INT);
        $consulta->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        return $registros;
    }

// QUERYS PARA HISTORIAL
    public static function traer_temperatura_sitio($cod_sitio){
        $conexion = new Conexion();

        $consulta = $conexion->prepare(' SELECT
                    S.COD_SITIO, S.SITIO, S.NOMBRE_SITIO, S.IP, S.TEMP_DUW_1, S.TEMP_DUW_2, S.TEMP_RECTIFICADOR, S.TEMP_WISE, S.TEMP_SET
                    FROM '.self::TABLA_1.' S
                    WHERE
                    S.COD_SITIO = :cod_sitio
                    ORDER BY
                    S.COD_SITIO  ');
        $consulta->bindParam(':cod_sitio', $cod_sitio, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        return $registros;
    }

    public static function traer_temperaturas_grafico_historial($cod_sitio){
        $conexion = new Conexion();

        $consulta = $conexion->prepare(' SELECT
                    H.COD_SITIO, H.FECHA, H.TEMP_DUW_1, H.TEMP_DUW_2, H.TEMP_RECTIFICADOR, H.TEMP_WISE, H.ALARMADO
                    FROM '.self::TABLA_2.' H
                    WHERE
                    H.COD_SITIO = :cod_sitio
                    ORDER BY
                    H.FECHA DESC
                    LIMIT 1440 ');
        $consulta->bindParam(':cod_sitio', $cod_sitio, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        return $registros;
    }

    public static function traer_temperaturas_duw($cod_sitio){
        $conexion = new Conexion();

        $consulta = $conexion->prepare(' SELECT
                    S.SITIO, S.NOMBRE_SITIO, H.FECHA, H.TEMP_DUW_1, H.TEMP_DUW_2, H.TEMP_RECTIFICADOR, H.TEMP_WISE, H.ALARMADO
                    FROM '.self::TABLA_2.' H, '.self::TABLA_1.' S
                    WHERE H.COD_SITIO = :cod_sitio AND 
                    S.COD_SITIO = H.COD_SITIO
                    ORDER BY
                    H.FECHA DESC  ');
        $consulta->bindParam(':cod_sitio', $cod_sitio, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        return $registros;
    }

    public static function traer_temperaturas_max_bateria($cod_sitio){
        $conexion = new Conexion();

        $consulta = $conexion->prepare(' SELECT
            S.COD_SITIO, S.SITIO, S.NOMBRE_SITIO, MAX(H.TEMP_RECTIFICADOR) MAX_TEMP_BATERIA
            FROM '.self::TABLA_1.' S
            LEFT JOIN '.self::TABLA_2.' H ON S.COD_SITIO = H.COD_SITIO
            WHERE S.ACTIVO = 1
            GROUP BY S.SITIO  ');
        $consulta->bindParam(':cod_sitio', $cod_sitio, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        return $registros;
    }


}