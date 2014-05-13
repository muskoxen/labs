<h2>Pacientes <small>Listado de pacientes</small></h2>
<div class="row">
	<div class="col-md-6">
		<div class="input-group">
			<input type="text" class="form-control" placeholder="Buscar">
			<span class="input-group-btn"><a class="btn btn-primary"><span class="glyphicon glyphicon-search"></span></a></span>
		</div>
	</div>
	<div class="col-md-1"></div>
	<div class="col-md-4">
		<a class="btn btn-success" id="agregar" rel="tooltip" title="Agregar" href="#"><span class="glyphicon glyphicon-plus" style="font-size: 12px"></span></a>
	</div>
</div>

<div id="vista" style="margin-top: 20px">
	<?php echo $tabla; ?>
</div>

<div id="modal-agregar" class="modal fade">
	<div class="modal-dialog" style="width: 80%">
	    <div class="modal-content">
			<div class="modal-header lead">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<span id="titulo">Nuevo paciente</span>
			</div>
			<div class="modal-body">
				<form id="form-paciente" name="form-paciente" action="#"  method="post" class="form-horizontal">    
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="nombre" class="col-sm-4 control-label">Nombre</label>
								<div class="col-sm-8">
									<input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre">
								</div>
							</div>
							<div class="form-group">
								<label for="apellidop" class="col-sm-4 control-label">A. Paterno</label>
								<div class="col-sm-8">
									<input type="text" id="apellidop" name="apellidop" class="form-control" placeholder="Apellido paterno">
								</div>
							</div>
							<div class="form-group">
								<label for="apellidom" class="col-sm-4 control-label">A. Materno</label>
								<div class="col-sm-8">
									<input type="text" id="apellidom" name="apellidom" class="form-control" placeholder="Apellido Materno">
								</div>
							</div>
							<div class="form-group">
								<label for="telefono" class="col-sm-4 control-label">Telefono</label>
								<div class="col-sm-8">
									<input type="text" id="telefono" name="telefono" class="form-control" placeholder="Telefono">
								</div>
							</div>
							<div class="form-group">
								<label for="correo" class="col-sm-4 control-label">Correo</label>
								<div class="col-sm-8">
									<input type="text" id="correo" name="correo" class="form-control" placeholder="Correo electronico">
								</div>
							</div>
							<div class="form-group">
								<label for="fechaNacimiento" class="col-sm-4 control-label">Nacimiento</label>
								<div class="col-sm-5">
									<div class="input-group">
										<input type="text" id="fechaNacimiento" name="fechaNacimiento" class="form-control" placeholder="yy-mm-dd">
										<span class="input-group-btn"><a class="btn btn-primary"><span class="glyphicon glyphicon-calendar"></span></a></span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="sexos" class="col-sm-4 control-label">Sexo</label>
								<div class="col-sm-8">
									<div class="radio-inline">
									  	<label>
										    <input type="radio" name="sexos" id="hombre" value="hombre" checked>
										    Hombre
									  	</label>
									</div>
									<div class="radio-inline">
									  	<label>
										    <input type="radio" name="sexos" id="mujer" value="mujer">
										   	Mujer
									  	</label>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="calle" class="col-sm-3 control-label">Calle</label>
								<div class="col-sm-8">
									<input type="text" id="calle" name="calle" class="form-control" placeholder="Calle">
								</div>
							</div>
							<div class="form-group">
								<label for="colonia" class="col-sm-3 control-label">Colonia</label>
								<div class="col-sm-8">
									<input type="text" id="colonia" name="colonia" class="form-control" placeholder="Colonia">
								</div>
							</div>
							<div class="form-group">
								<label for="numero" class="col-sm-3 control-label">Número</label>
								<div class="col-sm-4">
									<input type="text" id="numero" name="numero" class="form-control" placeholder="Número">
								</div>
							</div>
							<div class="form-group">
								<label for="codigoPostal" class="col-sm-3 control-label">C.P.</label>
								<div class="col-sm-4">
									<input type="text" id="codigoPostal" name="codigoPostal" class="form-control" placeholder="Codigo Postal">
								</div>
							</div>
							<div class="form-group">
								<label for="estado" class="col-sm-3 control-label">Estados</label>
								<div class="col-sm-6">
									<select class="form-control" id="estados">
										<option value="">- Seleccione -</option>
										<?php foreach ($estados as $campo): ?>
											<option value="<?=$campo->estadoId?>"><?=$campo->estado?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>
							<div class="form-group" id="divMunicipios" style="display: none"> 
								<label for="municipios" class="col-sm-3 control-label">Municipios</label>
								<div class="col-sm-6">
									<select class="form-control" name="municipio" id="municipios">
										<!-- Html hecho con javascript -->
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-1"></div>
						<div class="col-md-10">
							<div class="well well-small">
								Medio por el cual se entero:
								<div class="row">
									<div class="col-md-2">
										<div class="radio-inline">
										  	<label>
											    <input type="radio" name="medios" id="internet" value="internet" checked>
											    Internet
										  	</label>
										</div>
									</div>
									<div class="col-md-2">
										<div class="radio-inline">
										  	<label>
											    <input type="radio" name="medios" id="tv" value="tv">
											   	TV
										  	</label>
										</div>
									</div>
									<div class="col-md-2">
										<div class="radio-inline">
										  	<label>
											    <input type="radio" name="medios" id="radio" value="radio">
											    Radio
										  	</label>
										</div>
									</div>
									<div class="col-md-2">
										<div class="radio-inline">
										  	<label>
											    <input type="radio" name="medios" id="folleto" value="folleto">
											   	Folleto
										  	</label>
										</div>
									</div>
									<div class="col-md-2">
										<div class="radio-inline">
										  	<label>
											    <input type="radio" name="medios" id="otro" value="otros">
											    Otro
										  	</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="estado" class="col-sm-1 control-label">Otro</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" id="otroMedio" name="otroMedio" placeholder="Especifique el otro medio" readonly="">
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button class="btn btn-default" data-dismiss="modal" type="button">Cancelar</button>
				<button id="btn-guardar" name="btn-guardar" class="btn btn-primary" type="submit" data-loading-text="Espere..">Guardar</button>
			</div>
		</div>
	</div>
</div>

<script>
$(function(){
	$("input#fechaNacimiento").datepicker({ dateFormat : 'yy-mm-dd', changeMonth: true, changeYear: true });

	$("a#agregar").click(function(){
		event.preventDefault();
		$("div#modal-agregar").modal('toggle');
	});

	$("select#estados").change(function(){
		var estadoId = $(this).val();
		if(estadoId == '') {
			$("select#municipios").html('');
			$("div#divMunicipios").slideUp();
		}
		else {
			$.get(app.url + 'pacientes/municipios', { estadoId : estadoId }, function(response){
				var options = '';
				$.each(response, function(campo, valor){
					options += '<option value="'+valor.municipioId+'">'+valor.municipio+'</option>';
				});

				$("select#municipios").html(options);
				$("div#divMunicipios").slideDown();
			}, 'json');
		}
	});

	$(":radio[name=medios]").click(function(){
		if($("#otro").is(':checked')){
			$("#otroMedio").removeAttr('readonly');
		}
		else {
			$("#otroMedio").val('').attr('readonly', true);
		}
	});

	$("#btn-guardar").click(function(){
		$('#btn-guardar').button('loading');
		$.post(app.url + 'pacientes/nuevo', $("#form-paciente").serialize(), function(response){
			if(response.exito == true) {
				$("#modal-agregar").modal('hide');
				$.get( app.url + 'pacientes/ultimo_registro', function(busqueda){
					$("#vista").html(busqueda);
				}, 'json');
			}
			else {
				$('#btn-guardar').button('reset');
				first = true;
				$.each(response.msj, function(campo, aviso){
					if (first){
						$('div .has-error').removeClass('has-error');
						error = new Object({ campo: campo, mensaje: aviso });
						first = false;
					}
					//$('#'+campo).parents('.form-group').addClass('has-error');
				});
				$.noticia(error.mensaje, 'danger');
				$('#'+error.campo).focus();
			}

		}, 'json');
	});

	$paginacion = (function(el){
        var parametros = ($(el).attr('href'));
        $.post(parametros, function(data){
            $('#vista').html(data);
        });
        return false;
    });
	$('div#vista').on('click', 'div#paginacion ul li a.btn-paginar', function(e){
		e.preventDefault();
		$paginacion(this);
	});
});
</script>