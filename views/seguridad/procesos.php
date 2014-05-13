<script type="text/javascript">
$(function() {
	$('.navbar-collapse li a[href="modulo/listado"]').parents().addClass('active');
	//$('ol.breadcrumb').append('<li class="active"><a>Seguridad</a><span class="divider"></span></li><li class="active"><a href="modulo/listado">Módulos</a><span class="divider"></span></li><li class="active">Procesos</li>');
	$('#modal-proceso').on('hidden.bs.modal', function () {
		$('#form-proceso div').removeClass('has-error');
		$('#id_proceso').val('');
		$('#modo').val('0');
		$('#titulo').html('Nuevo Proceso');
		$('#btn-guardar-proceso').button('reset');
		document.getElementById("form-proceso").reset();
	});
	$('#btn-nuevo-proceso').click(function(){
		$('#modal-proceso').modal('show');
	});
	$('#btn-guardar-proceso').click(function(){
		$('#btn-guardar-proceso').button('loading');
		$.post(app.url + 'proceso/alta', $('#form-proceso').serialize(), function(result){
			if ( result.exito === true ){
				$('#btn-guardar-proceso').button('reset');
				$('#modal-proceso').modal('hide');
				$.noticia(result.msj,'success');
				$refrescarNotificaciones();
			}else{
				$('#btn-guardar-proceso').button('reset');
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
        $.post(app.url + 'proceso/busqueda', $('#form-busqueda').serialize(), function(data){
            $('#contenedor').html(data);
        });
        return false;
    });
	$modalEliminar = (function(el){
        var id = $(el).attr('data-id-proceso');
		$.confirmar('¿Seguro que desea eliminar?',{ 
			aceptar: function(){
				$.post(app.url + 'proceso/eliminar/' + id, function(result){
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
		$('#titulo').html('Edición Proceso');
		$('#id_proceso').val($(el).attr('data-id-proceso'));
		$('#id_modulo').val($(el).attr('data-id-modulo'));
		$('#nombre_proceso').val($(el).attr('data-nombre-proceso'));
		$('#proceso').val($(el).attr('data-proceso'));
		$('#modal-proceso').modal('show');
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
        $.post(app.url + 'proceso/paginacionPro/' + rango, $('#form-busqueda').serialize(), function(data){
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
    $('div#contenedor').on('click', 'a.btn-eliminar', function() {
        $modalEliminar(this);
        return false;
    });
    $('div#contenedor').on('click', 'a.btn-edicion', function() {
        $modalEdicion(this);
        return false;
    });
	$('#btn-regresar').click(function(){
		$.post(app.url + 'modulo/mod', function(data){
			$('div.panel-body').html(data);
		});
		return false;
	});
});
</script>
<?php foreach ($modulo as $campo): ?>
<p class="lead"><a id="btn-regresar" class="btn btn-default btn-xs opcion" title="Volver a Módulos">
<span class="glyphicon glyphicon-arrow-left"></span></a>&nbsp;&nbsp;PROCESOS DEL MÓDULO <?=strtoupper($campo->nombre_modulo)?></p>
<?php endforeach ?>
<form id="form-busqueda" name="form-busqueda" class="well" action="#" method="post" >
	<input type="hidden" id="idModulo" name="idModulo" value="<?php echo $idModulo; ?>" />
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
			<button type="button" id="btn-nuevo-proceso" class="btn btn-primary opcion pull-right" title="Nuevo Proceso"><span class="glyphicon glyphicon-plus-sign"></span></button>
		</div>
	</div>    
</form>
<div id="contenedor">
	<?php echo $tabla; ?>
</div>
<div id="modal-proceso" class="modal fade">
	<div class="modal-dialog">
	    <div class="modal-content">
			<div class="modal-header lead">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<span id="titulo">Nuevo Proceso</span>
			</div>
			<div class="modal-body">
			    <form id="form-proceso" name="form-proceso" action="#"  method="post" class="form-horizontal">    
					<input type="hidden" id="modo" name="modo" value="0" />
					<input type="hidden" id="id_modulo" name="id_modulo" value="<?=$idModulo?>" />
					<input type="hidden" id="id_proceso" name="id_proceso" />
			        <div class="form-group">
			            <label class="col-lg-4 control-label">Nombre Proceso</label>
			            <div class="col-lg-7">
			            	<input type="text" id="nombre_proceso" name="nombre_proceso" class="form-control" maxlength="255" />
			            </div>
			        </div>  
			        <div class="form-group">
			            <label class="col-lg-4 control-label">Proceso</label>
			            <div class="col-lg-7">
			            	<input type="text" id="proceso" name="proceso" class="form-control" />
			            </div>
			        </div>     
			    </form>
			</div>
		    <div class="modal-footer">
				<button class="btn btn-default" data-dismiss="modal" type="button">Cancelar</button>
				<button id="btn-guardar-proceso" name="btn-guardar-proceso" class="btn btn-primary" type="submit" data-loading-text="Espere..">Guardar</button>
			</div>
		</div>
	</div>
</div>