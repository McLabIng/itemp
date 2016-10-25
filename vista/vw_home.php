<?php
class vw_home {

    public static function lista_top_recurrentes(){
        $lista_top_recurrentes = vm_grafico_temperaturas::traer_temperaturas();
        $listado_sitios = sitios_temperatura::traer_sitios_totales();
        ?>
        <div class="ibox-title col-md-12 ui-widget-header blue-bg">
            <!-- <button class="pull-right btn btn-md btn-primary"><i class="fa fa-file-excel-o"></i></button> -->
            <input class="pull-right" type="text" id="myInputTextField" placeholder=" BUSCAR">
            <h4 class="p-xxs">Top Sitios con alarmas recurrentes</h4>
        </div>
        <div class="ibox-content table-responsive project-list col-md-12">
            <table class="table table-hover" id="editable">
                <thead>
                    <tr>
                        <th>Sitio</th>
                        <th>Temperaturas</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // $i = 1;
                // $count = count($lista_top_recurrentes);
                // // Evalúa si "$count" es par o impar
                // if ($count % 2 == 0) {
                //     $sites = ($count/2)+1;
                // } else {
                //     $sites = (($count+1)/2)+1;
                // }

                foreach($lista_top_recurrentes as $resultado):
                    // Condicional para alarmas de la DUW 1
                    if ($resultado['TEMP_DUW_1'] > 50 || $resultado['TEMP_DUW_2'] < 0) {
                        $alarma_duw1= 'coloralarm-2';
                    }else {
                        $alarma_duw1= '';
                    }

                    // Condicional para alarmas de la DUW 2
                    if ($resultado['TEMP_DUW_2'] == "0") {
                        $duw_2 = "-";
						$alarma_duw2= '';
                    } elseif ($resultado['TEMP_DUW_2'] > 50 || $resultado['TEMP_DUW_2'] < 0) {
                        $alarma_duw2= 'coloralarm-2';
                        $duw_2 = $resultado['TEMP_DUW_2'].' ºC';
                    } else {
                        $alarma_duw2= '';
                        $duw_2 = $resultado['TEMP_DUW_2'].' ºC';
                    }

                    // Condicional para alarmas del Rectificador
                    if ($resultado['TEMP_RECTIFICADOR'] > 28 || $resultado['TEMP_DUW_2'] < 0) {
                        $alarma_rect= 'coloralarm-2';
                    }else {
                        $alarma_rect= '';
                    }

                    // Condicional para alarmas de la WISE
                    if ($resultado['TEMP_WISE'] == "0") {
                        $wise = "-";
						$alarma_wise= '';
                    } elseif ($resultado['TEMP_WISE'] > 50 || $resultado['TEMP_DUW_2'] < 0) {
                        $alarma_wise= 'coloralarm-2';
                        $wise = $resultado['TEMP_WISE'].' ºC';
                    } else {
                        $alarma_wise= '';
                        $wise = $resultado['TEMP_WISE'].' ºC';
                    }
					
					if ($dato['UBICACION_BATERIA'] == 1) {
                        $ubicacion = 'EL MISMO';
                        $label_gabinete = 'label-success';
                    } else {
                        $ubicacion = 'DISTINTO';
                        $label_gabinete = 'label-default';}

                    // if ($i == $sites) {
                    //     $segunda_columna = '
                    //             </tbody>
                    //         </table>
                    //     </div>
                    //     <div class="table-responsive project-list col-md-6">
                    //     <table class="table table-hover">
                    //         <tbody>';
                    // } else {
                    //     $segunda_columna = '';
                    // }

                    // echo $segunda_columna;

                echo '  <tr id="sitio-'.$resultado['COD_SITIO'].'">';
                    echo '  <td class="col-md-4 col-xs-5">
                                <a data-toggle="tab" href="#sitio'.$resultado['COD_SITIO'].'"><h4><strong>'.$resultado['NOMBRE_SITIO'].'</strong></a>
                                <br/>
                                <h5 class="text-gray">'.$resultado['SITIO'].'<!--label class="label '.$label_gabinete.' pull-right">'.$ubicacion.'</label--></h5>
                                <br/>
                                <a class="text-gray-2 pull-right" target="_blank" href="http://'.$resultado['IP'].'"><small>DC Control </small><i class="fa fa-globe"></i></a>
                                <a class="text-gray-2" href="?mod=historial&cod_sitio='.$resultado['COD_SITIO'].'"><i class="fa fa-calendar"></i><small> Historial</small></a>  
                            </td>';
                    
                    echo '  <td class="col-md-8 col-xs-7" type="button" value="sitio-'.$resultado['COD_SITIO'].'" style="text-align: center" style="min-width:">
                                    <div class="col-sm-3">
                                        <a data-toggle="tab" href="#sitio'.$resultado['COD_SITIO'].'">
                                            <div class="text-gray ui-widget-content ui-state-hover ui-state-focus '.$alarma_duw1.' p-xxs m-l-n m-r-n-md">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <h5>DUW 1</h5>
                                                        <h2 style="font-weight: 300">'.$resultado['TEMP_DUW_1'].' ºC</h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-sm-3">
                                        <a data-toggle="tab" href="#sitio'.$resultado['COD_SITIO'].'">
                                            <div class="text-orange ui-widget-content ui-state-hover ui-state-focus '.$alarma_duw2.' p-xxs m-l-n m-r-n-md">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <h5>DUW 2</h5>
                                                        <h2 style="font-weight: 300">'.$duw_2.'</h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-sm-3">
                                        <a data-toggle="tab" href="#sitio'.$resultado['COD_SITIO'].'">
                                            <div class="text-success ui-widget-content ui-state-hover ui-state-focus '.$alarma_rect.' p-xxs m-l-n-md m-r-n">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <h5>BATERIA</h5>
                                                        <h2 style="font-weight: 300">'.$resultado['TEMP_RECTIFICADOR'].' ºC</h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-sm-3">
                                        <a data-toggle="tab" href="#sitio'.$resultado['COD_SITIO'].'">
                                            <div class="text-navy ui-widget-content ui-state-hover ui-state-focus '.$alarma_wise.' p-xxs m-l-n-md m-r-n">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <h5>WISE</h5>
                                                        <h2 style="font-weight: 300">'.$wise.'</h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </td>
                            </tr>';
                    // $i = $i + 1;
                endforeach;
                ?>
                </tbody>
            </table>
        </div>
    <?php
    }

    public static function ver_grafico_diario(){
        $lista_sitios = vm_grafico_temperaturas::traer_temperaturas();
        $active = " active";
        foreach ($lista_sitios as $dato){

            if ($dato['TEMP_DUW_2'] == 0) {
                $duw2_label = '';
            } else {
                $duw2_label = '<p class="label label-orange">DUW 2</p>';}

            if ($dato['TEMP_WISE'] == 0) {
                $wise_label = '';
            } else {
                $wise_label = '<p class="label label-primary">WISE</p>';}
				
			if ($dato['UBICACION_BATERIA'] == 1) {
                $ubicacion = 'EL MISMO';
            } else {
                $ubicacion = 'DISTINTO';}

            echo '  <div id="sitio'.$dato['COD_SITIO'].'" class="tab-pane'.$active.'">
                        <h4 class="text-center">Información del Sitio '.$dato['SITIO'].'</h4>
                        <h3 class="text-center"><strong>'.$dato['NOMBRE_SITIO'].'</strong></h3>
                        <br/>
                        <div style="text-align: center">
                            <p class="label">DUW 1</p>
                            '.$duw2_label.'
                            <p class="label label-success">Batería</p>
                            '.$wise_label.'
                        </div>
                        <br/>
                        <!--h5>Batería instalada en <strong>'.$ubicacion.'</strong> gabinete que DUW 1</h5>
                        <br/-->
                    </div>';
            $active = "";
        }
        // echo '  <a class="btn btn-success btn-outline btn-xs" href="?mod=historial&sitio='.$dato['COD_SITIO'].'"><i class="fa fa-search"></i>&nbsp;Ver</a>';
    }

}
