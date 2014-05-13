<?php 

Class Pacientes extends MY_Controller {

	var $filas_paginado = 5;
	var $numero_links = 2;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('modelo_pacientes', 'pacientes');
		$this->load->library('pagination');
	}

	function index() 
	{
		$this->pacientes();
	}

	/**
	* Listado de los pacientes
	*
	* @access public
	*/
	public function pacientes()
	{
		$filas = $this->pacientes->filas();
		$this->_paginacion($filas,'pacientes','paginacionPac');
		$params['pacientes'] = $this->pacientes->listado($this->filas_paginado,$this->uri->segment(3));
		$params['estados'] = $this->pacientes->estados();
		$tabla['tabla'] = $this->load->view('pacientes/pacientes_tabla', $params, TRUE);
		$vista['vista'] = $this->load->view('pacientes/pacientes', $tabla, TRUE);

		$this->load->view('plantilla', $vista);
	}

	/**
	* Obtener los municipios retorna un json
	*
	* @access public
	*/
	public function municipios()
	{
		$municipios = $this->pacientes->municipios();
		echo json_encode($municipios);
	}

	/**
    * Agregar un paciente
    *
    * @access public
    */
    public function nuevo()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|xss_clean');
        $this->form_validation->set_rules('apellidop', 'Apellido Paterno', 'trim|required|xss_clean');
        $this->form_validation->set_rules('apellidom', 'Apellido Materno', 'trim|xss_clean');
        $this->form_validation->set_rules('telefono', 'Telefono', 'trim|required|xss_clean');
        $this->form_validation->set_rules('correo', 'Correo', 'trim|xss_clean|email');
        $this->form_validation->set_rules('fechaNacimiento', 'Fecha de Nacimiento', 'trim|required|xss_clean');
        $this->form_validation->set_rules('calle', 'Calle', 'trim|required|xss_clean');
        $this->form_validation->set_rules('colonia', 'Colonia', 'trim|required|xss_clean');
        $this->form_validation->set_rules('numero', 'Número', 'trim|xss_clean');
        $this->form_validation->set_rules('codigoPostal', 'Codigo Postal', 'trim|xss_clean');
        $this->form_validation->set_rules('municipio', 'Municipio', 'trim|required|xss_clean');
        if($this->form_validation->run() == false) {
        	$resultado['exito'] = false;
        	$resultado['msj'] = $this->form_validation->_error_array;
        }
        else {
        	$guardar = $this->pacientes->nuevo();
        	$resultado['exito'] = true;
        	$resultado['msj']  = 'Paciente agregado con exito';
        }

        echo json_encode($resultado);
    }

    /**
    * Buscar pacientes
    *
    * @access public
    */
    public function ultimo_registro()
    {
    	
		$filas = 1;
		$this->_paginacion($this->filas_paginado,$filas,'pacientes','paginacionPac');
		$params['pacientes'] = $this->pacientes->ultimo_registro();
		$tabla['tabla'] = $this->load->view('pacientes/pacientes_tabla', $params, TRUE);
		$vista = $this->load->view('pacientes/pacientes', $tabla, TRUE);
		echo json_encode($vista);
    }

    /**
     * Metodo para paginar el listado
     *
     * @access public
     * @return string
     */
	public function paginacionPac(){
		if ( $this->input->is_ajax_request() ) {
			if ( $this->input->post('referencia') ) {
				$filas = $this->usuarioModelo->filas_usuarios_busqueda($this->input->post('referencia'));
	       		$this->_paginacion($filas,'usuario','paginacionUsu');
				$parametros['usuarios'] = $this->usuarioModelo->busqueda_usuario($this->input->post('referencia'),$this->filas_paginado,$this->uri->segment(3));
			}else{
				$filas = $this->pacientes->filas();
	            $this->_paginacion($filas,'pacientes','paginacionPac');
				$params['pacientes'] = $this->pacientes->listado($this->filas_paginado,$this->uri->segment(3));
			}
			$this->load->view('pacientes/pacientes_tabla', $params);
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