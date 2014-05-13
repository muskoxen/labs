<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modelo_permiso extends CI_Model{

	/**
     * Lista de Módulos y Procesos, asignados o no
     *
     * @access public
     * @param Rol
     * @return array
     */
	public function permisos($id_rol){		
		$modulos = array();
		$query = $this->db->query("
			SELECT modulos.id_modulo, modulos.modulo, procesos.id_proceso, procesos.nombre_proceso,
					(SELECT COUNT(*) FROM permisos WHERE permisos.id_rol = ".$id_rol." 
														AND permisos.id_modulo = modulos.id_modulo 
														AND permisos.id_proceso = procesos.id_proceso) AS permiso
			FROM modulos
			INNER JOIN procesos ON procesos.id_modulo = modulos.id_modulo
			WHERE modulos.id_modulo
			ORDER BY modulos.id_modulo, modulos.modulo, procesos.id_proceso");		
		foreach ($query->result() as $row){
			$modulos[$row->id_modulo][$row->modulo][$row->id_proceso][$row->nombre_proceso] = $row->permiso;
		}
		return $modulos;
	}

	/**
     * Lista de Módulos y Procesos, asignados o no, de acuerdo a la busqueda realizada
     *
     * @access public
     * @param Rol
     * @return array
     */
	public function busqueda_permisos($id_rol,$referencia) {
		$modulos = array();
		$query = $this->db->query("
			SELECT modulos.id_modulo, modulos.modulo, procesos.id_proceso, procesos.nombre_proceso,
					(SELECT COUNT(*) FROM permisos WHERE permisos.id_rol = ".$id_rol." 
														AND permisos.id_modulo = modulos.id_modulo 
														AND permisos.id_proceso = procesos.id_proceso) AS permiso
			FROM modulos
			INNER JOIN procesos ON procesos.id_modulo = modulos.id_modulo
			WHERE modulos.id_modulo NOT IN (7) AND modulos.modulo LIKE '%".$this->db->escape_str($referencia)."%'
				ORDER BY modulos.id_modulo, modulos.modulo, procesos.id_proceso");
		foreach ($query->result() as $row){
			$modulos[$row->id_modulo][$row->modulo][$row->id_proceso][$row->nombre_proceso] = $row->permiso;
		}
		return $modulos;
	}

	/**
     * Buscar permiso de acuerdo al rol deseado, para asignalo o quitarlo
     *
     * @access public
     * @return array
     */
	public function buscar_permiso(){
		$this->db->select("id_rol, id_modulo, id_proceso");
		$this->db->from('permisos');
		$this->db->where("id_rol", $this->input->post('id_rol'));
		$this->db->where("id_modulo", $this->input->post('id_modulo'));
		$this->db->where("id_proceso", $this->input->post('id_proceso'));
		$this->db->limit(1);
		return $this->db->get();
	}

	/**
     * Asignar o Quitar el permisos del proceso, módulo y rol deseado, dependiende si esta o no asignado
     *
     * @access public
     * @param Bandera
     * @return bool
     */
    public function cambiar_permiso($bandera = NULL){	
		$data = array();
		if ($bandera === TRUE){
			//cambia el permiso.
			$this->db->limit(1);
			$this->db->where("id_rol", $this->input->post('id_rol'));
			$this->db->where("id_modulo", $this->input->post('id_modulo'));
			$this->db->where("id_proceso", $this->input->post('id_proceso'));
			return $this->db->delete("permisos", $data);	
		}else{
			//crea el permiso.
			$data['id_rol'] = $this->input->post('id_rol');
			$data['id_modulo'] = $this->input->post('id_modulo');
			$data['id_proceso'] = $this->input->post('id_proceso');
			$this->db->limit(1);
			return $this->db->insert("permisos", $data);	
		}	
    }

    /**
     * Buscar un rol para asignarle permisos mientras este exista
     *
     * @access public
     * @param Rol
     * @return array
     */
	public function buscar_rol($id_rol){
		$this->db->select("id_rol");
		$this->db->from('roles');
		$this->db->where('estado', 'activo');
		$this->db->where("id_rol", $id_rol);
		$this->db->limit(1);
		return $this->db->get();
	}

}