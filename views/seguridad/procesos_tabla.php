<script type="text/javascript">
$(function() {
	$('.opcion').tooltip();
});
</script>
<table class="table table-striped table-hover table-condensed">
    <thead>
        <tr>
        	<th width="3%">ID</th>
            <th width="45%">NOMBRE DEL PROCESO</th>
            <th width="42%">PROCESO</th>                
            <th width="10%"></th>
        </tr>
    </thead>
    <tbody>
    	<?php if ($procesos): ?>
    		<?php foreach ($procesos as $key => $campo): ?>
	        	<tr>
		        	<td><?=$campo->id_proceso?></td>
		            <td><?=$campo->nombre_proceso?></td>
		            <td><?=$campo->proceso?></td>
		            <td>
		            	<div class="hidden-xs hidden-sm">
							<span class="pull-right">&nbsp;</span>
							<a data-id-proceso='<?=$campo->id_proceso?>' class="btn-eliminar btn btn-xs btn-danger pull-right opcion" title="Eliminar"><span class="glyphicon glyphicon-trash"></span></a>
							<span class="pull-right">&nbsp;</span>
							<a data-id-proceso="<?=$campo->id_proceso?>" data-id-modulo="<?=$campo->id_modulo?>" data-nombre-proceso="<?=$campo->nombre_proceso?>" data-proceso="<?=$campo->proceso?>" class="btn-edicion btn btn-xs btn-info pull-right opcion" title="Editar"><span class="glyphicon glyphicon-edit"></span></a>
		            	</div>
						<div class="btn-group pull-right visible-xs visible-sm">
							<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
								<span class="glyphicon glyphicon-wrench"></span> <span class="caret"></span>
							</button>
							<ul class="dropdown-menu" role="menu">                          
								<li><a data-id-proceso="<?=$campo->id_proceso?>" data-id-modulo="<?=$campo->id_modulo?>" data-nombre-proceso="<?=$campo->nombre_proceso?>" data-proceso="<?=$campo->proceso?>" class="btn-edicion" href="#"><span class="glyphicon glyphicon-edit"></span> Editar</a></li>
								<li><a data-id-proceso='<?=$campo->id_proceso?>' class="btn-eliminar" href="#"><span class="glyphicon glyphicon-trash"></span> Eliminar</a></li>
							</ul>
						</div>
		            </td>
		        </tr>
	        <?php endforeach ?>
    	<?php else: ?>
    			<tr>
	    			<td colspan="4">No se encontraron resultados.</td>
	    		</tr>
    	<?php endif ?>   
    </tbody>
</table>
<?php echo $this->pagination->create_links(); ?>