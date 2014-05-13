<table class="table table-hover table-striped">
	<thead>
		<tr>
			<th width="60%">Nombre</th>
			<th width="10%">Sexo</th>
			<th width="10%">Edad</th>
			<th width="10%">Analisis</th>
			<th width="10%">Opciones</th>
		</tr>
	</thead>
	<tbody>
		<?php if(count($pacientes) > 0) : ?>
			<?php foreach ($pacientes as $campo) : ?>
				<tr>
					<td><?php echo $campo->nombre.' '.$campo->apellidop.' '.$campo->apellidom; ?></td>
					<td><?php if($campo->sexo == 'hombre') : ?> <label class="label label-primary">Hombre</label><?php else : ?><label class="label label-pink">Mujer</label><?php endif ?></td>
					<td>
						<?php 
						    list($ano,$mes,$dia) = explode("-",$campo->fechaNacimiento);
						    $ano_diferencia  = date("Y") - $ano;
						    $mes_diferencia = date("m") - $mes;
						    $dia_diferencia   = date("d") - $dia;
						    if ($dia_diferencia < 0 || $mes_diferencia < 0)
						        $ano_diferencia--;
						   	echo $ano_diferencia;
						?>
					</td>
					<td></td>
					<td>
						<a class="btn btn-primary" href="#" rel="tooltip" title="Editar"><span class="glyphicon glyphicon-pencil"></span></a>
						<a class="btn btn-default" href="#" rel="tooltip" title="Detalle"><span class="glyphicon glyphicon-list"></span></a>
					</td>
				</tr>		
			<?php endforeach ?>
		<?php else : ?>
			<tr>
				<td colspan="5">
					<em>No hay resultados</em>
				</td>
			</tr>	
		<?php endif ?>
	</tbody>
</table>
<?php echo $this->pagination->create_links(); ?>