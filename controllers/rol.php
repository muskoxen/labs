<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rol extends MY_Controller {

	var $filas_paginado = 20;
    var $numero_links = 2;

    /**
     * Constructor
     *
     */
	public function __construct() {
		parent::__construct();
		$this->load->model('modelo_rol','rolModelo');
		$this->load->model('modelo_permiso','permisoModelo');
		$this->load->library('pagination');		
	}

	/**
     * Index
     *
     */
	public function index(){
		redirect(base_url().'rol/listado');
	}

	/**
     * Listado de todos los roles paginado de acuerdo a parametros
     *
     * @access public
     * @return string
     */
	public function listado() {
		$filas = $this->rolModelo->filas_roles();
		$this->_paginacion($this->filas_paginado,$filas,'rol','paginacionRol');
		$parametros['roles'] = $this->rolModelo->listado_paginado($this->filas_paginado,$this->uri->segment(3));
		$dato['tabla'] = $this->load->view('seguridad/roles_tabla', $parametros, TRUE);
		$menu['modulo'] = $this->load->view('seguridad/roles', $dato, TRUE);

		$this->load->view('menus', $menu);
	}

	public function mod(){
		if ( $this->input->is_ajax_request() ) {
			$filas = $this->rolModelo->filas_roles();
			$this->_paginacion($this->filas_paginado,$filas,'rol','paginacionRol');
			$parametros['roles'] = $this->rolModelo->listado_paginado($this->filas_paginado,$this->uri->segment(3));
			$dato['tabla'] = $this->load->view('seguridad/roles_tabla', $parametros, TRUE);
			$menu['modulo'] = $this->load->view('seguridad/roles', $dato);
		}
	}

	/**
     * Metodo para paginar el listado
     *
     * @access public
     * @return string
     */
	public function paginacionRol(){
		if ( $this->input->is_ajax_request() ) {
			if ( $this->input->post('referencia') ) {
				$filas = $this->rolModelo->filas_roles_busqueda($this->input->post('referencia'));
		       	$this->_paginacion($this->filas_paginado,$filas,'rol','paginacionRol');
				$parametros['roles'] = $this->rolModelo->busqueda_roles($this->input->post('referencia'),$this->filas_paginado,$this->uri->segment(3));
			}else{
				$filas = $this->rolModelo->filas_roles();
		        $this->_paginacion($this->filas_paginado,$filas,'rol','paginacionRol');
				$parametros['roles'] = $this->rolModelo->listado_paginado($this->filas_paginado,$this->uri->segment(3));
			}
			$this->load->view('seguridad/roles_tabla', $parametros);
		}
	}

	/**
     * Guardar / Editar un rol
     *
     * @access public
     * @return json
     */
	public function alta(){
		if ( $this->input->is_ajax_request() ) {
			$this->load->library("form_validation");
			$this->form_validation->set_rules('rol', 'Rol', 'trim|xss_clean|required');
			$this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|required');
			if ($this->form_validation->run() === FALSE){
				$result['msj'] = $this->form_validation->_error_array;
				$result['exito'] = FALSE;
			}else{
				$this->rolModelo->guardar();
				$result['msj'] = 'Rol guardado satisfactoriamente';
				$result['exito'] = TRUE;
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
		if ( $this->input->is_ajax_request() ) {
			$this->form_validation->set_rules('id_rol', 'id_rol', 'trim|xss_clean|required');
			if ($this->form_validation->run()){
				$result['rol'] = $this->rolModelo->obtener_rol();
				echo json_encode($result);
			}
		}
	}

	/**
     * Eliminar un rol
     *
     * @access public
     * @return json
     */
	public function eliminar($id_rol = 0){
		if ( $this->input->is_ajax_request() ) {
			if ($this->rolModelo->eliminar($id_rol)){
				$result['msj'] = "Se ha eliminado correctamente el Rol junto con sus Permisos.";
				$result['exito'] = TRUE;
			}else{
				$result['exito'] = FALSE;
			}
			$this->output->set_header('Content-type: application/json');
			echo json_encode($result);
		}		
	}

	/**
     * Buscador de Roles
     *
     * @access public
     * @return string
     */
	public function busqueda(){
		if ( $this->input->is_ajax_request() ) {
			$filas = $this->rolModelo->filas_roles_busqueda($this->input->post('referencia'));
	       	$this->_paginacion($this->filas_paginado,$filas,'rol','paginacionRol');
			$parametros['roles'] = $this->rolModelo->busqueda_roles($this->input->post('referencia'),$this->filas_paginado,$this->uri->segment(3));	
			$this->load->view('seguridad/roles_tabla', $parametros);
		}
	}

	/**
     * Catalogo de Modulos-Procesos para Asignar / Quitar permisos a un rol
     *
     * @access public
     * @param ID del rol
     * @return string
     */
	public function permisos($id_rol = NULL){
		if ( $id_rol == NULL ) {
			show_error('Id inv&aacute;lido', 401);
		}else{
			if ( $this->permisoModelo->buscar_rol($id_rol)->num_rows() == 1 ) {
				$data['menu'] = $this->permisoModelo->permisos($id_rol);
				$data['nombre_rol'] = $this->rolModelo->obtener_nombre_rol($id_rol)->nombre_rol;
				$data['id_rol'] = $id_rol;
				$info['tabla'] = $this->load->view('seguridad/permisos_tabla', $data, TRUE);
				$this->load->view('seguridad/permisos', $info);
			}else{
				show_error('Rol no existe', 401);
			}
		}
	}

	/**
     * Buscador para Asignar / Quitar permisos a un rol
     *
     * @access public
     * @return string
     */
	public function busqueda_permisos(){
		if ( $this->input->is_ajax_request() ) {
			$data['menu'] = $this->permisoModelo->busqueda_permisos($this->input->post('idRol'),$this->input->post('referencia'));
			$this->load->view('seguridad/permisos_tabla', $data);
		}
	}

	/**
     * Asignar / Quitar un permiso a un rol
     *
     * @access public
     * @return json
     */
	public function cambiar_permiso(){
		if ( $this->input->is_ajax_request() ) {
			$bandera = FALSE;
            $permiso = $this->permisoModelo->buscar_permiso();
            if ($permiso->num_rows() == 1){
				$bandera = TRUE;
				if ($this->permisoModelo->cambiar_permiso($bandera)){
					$result['msg'] = "editado";
					$result['modo'] = "<span class='label label-danger'><span class='glyphicon glyphicon-remove'></span></span>";
					$result['success'] = TRUE;
				}else{
					$result['msg'] = "No se puede cambiar el permiso";
					$result['success'] = FALSE;	
				}
			}else{  
				$bandera = FALSE;
				if ($this->permisoModelo->cambiar_permiso($bandera)){
					$result['msg'] = "creado";
					$result['modo'] = "<span class='label label-success'><span class='glyphicon glyphicon-ok'></span></span>";
					$result['success'] = TRUE;
				}else{
					$result['msg'] = "No se puede cambiar el permiso";
					$result['success'] = FALSE;
				}
			}
			$this->output->set_header('Content-type: application/json');
			echo json_encode($result);
		}		
	}	

	/**
     * Activar / Desactivar un rol
     *
     * @access public
     * @return json
     */
	public function cambiar_estado(){
		if ( $this->input->is_ajax_request() ) {
			if ($this->rolModelo->cambiar_estado()){
				$result['success'] = TRUE;
				if ( $this->input->post('estado') == "ACTIVO" ) {
					$result['msj'] = "Se DESACTIVO al Rol junto con sus Usuarios";
					$result['estado'] = TRUE;
				}else{
					$result['msj'] = "Se ACTIVO al Rol junto con sus Usuarios.";
					$result['estado'] = FALSE;
				}
			}else{
				$result['msg'] = "No se puedo cambiar el estado";
				$result['success'] = FALSE;	
			}
			$this->output->set_header('Content-type: application/json');
			echo json_encode($result);
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