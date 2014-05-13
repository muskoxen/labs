<?php 

Class Modelo_pacientes extends CI_Model {

	public function filas() 
	{
		$rows = $this->db->select('pacienteId')->get('pacientes')->num_rows();

		return $rows;
	}

	public function listado($fila, $segmento)
	{
		$pacientes = $this->db->select('pacienteId, nombre, apellidop, apellidom, sexo, fechaNacimiento, calle, colonia, numero, codigoPostal')
							  ->get('pacientes', $fila, (($segmento > 0) ? $segmento:0))
							  ->result();
		return $pacientes;
	}

	public function ultimo_registro()
	{
		$paciente = $this->db->select('pacienteId, nombre, apellidop, apellidom, sexo, fechaNacimiento, calle, colonia, numero, codigoPostal')
							 ->from('pacientes')
							 ->order_by('pacienteId', 'DESC')
							 ->limit(1)
							 ->get()
							 ->result();
		return $paciente;
	}

	public function estados()
	{
		$estados = $this->db->select('estadoId, estado')->from('estados')->get()->result();
		return $estados;
	}

	public function municipios()
	{
		$municipios = $this->db->select('municipioId, municipio')
							   ->where('estadoId', $this->input->get('estadoId'))
							   ->from('municipios')
							   ->get()
							   ->result();
		return $municipios;
	}

	public function nuevo()
	{
		$nuevo = array(
			'municipioId' => $this->input->post('municipio'),
			'nombre' 	=> $this->input->post('nombre'),
			'apellidop'	=> $this->input->post('apellidop'),
			'apellidom' => $this->input->post('apellidom'),
			'colonia'	=> $this->input->post('colonia'),
			'calle'		=> $this->input->post('calle'),
			'numero'	=> $this->input->post('numero'),
			'telefono'	=> $this->input->post('telefono'),
			'email'		=> $this->input->post('correo'),
			'sexo'		=> $this->input->post('sexos'),
			'fechaNacimiento' 	=> $this->input->post('fechaNacimiento'),
			'codigoPostal'		=> $this->input->post('codigoPostal'),
			'medios'			=> $this->input->post('medios'),
			'otroMedio'			=> $this->input->post('otroMedio'),
			'fechaRegistro'		=> date('Y-m-d H:i:s')
		);

		$this->db->insert('pacientes', $nuevo);
	}
}