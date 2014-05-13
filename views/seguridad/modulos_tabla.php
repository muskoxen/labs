<script type="text/javascript">
$(function() {
	$('.opcion').tooltip();
});
</script>
<table class="table table-striped table-hover table-condensed">
    <thead>
        <tr>
        	<th width="3%">ID</th>
            <th>MÓDULO</th>
            <th class="hidden-xs">CLASE</th>
            <th width="8%">PROCESOS</th>
            <th width="13%"></th>
        </tr>
    </thead>
    <tbody>
    	<?php if ($modulos): ?>
    		<?php foreach ($modulos as $campo): ?>
	    	<tr>
	        	<td><?=$campo->id_modulo?></td>
	            <td><?=$campo->modulo?></td>
                <td class="hidden-xs"><?=$campo->clase?></td>
	            <td><?=$campo->numero_procesos?></td>
	            <td>
                    <div class="hidden-xs hidden-sm">
                        <span class="pull-right">&nbsp;</span>
                        <a data-id-modulo='<?=$campo->id_modulo?>' class="btn-eliminar btn btn-xs btn-danger pull-right opcion" title="Eliminar"><span class="glyphicon glyphicon-trash"></span></a>
                        <span class="pull-right">&nbsp;</span>
                        <a data-id-modulo="<?=$campo->id_modulo?>" data-modulo='<?=$campo->modulo?>' data-clase="<?=$campo->clase?>" class="btn-edicion btn btn-xs btn-info pull-right opcion" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
                        <span class="pull-right">&nbsp;</span>
                        <a data-id-modulo="<?=$campo->id_modulo?>" class="btn-ver-procesos btn btn-xs btn-default pull-right opcion" title="Ver Procesos"><span class="glyphicon glyphicon-log-in"></span></a>
                    </div>
                    <div class="btn-group pull-right visible-xs visible-sm">
                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-wrench"></span> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a data-id-modulo="<?=$campo->id_modulo?>" class="btn-ver-procesos" href="#"><span class="glyphicon glyphicon-log-in"></span> Procesos</a></li>                            
                            <li><a data-id-modulo="<?=$campo->id_modulo?>" data-modulo='<?=$campo->modulo?>' data-clase="<?=$campo->clase?>" class="btn-edicion" href="#"><span class="glyphicon glyphicon-edit"></span> Editar</a></li>
                            <li><a data-id-modulo='<?=$campo->id_modulo?>' class="btn-eliminar" href="#"><span class="glyphicon glyphicon-trash"></span> Eliminar</a></li>
                        </ul>
                    </div>
	            </td>            
	        </tr>
	    	<?php endforeach ?> 
    	<?php else: ?>
    		<tr>
    			<td colspan="5">No se encontraron resultados.</td>
    		</tr>
    	<?php endif ?>
    </tbody>
</table>
<?php echo $this->pagination->create_links(); ?>