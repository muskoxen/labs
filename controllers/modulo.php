<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modulo extends MY_Controller {

    var $filas_paginado = 20;
    var $numero_links = 2;

    /**
     * Constructor
     *
     */
	public function __construct() {
		parent::__construct();
		$this->load->model('modelo_modulo','moduloModelo');
		$this->load->model('modelo_proceso','procesoModelo');
		$this->load->library('pagination');
	}

	/**
     * Index
     *
     */
	public function index(){
		redirect(base_url().'modulo/listado');
	}

	/**
     * Listado de todos los módulos paginado de acuerdo a parametros
     *
     * @access public
     * @return string
     */
	public function listado() {
		$filas = $this->moduloModelo->filas_modulos();
		$this->_paginacion($filas, 'modulo', 'paginacionMod');
		$parametros['modulos'] = $this->moduloModelo->listado_modulos($this->filas_paginado,$this->uri->segment(3));
		$dato['tabla'] = $this->load->view('seguridad/modulos_tabla', $parametros, TRUE);
		$menu['modulo'] = $this->load->view('seguridad/modulos', $dato, TRUE);

		$this->load->view('menus', $menu);
	}

	public function mod(){
		if ( $this->input->is_ajax_request() ) {
			$filas = $this->moduloModelo->filas_modulos();
			$this->_paginacion($filas, 'modulo', 'paginacionMod');
			$parametros['modulos'] = $this->moduloModelo->listado_modulos($this->filas_paginado,$this->uri->segment(3));
			$dato['tabla'] = $this->load->view('seguridad/modulos_tabla', $parametros, TRUE);
			$menu['modulo'] = $this->load->view('seguridad/modulos', $dato);
		}
	}

	/**
     * Metodo para paginar el listado
     *
     * @access public
     * @return string
     */
	public function paginacionMod(){
		if ( $this->input->is_ajax_request() ) {
			if ( $this->input->post('referencia') ) {
				$filas = $this->moduloModelo->filas_modulos_busqueda($this->input->post('referencia'));
		        $this->_paginacion($filas, 'modulo', 'paginacionMod');
				$parametros['modulos'] = $this->moduloModelo->busqueda_modulos($this->input->post('referencia'),$this->filas_paginado,$this->uri->segment(3));
			}else{
				$filas = $this->moduloModelo->filas_modulos();
	            $this->_paginacion($filas, 'modulo', 'paginacionMod');
				$parametros['modulos'] = $this->moduloModelo->listado_modulos($this->filas_paginado,$this->uri->segment(3));
			}
			$this->load->view('seguridad/modulos_tabla', $parametros);
		}
	}

	/**
     * Guardar / Editar un módulo
     *
     * @access public
     * @return json
     */
	public function alta(){
		if ( $this->input->is_ajax_request() ) {
			$this->load->library("form_validation");
			$this->form_validation->set_rules('modulo', 'Módulo', 'trim|xss_clean|required');
			$this->form_validation->set_rules('clase', 'Clase', 'trim|xss_clean|required');
			if ($this->form_validation->run() === FALSE){
				$result['msj'] = $this->form_validation->_error_array;
				$result['exito'] = FALSE;	
			}else{
				$this->moduloModelo->guardar();
				$result['msj'] = "Módulo guardado satisfactoriamente";
				$result['exito'] = TRUE;
			}
			$this->output->set_header('Content-type: application/json');
			echo json_encode($result);
		}		
	}

	/**
     * Eliminar un módulo
     *
     * @access public
     * @return json
     */
	public function eliminar($id_modulo = 0){
		if ( $this->input->is_ajax_request() ) {
			if ($this->moduloModelo->eliminar($id_modulo)){
				$result['msj'] = "Se ha eliminado correctamente el Módulo junto con sus Procesos y Permisos.";
				$result['exito'] = TRUE;
			}else{
				$result['msj'] = "No se pudo eliminar.";
				$result['exito'] = FALSE;
			}
			$this->output->set_header('Content-type: application/json');
			echo json_encode($result);
		}		
	}

	/**
     * Buscador de módulos
     *
     * @access public
     * @return string
     */
	public function busqueda(){
		if ( $this->input->is_ajax_request() ) {
			$filas = $this->moduloModelo->filas_modulos_busqueda($this->input->post('referencia'));
	        $this->_paginacion($filas, 'modulo', 'paginacionMod');
			$parametros['modulos'] = $this->moduloModelo->busqueda_modulos($this->input->post('referencia'),$this->filas_paginado,$this->uri->segment(3));
			$this->load->view('seguridad/modulos_tabla', $parametros);
		}		
	}

	/**
     * Ver el catalogo de procesos de un módulo
     *
     * @access public
     * @param ID del módulo
     * @return string
     */
	public function proceso($id_modulo=NULL){
		if ( $id_modulo == NULL ) {
			show_error('Id inv&aacute;lido', 401);
		}else{
			$info_modulo = $this->moduloModelo->obtener_modulo($id_modulo);
			if ( $info_modulo ) {
				$parametros['idModulo'] = $id_modulo;
				$parametros['modulo'] = $info_modulo;
				$filas = $this->procesoModelo->filas_procesos($id_modulo);
				$this->_paginacion($filas, 'proceso', 'paginacionPro');
				$parametros['procesos'] = $this->procesoModelo->listado_paginado_procesos($id_modulo,$this->filas_paginado,$this->uri->segment(4));
				$dato['tabla'] = $this->load->view('seguridad/procesos_tabla', $parametros, TRUE);
				$this->load->view('seguridad/procesos', $dato);
			}else{
				show_error('M&oacute;dulo no existe.', 401);
			}
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