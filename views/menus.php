<div class="row">
	<div class="col-xs-12 col-sm-3 col-md-2">
		<div id="menu-principal" class="list-group">
			<a href="usuario/mod" class="list-group-item"><span class="glyphicon glyphicon-user"></span> Usuarios</a>
			<a href="rol/mod" class="list-group-item"><span class="glyphicon glyphicon-briefcase"></span> Roles</a>
			<a href="modulo/mod" class="list-group-item"><span class="glyphicon glyphicon-cog"></span> Módulos</a>
		</div>
	</div>
	<div class="col-xs-12 col-sm-9 col-md-10">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 id="titulo-seguridad" class="panel-title"></h3>
			</div>
			<div class="panel-body">
				<?php echo $modulo ?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function() {
	$(document).ready(function() {
		var contenedor = document.getElementById('menu-principal'); // contenedor
		for (var i = 1; i <= (contenedor.getElementsByTagName('a').length) * 2 ; i++) { // a inicio y final, dan 2 c/u
			if ( $((contenedor.childNodes[i])).html() && 
			( $((contenedor.childNodes[i])).html() == $('.navbar-right li ul.dropdown-menu li.active').children('a').html() ) ) { // variable sea la etiqueta completa e igual que la del menu superior
				$((contenedor.childNodes[i])).addClass('active');
			};
		};
		
	});
	$('#menu-principal a').click(function(){
		$('#menu-principal a').removeClass('active');
		$(this).addClass('active');
		$('#titulo-seguridad').html('Control de ' + $(this).html().replace('<span class="glyphicon glyphicon-user"></span> ','').replace('<span class="glyphicon glyphicon-briefcase"></span> ','').replace('<span class="glyphicon glyphicon-cog"></span> ',''));
		var contenedor = document.getElementById('menu-seguridad'); // contenedor
		for (var i = 1; i <= (contenedor.getElementsByTagName('li').length) * 2 ; i++) { // a inicio y final, dan 2 c/u
			if ( $((contenedor.childNodes[i])).html() && 
				( $((contenedor.childNodes[i])).children('a').html() == $(this).html() ) ) { // variable sea la etiqueta completa e igual que la del menu superior
					$('#menu-seguridad li').removeClass('active');
					$((contenedor.childNodes[i])).addClass('active');
			};
		};
		$.post(app.url + $(this).attr('href'), function(data){
			$('div.panel-body').html(data);
		});
		return false;
	});
});
</script>