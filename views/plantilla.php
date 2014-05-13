<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="utf-8">
      <title><?=$this->config->item('descripcion')?></title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="Laboratorios">
      <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
      <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
      <![endif]-->
      <!-- Le styles -->
      <base href="<?=base_url()?>" target="_self" /> <!-- Url de aplicacion  -->
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="css/custom-theme/jquery-ui-1.10.0.custom.min.css" rel="stylesheet">
      <link href="css/app.css" rel="stylesheet">
      <link rel="stylesheet" href="css/icons/foundation-icons.css">
      <style type="text/css">
        i {
            font-size: 24px;
            padding: 0 10px;
        }
        .navbar-static-top {
            margin-bottom: 19px;
        }
      </style>
      <!-- Le fav and touch icons -->
      <script src="js/jquery-1.9.1.min.js"></script>
      <script src="js/jquery-ui-1.10.0.custom.min.js"></script>
      <script src="js/bootstrap.min.js"></script>
      <script src="js/app.js"></script>
  </head>
  <body>
    <!-- Static navbar -->
    <div class="navbar navbar-default navbar-static-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#" style="margin-left: 30px">Lapps</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="<?=base_url()?>" class="menu-popover" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Panel de Inicio"><i class="fi-home"></i></a></li>
                    <li><a href="<?=base_url()?>pacientes" class="menu-popover" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Pacientes"><i class="fi-torsos-all"></i></a></li>
                    <li class="dropdown">
                        <a href="<?=base_url()?>" class="dropdown-toggle" data-container="body" data-toggle="dropdown">
                            <i class="fi-database"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-header">Análisis de Laboratorio</li>
                            <li class="divider"></li>
                            <li><a href="">Análisis de Orina</a></li>
                            <li><a href="">Citología, histología, y anatomía patológica</a></li>
                            <li><a href="">Microbiología</a></li>
                        </ul>
                    </li>
                </ul>
                <ul id="opciones-seguridad" class="nav navbar-nav navbar-right">
                    <li><a><span class="glyphicon glyphicon-user"></span> <?php echo $this->session->userdata('nombre'); ?></a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-wrench"></span> <b class="caret"></b></a>
                        <ul class="dropdown-menu" id="menu-seguridad">
                            <li><a href="#" data-link="usuario/listado" data-titulo="Usuarios"><span class="glyphicon glyphicon-user"></span> Usuarios</a></li>
                            <li><a href="#" data-link="rol/listado" data-titulo="Roles"><span class="glyphicon glyphicon-briefcase"></span> Roles</a></li>
                            <li><a href="#" data-link="modulo/listado" data-titulo="Módulos"><span class="glyphicon glyphicon-cog"></span> Módulos</a></li>
                        </ul>
                    </li>
                    <li><a href="sesion/logout"><span class="glyphicon glyphicon-off"></span> Salir</a></li>
                </ul>
            </div><!--/.nav-collapse -->
            <div class="cargando progress progress-striped text-center" id="cargando">
                <div class="msg progress-bar progress-bar-warning progress-striped active" 
                role="progressbar" style="width: 100%">
                    <div class="bar" style="width: 100%;">Procesando</div>
                </div>
            </div>
        </div>
    </div>

    <!--<div class="container-fluid">
        <ol class="breadcrumb">
            <li><a href="<?=base_url()?>"><span class="glyphicon glyphicon-home"></span></a></li>
        </ol>
    </div>-->

    <div id="contenedor-principal" class="container-fluid" style="margin: 15px;">
        <!-- vaciar la informacion en el contenedor-->
        <?php echo $vista; ?>

    </div> <!-- /container -->

    <div id="notificacion" class='notifications top-right'></div>

    <div id="confirmacion" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header lead">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>Confirmación
                </div>
                <div class="modal-body">
                    <p><i class="icon-warning-sign"></i> <span></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-primary" data-loading-text="Espere.." data-complete-text="Eliminar">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

  </body>
</html>