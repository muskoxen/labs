<?php
	if ( $menu ) {
		echo '<h4 class="pull-left">LISTADO DE MÓDULOS</h4><br><br>';
		echo '<ul class="nav nav-tabs">';
		foreach($menu as $indice_modulo => $valores_modulos) {
			foreach($valores_modulos as $nombre_modulo => $valores_procesos) {
				echo '<li>
						<span id="modulo-'.$indice_modulo.'" ></span>
						<a href="#l'.$indice_modulo.'" data-toggle="tab"> '.strtoupper($nombre_modulo).'</a>
					  </li>';
			}
		}
		echo "</ul>";
		echo '<div id="primerDiv" class="tab-content" style="width:auto">';
			foreach($menu as $indice_modulo => $valores_modulos) {
				foreach($valores_modulos as $nombre_modulo => $valores_procesos) {
					echo '<div class="tab-pane" id="l'.$indice_modulo.'"><h3 class="lead" style="text-align:center;">'.strtoupper($nombre_modulo).'</h3>';
					foreach($valores_procesos as $indice_proceso => $valores_permisos) {
						echo '<ul class="list-unstyled">';
						foreach($valores_permisos as $nombre_proceso => $permiso) {
							if( $permiso == 1 ){
								echo '<li id="modo-'.$permiso.'">
										<a href="#" style="text-decoration:none;color:#333" class="modPermiso" rel="'.$indice_modulo.'|'.$indice_proceso.'">
											<span id="proceso-'.$indice_proceso.'">
												<span id="marca-'.$indice_proceso.'">
													<span class="label label-success"><span class="glyphicon glyphicon-ok"></span></span>
												</span> '.$nombre_proceso.'
											</span>
										</a>';
							}else{
								echo '<li id="modo-'.$permiso.'">
										<a href="#" style="text-decoration:none;color:#333" class="modPermiso" rel="'.$indice_modulo.'|'.$indice_proceso.'">
											<span id="proceso-'.$indice_proceso.'">
												<span id="marca-'.$indice_proceso.'">
													<span class="label label-danger"><span class="glyphicon glyphicon-remove"></span></span>
												</span> '.$nombre_proceso.'
											</span>
										</a>';
							}
						}
						echo '</ul>';
					}
					echo "</div>";
				}
			}
		echo '</div>';
	}else{
		echo '<h4 class="lead">No se encontraron resultados.</h4>';
	}
?>