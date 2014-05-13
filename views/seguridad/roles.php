<script type="text/javascript">
$(function() {
	$('#modal-rol').on('hidden.bs.modal', function () {
		$('#modo').val('0');
		$('#id_rol').val('');
		$('#titulo').html('Nuevo Rol');
		$('#btn-guardar-rol').button('reset');
		$('#form-rol div').removeClass('has-error');
		document.getElementById("form-rol").reset();
	});
	$('#btn-nuevo-rol').click(function(){
		$('#modal-rol').modal('show');
	});
	$('#btn-guardar-rol').click(function(){
		$('#btn-guardar-rol').button('loading');
		$.post(app.url + 'rol/alta', $('#form-rol').serialize(), function(result){
			if ( result.exito === true ){
				$('#btn-guardar-rol').button('reset');
				$('#modal-rol').modal('hide');
				$.noticia(result.msj,'success');
                $refrescarNotificaciones();
			}else{
				$('#btn-guardar-rol').button('reset');
				if ( result.exito === false ) {
					first = true;
					$.each(result.msj, function(campo, aviso){
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
        $.post(app.url + 'rol/busqueda', $('#form-busqueda').serialize(), function(data){
            $('#contenedor').html(data);
        });
        return false;
    });
	$cambiarStatus = (function(el){
    	params = $(el).attr("id").split('-');
		id_rol = params[1];
		estado = $('span#estado-'+id_rol).html();
		$('span#estado-'+id_rol).removeClass().addClass("wait").html("");
		$.post(app.url + 'rol/cambiar_estado', { id_rol : id_rol, estado : estado }, function(result){
			if(result.success === true){
				$('span#estado-'+id_rol).removeClass("wait");
				if(result.estado === true){
					$.noticia(result.msj,'danger');
					$('span#estado-'+id_rol).addClass("label label-danger state btn-status");
					$('span#estado-'+id_rol).html("INACTIVO");
				}else{
					$.noticia(result.msj,'success');
					$('span#estado-'+id_rol).addClass("label label-success state btn-status");
					$('span#estado-'+id_rol).html("ACTIVO");
				}
			}else{
				$.noticia(result.msj,'danger');
			}
		}, "json");
		return false;
    });
    $modalEliminar = (function(el){
        var id = $(el).attr('data-id');
		$.confirmar('¿Seguro que desea eliminar?',{ 
			aceptar: function(){
				$.post(app.url + 'rol/eliminar/' + id, function(result){
					if ( result.exito === true ){
						$.noticia(result.msj,'success');
						$refrescarNotificaciones();
					}else{
						if ( result.exito === false ) {
							$.noticia(result.msj,'error');
						};				
					};
				},'json');
			}
		});
		return false;
    });
    $modalEdicion = (function(el){
    	$('#modo').val('1');
		$('#titulo').html('Edición de Rol');
		$('#id_rol').val($(el).attr('data-id-rol'));
		$('#rol').val($(el).attr('data-rol'));
		$('#descripcion').val($(el).attr('data-descripcion'));
		$('#modal-rol').modal('show');
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
        $.post(app.url + 'rol/paginacionRol/' + rango, $('#form-busqueda').serialize(), function(data){
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
	$('div#contenedor').on('click', 'span.btn-status', function() {
		$cambiarStatus(this);
	});
	$('div#contenedor').on('click', 'a.btn-eliminar', function() {
		$modalEliminar(this);
		return false;
	});
	$('div#contenedor').on('click', 'a.btn-edicion', function() {
		$modalEdicion(this);
		return false;
	});
	$('div#contenedor').on('click', 'a.btn-ver-permisos', function() {
	    $.post(app.url + 'rol/permisos/' + $(this).attr('data-id-rol'), function(data){
            $('div.panel-body').html(data);
        });
        return false;
	});
});
</script>
<form id="form-busqueda" name="form-busqueda" class="form-search well" action="#" method="post">
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
			<button id="btn-nuevo-rol" type="button" class="btn btn-primary pull-right opcion" title="Nuevo Rol"><span class="glyphicon glyphicon-plus-sign"></span></button>
		</div>
	</div>	
</form>
<div id="contenedor">
	<?php echo $tabla; ?>
</div>
<div id="modal-rol" class="modal fade">
	<div class="modal-dialog">
	    <div class="modal-content">
			<div class="modal-header lead">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<span id="titulo">Nuevo Rol</span>
			</div>
			<div class="modal-body">
				<form id="form-rol" name="form-rol" action="#"  method="post" class="form-horizontal">    
					<input type="hidden" id="modo" name="modo" value="0" />
					<input type="hidden" id="id_rol" name="id_rol" />
				    <div class="form-group">
						<label class="col-lg-4 control-label">Nombre</label>
						<div class="col-lg-7">
					    	<input type="text" id="rol" name="rol" class="form-control"/>
					    </div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 control-label">Descripción</label>
						<div class="col-lg-7">
					    	<textarea id="descripcion" name="descripcion" class="form-control" rows="3"></textarea>
					    </div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button class="btn btn-default" data-dismiss="modal" type="button">Cancelar</button>
				<button id="btn-guardar-rol" name="btn-guardar-rol" class="btn btn-primary" type="submit" data-loading-text="Espere..">Guardar</button>
			</div>
		</div>
	</div>
</div>