<link href="css/tabs.css" rel="stylesheet">
<script type="text/javascript">
var id_rol, id_modulo, id_proceso, modo;
$(function() {
	$('.navbar-collapse li a[href="rol/listado"]').parents().addClass('active');
	//$('ol.breadcrumb').append('<li class="active"><a>Seguridad</a><span class="divider"></span></li><li class="active"><a href="rol/listado">Roles</a><span class="divider"></span></li><li class="active">Permisos</li>');
	$('#btn-buscar').click(function(){
        $.post(app.url + 'rol/busqueda_permisos', $('#form-busqueda').serialize(), function(data){
            $('#contenedor').html(data);
        });
        return false;
    });
	$permisosModulos = (function(el){
		id_rol = $('#idRol').val();
		var parametros = ($(el).attr('rel')).split('|');
		id_modulo = parametros[0];
		id_proceso = parametros[1];
		$.post("<?=base_url()?>rol/cambiar_permiso", { id_rol : id_rol, id_modulo : id_modulo, id_proceso : id_proceso}, function(result){
			if( result.success === true ){
				$('div#primerDiv div#l' + id_modulo + ' ul li a span#proceso-' + id_proceso + ' span#marca-' + id_proceso).html(result.modo);
			}else{
				$.noticia(result.msg,'error');
			}
		},"json");
		return false;
	});
	$('div#contenedor').on('click', 'div.tab-pane ul li a.modPermiso',function(e) {
    	e.preventDefault();
        $permisosModulos(this);
    });
    $('#btn-regresar').click(function(){
		$.post(app.url + 'rol/mod', function(data){
			$('div.panel-body').html(data);
		});
		return false;
	});
});
</script>
<p class="lead">
	<a id="btn-regresar" class="btn btn-xs btn-default opcion" title="Volver a Roles">
	<span class="glyphicon glyphicon-arrow-left"></span></a>&nbsp;&nbsp;PERMISOS DE <?=strtoupper($nombre_rol)?>
</p>

<form id="form-busqueda" name="form-busqueda" class="well" action="#" method="post" >
	<input type="hidden" id="idRol" name="idRol" value="<?=$id_rol?>" />
	<div class="row">
		<div class="col-xs-6 col-sm-6 col-md-4">
			<div class="input-group">
				<input type="text" id="referencia" name="referencia" autofocus="autofocus" class="form-control" placeholder="ingrese texto a buscar..">
				<span class="input-group-btn">
					<button id="btn-buscar" class="btn btn-success" type="submit"><span class="glyphicon glyphicon-search"></span></button>
				</span>
			</div>
		</div>
	</div>
</form>
<div id="contenedor" class="tabbable tabs-left">
	<?php echo $tabla; ?>
</div>