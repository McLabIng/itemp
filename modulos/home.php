<?php
session_start();
require_once 'clases/usuario.php';
require_once 'clases/vm_grafico_temperaturas.php';
require_once 'vista/vw_home.php';
require_once 'clases/sitios_temperatura.php';
require_once 'clases/tipo_rectificador.php';

$Fecha=getdate();
$Anio=$Fecha["year"];

$mi_usuario = cl_usuario::traer_usuario($_SESSION["username"]);
$mi_area = $mi_usuario->getCod_area();
$mi_rol = $mi_usuario->getCod_rol();

$cantidad = 48;
$sitios = vm_grafico_temperaturas::traer_temperaturas();
foreach ($sitios as $key) { 
    $cod_sitios[] = $key['COD_SITIO'];
}
foreach ($cod_sitios as $sitio) {
    ${'datos_sitio_'.$sitio} = vm_grafico_temperaturas::traer_temperaturas_grafico($sitio,$cantidad);
    foreach(${'datos_sitio_'.$sitio} as $rows){ 
        ${'data_temp_inverso_'.$sitio}[] = array($rows['COD_SITIO'],$rows['FECHA'],$rows['TEMP_DUW_1'],$rows['TEMP_DUW_2'],$rows['TEMP_RECTIFICADOR'],$rows['TEMP_WISE'],$rows['ALARMADO']);
        ${'data_temp_'.$sitio} = array_reverse(${'data_temp_inverso_'.$sitio});
    }   
}

$lista_tipo_rectificador = tipo_rectificador::listado_tipo_rectificador();

?>
<script type="text/javascript">
    setTimeout(function(){
        location = '';
    },600000)
</script>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <h2 class="pull-right">Sub Gerencia O&M Infraestructura</h2>
        <h2>iTEMP - Sistema de Temperatura de Sitios.
        <div class="pull-right">
        <!--?php
        // Vista de tabla por regiones
        vw_home::botonera_tv();
        ?-->
        </div></h2>
        <ol class="breadcrumb">
            <li class="active">
                <a href="?mod=home">Alarmas a nivel nacional</a>
            </li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInDown">

    <div class="row">
    
        <div class="col-lg-5 col-md-12">
            <div  class="ibox float-e-margins">
                <?php
                    vw_home::lista_top_recurrentes($lista_tipo_rectificador);
                ?>
            </div>
        </div>
        <div class="col-lg-7 col-md-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title col-md-12 ui-widget-header blue-bg">
                    <h4 class="p-xxs">Gráfico de temperaturas</h4>
                </div>
                <div class="ibox-content">
                    <div class="tab-content">
                        <?php
                        vw_home::ver_grafico_diario();
                        ?>
                    </div>
                    <canvas id="sitio_base" height="600" width="1200"></canvas>
                    <!-- <div id="chartdiv" style="width: 100%; height: 400px;"> -->
                        
                    <!-- </div> -->
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Mainly scripts -->
<script src="js/jquery-2.1.1.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="js/inspinia.js"></script>
<script src="js/plugins/pace/pace.min.js"></script>

<!-- Tinycon -->
<script src="js/plugins/tinycon/tinycon.min.js"></script>

<!-- jQuery UI -->
<script src="js/plugins/jquery-ui/jquery-ui.min.js"></script>

<!-- Sparkline -->
<script src="js/plugins/sparkline/jquery.sparkline.min.js"></script>

<!-- Sparkline demo data  -->
<script src="js/demo/sparkline-demo.js"></script>

<!-- Data Tables -->
<script src="js/plugins/dataTables/datatables.min.js"></script>

<script src="js/plugins/masonary/masonry.pkgd.min.js"></script>

<!-- Chosen -->
<script src="js/plugins/chosen/chosen.jquery.js"></script>

<!-- Switchery -->
<script src="js/plugins/switchery/switchery.js"></script>

<!-- MENU -->
<script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>

<!-- amchart-->
<script src="js/plugins/amcharts/amcharts.js" type="text/javascript"></script>
<script src="js/plugins/amcharts/serial.js" type="text/javascript"></script>

<script src="js/plugins/chartJs/Chart.bundle.js"></script>

<!-- Input Mask -->
<script src="js/plugins/ip/jquery.mask.min.js"></script>

<script>    
    // Chosen
    $('.chosen_select_tipo_rectificador').chosen({
        width: "100%",
        disable_search: true
    });

    //EDITABLE
    $(document).ready(function(){
        /* Init DataTables */
        var oTable = $('#editable').DataTable({
        "paging":   false,
        "ordering": false,
        // "info": false,
        // "filter": false,
        "scrollY": "600px",
        // "scrollCollapse": true,
        "order": [[ 1, "desc" ]],
        "aoColumns": [
            null,
            { "orderSequence": [ "desc", "asc" ] },
        ],
        // "dom": "<'row'<'col-sm-6'l><'col-sm-6'f>>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
        // "lengthMenu": [ [6, 25, 50, -1], [6, 25, 50, "All"] ],
        // "iDisplayLength": 20,
        });

        $('#myInputTextField').keyup(function(){
            oTable.search($(this).val()).draw() ;
        });

        // Input Mask
        $('.ip_address').mask('0ZZ.0ZZ.0ZZ.0ZZ', {translation:  {'Z': {pattern: /[0-9]/, optional: true}}});
        $('.ip_wise').mask('0ZZ.0ZZ.0ZZ.0ZZ', {translation:  {'Z': {pattern: /[0-9]/, optional: true}}});

    });

    // ##### Gráficos #####
    <?php
        $var = ${'data_temp_'.$cod_sitios[0]};
        echo " 
        var config = {
            labels: ["; foreach($var as $datos){$date = date("M-d H:i", strtotime($datos[1])); echo " '".$date."',";} echo "],
            datasets: [{
                label: 'DUW 1',
                type: 'line',
                borderWidth: 2,
                borderColor: 'rgba(140,140,140,1)',
                backgroundColor: 'rgba(140,140,140,1)',
                pointBorderColor: 'rgba(255,255,255,1)',
                pointBorderWidth: 1.5,
                data: ["; foreach($var as $datos){ echo $datos[2].",";} echo "],
                fill: false,
            }"; if ($datos[3] == 0) { echo "";} else { echo "
              , {
                label: 'DUW 2',
                type: 'line',
                borderWidth: 2,
                borderColor: 'rgba(255,144,43,1)',
                backgroundColor: 'rgba(255,144,43,1)',
                pointBorderColor: 'rgba(255,255,255,1)',
                pointBorderWidth: 1.5,
                data: ["; foreach($var as $datos){ echo $datos[3].",";} echo "],
                fill: false,
            }";} echo "
              , {
                label: 'Batería',
                type: 'line',
                borderWidth: 2,
                borderColor: 'rgba(14,132,201,1)',
                backgroundColor: 'rgba(14,132,201,1)',
                pointBorderColor: 'rgba(255,255,255,1)',
                pointBorderWidth: 1.5,
                data: ["; foreach($var as $datos){ echo $datos[4].",";} echo "],
                fill: false,
            }"; if ($datos[5] == 0) { echo ""; } else { echo "
              , {
                label: 'WISE',
                type: 'line',
                borderWidth: 2,
                borderColor: 'rgba(36, 178, 147,1)',
                backgroundColor: 'rgba(36, 178, 147,1)',
                pointBorderColor: 'rgba(255,255,255,1)',
                pointBorderWidth: 1.5,
                data: ["; foreach($var as $datos){ echo $datos[5].",";} echo "],
                fill: false,
            }";} echo "
              , {
                label: 'Alarmas',
                type: 'bar',
                borderColor: 'rgba(254, 43, 2, 0.5)',
                backgroundColor: 'rgba(254, 43, 2, 0.1)',
                borderWidth: 1,
                data: ["; foreach($var as $datos){ echo 60*$datos[6].',';} echo "],
            }]
        };
        ";?>

        window.onload = function() {
            var ctx = document.getElementById("sitio_base").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: config,
                options: {
                    responsive: true,
                    title: {
                        display: false,
                    },
                    legend: {
                        display: false,
                    },
                    tooltips: {
                        mode: 'label',
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            labelString: '<?php foreach ($var as $datos) { echo date("H:i", strtotime($datos[1]));}?>'
                        }]
                    }
                }
            });
        };

        <?php
        foreach ($cod_sitios as $sitio) {
            $var = ${'data_temp_'.$sitio};

        echo "
        $('#sitio-".$sitio."').click(function() {
            window.myBar.destroy();
            var config = {
                labels: ["; foreach($var as $datos){ $date = date("M-d H:i", strtotime($datos[1])); echo "'".$date."',";} echo "],
                datasets: [{
                    label: 'DUW 1',
                    type: 'line',
                    borderWidth: 2,
                    borderColor: 'rgba(140,140,140,1)',
                    backgroundColor: 'rgba(140,140,140,1)',
                    pointBorderColor: 'rgba(255,255,255,1)',
                    pointBorderWidth: 1.5,
                    data: ["; foreach($var as $datos){ echo $datos[2].",";} echo "],
                    fill: false,
                }"; if ($datos[3] == 0) { echo "";} else { echo "
                  , {
                    label: 'DUW 2',
                    type: 'line',
                    borderWidth: 2,
                    borderColor: 'rgba(255,144,43,1)',
                    backgroundColor: 'rgba(255,144,43,1)',
                    pointBorderColor: 'rgba(255,255,255,1)',
                    pointBorderWidth: 1.5,
                    data: ["; foreach($var as $datos){ echo $datos[3].",";} echo "],
                    fill: false,
                }";} echo "
                  , {
                    label: 'Batería',
                    type: 'line',
                    borderWidth: 2,
                    borderColor: 'rgba(14,132,201,1)',
                    backgroundColor: 'rgba(14,132,201,1)',
                    pointBorderColor: 'rgba(255,255,255,1)',
                    pointBorderWidth: 1.5,
                    data: ["; foreach($var as $datos){ echo $datos[4].",";} echo "],
                    fill: false,
                }"; if ($datos[5] == 0) { echo ""; } else { echo "
                  , {
                    label: 'WISE',
                    type: 'line',
                    borderWidth: 2,
                    borderColor: 'rgba(36, 178, 147,1)',
                    backgroundColor: 'rgba(36, 178, 147,1)',
                    pointBorderColor: 'rgba(255,255,255,1)',
                    pointBorderWidth: 1.5,
                    data: ["; foreach($var as $datos){ echo $datos[5].",";} echo "],
                    fill: false,
                }";} echo "
                  , {
                    label: 'Alarmas',
                    type: 'bar',
                    borderColor: 'rgba(254, 43, 2, 0.5)',
                    backgroundColor: 'rgba(254, 43, 2, 0.1)',
                    borderWidth: 1,
                    data: ["; foreach($var as $datos){ echo 60*$datos[6].',';} echo "],
                }]
            };

            var ctx = document.getElementById('sitio_base').getContext('2d');
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: config,
                options: {
                    responsive: true,
                    title: {
                        display: false,
                    },
                    legend: {
                        display: false,
                    },
                    tooltips: {
                        mode: 'label',
                    },
                }
            });
        });
        ";}?>

    //##### Gráficos AM Charts ##### 
    var alerta = 28;
    var graph;
    var chart;
    var chartData = [
        <?php foreach(${'data_temp_'.$cod_sitios[0]} as $datos){
        echo '
            {"fecha": "'.$datos[1].'", "duw1": '.$datos[2]; if ($datos[3] == 0) { echo '';} else { echo ', "duw2": '.$datos[3];} echo ', "bateria": '.$datos[4]; if ($datos[5] == 0) { echo '';} else { echo ', "wise": '.$datos[5];} echo ' },';}?>
            ];


    AmCharts.ready(function () {
        // SERIAL CHART
        chart = new AmCharts.AmSerialChart();

        chart.dataProvider = chartData;
        chart.categoryField = "fecha";
        chart.startDuration = 0.3;
        chart.balloon.color = "#000000";
        chart.dataDateFormat = "YYYY-MM-DD JJ:NN:SS";

        // listen for "dataUpdated" event (fired when chart is inited) and call zoomChart method when it happens
        chart.addListener("dataUpdated", zoomChart);

        // AXES
        // category
        var categoryAxis = chart.categoryAxis;
        categoryAxis.parseDates = true; // in order char to understand dates, we should set parseDates to true
        categoryAxis.minPeriod = "mm";
        // categoryAxis.dashLength = 1;
        // categoryAxis.fillAlpha = 0;
        // categoryAxis.fillColor = "#FAFAFA";
        categoryAxis.gridAlpha = 0.15;
        // categoryAxis.gridPosition = "start";
        categoryAxis.axisColor = "#DADADA";
        // categoryAxis.axisAlpha = 0;
        // categoryAxis.position = "bottom";
        // categoryAxis.minorGridEnabled = true;
        // categoryAxis.minorGridAlpha = 0.1;

        // value
        var valueAxis = new AmCharts.ValueAxis();
        valueAxis.title = "Temperatura";
        valueAxis.dashLength = 0;
        valueAxis.axisAlpha = 0;
        // valueAxis.minimum = 60;
        // valueAxis.maximum = 0;
        valueAxis.integersOnly = true;
        valueAxis.gridCount = 10;
        valueAxis.reversed = false; // this line makes the value axis reversed
        chart.addValueAxis(valueAxis);

        // GUIDE Alerta Temperatura
        var guide = new AmCharts.Guide();
        guide.value = alerta;
        guide.lineColor = "rgba(255,102,1,1)";
        guide.dashLength = 2;
        guide.label = "Límite Temperatura Batería";
        guide.inside = true;
        guide.lineAlpha = 1;
        guide.lineThickness = 1;
        valueAxis.addGuide(guide);

        // ALERT guide
        <?php
        foreach (${'data_temp_'.$cod_sitios[0]} as $datos) {
        $fecha = date("H:i M-d", strtotime($datos[1]));
        if ($datos[6] == 0) { echo '';} else { echo '
        var guide1 = new AmCharts.Guide();
        guide1.date = "'.$datos[1].'";
        guide1.lineColor = "rgba(255,102,1,1)";
        guide1.lineAlpha = 1;
        guide1.lineThickness = 2;
        guide1.dashLength = 2;
        guide1.inside = true;
        guide1.labelRotation = 90;
        guide1.label = "ALARMA";
        categoryAxis.addGuide(guide1);';}
        }?>

        // GRAPHS
        // DUW 1 graph
        var graph = new AmCharts.AmGraph();
        graph.type = "smoothedLine"; // this line makes the graph smoothed line.
        graph.title = "DUW 1";
        graph.valueField = "duw1";
        graph.hidden = false; // this line makes the graph initially hidden
        graph.balloonText = "DUW 1: [[value]] ºC";
        graph.lineAlpha = 1;
        graph.lineColor = "rgba(140,140,140,1)";
        graph.lineThickness = 2;
        graph.bullet = "round";
        // graph.bulletColor = "rgba(140,140,140,1)";
        graph.bulletSize = 6;
        graph.bulletBorderColor = "#FFFFFF";
        graph.bulletBorderAlpha = 1;
        // graph.bulletBorderThickness = 2;
        chart.addGraph(graph);

        <?php
        if ($datos[3] == 0) { echo '';} else { echo '
        // DUW 2 graph
        var graph = new AmCharts.AmGraph();
        graph.type = "smoothedLine"; // this line makes the graph smoothed line.
        graph.title = "DUW 2";
        graph.valueField = "duw2";
        graph.hidden = false; // this line makes the graph initially hidden
        graph.balloonText = "DUW 2: [[value]] ºC";
        graph.lineAlpha = 1;
        graph.lineColor = "rgba(255,144,43,1)";
        graph.lineThickness = 2;
        graph.bullet = "round";
        graph.bulletSize = 6;
        graph.bulletBorderColor = "#FFFFFF";
        graph.bulletBorderAlpha = 1;
        // graph.bulletBorderThickness = 2;
        chart.addGraph(graph);
        ';}?>

        // Batería graph
        var graph = new AmCharts.AmGraph();
        graph.type = "smoothedLine"; // this line makes the graph smoothed line.
        graph.title = "Batería";
        graph.valueField = "bateria";
        graph.balloonText = "Batería: [[value]] ºC";
        graph.lineAlpha = 1;
        graph.lineColor = "rgba(14,132,201,1)";
        graph.lineThickness = 2;
        graph.bullet = "round";
        graph.bulletSize = 6;
        graph.bulletBorderColor = "#FFFFFF";
        graph.bulletBorderAlpha = 1;
        // graph.bulletBorderThickness = 2;
        chart.addGraph(graph);

        // WISE graph
        <?php
        if ($datos[5] == 0) { echo '';} else { echo '
        var graph = new AmCharts.AmGraph();
        graph.type = "smoothedLine"; // this line makes the graph smoothed line.
        graph.title = "WISE";
        graph.valueField = "wise";
        graph.balloonText = "WISE: [[value]] ºC";
        graph.lineAlpha = 1;
        graph.lineColor = "rgba(26,179,148,1)";
        graph.lineThickness = 2;
        graph.bullet = "round";
        graph.bulletSize = 6;
        graph.bulletBorderColor = "#FFFFFF";
        graph.bulletBorderAlpha = 1;
        // graph.bulletBorderThickness = 2;
        chart.addGraph(graph);';}?>

        // CURSOR
        var chartCursor = new AmCharts.ChartCursor();
        chartCursor.cursorPosition = "mouse";
        chartCursor.zoomable = false;
        chartCursor.cursorAlpha = 0;
        chartCursor.categoryBalloonDateFormat = "JJ:NN, DD MMMM";
        chart.addChartCursor(chartCursor);

        // LEGEND
        var legend = new AmCharts.AmLegend();
        legend.useGraphSettings = true;
        legend.align = "center";
        chart.addLegend(legend);

        // SCROLLBAR
        // var chartScrollbar = new AmCharts.ChartScrollbar();
        // chartScrollbar.graph = graph;
        // chartScrollbar.scrollbarHeight = 15;
        // chart.addChartScrollbar(chartScrollbar);

        function zoomChart() {
            // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
            // chart.zoomToIndexes(chartData.length - 40, chartData.length - 1);
            chart.zoomToIndexes(<?php echo count(${'datos_sitio_'.$cod_sitios[0]})-$show;?>, <?php echo count(${'datos_sitio_'.$cod_sitios[0]}).')';?>;
            // chart.zoomToDates(new Date(2016-06-15, 0), new Date(2016-06-20, 0));
        }

        // WRITE
        chart.write("chartdiv");
    });


    // <?php

    // foreach ($cod_sitios as $sitio) {
    //     $var = ${'data_temp_'.$sitio};
    //     echo '
    //     var chartData = [';
    //         foreach($var as $datos){
    //         echo '
    //             {"fecha": "'.$datos[1].'", "duw1": '.$datos[2]; if ($datos[3] == 0) { echo '';} else { echo ', "duw2": '.$datos[3];} echo ', "bateria": '.$datos[4]; if ($datos[5] == 0) { echo '';} else { echo ', "wise": '.$datos[5];} echo ' },';
    //         }
    //     echo '];


    //     AmCharts.ready(function () {
    //         // SERIAL CHART
    //         chart = new AmCharts.AmSerialChart();

    //         chart.dataProvider = chartData;
    //         chart.categoryField = "fecha";
    //         chart.startDuration = 0.3;
    //         chart.balloon.color = "#000000";
    //         chart.dataDateFormat = "YYYY-MM-DD JJ:NN:SS";

    //         // listen for "dataUpdated" event (fired when chart is inited) and call zoomChart method when it happens
    //         chart.addListener("dataUpdated", zoomChart);

    //         // AXES
    //         // category
    //         var categoryAxis = chart.categoryAxis;
    //         categoryAxis.parseDates = true; // in order char to understand dates, we should set parseDates to true
    //         categoryAxis.minPeriod = "mm";
    //         // categoryAxis.dashLength = 1;
    //         // categoryAxis.fillAlpha = 0;
    //         // categoryAxis.fillColor = "#FAFAFA";
    //         categoryAxis.gridAlpha = 0.15;
    //         // categoryAxis.gridPosition = "start";
    //         categoryAxis.axisColor = "#DADADA";
    //         // categoryAxis.axisAlpha = 0;
    //         // categoryAxis.position = "bottom";
    //         // categoryAxis.minorGridEnabled = true;
    //         // categoryAxis.minorGridAlpha = 0.1;

    //         // value
    //         var valueAxis = new AmCharts.ValueAxis();
    //         valueAxis.title = "Temperatura";
    //         valueAxis.dashLength = 0;
    //         valueAxis.axisAlpha = 0;
    //         // valueAxis.minimum = 60;
    //         // valueAxis.maximum = 0;
    //         valueAxis.integersOnly = true;
    //         valueAxis.gridCount = 10;
    //         valueAxis.reversed = false; // this line makes the value axis reversed
    //         chart.addValueAxis(valueAxis);

    //         // GUIDE Alerta Temperatura
    //         var guide = new AmCharts.Guide();
    //         guide.value = alerta;
    //         guide.lineColor = "rgba(255,102,1,1)";
    //         guide.dashLength = 2;
    //         guide.label = "Límite Temperatura Batería";
    //         guide.inside = true;
    //         guide.lineAlpha = 1;
    //         guide.lineThickness = 1;
    //         valueAxis.addGuide(guide);';

    //         // ALERT guide
    //         foreach ($var as $datos) {
    //         $fecha = date("H:i M-d", strtotime($datos[1]));
    //         if ($datos[6] == 0) { echo '';} else { echo '
    //         var guide1 = new AmCharts.Guide();
    //         guide1.date = "'.$datos[1].'";
    //         guide1.lineColor = "rgba(255,102,1,1)";
    //         guide1.lineAlpha = 1;
    //         guide1.lineThickness = 2;
    //         guide1.dashLength = 2;
    //         guide1.inside = true;
    //         guide1.labelRotation = 90;
    //         guide1.label = "ALARMA";
    //         categoryAxis.addGuide(guide1);';}
    //         };

    //         // GRAPHS
    //         // DUW 1 graph
    //         echo '
    //         var graph = new AmCharts.AmGraph();
    //         graph.type = "smoothedLine"; // this line makes the graph smoothed line.
    //         graph.title = "DUW 1";
    //         graph.valueField = "duw1";
    //         graph.hidden = false; // this line makes the graph initially hidden
    //         graph.balloonText = "DUW 1: [[value]] ºC";
    //         graph.lineAlpha = 1;
    //         graph.lineColor = "rgba(140,140,140,1)";
    //         graph.lineThickness = 2;
    //         graph.bullet = "round";
    //         // graph.bulletColor = "rgba(140,140,140,1)";
    //         graph.bulletSize = 6;
    //         graph.bulletBorderColor = "#FFFFFF";
    //         graph.bulletBorderAlpha = 1;
    //         // graph.bulletBorderThickness = 2;
    //         chart.addGraph(graph);';

    //         if ($datos[3] == 0) { echo '';} else { echo '
    //         // DUW 2 graph
    //         var graph = new AmCharts.AmGraph();
    //         graph.type = "smoothedLine"; // this line makes the graph smoothed line.
    //         graph.title = "DUW 2";
    //         graph.valueField = "duw2";
    //         graph.hidden = false; // this line makes the graph initially hidden
    //         graph.balloonText = "DUW 2: [[value]] ºC";
    //         graph.lineAlpha = 1;
    //         graph.lineColor = "rgba(255,144,43,1)";
    //         graph.lineThickness = 2;
    //         graph.bullet = "round";
    //         graph.bulletSize = 6;
    //         graph.bulletBorderColor = "#FFFFFF";
    //         graph.bulletBorderAlpha = 1;
    //         // graph.bulletBorderThickness = 2;
    //         chart.addGraph(graph);
    //         ';};

    //         // Batería graph
    //         echo'
    //         var graph = new AmCharts.AmGraph();
    //         graph.type = "smoothedLine"; // this line makes the graph smoothed line.
    //         graph.title = "Batería";
    //         graph.valueField = "bateria";
    //         graph.balloonText = "Batería: [[value]] ºC";
    //         graph.lineAlpha = 1;
    //         graph.lineColor = "rgba(14,132,201,1)";
    //         graph.lineThickness = 2;
    //         graph.bullet = "round";
    //         graph.bulletSize = 6;
    //         graph.bulletBorderColor = "#FFFFFF";
    //         graph.bulletBorderAlpha = 1;
    //         // graph.bulletBorderThickness = 2;
    //         chart.addGraph(graph);';

    //         // WISE graph
    //         if ($datos[5] == 0) { echo '';} else { echo '
    //         var graph = new AmCharts.AmGraph();
    //         graph.type = "smoothedLine"; // this line makes the graph smoothed line.
    //         graph.title = "WISE";
    //         graph.valueField = "wise";
    //         graph.balloonText = "WISE: [[value]] ºC";
    //         graph.lineAlpha = 1;
    //         graph.lineColor = "rgba(26,179,148,1)";
    //         graph.lineThickness = 2;
    //         graph.bullet = "round";
    //         graph.bulletSize = 6;
    //         graph.bulletBorderColor = "#FFFFFF";
    //         graph.bulletBorderAlpha = 1;
    //         // graph.bulletBorderThickness = 2;
    //         chart.addGraph(graph);';};

    //         // CURSOR
    //         echo'
    //         var chartCursor = new AmCharts.ChartCursor();
    //         chartCursor.cursorPosition = "mouse";
    //         chartCursor.zoomable = false;
    //         chartCursor.cursorAlpha = 0;
    //         chartCursor.categoryBalloonDateFormat = "JJ:NN, DD MMMM";
    //         chart.addChartCursor(chartCursor);

    //         // LEGEND
    //         var legend = new AmCharts.AmLegend();
    //         legend.useGraphSettings = true;
    //         legend.align = "center";
    //         chart.addLegend(legend);

    //         // SCROLLBAR
    //         // var chartScrollbar = new AmCharts.ChartScrollbar();
    //         // chartScrollbar.graph = graph;
    //         // chartScrollbar.scrollbarHeight = 15;
    //         // chart.addChartScrollbar(chartScrollbar);

    //         function zoomChart() {
    //             // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
    //             // chart.zoomToIndexes(chartData.length - 40, chartData.length - 1);
    //             chart.zoomToIndexes(0, 48);
    //             // chart.zoomToDates(new Date(2016-06-15, 0), new Date(2016-06-20, 0));
    //         }

    //         // WRITE
    //         chart.write("chartdiv");
    //     });';
    // }
    // ?>
    </script>