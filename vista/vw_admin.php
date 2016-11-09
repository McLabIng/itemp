<?php
class vw_admin {

    public static function lista_sitios($listado_sitios){
        ?>
        <form class="form-horizontal m-t-md" role="form" method="post" action="">
            <div class="table-responsive project-list-2">
                <table class="table table-hover table-striped" id="admin_table">
                    <thead>
                        <tr>
                            <th class="col-md-1">Sitio</th>
                            <th class="col-md-3">Nombre Sitio</th>
                            <th class="col-md-1">IP</th>
                            <th class="col-md-1">WISE</th>
                            <th class="col-md-1" style="text-align: center">Activo / Inactivo</th>
                            <!-- <th></th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($listado_sitios as $resultado) {
                            if ($resultado["ACTIVO"] == 1) {
                                $check = "checked";
                            } else {
                                $check = "";
                            }

                            echo ' <tr>
                            ';
                            echo ' <td class="col-md-1">'.$resultado["SITIO"].'</td>
                            ';
                            echo ' <td class="col-md-3">'.$resultado["NOMBRE_SITIO"].'</td>
                            ';
                            echo ' <td class="col-md-1"><a href="http://'.$resultado["IP"].'" target="_blank">'.$resultado["IP"].'</td>
                            ';
                            echo ' <td class="col-md-1">'.$resultado["WISE"].'</td>
                            ';
                            echo ' <td class="col-md-1" style="text-align: center"><input type="checkbox" class="switch_'.$resultado['SITIO'].'" '.$check.' id="id_'.$resultado['SITIO'].'" name="id_'.$resultado['SITIO'].'" /></td>
                            ';
                            // echo ' <td class="col-sm-1" style="text-align: center"><a class="btn btn-success btn-outline btn-xs" href=""><i class="fa fa-edit"></i></a></td>
                            // ';
                            echo ' </tr>
                            ';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-12 pull-right">
                <a class="btn btn-sm btn-default m-t-n-xs" type="submit" href="?mod=admin"><i class="fa fa-times"></i> Cancelar</a>&nbsp;&nbsp;
                <input type="hidden" name="accion" value="actualizar_sitios">
                <button class="btn btn-sm btn-primary m-t-n-xs" type="submit"><i class="fa fa-check"></i> Actualizar</button>
            </div>
        
            <?php
            $action = $_POST["accion"];
            if ($action == "actualizar_sitios") {
                foreach ($listado_sitios as $resultado) {
                    $sitio = $resultado["SITIO"];
                    $nombre_sitio = $resultado["NOMBRE_SITIO"];
                    $ip = $resultado["IP"];
                    $ip_wise = $resultado["WISE"];
                    if ($_POST['id_'.$resultado['SITIO']] <> ''){
                        $estudio = 1;
                        $activo = 1;
                    } else {
                        $estudio = 0;
                        $activo = 0;
                    }
                    

                    if (!sitios_temperatura::Actualizar_sitios($sitio, $nombre_sitio, $ip, $ip_wise, $estudio, $activo)){
                        echo "Error de ingreso de sitio -- Favor chequear o avisar a soporte";
                    }
                    else {
                        echo '<script type="text/javascript">window.location="?mod=admin"</script>';
                    }
                }
            }
            ?>
        </form>
        <?php
    }

}
