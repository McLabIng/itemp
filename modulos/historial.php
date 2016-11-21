<?php
session_start();
require_once 'clases/usuario.php';
require_once 'clases/vm_grafico_temperaturas.php';
require_once 'vista/vw_historial.php';

$Fecha=getdate();
$Anio=$Fecha["year"];

$mi_usuario = cl_usuario::traer_usuario($_SESSION["username"]);
$mi_area = $mi_usuario->getCod_area();
$mi_rol = $mi_usuario->getCod_rol();

$cod_sitio = $_GET['cod_sitio'];

$cantidad = 48;
$show = 48; // Numero de datos para mostrar en filtro. 

$sitio = vm_grafico_temperaturas::traer_temperatura_sitio($cod_sitio);
foreach($sitio as $rows){ 
    $datos[] = array($rows['COD_SITIO'],$rows['FECHA'],$rows['TEMP_DUW_1'],$rows['TEMP_DUW_2'],$rows['TEMP_RECTIFICADOR'],$rows['TEMP_WISE'],$rows['ALARMADO']);
}

$codigo_sitio = $sitio['COD_SITIO'];

$datos_sitio = vm_grafico_temperaturas::traer_temperaturas_grafico_historial($cod_sitio);
foreach($datos_sitio as $rows){ 
    $data_temp_inverso[] = array($rows['COD_SITIO'],$rows['FECHA'],$rows['TEMP_DUW_1'],$rows['TEMP_DUW_2'],$rows['TEMP_RECTIFICADOR'],$rows['TEMP_WISE'],$rows['ALARMADO']);
    $data_temp = array_reverse($data_temp_inverso);
}
$lista_top_recurrentes = vm_grafico_temperaturas::traer_temperaturas_duw($cod_sitio);
foreach ($lista_top_recurrentes as $value) {
    $sitio = $value['SITIO'];
    $nombre_sitio = $value['NOMBRE_SITIO'];
}
?>
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
                <a href="?mod=home">Historial de Temperaturas</a>
            </li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInDown">

    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title col-md-12 ui-widget-header blue-bg">
                    <h4 class="p-xxs">Historial de Temperaturas - Sitio <?php ?></h4>
                </div>
                <div class="ibox-content">
                    <?php
                    vw_historial::sitio_recurrente($cod_sitio);
                    ?>
                    <div id="chartdiv" style="width: 100%; height: 400px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 m-b-xl">
            <div  class="ibox float-e-margins">
                <div class="ibox-title col-md-12 ui-widget-header blue-bg">
                    <h4 class="p-xxs">Top Sitios con alarmas recurrentes</h4>
                </div>
                <div class="ibox-content col-md-12">
                    <?php
                    vw_historial::lista_temperaturas($cod_sitio);
                    ?>
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

<!-- Data picker -->
<script type="text/javascript" src="js/plugins/datapicker/bootstrap-datepicker.js"></script>

<!-- slick carousel-->
<script src="js/plugins/slick/slick.min.js"></script>

<!-- <script src="js/plugins/chartJs/Chart.bundle.js"></script> -->
<script src="js/plugins/chartJs/Chart.min.js"></script>

<!-- amchart-->
<script src="js/plugins/amcharts/amcharts.js" type="text/javascript"></script>
<script src="js/plugins/amcharts/serial.js" type="text/javascript"></script>


<!--##### Gráficos #####-->    
<script>
    var alerta = 50;
    var graph;
    var chart;
    var chartData = [
        <?php foreach($data_temp as $datos){
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
        guide.label = "Límite Alta Temperatura";
        guide.inside = true;
        guide.lineAlpha = 1;
        guide.lineThickness = 1;
        valueAxis.addGuide(guide);

        // ALERT guide
        <?php
        foreach ($data_temp as $datos) {
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
        graph.balloonText = "Temperatura en DUW 1: [[value]] ºC";
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
        graph.balloonText = "Temperatura en DUW 2: [[value]] ºC";
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
        graph.balloonText = "Temperatura en Batería: [[value]] ºC";
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
        graph.balloonText = "Temperatura de WISE: [[value]] ºC";
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
        var chartScrollbar = new AmCharts.ChartScrollbar();
        chartScrollbar.graph = graph;
        // chartScrollbar.oppositeAxis = false;
        chartScrollbar.offset = 30;
        chartScrollbar.scrollbarHeight = 40;
        chartScrollbar.backgroundAlpha = 0;
        chartScrollbar.selectedBackgroundAlpha = 0.1;
        chartScrollbar.selectedBackgroundColor = "#888888";
        chartScrollbar.graphFillAlpha = 0;
        chartScrollbar.graphLineAlpha = 0.5;
        chartScrollbar.selectedGraphFillAlpha = 0;
        chartScrollbar.selectedGraphLineAlpha = 1;
        chartScrollbar.autoGridCount = true;
        chartScrollbar.color = "#AAAAAA";
        chart.addChartScrollbar(chartScrollbar);

        // WRITE
        chart.write("chartdiv");
    });

    function zoomChart() {
        // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
        // chart.zoomToIndexes(chartData.length - 40, chartData.length - 1);
        chart.zoomToIndexes(<?php echo count($datos_sitio)-$show;?>, <?php echo count($datos_sitio).')';?>;
        // chart.zoomToDates(new Date(2016-06-15, 0), new Date(2016-06-20, 0));
    }

    // this method converts string from input fields to date object
    function stringToDate(str) {
        var dArr = str.split("-");
        var date = new Date(Number(dArr[2]), Number(dArr[1]) - 1, dArr[0]);
        return date;
    }

    // this method is called when user changes dates in the input field
    function changeZoomDates() {
        var startDateString = document.getElementById("min").value;
        var iMaxim = document.getElementById("max").value;

        var iMaximo_year = iMaxim.substring(6,10);
        var iMaximo_month = iMaxim.substring(3,5);
        var iMaximo_day = iMaxim.substring(0,2);
        var iMaximo = iMaximo_month.concat('/',iMaximo_day,'/',iMaximo_year);

        var iMax = new Date(iMaximo);

        iMax.setDate(iMax.getDate() + 1);

        var iMax_day = iMax.getDate();
        var iMax_month = iMax.getMonth() + 1;
        var iMax_year = iMax.getFullYear();

        var endDateString = iMax_day + '-' + iMax_month + '-' + iMax_year;

        var startDate = stringToDate(startDateString);
        var endDate = stringToDate(endDateString);
        chart.zoomToDates(startDate, endDate);
    }

    /* Función para el filtro de fechas */
    jQuery.fn.dataTableExt.afnFiltering.push(
        function( oSettings, aData, iDataIndex ) {
            var iMin = document.getElementById('min').value;
            var iMax = document.getElementById('max').value;

            var iMinimo_year = iMin.substring(6,10);
            var iMinimo_month = iMin.substring(3,5);
            var iMinimo_day = iMin.substring(0,2);
            var iMinimo = iMinimo_year.concat(iMinimo_month,iMinimo_day);

            var iMaximo_year = iMax.substring(6,10);
            var iMaximo_month = iMax.substring(3,5);
            var iMaximo_day = iMax.substring(0,2);
            var iMaximo = iMaximo_year.concat(iMaximo_month,iMaximo_day);

            var iCol = 2;
            var iDate_year = aData[iCol].substring(6,10);
            var iDate_month = aData[iCol].substring(3,5);
            var iDate_day = aData[iCol].substring(0,2);
            var iDate = iDate_year.concat(iDate_month,iDate_day);
            
            if ((iMinimo === "" && iMaximo === "") || (iMinimo <= iDate && iMaximo === "") || (iMinimo === "" && iMaximo >= iDate) || (iMinimo <= iDate && iMaximo >= iDate)) {
                return true;
            } else {
                return false;
            }
        }
    );

    $(document).ready(function(){

        $('#data .input-daterange').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true,
            format: "dd-mm-yyyy",
        });

        /* Init DataTables */
        var oTable = $('#editable').DataTable({
            // paging:   true,
            // ordering: false,
            // info: false,
            // filter: false,
            // scrollY: "600px",
            // scrollCollapse: true,
            // order: [[ 1, "desc" ]],
            // aoColumns: [
            //     null,
            //     { "orderSequence": [ "desc", "asc" ] },
            // ],
            // lengthMenu: [ [6, 25, 50, -1], [6, 25, 50, "All"] ],
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
            buttons: [
                {
                    extend: 'excel',
                    title: 'Sitio <?php echo $sitio.' - '.$nombre_sitio.' - '.date("d-m-Y"); ?>', 
                    className: 'btn-sm'
                },
                {
                    extend: 'pdf', 
                    title: 'Sitio <?php echo $sitio.' - '.$nombre_sitio.' - '.date("d-m-Y"); ?>', 
                    download: 'download',
                    className: 'btn-sm',
                    // exportOptions: {
                    //     columns: "0,1,2,3,4:visIdx"
                    // },
                    // orientation: 'landscape',
                    pageSize: 'LETTER',
                    customize: function ( doc ) {
                        doc.content.splice( 1, 0, {
                            margin: [ 0, 0, 0, 12 ],
                            alignment: 'center',
                            image: 'data:image/png;base64,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'
                        });
                    }
                },
            ],
        });

        /* Add event listeners to the two range filtering inputs */
        $('#min, #max').keyup( function() { oTable.draw(); } );

        $('#myInputTextField').keyup(function(){
            oTable.search($(this).val()).draw() ;
        });
    });
</script>