<?php
session_start();
?>

<div class="middle-box text-center loginscreen animated fadeInDown">
    <div>
    <br>
    <br>
        <div>
            
            <img src="img/entel_logo_gray.png" style="max-height: 180px">
            <!--h1 class="logo-name">SCL+</h1-->

        </div>
        <br>
        <h2>Bienvenido a iTEMP</h2>
        <p>Sistema de Control de Temperaturas</p>
        <p style="opacity: 0.6">Identifícate para ingresar.</p>
        <form name="login" class="m-t" role="form" action="modulos/control.php" method="post">
            <div class="form-group">
                <span
                    <?php if ($_SESSION["autentificado"]== "NO"){?>
                    ><p class="alert-warning"><b>Datos incorrectos</b></p>
                    <?php }else{ ?>
                        >
                    <?php } ?>
                </span>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Usuario" name="usuario" required="">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" placeholder="Contraseña" name="contrasena" required="">
            </div>
            <button type="submit" class="btn btn-primary block full-width m-b">Entrar</button>
        </form>
        <p class="m-t"> <small>SNMP &copy; 2016</small> </p>
    </div>
</div>