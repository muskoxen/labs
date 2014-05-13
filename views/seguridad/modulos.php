<script type="text/javascript">    
$(function() {
	$('#modal-modulo').on('hidden.bs.modal', function () {
		$('#id_modulo').val('');
		$('#modo').val('0');
		$('#titulo').html('Nuevo Módulo');
		$('#form-modulo div').removeClass('has-error');
		$('#btn-guardar-modulo').button('reset');
		document.getElementById("form-modulo").reset();
	});
	$('#btn-nuevo-modulo').click(function(){
		$('#modal-modulo').modal('show');
	});
	$('#btn-guardar-modulo').click(function(){
		$('#btn-guardar-modulo').button('loading');
		$.post(app.url + 'modulo/alta', $('#form-modulo').serialize(), function(result){
			if ( result.exito === true ){
				$('#btn-guardar-modulo').button('reset');
				$('#modal-modulo').modal('hide');
				$.noticia(result.msj,'success');
                $refrescarNotificaciones();
			}else{
				$('#btn-guardar-modulo').button('reset');
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
        $.post(app.url + 'modulo/busqueda', $('#form-busqueda').serialize(), function(data){
            $('#contenedor').html(data);
        });
        return false;
    });
	$modalEdicion = (function(el){
		$('#modo').val('1');
		$('#titulo').html('Edición Módulo');
		$('#id_modulo').val($(el).attr('data-id-modulo'));
		$('#modulo').val($(el).attr('data-modulo'));
		$('#clase').val($(el).attr('data-clase'));
		$('#modal-modulo').modal('show');
		return false;
    });
    $modalEliminar = (function(el){
        var id = $(el).attr('data-id-modulo');
		$.confirmar('¿Seguro que desea eliminar?',{ 
			aceptar: function(){
				$.post(app.url + 'modulo/eliminar/' + id, function(result){
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
        $.post(app.url + 'modulo/paginacionMod/' + rango, $('#form-busqueda').serialize(), function(data){
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
    $('div#contenedor').on('click', 'div#paginacion ul li a.btn-paginar',function(e){
		e.preventDefault();
		$paginacion(this);
	});
	$('div#contenedor').on('click', 'a.btn-eliminar', function() {
	    $modalEliminar(this);
	    return false;
	});
	$('div#contenedor').on('click', 'a.btn-edicion', function() {
	    $modalEdicion(this);
	    return false;
	});
	$('div#contenedor').on('click', 'a.btn-ver-procesos', function() {
	    $.post(app.url + 'modulo/proceso/' + $(this).attr('data-id-modulo'), function(data){
            $('div.panel-body').html(data);
        });
        return false;
	});
});
</script>
<form id="form-busqueda" name="form-busqueda" class="well" action="#" method="post" >
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
			<button type="button" id="btn-nuevo-modulo" class="btn btn-primary opcion pull-right" title="Nuevo Módulo"><span class="glyphicon glyphicon-plus-sign"></span></button>
		</div>
	</div>
</form>
<div id="contenedor" class="table-responsive">
	<?php echo $tabla; ?>
</div>
<div id="modal-modulo" class="modal fade">
	<div class="modal-dialog">
	    <div class="modal-content">
			<div class="modal-header lead">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<span id="titulo">Nuevo Módulo</span>
			</div>
			<div class="modal-body">
		        <form id="form-modulo" name="form-modulo" action="#"  method="post" class="form-horizontal">    
					<input type="hidden" id="modo" name="modo" value="0" />
					<input type="hidden" id="id_modulo" name="id_modulo" />
		            <div class="form-group">
						<label class="col-lg-3 control-label">Módulo</label>
						<div class="col-lg-8">
					    	<input type="text" id="modulo" name="modulo" class="form-control" />
					     </div>
					</div>
					 <div class="form-group">
						<label class="col-lg-3 control-label">Clase</label>
						<div class="col-lg-8">
					    	<input type="text" id="clase" name="clase" class="form-control" />
					     </div>
					</div>
		        </form>
		    </div>
		    <div class="modal-footer">
				<button class="btn btn-default" data-dismiss="modal" type="button">Cancelar</button>
				<button id="btn-guardar-modulo" name="btn-guardar-modulo" class="btn btn-primary" type="submit" data-loading-text="Espere..">Guardar</button>
			</div>
		</div>
	</div>
</div>