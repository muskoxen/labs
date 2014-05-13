<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/** 
* @author Gerardo Rochín <GerardoRochin@Gmail.Com>
*/
class MY_Controller extends CI_Controller{
	
	private $_login_url	= 'login/';
	private $_login_var	= 'registrado';
	private $_msg_403	= '<strong>Mensaje de error 403</strong>: Acceso Denegado / Prohibido.';
	
	/**
	* Constructor
	*
	* @access public
	* @return void
	*/
	public function __construct(){
		parent::__construct();
		$this->_validar_proceso();
	}
	
	/**
	* Plantilla General
	*
	* @access public
	* @return string
	*/
	public function template($params=NULL){
		$this->load->view('index', $params);
	}
	
	/**
	* json response
	*
	* @access public
	* @return json
	*/
	public function response($response=NULL, $codigo=200){
		$this->output->set_status_header($codigo)->set_content_type('application/json')->set_output(json_encode($response));
	}
	
	/**
	* Validar si el proceso executado es valido para el usuario.
	*
	* @access private
	* @return void
	*/
	private function _validar_proceso(){
		if ($this->session->userdata($this->_login_var) === TRUE){
			if ($this->_permiso_valido() !== TRUE){
				// No tiene permisos para ver el modulo/proceso
				if ( $this->input->is_ajax_request() ) {
					$this->output->set_status_header(403);
					show_error($this->_msg_403, 403);
				}else{
					($this->input->is_ajax_request()) ? $this->output->set_status_header(403) : show_error($this->_msg_403, 403);
				}
			}
		}else{
			// Sesion no iniciada
			($this->input->is_ajax_request()) ? $this->output->set_status_header(401) : redirect(base_url('login'), 'refresh');
		}
	}
	
	/**
	* Buscar Modulo y Metodo en ejecución.
	*
	* @access private
	* @return bool
	*/
	private function _permiso_valido() {
		$this->db->select('modulos.id_modulo, procesos.id_proceso');
		$this->db->from('permisos');
		$this->db->join('usuarios', 'usuarios.id_rol = permisos.id_rol');
		$this->db->join('modulos', 'modulos.id_modulo = permisos.id_modulo'); 
		$this->db->join('procesos', 'procesos.id_proceso = permisos.id_proceso');
		$this->db->where('usuarios.estado', 1); // El usuario tiene que estr activo
		$this->db->where('permisos.id_rol', $this->session->userdata('id_rol')); // Rol de Usuario
		$this->db->where('modulos.clase',  $this->router->fetch_class()); // Modulo en ejecución
		$this->db->where('procesos.proceso', $this->router->fetch_method()); // Proceso  en ejecución
		$this->db->limit(1);
		return ($this->db->get()->num_rows() === 1) ? TRUE : FALSE;
	}

}
/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */