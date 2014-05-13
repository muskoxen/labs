<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modelo_proceso extends CI_Model{

	/**
     * Número total de los registros de los procesos
     *
     * @access public
     * @return int
     */
	public function filas_procesos($id_modulo){
        $this->db->select('procesos.id_proceso');
        $this->db->from("procesos");
        $this->db->join("modulos", "modulos.id_modulo = procesos.id_modulo", "inner");
        $this->db->where('procesos.id_modulo', $id_modulo);
        return $this->db->get()->num_rows();
    }

    /**
     * Listado de todos los procesos paginado de acuerdo a parametros
     *
     * @access public
     * @param Numero de filas por pagina, Segmento de la Tabla
     * @return array
     */
    public function listado_paginado_procesos($id_modulo,$numeroFilas,$segmento){	
		$this->db->select("procesos.id_proceso, procesos.proceso, procesos.nombre_proceso, modulos.id_modulo, modulos.modulo");
		$this->db->join("modulos", "modulos.id_modulo = procesos.id_modulo", "inner");
		$this->db->where('procesos.id_modulo', $id_modulo);
		$query = $this->db->get('procesos',$numeroFilas,(($segmento > 0) ? $segmento:0));
        return $query->result();	
	}

	/**
     * Número total de los registros de procesos de acuerdo a la busqueda realizada
     *
     * @access public
     * @param Dato a buscar
     * @return int
     */
	public function filas_procesos_busqueda($referencia,$id_modulo){
        $referencia = $this->db->escape_str($referencia);
		$this->db->select("procesos.id_proceso, procesos.proceso, procesos.nombre_proceso, modulos.id_modulo, modulos.modulo");
		$this->db->from("procesos");
		$this->db->join("modulos", "modulos.id_modulo = procesos.id_modulo", "inner");
		$this->db->where('procesos.id_modulo', $id_modulo);
		$this->db->where("( procesos.proceso LIKE '%$referencia%' OR procesos.nombre_proceso LIKE '%$referencia%' OR modulos.modulo LIKE '%$referencia%' )");
        return $this->db->get()->num_rows();
    }

    /**
     * Listado de todos los procesos paginado de acuerdo a parametros y busqueda realizada
     *
     * @access public
     * @param Dato a buscar, Numero de filas por pagina, Segmento de la Tabla
     * @return array
     */
    public function busqueda_procesos($referencia,$id_modulo,$numeroFilas,$segmento){
		$referencia = $this->db->escape_str($referencia);
		$this->db->select("procesos.id_proceso, procesos.proceso, procesos.nombre_proceso, modulos.id_modulo, modulos.modulo");
		$this->db->join("modulos", "modulos.id_modulo = procesos.id_modulo", "inner");
		$this->db->where('procesos.id_modulo', $id_modulo);
		$this->db->where("( procesos.proceso LIKE '%$referencia%' OR procesos.nombre_proceso LIKE '%$referencia%' OR modulos.modulo LIKE '%$referencia%' )");
		$query = $this->db->get('procesos',$numeroFilas,(($segmento > 0) ? $segmento:0));
        return $query->result();
    }

    /**
     * Guardar / Editar un proceso
     *
     * @access public
     * @return bool
     */
	public function guardar(){
		$data['id_modulo'] = $this->input->post('id_modulo');
		$data['proceso'] = $this->input->post('proceso');
		$data['nombre_proceso'] = $this->input->post('nombre_proceso');
		switch ($this->input->post('modo')){
			case 0:
					return $this->db->insert("procesos", $data);
				break;
			case 1:
					$this->db->limit(1);
					$this->db->where('id_proceso', $this->input->post('id_proceso'));
					return $this->db->update("procesos", $data);			
				break;
		}	
    }

    /**
     * Eliminar definitivamente un proceso
     *
     * @access public
     * @param ID del proceso que se quiere eliminar
     * @return bool
     */
    public function eliminar($id_proceso = NULL){
		$row = $this->db->get_where("procesos", array("id_proceso" => $id_proceso));
		if ($row->result()) {
			//eliminar todos los permisos que tenian asignado ese proceso
			$this->db->where("id_proceso", $id_proceso);
			$this->db->delete("permisos");
			//eliminar el proceso
			$this->db->limit(1);
			$this->db->where("id_proceso", $id_proceso);
			$this->db->delete("procesos");
		    return TRUE;
		}else{
      		return FALSE;
		}		
    }

	/**
	* Obtener un proceso en especifico
	*
	* @access public
	* @return object
	*/
	public function obtener_proceso(){
		$this->db->select("id_proceso, proceso, nombre_proceso");
		$this->db->from("procesos");
		$this->db->where('id_proceso', $this->input->post('id_proceso'));
		$this->db->limit(1);
		return $this->db->get()->row();
	}
	
}