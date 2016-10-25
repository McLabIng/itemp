<?php
session_start();
date_default_timezone_set('America/Santiago');
require_once 'clases/cl_menu.php';
require_once 'clases/usuario.php';
require_once 'clases/area.php';
require_once 'clases/datos_empresa.php';
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>iTEMP - Sistema de Temperatura de Sitios.</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="shortcut icon" href="img/favicon.ico">

    <link href="css/plugins/slick/slick.css" rel="stylesheet">
    <link href="css/plugins/slick/slick-theme.css" rel="stylesheet">

    <!-- Data Tables -->
    <link href="css/plugins/dataTables/datatables.min.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>

<body class="skin-1 mini-navbar">

<div id="wrapper navbar-default navbar-static-side navbar-static-top"><!-- DIV PRINCIPAL -->
<?php

if  (isset($_SESSION["username"])){
    $username = $_SESSION["username"];
    $usuario = cl_usuario::traer_usuario($username);
    $cod_area = $usuario->getCod_area();
    $area = area::traer_area($cod_area);
    $datos_empresa = datos_empresa::traer_mi_empresa();
    if ($usuario){
        echo '<nav class="navbar-default navbar-static-side" role="navigation">';
        echo '<div class="sidebar-collapse">';
        echo '<ul class="nav" id="side-menu">';
        echo '<li class="nav-header">
                <div class="dropdown profile-element"> <span>
                    <img alt="image" class="img-container" src="'.$datos_empresa->getURL_logo().'" />
                     </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">'.$usuario->getNombre().' '.$usuario->getApellido().'</strong>
                     </span> <span class="text-muted text-xs block">'.$area->getArea().'<b class="caret"></b></span> </span> </a>
                    <ul class="dropdown-menu animated fadeInDown m-t-xs">
                        <li><a href="?mod=logout">Logout</a></li>
                    </ul>
                </div>
                <div class="logo-element"><img alt="image" class="img-container" src="'.$datos_empresa->getURL_logo().'" /></div>
            </li>';
        $cod_area = $usuario->getCod_area();
        $rol = $usuario->getCod_rol();
        $menu = menu::traer_menu($cod_area, $rol);
        $grupo = "";
        $grupo2 = "";

        echo ' <li><a href="?mod=home"><i class="fa fa-area-chart"></i><span class="nav-label">Temperaturas</span></a></li>';
        // echo ' <li><a href="?mod=historial"><i class="fa fa-area-chart"></i><span class="nav-label">Historial</span></a></li>';
        // foreach($menu as $resultados):
        // if ($resultados['GRUPO']==''){
        //     if ($grupo2 <> ""){
        //         echo '</ul>';
        //         echo '</li>';
        //     }
        //     echo '  <li><a href="'.$resultados['REFERENCIA'].'"><i class="fa fa-area-chart"></i><span class="nav-label">Temperaturas</span></a></li>';
        // }
        // else {
        //     $grupo = $resultados['GRUPO'];
        //     if ($grupo == $grupo2){
        //         echo '  <li><a href="'.$resultados['REFERENCIA'].'"><span class="nav-label">Temperaturas</span></a></li>';
        //         $grupo2 = $resultados['GRUPO'];
        //     }
        //     else {
        //         if ($grupo2 <> ""){
        //             echo '</ul>';
        //             echo '</li>';
        //         }
        //         echo '<li>';
        //         echo '<a href="#"><i class="fa fa-area-chart"></i><span class="nav-label">'.$resultados['GRUPO'].' </span><span class="fa arrow"></span></a>';
        //         echo '<ul class="nav nav-second-level">';
        //         echo '  <li><a href="'.$resultados['REFERENCIA'].'">Temperaturas</a></li>';
        //         $grupo2 = $resultados['GRUPO'];
        //     }

        // }
        // endforeach;
        if ($grupo2 <> ""){
            echo '</ul>';
            echo '</li>';
        }
        echo '</ul>';
        echo '</li>';
        ?>
        </ul>
        </div>
        </nav><!-- MENU -->
<?php
    }
    else {
        echo '<p align="center" style="font-size:14px" style="font-weight:bold"> Debe autentificarse para continuar. Presione SALIR. </p>';
    }
}
?>

    <div id="load" style="display:none;position:absolute;top:0;bottom:0%;left:0;right:0%;z-index:99;height:300px;width:300px;background-image:url(img/big-ajax-loader.gif);"></div>

    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" href="#"><i class="fa fa-bars"></i> </a>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <span class="m-r-sm text-muted welcome-message">Bienvenido a iTEMP</span>
                    </li>
                    <li>
                        <a href="?mod=logout">
                            <p><i class="fa fa-sign-out"></i> Log out</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    <!--<div class="row  border-bottom white-bg dashboard-header">-->
        <?php
        if (file_exists( $path_modulo )) include( $path_modulo );
        else die('Error al cargar el módulo <b>'.$modulo.'</b>. No existe el archivo <b>'.$conf[$modulo]['archivo'].'</b>');
        ?>
    <!--</div>-->

        <div class="footer">
            <div class="col-md-6">
                <strong>Copyright</strong> McLab Ingenieria SpA&copy; 2015-2016
                <a href="http://www.mclab.cl" target="blank"><img alt="image" class="img-container pull-right" src="img/logo-mclabSPA.png" style="width: 100px"/></a>
            </div>
        </div>

    </div>
</div><!-- DIV PRINCIPAL -->


</body>
</html>