<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel extends MY_Controller {
    
    /**
     * Constructor
     *
     */
	public function __construct(){
		parent::__construct();
    }
    
    /**
     * Index
     *
     */
	public function index(){
        $params['modulo'] = null; 
		$vista['vista'] = $this->load->view('panel/panel', $params, true);
		$this->load->view('plantilla', $vista);
	}

    public function seguridad(){
        $menu['modulo'] = NULL;
        $params['plantilla'] = $this->load->view('menus', $menu, TRUE);
        $this->load->view('plantilla',$params);
    }

}