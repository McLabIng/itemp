<?php
session_start();
require_once 'clases/usuario.php';
require_once 'clases/sitios_temperatura.php';
require_once 'vista/vw_admin.php';

$Fecha=getdate();
$Anio=$Fecha["year"];

$mi_usuario = cl_usuario::traer_usuario($_SESSION["username"]);
$mi_area = $mi_usuario->getCod_area();
$mi_rol = $mi_usuario->getCod_rol();

$listado_sitios = sitios_temperatura::traer_listado_sitios_totales();
?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <h2 class="pull-right">Sub Gerencia O&M Infraestructura</h2>
        <h2>iTEMP - Sistema de Temperatura de Sitios.</h2>
        <ol class="breadcrumb">
            <li class="active">
                <a href="?mod=home">Adminisrtación de Sitios</a>
            </li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInDown">

    <div class="row">
        <div class="ibox float-e-margins m-b-xl">
            <div class="ibox-title col-md-12 ui-widget-header blue-bg">
                <h4 class="p-xxs">Listado de Sitios</h4>
            </div>
            <div class="ibox-content col-md-12">
                <?php
                vw_admin::lista_sitios($listado_sitios);
                ?>
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

<!-- Data Tables -->
<script src="js/plugins/dataTables/datatables.min.js"></script>

<!-- Input Mask -->
<script src="js/plugins/ip/jquery.mask.min.js"></script>

<!-- Switchery -->
<script src="js/plugins/switchery/switchery.js"></script>

<script>
//EDITABLE
    $(document).ready(function(){
        /* Init DataTables */
        var oTable = $('#admin_table').DataTable({
            "paging":   false,
            "ordering": false,
            // "info": false,
            // "filter": false,
            "scrollY": "600px",
            // "scrollCollapse": true,
            // "order": [[ 1, "desc" ]],
            // "aoColumns": [
            //     null,
            //     { "orderSequence": [ "desc", "asc" ] },
            // ],
            "dom": "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
            // "lengthMenu": [ [6, 25, 50, -1], [6, 25, 50, "All"] ],
            // "iDisplayLength": 20,
            "buttons": [{
                    extend: 'excel',
                    title: 'iTemp - Sitios Ingresados al <?php echo date("d M, Y");?>',
                    className: 'btn-sm',
                    exportOptions: {
                        columns: "0,1,2,3,4:visIdx"
                    },
                    // orientation: 'landscape',
                    pageSize: 'LETTER'
                },
                {
                    extend: 'pdf',
                    title: 'iTemp - Sitios Ingresados al <?php echo date("d M, Y");?>',
                    download: 'download',
                    className: 'btn-sm',
                    exportOptions: {
                        columns: "0,1,2,3,4:visIdx"
                    },
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
        

        // Input Mask
        $('.ip_address').mask('0ZZ.0ZZ.0ZZ.0ZZ', {translation:  {'Z': {pattern: /[0-9]/, optional: true}}});
        $('.ip_wise').mask('0ZZ.0ZZ.0ZZ.0ZZ', {translation:  {'Z': {pattern: /[0-9]/, optional: true}}});

        // Switchery
        <?php
        foreach ($listado_sitios as $resultado) {
            if ($resultado["ACTIVO"] == 1) {
                $color_switch = "#1ab394";
            } else {
                $color_switch = "#ed5565";
            }

            echo"
            var switch_".$resultado['SITIO']." = document.querySelector('.switch_".$resultado['SITIO']."');
            var switchery = new Switchery(switch_".$resultado['SITIO'].", { color: '".$color_switch."' });
            ";
        }
        ?>

    });
</script>
