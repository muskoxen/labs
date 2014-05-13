<script type="text/javascript">
$(function() {
	$('.opcion').tooltip();
});
</script>
<table id="tabla-usuarios" class="table table-striped table-hover table-condensed">
    <thead>
        <tr>
        	<th width="2%">ID</th>
        	<th width="8%">USUARIO</th>
            <th width="12%"></th>
            <th width="10%">GRUPO</th>
            <th width="8%">NOMBRE</th>
            <th width="10%" class="hidden-xs hidden-sm">CORREO</th>
            <th width="5%" class="hidden-xs"><span class="pull-right">ESTADO</span></th>
        </tr>
    </thead>
    <tbody>
	<?php if ($usuarios): ?>
		<?php foreach ($usuarios as $campo): ?>
	    	<tr>
	        	<td><?=$campo->id_usuario?></td>
	            <td><?=$campo->usuario?></td>
	            <td>
	            	<div class="hidden-xs hidden-sm">
	            		<a data-id='<?=$campo->id_usuario?>' class="btn btn-danger btn-xs btn-eliminar pull-right opcion" title="Eliminar">
		            		<span class="glyphicon glyphicon-trash"></span>
		            	</a>
			        	<span class="pull-right">&nbsp;</span>
			        	<a data-id-usuario='<?=$campo->id_usuario?>' class="btn btn-info btn-xs btn-editar pull-right opcion" title="Editar">
			        		<span class="glyphicon glyphicon-edit"></span>
			        	</a>
			        	<span class="pull-right">&nbsp;</span>
			        	<a data-id-usuario='<?=$campo->id_usuario?>' data-usuario="<?=$campo->usuario?>" data-correo="<?=$campo->correo?>" class="btn btn-default btn-xs btn-cambiar-password pull-right opcion" title="Cambiar Contrase&ntilde;a">
			        		<span class="glyphicon glyphicon-lock"></span>
			        	</a>
	            	</div>
					<div class="btn-group pull-right visible-xs visible-sm">
					    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
					        <span class="glyphicon glyphicon-wrench"></span> <span class="caret"></span>
					    </button>
					    <ul class="dropdown-menu" role="menu">
					        <li><a data-id-usuario='<?=$campo->id_usuario?>' data-usuario="<?=$campo->usuario?>" data-correo="<?=$campo->correo?>" class="btn-cambiar-password" href="#"><span class="glyphicon glyphicon-lock"></span> Cambiar Contrase&ntilde;a</a></li>                            
					        <li><a data-id-usuario='<?=$campo->id_usuario?>' class="btn-editar" href="#"><span class="glyphicon glyphicon-edit"></span> Editar</a></li>
					        <li><a data-id='<?=$campo->id_usuario?>' class="btn-eliminar" href="#"><span class="glyphicon glyphicon-trash"></span> Eliminar</a></li>
					    </ul>
					</div>
	            </td>
	            <td><?=$campo->rol?></td>
	            <td><?=$campo->nombre?></td>
	            <td class="hidden-xs hidden-sm"><?=$campo->correo?></td>
	            <td class="hidden-xs">
	            <?php
	                switch($campo->estado){
	                    case 'ACTIVO': ?><span href="#" id="estado-<?=$campo->id_usuario?>" class="label label-success btn-status pull-right">ACTIVO</span><?php
	                    	break;
	                    case 'INACTIVO': ?><span href="#" id="estado-<?=$campo->id_usuario?>" class="label label-danger btn-status pull-right">INACTIVO</span><?php
	                    	break;
	                }
	            ?>                        
	            </td>
	        </tr> 
	    <?php endforeach ?>
	<?php else: ?>
			<tr>
    			<td colspan="8">No se encontraron resultados</td>
    		</tr>
	<?php endif ?>
    </tbody>
</table>
<?php echo $this->pagination->create_links(); ?>