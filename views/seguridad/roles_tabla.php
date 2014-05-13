<script type="text/javascript">
$(function() {
	$('.opcion').tooltip();
});
</script>
<table class="table table-striped table-hover table-condensed">
    <thead>
        <tr>
        	<th width="2%">ID</th>	
            <th width="36%">NOMBRE</th>
            <th width="12%"></th>
            <th width="40%" class="hidden-xs hidden-sm">DESCRIPCIÓN</th>
            <th width="5%" class="hidden-xs hidden-sm">ESTADO</th>            
        </tr>
    </thead>
    <tbody>
    	<?php if ($roles): ?>
    		<?php foreach ($roles as $campo): ?>
	    		<tr>
		        	<td><?=$campo->id_rol?></td>
		            <td><?=$campo->rol?></td>
		            <td>
		            	<div class="hidden-xs hidden-sm">
							<?php if ( $campo->usuarios == 0): ?>
								<a data-id='<?=$campo->id_rol?>' class="btn-eliminar btn btn-xs btn-danger pull-right opcion" title="Eliminar"><span class="glyphicon glyphicon-trash"></span></a>
								<span class="pull-right">&nbsp;</span>
							<?php endif ?>
							<a data-id-rol="<?=$campo->id_rol?>" data-rol="<?=$campo->rol?>" data-descripcion='<?=$campo->descripcion?>' class="btn-edicion btn btn-xs btn-info pull-right opcion" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
							<span class="pull-right">&nbsp;</span>
							<a data-id-rol="<?=$campo->id_rol?>" class="btn-ver-permisos btn btn-xs btn-default pull-right opcion" title="Ver Permisos"><span class="glyphicon glyphicon-tasks"></span></a>
		            	</div>

						<div class="btn-group pull-right visible-xs visible-sm">
	                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
	                            <span class="glyphicon glyphicon-wrench"></span> <span class="caret"></span>
	                        </button>
	                        <ul class="dropdown-menu" role="menu">
	                            <li><a data-id-rol="<?=$campo->id_rol?>" class="btn-ver-permisos" href="#"><span class="glyphicon glyphicon-tasks"></span> Ver Permisos</a></li>                            
	                            <li><a data-id-rol="<?=$campo->id_rol?>" data-rol="<?=$campo->rol?>" data-descripcion='<?=$campo->descripcion?>' class="btn-edicion" href="#"><span class="glyphicon glyphicon-edit"></span> Editar</a></li>
	                            <?php if ( $campo->usuarios == 0 ): ?>
	                            	<li><a data-id='<?=$campo->id_rol?>' class="btn-eliminar" href="#"><span class="glyphicon glyphicon-trash"></span> Eliminar</a></li>
	                            <?php endif ?>	                            
	                        </ul>
	                    </div>

		            </td>
		            <td  class="hidden-xs hidden-sm"><?=$campo->descripcion?></td>
		            <td class="hidden-xs hidden-sm">
		            <?php
		                switch($campo->estado){
		                    case 'activo': ?><span id="estado-<?=$campo->id_rol?>" href="#" class="label label-success btn-status">ACTIVO</span><?php
		                    	break;
		                    case 'inactivo': ?><span id="estado-<?=$campo->id_rol?>" href="#" class="label label-danger btn-status">INACTIVO</span><?php
		                    	break;
		                }
		            ?>
		            </td>
		        </tr>
	    	<?php endforeach ?>
    	<?php else: ?>
			<tr>
    			<td colspan="5">No se encontraron resultados</td>
    		</tr>
    	<?php endif ?>
    </tbody>
</table>
<?php echo $this->pagination->create_links(); ?>