<?php
class vw_historial {

    public static function sitio_recurrente($codigo_sitio){
        $sitio_recurrente = vm_grafico_temperaturas::traer_temperatura_sitio($codigo_sitio);
        $datos_sitio = $sitio_recurrente[0];
        ?>
        <div class="table-responsive project-list">
            <table class="table">
                <tbody>
                <?php
                    // Condicional para alarmas de la DUW 1
                    if ($datos_sitio['TEMP_DUW_1'] > 50 || $datos_sitio['TEMP_DUW_2'] < 0) {
                        $alarma_duw1= 'coloralarm-2';
                    }else {
                        $alarma_duw1= '';
                    }

                    // Condicional para alarmas de la DUW 2
                    if ($datos_sitio['TEMP_DUW_2'] == 0) {
                        $duw_2 = "-";
                        $alarma_duw2= '';
                    } elseif ($datos_sitio['TEMP_DUW_2'] > 50 || $datos_sitio['TEMP_DUW_2'] < 0) {
                        $alarma_duw2= 'coloralarm-2';
                        $duw_2 = $datos_sitio['TEMP_DUW_2'].' ºC';
                    } else {
                        $alarma_duw2= '';
                        $duw_2 = $datos_sitio['TEMP_DUW_2'].' ºC';
                    }

                    // Condicional para alarmas del Rectificador
                    if ($datos_sitio['TEMP_RECTIFICADOR'] > 50 || $datos_sitio['TEMP_DUW_2'] < 0) {
                        $alarma_rect= 'coloralarm-2';
                    }else {
                        $alarma_rect= '';
                    }

                    // Condicional para alarmas de la WISE
                    if ($datos_sitio['TEMP_WISE'] == 0) {
                        $wise = "-";
                        $alarma_wise= '';
                    } elseif ($datos_sitio['TEMP_WISE'] > 50 || $datos_sitio['TEMP_DUW_2'] < 0) {
                        $alarma_wise= 'coloralarm-2';
                        $wise = $datos_sitio['TEMP_WISE'].' ºC';
                    } else {
                        $alarma_wise= '';
                        $wise = $datos_sitio['TEMP_WISE'].' ºC';
                    }

                echo '  <tr>';
                echo '      <td class="col-md-3 hidden-xs text-center">
                                <h2 class="text-success"><strong>'.$datos_sitio['NOMBRE_SITIO'].'</strong></a>
                                <br/>
                                <h4 class="text-danger">'.$datos_sitio['SITIO'].'</h4></h2> 
                            </td>

                            <td class="col-sm-2 text-center">
                                <div class="text-gray ui-widget-content ui-state-hover ui-state-focus '.$alarma_duw1.' p-xxs">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h5>DUW 1</h5>
                                            <h2>'.$datos_sitio['TEMP_DUW_1'].' ºC</h2>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="col-sm-2 text-center">
                                <div class="text-orange ui-widget-content ui-state-hover ui-state-focus '.$alarma_duw2.' p-xxs">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h5>DUW 2</h5>
                                            <h2>'.$duw_2.'</h2>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="col-sm-2 text-center">
                                <div class="text-success ui-widget-content ui-state-hover ui-state-focus '.$alarma_rect.' p-xxs">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h5>BATERIA</h5>
                                            <h2>'.$datos_sitio['TEMP_RECTIFICADOR'].' ºC</h2>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="col-sm-2 text-center">
                                <div class="text-navy ui-widget-content ui-state-hover ui-state-focus '.$alarma_wise.' p-xxs">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h5>WISE</h5>
                                            <h2>'.$wise.'</h2>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a class="btn btn-white btn-sm text-gray-2 pull-right" target="_blank" href="http://'.$datos_sitio['IP'].'"><i class="fa fa-globe"> DC Control</i></a> 
                            </td>
                        </tr>';
                    ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    public static function ver_grafico(){
        $lista_sitios = vm_grafico_temperaturas::traer_temperatura_sitio(1);
        $datos_sitio = $lista_sitios[0];

            if ($datos_sitio['TEMP_DUW_2'] == 0) {
                $duw2_label = '';
            } else {
                $duw2_label = '<p class="label label-orange">DUW 2</p>';}

            if ($datos_sitio['TEMP_WISE'] == 0) {
                $wise_label = '';
            } else {
                $wise_label = '<p class="label label-primary">WISE</p>';}

        echo '  <div style="text-align: center">
                    <p class="label">DUW 1</p>
                    '.$duw2_label.'
                    <p class="label label-success">Batería</p>
                    '.$wise_label.'
                </div>
                <br/>';
    }


    public static function lista_temperaturas($cod_sitio){
        $lista_top_recurrentes = vm_grafico_temperaturas::traer_temperaturas_duw($cod_sitio);
        // $listado_sitios = sitios_temperatura::traer_sitios_totales();
        ?>
        <div class="table-responsive project-list-2 col-md-12">
            <table class="table table-hover" id="editable">
                <thead>
                    <tr>
                        <th class="col-md-2">Sitio</th>
                        <th class="col-md-3">Nombre Sitio</th>
                        <th class="col-md-3">Fecha</th>
                        <th class="col-md-1">Temp. DUW1</th>
                        <th class="col-md-1">Temp. DUW2</th>
                        <th class="col-md-1">Temp. Batería</th>
                        <th class="col-md-1">Temp. WISE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($lista_top_recurrentes as $resultado):
                        echo '  <tr>';
                            echo '  <td class="col-md-2">'.$resultado['SITIO'].'</td>';
                            echo '  <td class="col-md-3">'.$resultado['NOMBRE_SITIO'].'</td>';
                            echo '  <td class="col-md-3">'.$resultado['FECHA'].'</td>';
                            echo '  <td class="col-md-1">'.$resultado['TEMP_DUW_1'].'</td>';
                            echo '  <td class="col-md-1">'.$resultado['TEMP_DUW_2'].'</td>';
                            echo '  <td class="col-md-1">'.$resultado['TEMP_RECTIFICADOR'].'</td>';
                            echo '  <td class="col-md-1">'.$resultado['TEMP_WISE'].'</td>';
                        echo '  </tr>'; 
                        endforeach;
                    ?>
                </tbody>
            </table>
        </div>
    <?php
    }

}
