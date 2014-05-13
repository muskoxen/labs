<?php if (! defined('BASEPATH')) exit('No direct script access');
/** 
 * @package Generico
 * @subpackage Generico 
 * @author Gerardo Rochín
 * @since 1.0
 */ 
class Acl
{
	var $CI;
	var $login_url	= 'login';
	var $login_var	= 'logged';
	var $acl 		= TRUE; // (TRUE): Valida que la sesion sea correcta y que los permisos seteados sea el correcto para el modulo, (FALSE): Valida solo que la sesión sea correcta
	var $permisos;
	var $check;
	var $modulo;

	function __construct()
	{
		$CI =& get_instance();
		$CI->load->library('session');
		$CI->load->helper('url');
		($CI->session->userdata('roles')) ? $this->permisos = unserialize(gzuncompress(base64_decode($CI->session->userdata('roles')))) : NULL;
	}

	/**
	* Arreglo de permisos
	*
	* @access public
	* @return array
	*/
	public function lista_permisos()
	{
		return $this->permisos;
	}

	/**
	* Redirecciona del formulario al controlador principal si la sesión existe
	*
	* @access public
	* @return void
	*/
	public function login()
	{
		$CI =& get_instance();
		if ($CI->session->userdata($this->login_var) === TRUE)
		{
			redirect(base_url()); // Redirecciona al controlador principal
		}
	}

	/**
	 * valida en un controlador si la sesión es valida
	 *
	 * @access public
	 * @param perms permisos de usuario
	 * @return void
	 */
	public function modulo($modulo=0,$proc=0)
	{
		
		$CI =& get_instance();
		$this->modulo = $modulo;		
		if ($CI->session->userdata($this->login_var) === TRUE)
		{
			if ($this->acl === TRUE) // Modo permisos por catalogo
			{
				if(!$this->_permiso_valido($modulo,$proc))
				{
					if ( isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) // peticion es ajax
					{
						log_message('error', $_SERVER['REMOTE_ADDR'].': intento ingresar sin tener permiso. { Modulo ID: ('.$modulo.') } , { Proceso ID: ('.$proc.') }');	
						$CI->output->set_status_header('401');
						exit;
					}
					else // peticion normal
					{
						show_error('Sin Permisos de Acceso<script></script>',401);
						log_message('error', $_SERVER['REMOTE_ADDR'].': intento ingresar sin tener permiso. { Modulo ID: ('.$modulo.') } , { Proceso ID: ('.$proc.') }');	
						exit;
					}
				}
			}
			else // Modo simple
			{
				exit;
			}
		}
		else
		{ 
			if ( isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') // peticion es ajax
			{
				$CI->output->set_status_header('401');
				log_message('error', $_SERVER['REMOTE_ADDR'].': intento ingresar sin tener una sesión iniciada');	
				exit;
			}
			else // peticion normal
			{
				$this->logout();
				log_message('error', $_SERVER['REMOTE_ADDR'].': intento ingresar sin tener una sesión iniciada');	
				exit;
			}
		}
	}
	
	/**
	* Permiso Para Proceso
	*
	* @access public
	* @return void
	*/
	public function proceso($id_proceso=0)
	{
		foreach ($this->check as $key => $campo)
		{
			if ($campo->id_modulo == $this->modulo && $campo->id_proceso == $id_proceso)
			{
				return TRUE;
			}
		}
		//return in_array($id_proceso,$this->check[$this->modulo]);
	}

	/**
	* Valida Permiso para el proceso del modulo indicado
	*
	* @access private
	* @param param
	* @return void
	*/
	public function _proceso_valido()
	{
		return true;
	}

	/**
	* Valida que el permiso de la sesión sea el mismo en la base de datos
	*
	* @access private
	* @param param
	* @return bool
	*/
	private function _permiso_valido($modulo,$proc)
	{
		$CI =& get_instance();
		$CI->load->model('modelo_usuario','usuarios');
		$query = $CI->usuarios->usuario_valido($modulo);
		$this->check = $query;
		if ($query->num_rows() >= 1)
		{
			//$this->_guarda_permisos($query);
			$this->check = $query->result();
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	/**
	* Guarda en variable global los permisos
	*
	* @access private
	* @param object $query
	* @return void
	*/
	private function _guarda_permisos($query)
	{
		$CI =& get_instance();
		
		$modulos = array();
		$procesos = array();
		$modulo_ac = NULL;
		$CI->benchmark->mark('a');
		foreach($query->result() as $key => $permiso)
		{
			if ($modulo_ac != $permiso->id_modulo)
			{
				$modulo_ac = $permiso->id_modulo;
				$procesos = array();
			}
			array_push($procesos,$permiso->id_proceso);
			$modulos[$permiso->id_modulo] = $procesos;
		}
		$this->check = $modulos;
	}

	/**
	* Regresa si esta activa la opción de manejar permisos por listas de control
	*
	* @access public
	* @return bool
	*/
	public function permisos()
	{
		return $this->acl;
	}

	/**
	* Destruye la sesión
	*
	* @access public
	* @return void
	*/
	public function logout()
	{
		redirect(base_url().$this->login_url);
	}
}
/* End of file acl.php */
/* Location: ./libraries/acl.php */