<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modelo_modulo extends CI_Model{

	/**
     * Número total de los registros de los módulos
     *
     * @access public
     * @return int
     */
	public function filas_modulos(){
		$this->db->select('id_modulo');
		$this->db->from("modulos");
		return $this->db->get()->num_rows();	
	}
	
	/**
     * Listado de todos los módulos paginado de acuerdo a parametros
     *
     * @access public
     * @param Numero de filas por pagina, Segmento de la Tabla
     * @return array
     */
	public function listado_modulos($numeroFilas, $segmento){
		$this->db->select('id_modulo, id_modulo AS idModulo, modulo, clase, (SELECT COUNT(id_proceso) FROM procesos WHERE id_modulo = idModulo)  AS numero_procesos',FALSE);
		$query = $this->db->get('modulos',$numeroFilas,(($segmento > 0) ? $segmento:0));
        return $query->result();;	
	}

	/**
     * Guardar / Editar un módulo
     *
     * @access public
     * @return bool
     */
	public function guardar(){
		$data['modulo'] = $this->input->post('modulo');
        $data['clase'] = $this->input->post('clase');
		switch ($this->input->post('modo')){
			case 0: 
					return $this->db->insert("modulos", $data); 
				break;
			case 1:
					$this->db->limit(1);
					$this->db->where('id_modulo', $this->input->post('id_modulo'));
					return $this->db->update("modulos", $data);
				break;
		}	
    }

    /**
     * Eliminar definitivamente un módulo
     *
     * @access public
     * @param ID del módulo que se quiere eliminar
     * @return bool
     */
    public function eliminar($id_modulo = NULL){
		$row = $this->db->get_where("modulos", array("id_modulo" => $id_modulo));
		if ($row->result()) {
            // Eliminar todos los permisos que pertenecen al módulo
            $this->db->where("id_modulo", $id_modulo);
            $this->db->delete("permisos");
            // Eliminar todos los procesos que pertenecen al módulo
            $this->db->where("id_modulo", $id_modulo);
            $this->db->delete("procesos");
            // Eliminar el módulo
			$this->db->limit(1);
			$this->db->where("id_modulo", $id_modulo);
			$this->db->delete("modulos");
		    return TRUE;
		}else{
      		return FALSE;
		}		
    }
 
 	/**
     * Número total de los registros de módulos de acuerdo a la busqueda realizada
     *
     * @access public
     * @param Dato a buscar
     * @return int
     */
    public function filas_modulos_busqueda($referencia){
        $referencia = $this->db->escape_str($referencia);
		$this->db->select('id_modulo');
		$this->db->from("modulos");
		$this->db->like('modulo',$referencia);
        return $this->db->get()->num_rows();
    }

    /**
     * Listado de todos los módulos paginado de acuerdo a parametros y busqueda realizada
     *
     * @access public
     * @param Dato a buscar, Numero de filas por pagina, Segmento de la Tabla
     * @return array
     */
    public function busqueda_modulos($referencia,$numeroFilas,$segmento){
    	$referencia = $this->db->escape_str($referencia);
		$this->db->select('id_modulo, id_modulo AS idModulo, modulo, clase, (SELECT COUNT(id_proceso) FROM procesos WHERE id_modulo = idModulo)  AS numero_procesos',FALSE);
		$this->db->like('modulo',$referencia);
		$query = $this->db->get('modulos',$numeroFilas,(($segmento > 0) ? $segmento:0));
        return $query->result();
    }

    /**
     * Obtener un módulo en especifico
     *
     * @access public
     * @param ID del módulo
     * @return array
     */
	public function obtener_modulo($id_modulo){
		$this->db->select("id_modulo, modulo AS nombre_modulo");
		$this->db->from("modulos");
		$this->db->where('id_modulo', $id_modulo);
		$this->db->limit(1);
		return $this->db->get()->result();
	}

}