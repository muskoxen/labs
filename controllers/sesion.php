<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sesion extends CI_Controller {

	/**
     * Constructor
     *
     */
	public function __construct(){
		parent::__construct();
		$this->load->model('modelo_usuario','usuarioModelo');
	}

	/**
     * Index
     *
     */
	public function index(){
		redirect(base_url().'login');
	}

	/**
     * Login
     *
     * @access public
     */
	public function login(){
		if ($this->session->userdata('registrado') === TRUE){
			redirect(base_url()); // Redirecciona al controlador principal
		}else{
			$this->load->view('login');
		}
	}
   
   	/**
     * Cerrar la sesión
     *
     * @access public
     */
    public function logout(){
		$this->_destroy_session();
		redirect(base_url('login'));
    }

    /**
     * Levantar sesión
     *
     * @access public
     */
    public function jx_auth(){
		if($this->input->is_ajax_request()){		
			$this->form_validation->set_rules('usuario','Usuario','trim|xss_clean|required');      
			$this->form_validation->set_rules('password','Contrase&ntilde;a','trim|xss_clean|required|sha1');
			if($this->form_validation->run() === TRUE){ 				
				$login = $this->usuarioModelo->busca_usuario();//valida que el usuario exista.
				if ($login->num_rows() == 1){//si es 1, existe usuario.
					$usuario = $login->row(); //obtenemos sus datos
					if ($usuario->password === $this->input->post('password')){ //validamos que la contraseña sea correcta.
						$iniciar = array();  //creamos las variables de session.  
						$roles = $this->usuarioModelo->obtener_permisos($usuario->id_rol);//obtenemos los permisos asignados                                 
						$iniciar = 
							array(
								'id_usuario'	=> $usuario->id_usuario,
								'usuario'		=> $usuario->usuario,
								'id_rol'		=> $usuario->id_rol,
								'nombre'		=> $usuario->nombre,
								'registrado'		=> TRUE
						);
						$this->usuarioModelo->ultimo_acceso($usuario->id_usuario);
						$this->_destroy_session();
						$this->session->set_userdata($iniciar);                 
						$result['exito'] = TRUE;
						$result['redirect'] = base_url();
					}else{ //La contraseña es incorrecta
						$result['exito'] = FALSE;
						$result['aviso'] = 'password';
						$result['msj'] = 'La Contrase&ntilde;a es incorrecta.';
					}
				}else{ //El usuario no existe
					$result['exito'] = FALSE;
					$result['aviso'] = 'usuario';
					$result['msj'] = 'El Usuario no existe.';
				}
			}else{
				$errorMsg = $this->form_validation->_error_array;
				$result['exito'] = FALSE;
				$result['msj'] = current($errorMsg);
			}	
			echo json_encode($result);
		}
    }

    /**
     * Destruir la sesión
     *
     * @access private
     */
	private function _destroy_session(){
		$destroy = 
			array(
				'id_usuario' => NULL,
				'usuario'	=> NULL,
				'id_rol'	=> NULL,
				'nombre'	=> NULL,
				'registrado'	=> FALSE
		);
		$this->session->unset_userdata($destroy);
		$this->session->sess_destroy();
	}
}