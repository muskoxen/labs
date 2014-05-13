<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$this->config->item('descripcion')?> | Iniciar Sesión</title>
<meta name="description" content="Laboratorios">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate"/> 
<meta http-equiv="Pragma" content="must-revalidate"/> 
<meta http-equiv="Expires" content="0"/> 
<meta name="robots" content="noindex, none" />
<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<base href="<?=base_url()?>" target="_self" /> <!-- Url de aplicacion  -->
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/usuarios.login.css">
<link rel="stylesheet" href="css/icons/foundation-icons.css">
<!-- Le fav and touch icons -->
<link rel="shortcut icon" href="img/favicon.ico">
<!-- <link type="text/css" href="css/usuarios.login.css" rel="stylesheet" />-->
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/tipTip.js"></script>
<script type="text/javascript"> 
function activar() {
    $('#cargando').css({'display' : 'none'});
    $('#usuario, #password, #btn-acceso').attr('disabled', false);
}
function desactivar() {
    $('#cargando').css({'display' : 'block'});
    $('#usuario, #password, #btn-acceso').attr('disabled', true);
}
$(function(){
    $('#btn-acceso').click(function(event){
        event.preventDefault();
        desactivar();
        if ($('#usuario').val().length == 0){
            setTimeout(function() {
                activar();
                $('#usuario').attr('title','El campo usuario es obligatorio').addClass('error').tipTip({delay:100,defaultPosition:'right',activation:'focus'}).focus();
            },700);
            return false;
        } else if ($('#password').val().length == 0){
            setTimeout(function() {
                activar();
                $('#password').attr('title','El campo contraseña es obligatorio').addClass('error').tipTip({delay:100,defaultPosition:'right',activation:'focus'}).focus();
            },700);
            return false;
        }else{
            $.post('sesion/jx_auth', { usuario: $('#usuario').val(), password: $('#password').val() }, 
            function(result) {
                if(result.exito === true) {
                    setTimeout(function() {
                        window.document.location = result.redirect;
                    },700);
                    return false;
                } else {  
                    setTimeout(function() {
                        activar();
                        $('#'+result.aviso).attr('title',result.msj).addClass('error').tipTip({delay:100,defaultPosition:'right',activation:'focus'}).focus();
                    },700);
                    return false;
                }
            },'json');
            return false;   
        }
    });
});
</script>
</head>
<body>
    <div class="navbar navbar-default navbar-static-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#"><?=$this->config->item('nombre')?></a>
            </div>
        </div>
    </div>
    <div id="content" class="well">
        <div class="logo"></div>
        <div class="separador" style="margin-right: 17px;"></div>
        <div class="formulario">
            <form id="form-login" name="form-login" action="" method="POST" onsubmit="return(false)" class="form">
                <fieldset >
                    <div>
                        
                        <div class="input-group forms">
                            <span class="input-group-addon"><i class="fi-torso-business"></i></span>
                            <input type="text" class="form-control input-lg" name="usuario" id="usuario" value="" autocomplete="off" autofocus="autofocus" placeholder="Usuario" />
                        </div>
                    </div>
                    <div>
                        
                        <div class="input-group forms">
                            <span class="input-group-addon"><i class="fi-lock"></i></span>
                            <input type="password" class="form-control input-lg" name="password" id="password" value="" placeholder="Contraseña" />
                        </div>
                    </div>
                    <span id="cargando" class="cargando">Verificando..</span>
                    <button type="submit" class="btn btn-danger" style="float:right;margin-top:10px;" name="btn-acceso" id="btn-acceso" tabindex="30">Entrar &nbsp; <span class="glyphicon glyphicon-log-in"></span></button>
                </fieldset>
            </form>
        </div>
    </div>
    <footer class="footer" style="height:57px">
        <address class="fl" style="margin-left:10px;">
            <strong>Laboratorios labs inc</strong><br>
            Sistema Gestor de Laboratorios<br>
            <abbr title="Teléfono">Tel:</abbr>701-01-01 <abbr title="Extensión">Ext:</abbr>12
        </address>
    </footer>    
</body>
</html>