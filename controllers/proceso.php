<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proceso extends MY_Controller {

	var $filas_paginado = 20;
    var $numero_links = 2;
    
    /**
     * Constructor
     *
     */
	public function __construct() {
		parent::__construct();
		$this->load->model('modelo_proceso','procesoModelo');
		$this->load->library('pagination');
	}

	/**
     * Metodo para paginar el listado
     *
     * @access public
     * @return string
     */
	public function paginacionPro(){
		if ( $this->input->is_ajax_request() ) {
			if ( $this->input->post('referencia') ) {
				$filas = $this->procesoModelo->filas_procesos_busqueda($this->input->post('referencia'),$this->input->post('idModulo'));
		        $this->_paginacion($this->filas_paginado,$filas,'proceso','paginacionPro');
				$parametros['procesos'] = $this->procesoModelo->busqueda_procesos($this->input->post('referencia'),$this->input->post('idModulo'),$this->filas_paginado,$this->uri->segment(3));
			}else{
				$filas = $this->procesoModelo->filas_procesos($this->input->post('idModulo'));
				$this->_paginacion($this->filas_paginado,$filas,'proceso','paginacionPro');
				$parametros['procesos'] = $this->procesoModelo->listado_paginado_procesos($this->input->post('idModulo'),$this->filas_paginado,$this->uri->segment(3));
			}			
			$this->load->view('seguridad/procesos_tabla', $parametros);
		}		
	}

	/**
     * Buscador de procesos
     *
     * @access public
     * @return string
     */
	public function busqueda(){
		if( $this->input->is_ajax_request() ) {
			$filas = $this->procesoModelo->filas_procesos_busqueda($this->input->post('referencia'),$this->input->post('idModulo'));
	        $this->_paginacion($this->filas_paginado,$filas,'proceso','paginacionPro');
			$parametros['procesos'] = $this->procesoModelo->busqueda_procesos($this->input->post('referencia'),$this->input->post('idModulo'),$this->filas_paginado,$this->uri->segment(3));
			$this->load->view('seguridad/procesos_tabla', $parametros);
		}
	}

	/**
     * Guardar / Editar un proceso
     *
     * @access public
     * @return json
     */
	public function alta(){
		if ( $this->input->is_ajax_request() ) {
			$this->load->library("form_validation");
			$this->form_validation->set_rules('nombre_proceso', 'Nombre del Proceso', 'trim|xss_clean|required');
			$this->form_validation->set_rules('proceso', 'Proceso', 'trim|xss_clean|required|callback_soloMinusculasGuion');
			if ($this->form_validation->run() === FALSE){
				$result['msj'] = $this->form_validation->_error_array;
				$result['exito'] = FALSE;
			}else{
				$this->procesoModelo->guardar();
				$result['msj'] = "Proceso guardado satisfactoriamente";
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
			$this->form_validation->set_rules('id_proceso', 'id_proceso', 'trim|xss_clean|required');
			if ($this->form_validation->run()) {
				$result['proceso'] = $this->procesoModelo->obtener_proceso();
				echo json_encode($result);
			}
		}		
	}

	/**
     * Eliminar un proceso
     *
     * @access public
     * @return json
     */
	public function eliminar($id_proceso = 0){
		if ( $this->input->is_ajax_request() ) {
			if ($this->procesoModelo->eliminar($id_proceso)){
				$result['msj'] = "Se ha eliminado correctamente el Proceso junto con sus Permisos.";
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
     * Validar que una proceso, solo sea minusculas y guiones bajos
     *
     */
	public function soloMinusculasGuion($string){
		if (!(preg_match("/^([a-z_])+$/i", $string))){
			$this->form_validation->set_message('soloMinusculasGuion', 'El %s solo debe contener letras y guion bajo.');
            return FALSE;
        }else{
            return TRUE;
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