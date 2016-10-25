<?php
session_start();
require_once 'clases/usuario.php';
require_once 'clases/vm_grafico_temperaturas.php';
require_once 'vista/vw_home.php';

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
?>
<script type="text/javascript">
    setTimeout(function(){
        location = ''
    },600000)
</script>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">

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
            <div class="ibox float-e-margins">
                <div class="ibox-title col-md-12 ui-widget-header blue-bg">
                    <!--div class="pull-right">
                        <button class="btn btn-xs btn-primary btn-outline">Exportar &nbsp;<i class="fa fa-file-excel-o"></i></button>
                    </div-->
                    <h4 class="p-xxs">Top Sitios con alarmas recurrentes</h4>
                    <!--h6>Listado de temperaturas</h6-->
                </div>
                <?php
                    vw_home::lista_top_recurrentes();
                ?>
            </div>
        </div>
        <div class="col-lg-7 col-md-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title col-md-12 ui-widget-header blue-bg">
                    <h4 class="p-xxs">Gráfico de temperaturas</h4>
                    <!--h6>Ultimos 60 minutos</h6-->
                </div>
                <div class="ibox-content">
                    <div class="tab-content">
                        <?php
                        vw_home::ver_grafico_diario();
                        ?>
                    </div>
                    <div class="chartWrapper">
                        <div class="chartAreaWrapper">
                            <canvas id="sitio_base" height="600" width="1200"></canvas>
                        </div>
                        <canvas id="myChartAxis" height="600" width="0"></canvas>
                    </div>
                    <!--button id="addData">Datos Antiguos</button>
                    <button id="removeData">Quitar Datos</button-->
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

<!-- Exportar Excel -->
<script src="js/plugins/excel/jquery.table2excel.js"></script>

<!-- slick carousel-->
<script src="js/plugins/slick/slick.min.js"></script>

<script src="js/plugins/chartJs/Chart.bundle.js"></script>
<!-- <script src="js/plugins/chartJs/Chart.min.js"></script> -->
<!-- <script src="js/plugins/chartJs/jquery.min.js"></script> -->

<!--##### Gráficos #####-->
<script>
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
</script>

<!-- Script para scroll de la sección "Tabla de temperaturas" -->
<script>
    $(document).ready(function () {
        // Add slimscroll to element
        $('.scroll_content').slimscroll({
            height: '598px',
            // height: 'relative',
            opacity: 0.1,
            wheelStep : 10,
        })});
</script>

<script>
    $(document).ready(function(){
        $('.slick').slick({
            dots: true
        });
    });
</script>