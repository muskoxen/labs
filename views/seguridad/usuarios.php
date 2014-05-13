<?php if (! defined('BASEPATH')) exit('No direct script access'); ?>
<script type="text/javascript" charset="utf-8" src="<?=base_url()?>js/passwordstrength.min.js"></script>
<script type="text/javascript">
$(function() { 
	$('.fuerzaMsg').val('Seguridad de la Contraseña');
	$('.fuerzaPass').css({'background-color': '#CCC','color':'#4F4F4F'});
	$('#password, #password_new').on( 'keyup', function() {
		var fuerza = passwordStrength($(this).val())
		switch(fuerza){
			case 0:
				$('.fuerzaMsg').val('Seguridad de la Contraseña').css({'color':'#4F4F4F'});
				$('.fuerzaPass').css({'background-color': '#CCC','color':'#4F4F4F'});
			break;
			case 1:
				$('.fuerzaPass').css({'background-color' : '#ff6600', 'color':'#FFF'});
				$('.fuerzaMsg').val('Débil');
				break;
			case 2:
				$('.fuerzaPass').css({'background-color' : 'red', 'color':'#FFF'});
				$('.fuerzaMsg').val('Corta');
				break;
			case 3:
				$('.fuerzaPass').css({'background-color' : '#6699cc', 'color':'#FFF'});
				$('.fuerzaMsg').val('Media');
				break;
			case 4:
				$('.fuerzaPass').css({'background-color' : '#9dc425', 'color':'#FFF'});
				$('.fuerzaMsg').val('Fuerte');
				break;
			default :
				$('.fuerzaPass').css({'background-color' : '#555', 'color':'#FFF'});
		}
	}).focusout( function() {
		$( this ).trigger( 'keyup' );
	});
	$('#loading').hide();
    $('#correo').blur(function(){
		var a = $("#correo").val();
		var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
		if( filter.test(a) ){
			$('#loading').show();
			$.post(app.url + 'usuario/comprobar_correo', { correo: $('#correo').val() }, function(response){
				$('#loading').hide();
				if( response.exito === true ){
					$('#msjCorreo_new').html('');
				}else{
					$('#newCorreo').addClass("error");
					setTimeout("finishAjax('msjCorreo_new', '" + response.msj + "')", 400);					
				}
			});
		}
		return false;
	});
	$('#modal-cambiar-password').on('hidden.bs.modal', function () {
		$('#id_usuario_cp').val('');
		$('#usuario_cp').val('');
		$('#correoE_cp').val('');
		$('.fuerzaMsg').val('Seguridad de la contraseña').css({'color':'#4F4F4F'});
		$('.fuerzaPass').css({'background-color': '#CCC','color':'#4F4F4F'});
		$('#form-cambiar-password div').removeClass('has-error');
		$('#btn-guardar-nuevo-password').button('reset');
		document.getElementById("form-cambiar-password").reset();
	});
	$mostrarForm = (function(){
    	$('#btn-nuevo-usuario').removeClass('btn-agregar btn-primary').addClass('btn-ocultar btn-danger').children('i').removeClass('icon-ok').addClass('icon-minus-sign');
    	$('#form-usuario').slideDown();
    	$('#tabla-usuarios').slideUp();
    	$('div#paginacion').css({'display' : 'none'});
    	$('#referencia').val('').attr('disabled',true);
	    $('#btn-buscar').attr('disabled',true);
    	$('#nombre').focus();
    });
    $("form#form-busqueda").on('click', 'button.btn-agregar', function(){
    	$mostrarForm();
    	$('#lbl-accion').html('Nuevo').addClass('label-success');
    });
    $ocultarForm = (function(){    	
		$('#form-usuario').slideUp();
		$('#tabla-usuarios').slideDown();
		$('div#paginacion').css({'display' : 'block'});
		$('#btn-nuevo-usuario').removeClass('btn-ocultar btn-danger').addClass('btn-agregar btn-primary').children('i').removeClass('icon-minus-sign').addClass('icon-ok');
    	$('#usuario').attr('disabled',false);
    	$('#contPass').css({'display':'block'});
		$('#newPassword').css({'display':'block'});
		$('#newR_password').css({'display':'block'});    	
		$('.fuerzaMsg').val('Seguridad de la Contraseña').css({'color':'#4F4F4F'});
		$('.fuerzaPass').css({'background-color': '#CCC','color':'#4F4F4F'});
		$('#referencia').attr('disabled',false).focus();
	    $('#btn-buscar').attr('disabled',false);
	    $('#msjCorreo_new').html('');
	    $('#lbl-accion').html('').removeClass('badge-success');

		$('#form-usuario div').removeClass('has-error');

		$('#msjNombre_new').html('');
		$('#msjCorreo_new').html('');
		$('#msjGrupo_new').html('');
		$('#msjUsuario_new').html('');
		$('#msjPass_new').html('');
		$('#msjPassR_new').html('');
		$('#correo').val('');
		$('#nombre').val('');
		$('#id_rol').val('');
		$('#usuario').val('');
		$('#password').val('');
		$('#confirm_password').val('');
		$('#modo').val('0');
		$('#id_usuario').val('');
		document.getElementById('form-usuario').reset();
    });
    $('form#form-busqueda').on('click', 'button.btn-ocultar', function(){
		$ocultarForm();
	});
	$('#btn-cancelar-usuario').click(function(){
		$ocultarForm();
	});
	$('#btn-guardar-usuario').click(function(){
		$('#btn-guardar-usuario').button('loading');
		$.post(app.url + 'usuario/alta', $('#form-usuario').serialize(), function(resultado){
			if ( resultado.exito === true ){
				$('#btn-guardar-usuario').button('reset');
				$ocultarForm();
				$.noticia(resultado.msj,'success');
                $refrescarNotificaciones();
			}else{
				$('#btn-guardar-usuario').button('reset');
				if ( resultado.exito === false ) {
					first = true;
					$.each(resultado.msj, function(campo, aviso){
						if (first){
							$('div > .has-error').removeClass('has-error');
							error = new Object({ campo: campo, mensaje: aviso });
							first = false;
						}
						$('#'+campo).parents('.form-group').addClass('has-error');
					});
					$.noticia(error.mensaje, 'danger');
					$('#'+error.campo).focus();
				};
			};
		},'json');
		return false;
	});
	$('#btn-guardar-nuevo-password').click(function(){
		$('#btn-guardar-nuevo-password').button('loading');
		$.post(app.url + 'usuario/cambiarContrasena', $('#form-cambiar-password').serialize(), function(resultado){
			if ( resultado.exito === true ) {
				$('#btn-guardar-nuevo-password').button('reset');
				$('#modal-cambiar-password').modal('hide');
				$.noticia(resultado.msj,'success');
                $refrescarNotificaciones();
			}else{
				$('#btn-guardar-nuevo-password').button('reset');
				if ( resultado.exito === false ) {
					first = true;
					$.each(resultado.msj, function(campo, aviso){
						if (first){
							$('div .has-error').removeClass('has-error');
							error = new Object({ campo: campo, mensaje: aviso });
							first = false;
						}
						$('#'+campo).parents('.form-group').addClass('has-error');
					});
					$.noticia(error.mensaje, 'danger');
					$('#'+error.campo).focus();
				};
			};
		},'json');
		return false;
	});
	$('#btn-buscar').click(function(){
        $.post(app.url + 'usuario/busqueda', $('#form-busqueda').serialize(), function(data){
            $('#contenedor').html(data);
        });
        return false;
    });
    $modalEdicion = (function(el){
    	$.post(app.url + 'usuario/edicion', { id_usuario : $(el).attr('data-id-usuario') }, function(resultado){
			var editar = resultado.usuario;
			$('#modo').val('1');
			$('#id_usuario').val(editar.id_usuario);
			$('#nombre').val(editar.nombre);
			$('#correo').val(editar.correo);
			$('#id_rol').val(editar.id_rol);
			$('#usuario').val(editar.usuario).attr('disabled',true);
			$('#contPass').css({'display':'none'});
			$('#newPassword').css({'display':'none'});
			$('#newR_password').css({'display':'none'});
			$('#lbl-accion').html('Editar').addClass('label-warning');
			$mostrarForm();
		},'json');
		return false;
    });
	$modalEliminar = (function(el){
        var id = $(el).attr('data-id');
		$.confirmar('¿Seguro que desea eliminar?',{ 
			aceptar: function(){
				$.post(app.url + 'usuario/eliminar/' + id, function(result){
					if (result.exito === true){
						$.noticia(result.msj,'success');
						$refrescarNotificaciones();
					}else{
						if ( result.exito === false ) {
							$.noticia(result.msj,'danger');
						};				
					};
				},'json');
			}
		});
		return false;
    });
    $modalCambiarPassword = (function(el){
		$('#id_usuario_cp').val($(el).attr('data-id-usuario'));
		$('#usuario_cp').val($(el).attr('data-usuario'));
		$('#correoE_cp').val($(el).attr('data-correo'));
		$('#modal-cambiar-password').modal('show');
    });
    $cambiarStatus = (function(el){
    	params = $(el).attr("id").split('-');
		id_usuario = params[1];
		estado = $('span#estado-'+id_usuario).html();
		$('span#estado-'+id_usuario).removeClass().addClass("wait").html("");
		$.post(app.url + 'usuario/cambiar_estado', { id_usuario : id_usuario, estado : estado }, function(result){
			if( result.success === true ){
				$('span#estado-'+id_usuario).removeClass("wait");
				if( result.estado === true ){
					$.noticia(result.msj,'danger');
					$('span#estado-'+id_usuario).addClass("label label-danger state btn-status pull-right");
					$('span#estado-'+id_usuario).html("INACTIVO");
				}else{
					$.noticia(result.msj,'success');
					$('span#estado-'+id_usuario).addClass("label label-success state btn-status pull-right");
					$('span#estado-'+id_usuario).html("ACTIVO");						
				}
			}else{
				$.noticia(result.msj,'error');
			}
		},"json");
		return false;
    });
    $refrescarNotificaciones = (function(){
        var filas = '<?php echo $this->filas_paginado; ?>';
        var rango = 0;
        if ( $('div#paginacion ul li.active a').length ) {
            if ( $('div#paginacion ul li.active a').html() !== '') {
                rango = ($('div#paginacion ul li.active a').html() - 1) * filas; 
            };
        }else{
            rango = 0;
        };
        $.post(app.url + 'usuario/paginacionUsu/' + rango, $('#form-busqueda').serialize(), function(data){
            $('#contenedor').html(data);
        });
    });
    $paginacion = (function(el){
        var parametros = ($(el).attr('href'));
        $.post(parametros, $('#form-busqueda').serialize(), function(data){
            $('#contenedor').html(data);
        });
        return false;
    });
	$('div#contenedor').on('click', 'div#paginacion ul li a.btn-paginar', function(e){
		e.preventDefault();
		$paginacion(this);
	});
	$('div#contenedor').on('click', 'a.btn-editar', function() {
		$modalEdicion(this);
		return false;
	});
	$('div#contenedor').on('click', 'a.btn-eliminar', function() {
		$modalEliminar(this);
		return false;
	});
	$('div#contenedor').on('click', 'a.btn-cambiar-password', function() {
		$modalCambiarPassword(this);
	});
    $('div#contenedor').on('click', 'span.btn-status', function() {
        $cambiarStatus(this);
    });
});
function finishAjax(id, response){
	$('#'+id).html(unescape(response));
	$('#'+id).fadeIn();
}
</script>
<style type="text/css"> .state:hover{ cursor:pointer; } </style>
<form id="form-busqueda" name="form-busqueda" class="well" action="#" method="post">
	<div class="row">
		<div class="col-xs-6 col-sm-6 col-md-4">
			<div class="input-group">
				<input type="text" id="referencia" name="referencia" autofocus="autofocus" class="form-control" placeholder="ingrese texto a buscar..">
				<span class="input-group-btn">
					<button id="btn-buscar" class="btn btn-success" type="submit"><span class="glyphicon glyphicon-search"></span></button>
				</span>
			</div>
		</div>
		<div class="col-xs-6 col-sm-6 col-md-8">
			<button id="btn-nuevo-usuario" name="btn-nuevo-usuario" type="button" class="btn-agregar btn btn-primary opcion pull-right" title="Nuevo Usuario"><span class="glyphicon glyphicon-plus-sign"></span></button>
		</div>
	</div>
</form>
<form class="form-horizontal"  style="display:none" id="form-usuario" name="form-usuario" action="#"  method="post" onsubmit="return(false)">
	<input type="hidden" id="modo" name="modo" value="0" />
	<input type="hidden" id="id_usuario" name="id_usuario" />
	<div class="row">
    	<div class="col-md-6">
    		<legend class="lead">Datos Generales<span id="lbl-accion" class="pull-right label"></span></legend>
    		<div class="form-group" id="newNombre">
				<label class="col-lg-3 control-label">Nombre</label>
				<div class="col-lg-6">	
			    	<input type="text" id="nombre" name="nombre" class="form-control"/>
			    	<span class="help-inline" id="msjNombre_new"></span>
			     </div>
			</div>
			<div class="form-group" id="newCorreo">
				<label class="col-lg-3 control-label">Correo Institucional</label>
				<div class="col-lg-6 inline">	
			    	<input type="text" id="correo" name="correo" class="form-control"/>
			    	<span class="help-inline" id="loading"><img src="<?php echo base_url(); ?>img/buscando.gif" alt="" /></span>
			    	<span class="help-inline" id="msjCorreo_new"></span>
			     </div>
			</div>
			<div class="form-group" id="newGrupo">
				<label class="col-lg-3 control-label">Grupo</label>
				<div class="col-lg-4">
			    	<select id="id_rol" name="id_rol" class="form-control">
			    		<?php $indice = 0; ?>
						<?php foreach ($roles as $key => $campo): ?>
							<?php if ( $indice == 0): ?>
								<option value="" selected="selected">Seleccione</option>
								<option value="<?=$campo->id_rol?>" ><?=$campo->rol?></option>
							<?php else: ?>
								<option value="<?=$campo->id_rol?>"><?=$campo->rol?></option>
							<?php endif ?>
			        		<?php $indice++; ?>
			        	<?php endforeach ?>
			        </select>
			    	<span class="help-inline" id="msjGrupo_new"></span>
			     </div>
			</div>
    	</div>
    	<div class="col-md-6">
    		<legend class="lead">Datos de Acceso</legend>
			<div class="form-group" id="newUsuario">
				<label class="col-lg-3 control-label">Usuario <br><small>min. 8 caracteres</small></label>
				<div class="col-lg-4">
			    	<input type="text" id="usuario" name="usuario" maxlength="10" class="form-control" />
			    	<span class="has-error" id="msjUsuario_new"></span>
			     </div>
			</div>
			<div class="form-group" id="contPass">
				<label class="col-lg-3 control-label"></label>
				<div class="col-lg-4">
			    	<input type="text" class="fuerzaMsg fuerzaPass form-control" name="fuerzaPass" value="Seguridad de la Contraseña" size="40" readonly="readonly">
			    </div> 
			</div>  
			<div class="form-group" id="newPassword">
			    <label class="col-lg-3 control-label">Contrase&ntilde;a <br><small>min. 8 caracteres</small></label>
			    <div class="col-lg-4">
			    	<input type="password" id="password" name="password" maxlength="20" class="form-control"/> 
			    	<span class="help-inline" id="msjPass_new"></span>
			    </div>               
			</div>         
			<div class="form-group" id="newR_password">
			    <label class="col-lg-3 control-label">Repetir Contrase&ntilde;a</label>
			    <div class="col-lg-4">
			    	<input type="password" id="confirm_password" name="confirm_password" maxlength="20" class="form-control"/>
			    	<span class="help-inline" id="msjPassR_new"></span>
			    </div> 
			</div>
    	</div>
	</div>
	<div>
		<button id="btn-guardar-usuario" name="btn-guardar-usuario" type="submit" class="btn btn-primary pull-right" data-loading-text="Espere.." >Guardar</button>
		<span class="pull-right">&nbsp;</span>
		<button id="btn-cancelar-usuario" type="button" class="btn btn-default pull-right">Cancelar</button>
	</div>
</form>
<div id="contenedor" class="table-responsive">
	<?php echo $tabla; ?>
</div>
<div id="modal-cambiar-password" class="modal fade">
	<div class="modal-dialog">
	    <div class="modal-content">
	        <div class="modal-header lead">
	            <button type="button" class="close" data-dismiss="modal">×</button>
				<span>Cambiar Contrase&ntilde;a</span>
	        </div>
	        <div class="modal-body">
	            <form id="form-cambiar-password" name="form-cambiar-password" action="#"  method="post" onsubmit="return(false)" class="form-horizontal"> 
		            <div class="form-group">
		                <input type="hidden" id="id_usuario_cp" name="id_usuario_cp" />
		                <input type="hidden" id="usuario_cp" name="usuario_cp" />
		                <input type="hidden" id="correoE_cp" name="correoE_cp" />
		            	<label class="col-lg-4 control-label">&nbsp;</label>
		            	<div class="col-lg-6">
		                	<input type="text" class="fuerzaMsg fuerzaPass form-control" name="fuerzaPass" value="Seguridad de la Contraseña" size="40" readonly="readonly">
		                </div>
		            </div>  
		            <div class="form-group">
		                <label class="col-lg-4 control-label">Nueva Contraseña</label>
		                <div class="col-lg-6">
			                <input type="password" id="password_new" name="password_new" tabindex="8" class="form-control" />
			                <span class="help-inline" id="msjPass_np"></span>
		                </div>
		            </div>
		            <div class="form-group">
		                <label class="col-lg-4 control-label">Repetir Contraseña</label>
		                <div class="col-lg-6">
			                <input type="password" id="password_confirm" name="password_confirm" tabindex="9" class="form-control" />
			                <span class="help-inline" id="msjPassR_np"></span>
		                </div>
		            </div>
		        </form>
	        </div>
	        <div class="modal-footer">
				<button class="btn btn-default" data-dismiss="modal" type="button">Cancelar</button>
				<button id="btn-guardar-nuevo-password" name="btn-guardar-nuevo-password" class="btn btn-primary" type="button" data-loading-text="Espere..">Guardar</button>
			</div>
	    </div>
	</div>
</div>