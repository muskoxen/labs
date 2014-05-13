<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modelo_rol extends CI_Model{

	/**
     * Número total de los registros de roles
     *
     * @access public
     * @return int
     */
	public function filas_roles(){
        $this->db->select('id_rol');
        $this->db->from("roles");
        return $this->db->get()->num_rows();
    }

    /**
     * Listado de todos los roles paginado de acuerdo a parametros
     *
     * @access public
     * @param Numero de filas por pagina, Segmento de la Tabla
     * @return array
     */
   	public function listado_paginado($numeroFilas,$segmento){	
		$this->db->select('id_rol, rol, descripcion, estado, (SELECT COUNT(id_usuario) FROM usuarios WHERE usuarios.id_rol = roles.id_rol) AS usuarios',FALSE);
		$query = $this->db->get('roles',$numeroFilas,(($segmento > 0) ? $segmento:0));
        return $query->result();
	}

	/**
     * Listado del catalogo de roles
     *
     * @access public
     * @return array
     */
   	public function listado(){	
		$this->db->select('id_rol,rol');
		$this->db->from("roles");
		$this->db->order_by("id_rol","ASC");
		return $this->db->get()->result();	
	}

	/**
     * Guardar / Editar un rol
     *
     * @access public
     * @return bool
     */
	public function guardar(){
		$data['rol'] = $this->input->post('rol');
		$data['descripcion'] = $this->input->post('descripcion');
		switch ($this->input->post('modo')){
			case 0: 
					return $this->db->insert("roles", $data); 
				break;
			case 1:
					$this->db->limit(1);
					$this->db->where('id_rol', $this->input->post('id_rol'));
					return $this->db->update("roles", $data);			
				break;
		}	
    }

    /**
     * Eliminar definitivamente un rol
     *
     * @access public
     * @param ID del rol que se quiere eliminar
     * @return bool
     */
    public function eliminar($id_rol = NULL){
		$row = $this->db->get_where("roles", array("id_rol" => $id_rol));
		if ($row->result()) {
			// Eliminar todos los permisos que tiene asignado ese rol
            $this->db->where("id_rol", $id_rol);
            $this->db->delete("permisos");
            // Eliminar el rol
			$this->db->limit(1);
			$this->db->where("id_rol", $id_rol);
			$this->db->delete("roles");
		    return TRUE;
		}else{
      		return FALSE;
		}		
    }
  
  	/**
     * Número total de los registros de roles de acuerdo a la busqueda realizada
     *
     * @access public
     * @param Dato a buscar
     * @return int
     */
    public function filas_roles_busqueda($referencia){
        $referencia = $this->db->escape_str($referencia);
		$this->db->select('id_rol');
		$this->db->from("roles");
		$this->db->like('rol',$referencia);
        return $this->db->get()->num_rows();
    }

    /**
     * Listado de todos los roles paginado de acuerdo a parametros y busqueda realizada
     *
     * @access public
     * @param Dato a buscar, Numero de filas por pagina, Segmento de la Tabla
     * @return array
     */
    public function busqueda_roles($referencia,$numeroFilas,$segmento){
		$referencia = $this->db->escape_str($referencia);
		$this->db->select('id_rol, rol, descripcion, estado, (SELECT COUNT(id_usuario) FROM usuarios WHERE usuarios.id_rol = roles.id_rol) AS usuarios',FALSE);
		$this->db->like('rol',$referencia);
		$query = $this->db->get('roles',$numeroFilas,(($segmento > 0) ? $segmento:0));
        return $query->result();
    }

    /**
	* Obtener un rol en especifico
	*
	* @access public
	* @param ID rol
	* @return object
	*/
	public function obtener_rol($id_rol = NULL){
		if($id_rol != NULL){
			$this->db->select("id_rol, rol AS nombre_rol");
			$this->db->from("roles");
			$this->db->where('id_rol', $id_rol);
			$this->db->limit(1);
			return $this->db->get()->result();
		}else{
			$this->db->select("id_rol, rol, descripcion");
			$this->db->from("roles");
			$this->db->where('id_rol', $this->input->post('id_rol'));
			$this->db->limit(1);
			return $this->db->get()->row();
		}	
	}

	/**
	* Obtener el nombre de un rol en especifico
	*
	* @access public
	* @param ID rol
	* @return object
	*/
	public function obtener_nombre_rol($id_rol = NULL){
		$this->db->select("rol AS nombre_rol");
		$this->db->from("roles");
		$this->db->where('id_rol', $id_rol);
		$this->db->limit(1);
		return $this->db->get()->row();
	}

	/**
     * Cambiar el estado de un rol
     *
     * @access public
     * @return bool
     */
    public function cambiar_estado(){	
		$data = array();
		if($this->input->post('estado') == "INACTIVO"){
			$data['estado'] = "activo";
			//activar todos los usuarios asignados a ese rol
			$usuario['estado'] = "ACTIVO";
        	$this->db->update("usuarios", $data ,array('id_rol' => $this->input->post('id_rol')));
		}else{
			$data['estado'] = "inactivo";
			//desactivar todos los usuarios asignados a ese rol
			$usuario['estado'] = "INACTIVO";
        	$this->db->update("usuarios", $data ,array('id_rol' => $this->input->post('id_rol')));
		}
		$this->db->limit(1);
		return $this->db->update("roles", $data ,array('id_rol' => $this->input->post('id_rol')));
    }

}