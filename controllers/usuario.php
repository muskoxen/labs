<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario extends MY_Controller{

	var $filas_paginado = 20;
    var $numero_links = 2;
	var $resultado;
	
	/**
     * Constructor
     *
     */
	public function __construct(){
		parent::__construct();
		$this->resultado = new stdClass();
		$this->load->model('modelo_usuario','usuarioModelo');
		$this->load->model('modelo_rol','rolModelo');		
		$this->load->library('pagination');
	}

	/**
     * Index
     *
     */
	public function index(){
		redirect(base_url().'usuario/listado');
	}

	/**
     * Listado de todos los Usuarios paginado de acuerdo a parametros
     *
     * @access public
     * @return string
     */
	public function listado(){
		$parametros['roles'] = $this->rolModelo->listado();
		$filas = $this->usuarioModelo->filas_usuarios();
		$this->_paginacion($this->filas_paginado,$filas,'usuario','paginacionUsu');
		$parametros['usuarios'] = $this->usuarioModelo->listado_paginado($this->filas_paginado,$this->uri->segment(3));
		$dato['tabla'] = $this->load->view('seguridad/usuarios_tabla', $parametros, TRUE);
		$menu['modulo'] = $this->load->view('seguridad/usuarios', $dato, TRUE);

		$this->load->view('menus', $menu);
	}

	public function mod(){
		if ( $this->input->is_ajax_request() ) {
			$parametros['roles'] = $this->rolModelo->listado();
			$filas = $this->usuarioModelo->filas_usuarios();
			$this->_paginacion($this->filas_paginado,$filas,'usuario','paginacionUsu');
			$parametros['usuarios'] = $this->usuarioModelo->listado_paginado($this->filas_paginado,$this->uri->segment(3));
			$dato['tabla'] = $this->load->view('seguridad/usuarios_tabla', $parametros, TRUE);
			$menu['modulo'] = $this->load->view('seguridad/usuarios', $dato);
		}
	}

	/**
     * Metodo para paginar el listado
     *
     * @access public
     * @return string
     */
	public function paginacionUsu(){
		if ( $this->input->is_ajax_request() ) {
			if ( $this->input->post('referencia') ) {
				$filas = $this->usuarioModelo->filas_usuarios_busqueda($this->input->post('referencia'));
	       		$this->_paginacion($this->filas_paginado,$filas,'usuario','paginacionUsu');
				$parametros['usuarios'] = $this->usuarioModelo->busqueda_usuario($this->input->post('referencia'),$this->filas_paginado,$this->uri->segment(3));
			}else{
				$filas = $this->usuarioModelo->filas_usuarios();
	            $this->_paginacion($this->filas_paginado,$filas,'usuario','paginacionUsu');
				$parametros['usuarios'] = $this->usuarioModelo->listado_paginado($this->filas_paginado,$this->uri->segment(3));
			}
			$this->load->view('seguridad/usuarios_tabla', $parametros);
    	}
	}

	/**
     * Guardar / Editar un Usuario
     *
     * @access public
     * @return json
     */
	public function alta(){
		if ( $this->input->is_ajax_request() ) {
			$this->load->library("form_validation");
			$this->form_validation->set_rules('nombre', 'Nombre', 'trim|xss_clean|required');			
			$this->form_validation->set_rules('correo','Correo','trim|xss_clean|required|valid_email');
			$this->form_validation->set_rules('id_rol', 'Grupo', 'trim|xss_clean|required');
			if ( $this->input->post('modo') == '0' ) {
				$this->form_validation->set_rules('usuario', 'Usuario', 'trim|xss_clean|required|alpha_numeric|min_length[8]|max_length[10]|callback_existe_usuario');
				$this->form_validation->set_rules('password', 'Contrase&ntilde;a', 'trim|xss_clean|required|min_length[8]|max_length[20]|callback_no_permitir_nombre');
				$this->form_validation->set_rules('confirm_password', 'Repetir Contrase&ntilde;a', 'trim|xss_clean|required|matches[password]');
			}
			if ($this->form_validation->run() === FALSE){
				$resultado['msj'] = $this->form_validation->_error_array;
				$resultado['exito'] = FALSE;
			}else{
				$this->usuarioModelo->guardar();
				$resultado['msj'] = 'Usuario guardado satisfactoriamente.';
				$resultado['exito'] = TRUE;
			}
			$this->output->set_header('Content-type: application/json');
			echo json_encode($resultado);
		}
	}

	/**
     * Verificar si un usuario ya existe en los registros
     *
     * @access public
     * @param Nombre de usuario
     * @return bool
     */
	public function existe_usuario($usuario){
		$existe = $this->usuarioModelo->existe_usuario($usuario);
		if($existe === TRUE){
			$this->form_validation->set_message('existe_usuario', 'El Usuario "'.$usuario.'" ya existe, elija otro.');
			return FALSE;
		}else{
		    return TRUE;
		}
	}

	/**
     * Verificar que no usuario y contraseña no sean iguales, al registrar
     *
     * @access public
     * @param Contraseña
     * @return bool
     */
	public function no_permitir_nombre($password){
		$usuario = $this->input->post('usuario');
		if($usuario !== $password){
			return TRUE;
		}else{
			$this->form_validation->set_message('no_permitir_nombre', 'T&uacute; nombre de usuario y contrase&ntilde;a son iguales. Por razones de seguridad no se permite.');
			return FALSE;
		}
	}

	/**
     * Verificar que no exista un correo
     *
     * @access public
     * @return json
     */
	public function comprobar_correo(){
		if ( $this->input->is_ajax_request() ) {	
			if($this->usuarioModelo->existe_correo() === TRUE){
				$result['exito'] = TRUE;
				$result['msj'] = "";
			}else{
				$result['exito'] = FALSE;
				$result['msj'] = "Correo electr&oacute;nico ya est&aacute; en uso.";
			}
			$this->output->set_header('Content-type: application/json');
			echo json_encode($result);
		}		
	}

	/**
     * Eliminar un Usuario
     *
     * @access public
     * @return json
     */
	public function eliminar($id_usuario = 0){
		if ( $this->input->is_ajax_request() ) {
			if ($this->usuarioModelo->eliminar($id_usuario)){
				$resultado['msj'] = "Se ha eliminado correctamente.";
				$resultado['exito'] = TRUE;	
			}else{
				$resultado['msj'] = "No se pudo eliminar.";
				$resultado['exito'] = FALSE;
			}
			$this->output->set_header('Content-type: application/json');
			echo json_encode($resultado);
		}
	}

	/**
     * Activar / Desactivar un Usuario
     *
     * @access public
     * @return json
     */
	public function cambiar_estado(){
		if ( $this->input->is_ajax_request() ) {
			if ($this->usuarioModelo->cambiar_estado()){
				$result['success'] = TRUE;
				if($this->input->post('estado') == "ACTIVO"){
					$result['msj'] = "Se DESACTIVO al usuario.";
					$result['estado'] = TRUE;
				}else{
					$result['msj'] = "Se ACTIVO al usuario.";
					$result['estado'] = FALSE;
				}
			}else{
				$result['msj'] = "No se puedo cambiar el estado";
				$result['success'] = FALSE;	
			}
			$this->output->set_header('Content-type: application/json');
			echo json_encode($result);
		}
	}

	/**
     * Cambiar contraseña de un Usuario y enviar un correo electronico notificandole
     *
     * @access public
     * @return json
     */
	public function cambiarContrasena(){
		if ( $this->input->is_ajax_request() ) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('id_usuario_cp', 'id_usuario', 'trim|xss_clean|required');
			$this->form_validation->set_rules('password_new','Nueva Contraseña','trim|xss_clean|required|min_length[6]|max_length[20]|callback_no_permitir_nombre');			
			$this->form_validation->set_rules('password_confirm', 'Repetir Contraseña', 'trim|xss_clean|required|matches[password_new]');
			if($this->form_validation->run() === FALSE){
				$result['msj'] = $this->form_validation->_error_array;
				$result['exito'] = FALSE;
			}else{
				if ($this->usuarioModelo->cambiar_contrasena(sha1($this->input->post('password_new'))) === TRUE){
					$usuario = $this->input->post('usuario_cp');
					$correo = $this->input->post('correoE_cp');	
					$password = $this->input->post('password_new');	
					$asunto = $this->config->item('nombre')." - Acceso";
					$mensaje = " \n\n";
					$mensaje .= "Su contrase&ntilde;a ha sido modificada: \n\n";
					$mensaje .= "La nueva contrase&ntilde;a es: ".$password."\n";
					$mensaje .= "Para iniciar sesi&oacute;n, visit&eacute; el siguiente enlace:\n";	
					$mensaje .= base_url()."sesion/login\n\n\n";
					$mensaje .= "Para m&aacute;s informaci&oacute;n puede contactar  al administrador\n\n";
					$this->load->library('email');
					$this->email->from("yee.antonio@gmail.com", "Administrador");
					$this->email->to($correo);
					$this->email->subject($asunto);
					$this->email->message($mensaje);
					if (!$this->email->send()) {
						$result['exito'] = FALSE;
						$result['msj'] = $this->email->print_debugger();
					}else{
						$result['exito'] = TRUE;
						$result['msj'] = "Contraseña guardada satisfactoriamente.";
					}
				}else{
					$result['exito'] = FALSE;
					$result['msj'] = "No se pudo cambiar la contraseña.";
				}
			}
			$this->output->set_header('Content-type: application/json');
			echo json_encode($result);
		}
	}

	/**
     * Obtener los datos para editar
     *
     * @access public
     * @return json
     */
	public function edicion(){		
		if( $this->input->is_ajax_request() ){
			$this->load->library("form_validation");
			$this->form_validation->set_rules('id_usuario', 'id_usuario', 'trim|xss_clean|required');
			if ($this->form_validation->run()){
				$result['usuario'] = $this->usuarioModelo->obtener_usuario();
			}
			echo json_encode($result);
		}
	}

	/**
     * Buscador de Usuarios
     *
     * @access public
     * @return string
     */
	public function busqueda(){
		if( $this->input->is_ajax_request() ){
			$filas = $this->usuarioModelo->filas_usuarios_busqueda($this->input->post('referencia'));
	       	$this->_paginacion($this->filas_paginado,$filas,'usuario','paginacionUsu');
			$parametros['usuarios'] = $this->usuarioModelo->busqueda_usuario($this->input->post('referencia'),$this->filas_paginado,$this->uri->segment(3));
			$this->load->view('seguridad/usuarios_tabla', $parametros);
		}
	}
	
	/**
     * Verificar una cadena que solo contenga letras
     *
     * @access public
     * @param Cadena
     * @return bool
     */
	public function soloLetrasEspacios($string){        
		$string = utf8_encode($string);
		if (!(preg_match("/^([a-zA-ZéíóúAÉÍÓÚÑñ ])+$/i", $string))){
			$this->form_validation->set_message('soloLetrasEspacios', 'El %s solo debe contener letras.');
            return FALSE;
        }else{
            return TRUE;
        }
	}

	/**
     * Verificar que coincidan las contraseñas
     *
     * @access public
     * @param Contraseña
     * @return bool
     */
	public function password_old($password_old){
		if (sha1($password_old) === $this->usuarioModelo->obtener_contrasena()){
			return TRUE; 
		}else{
			$this->form_validation->set_message('password_old', 'La Contrase&ntilde;a Actual no es correcta.');
			return FALSE;
		}
	}

	/**
     * Configuración para paginar
     *
     */
	private function _paginacion($total_registros, $controlador, $funcion){
        $configPaginacion = array(
            'base_url' => base_url().''.$controlador.'/'.$funcion,
            'total_rows' =>  $total_registros,
            'per_page' => $this->filas_paginado,
            'num_links' => $this->numero_links,
            'first_link' => '&laquo;',
            'next_link' => '&rsaquo;',
            'prev_link' => '&lsaquo;',
            'last_link' => '&raquo;',
            'full_tag_open' => '<div id="paginacion" class="pull-right"><ul class="pagination pagination-sm">',
            'full_tag_close' => '</ul></div>',
            'first_tag_open' => '<li>',
            'first_tag_close' => '</li>',
            'last_tag_open' => '<li>',
            'last_tag_close' => '</li>',
            'cur_tag_open' => '<li class="active"><a>',
            'cur_tag_close' => '</a></li>',
            'next_tag_open' => '<li>',
            'next_tag_close' => '</li>',
            'prev_tag_open' => '<li>',
            'prev_tag_close' => '</li>',
            'num_tag_open' => '<li>',
            'num_tag_close' => '</li>',
            'page_query_string' => FALSE,
            'query_string_segment' => "per_page",
            'display_pages' => TRUE,
            'anchor_class' => 'class="btn-paginar"');
        $this->pagination->initialize($configPaginacion);
    }
	
}