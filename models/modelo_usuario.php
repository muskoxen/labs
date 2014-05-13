<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modelo_usuario extends CI_Model{

    /**
     * Número total de los registros de usuarios
     *
     * @access public
     * @return int
     */
    public function filas_usuarios(){
        $this->db->select('id_usuario');
        $this->db->from("usuarios");
        $this->db->join("roles", "roles.id_rol = usuarios.id_rol", "inner");
        return $this->db->get()->num_rows();
    }

    /**
     * Listado de todos los usuarios paginado de acuerdo a parametros
     *
     * @access public
     * @param Numero de filas por pagina, Segmento de la Tabla
     * @return array
     */
    public function listado_paginado($numeroFilas,$segmento){   
        $this->db->select('usuarios.id_usuario, usuarios.usuario, usuarios.nombre, correo, roles.rol, usuarios.estado');
        $this->db->join("roles", "roles.id_rol = usuarios.id_rol", "inner");
        $query = $this->db->get('usuarios',$numeroFilas,(($segmento > 0) ? $segmento:0));
        return $query->result();
    }

    /**
     * Validar que no exista un usuario que se quiere registrar
     *
     * @access public
     * @param Nombre de usuario
     * @return bool
     */
    public function existe_usuario($usuario){
        $this->db->select('id_usuario');
        $this->db->from("usuarios");
        $this->db->where('usuario',$usuario); 
        $this->db->limit(1); 
        $query = $this->db->get();
        if($query->num_rows() == 1) {
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /**
     * Guardar / Editar un usuario
     *
     * @access public
     * @return bool
     */
    public function guardar(){
        if ( $this->input->post('modo') == '0' ) {
            $data['nombre'] = $this->input->post('nombre');
            $data['id_rol'] = $this->input->post('id_rol');
            $data['correo'] = $this->input->post('correo');
            $data['password'] = sha1($this->input->post('password'));
            $data['usuario'] = $this->input->post('usuario');
            $data['estado'] = 'ACTIVO';
            return $this->db->insert("usuarios", $data); 
        }else{
            if ( $this->input->post('modo') == '1' ) {
                $data['nombre'] = $this->input->post('nombre');
                $data['id_rol'] = $this->input->post('id_rol');
                $data['correo'] = $this->input->post('correo');
                $data['fecha_modificado'] = date("Y-m-d");
                $this->db->where('id_usuario',$this->input->post('id_usuario')); 
                return $this->db->update("usuarios", $data); 
            }
        }           
    }

    /**
     * Eliminar definitivamente un usuario
     *
     * @access public
     * @param ID del usuario que se quiere eliminar
     * @return bool
     */
    public function eliminar($id_usuario = NULL){
        $row = $this->db->get_where("usuarios", array("id_usuario" => $id_usuario));
        if ($row->result()) {
            $this->db->limit(1);
            $this->db->where("id_usuario",$id_usuario);
            $this->db->delete("usuarios");
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /**
     * Buscar al usuario que se quiere loguear
     *
     * @access public
     * @return array
     */
    public function busca_usuario(){
        $this->db->select('roles.id_rol, id_usuario, usuario, password, nombre');
        $this->db->from('usuarios');
        $this->db->join("roles", "roles.id_rol = usuarios.id_rol", "inner");
        $this->db->where('usuario',$this->input->post('usuario'));
        $this->db->where('usuarios.estado','ACTIVO');
        $this->db->where('roles.estado','activo');
        $this->db->limit(1);
        return $this->db->get();
    }

    /**
     * Obtener todos los permisos asignado a un usuario
     *
     * @access public
     * @param Rol de usuario
     * @return array
     */
    public function obtener_permisos($id_rol = NULL){
        if ($id_rol != NULL){
            $this->db->select('id_modulo, id_proceso');
            $this->db->from('permisos');
            $this->db->where('id_rol', $id_rol);
            $this->db->order_by('id_modulo, id_proceso','ASC');
            $modulos = array();
            $procesos = array();
            $modulo_ac = NULL;
            foreach($this->db->get()->result() as $key => $permiso){
                if ($modulo_ac != $permiso->id_modulo){
                        $modulo_ac = $permiso->id_modulo;
                        $procesos = array();
                }
                array_push($procesos,$permiso->id_proceso);
                $modulos[$permiso->id_modulo] = $procesos;
            }
            return $modulos;
        }
    }

    /**
     * Obtener los datos de un usuario en especifico
     *
     * @access public
     * @return object
     */
    public function obtener_usuario(){
        $this->db->select('usuarios.id_usuario, usuarios.usuario, usuarios.id_rol, usuarios.nombre, usuarios.correo');
        $this->db->from("usuarios");
        $this->db->where('id_usuario', $this->input->post('id_usuario'));
        $this->db->limit(1);
        return $this->db->get()->row();
    }

    /**
     * Número total de los registros de usuarios de acuerdo a la busqueda realizada
     *
     * @access public
     * @param Dato a buscar
     * @return int
     */
    public function filas_usuarios_busqueda($referencia){
        $referencia = $this->db->escape_str($referencia);
        $this->db->select('usuarios.id_usuario');
        $this->db->from("usuarios");
        $this->db->join('roles','roles.id_rol = usuarios.id_rol','INNER');
        $this->db->like('nombre',$referencia);
        $this->db->or_like('usuario',$referencia);
        $this->db->or_like('correo',$referencia);
        return $this->db->get()->num_rows();
    }

    /**
     * Listado de todos los usuarios paginado de acuerdo a parametros y busqueda realizada
     *
     * @access public
     * @param Dato a buscar, Numero de filas por pagina, Segmento de la Tabla
     * @return array
     */
    public function busqueda_usuario($referencia,$numeroFilas,$segmento){
        $referencia = $this->db->escape_str($referencia);
        $this->db->select('usuarios.id_usuario, usuarios.nombre, usuarios.correo,  usuarios.usuario, roles.rol, usuarios.estado');
        $this->db->join('roles','roles.id_rol = usuarios.id_rol','INNER');
        $this->db->like('nombre',$referencia);
        $this->db->or_like('usuario',$referencia);
        $this->db->or_like('correo',$referencia);
        $query = $this->db->get('usuarios',$numeroFilas,(($segmento > 0) ? $segmento:0));
        return $query->result();
    }

    /**
     * Validar que no exista el correo que se quiere registrar con un usuario
     *
     * @access public
     * @return bool
     */
    public function existe_correo(){
        $correo = trim($this->input->post('correo'));
        $query = $this->db->query('SELECT id_usuario FROM usuarios where correo="'.$correo.'"');
        if($query->num_rows() > 0){
            return FALSE;
        }else{
            return TRUE;
        }
    }

    /**
     * Cambiar el estado de un usuario
     *
     * @access public
     * @param Módulo seteado en el controlador
     * @return array
     */
    public function cambiar_estado(){    
        $data = array();
        if($this->input->post('estado') == "INACTIVO"){
            $data['estado'] = "ACTIVO";
        }else{
            if ($this->input->post('estado') == "ACTIVO") {
                $data['estado'] = "INACTIVO";
            }
        }       
        $this->db->limit(1);
        return $this->db->update("usuarios", $data ,array('id_usuario' => $this->input->post('id_usuario')));
    }

    /**
     * Obtener la contraseña actual de un usuario
     *
     * @access public
     * @return password
     */
    public function obtener_contrasena(){
        $this->db->select('password');
        $this->db->from("usuarios");
        $this->db->where('id_usuario', $this->input->post('id_usuario'));
        $this->db->limit(1);
        return $this->db->get()->row()->password;
    }

    /**
     * Cambiar la contraseña actual de un usuario
     *
     * @access public
     * @param Contraseña nueva
     * @return bool
     */
    public function cambiar_contrasena($contrasena_nueva = NULL){
        $data['password'] = $contrasena_nueva;
        $this->db->limit(1);
        $result = $this->db->update("usuarios", $data,array('id_usuario' => $this->input->post('id_usuario_cp')));
        return ($result == 1) ? TRUE : FALSE;
    }

    /**
     * Actualizar el registro de la última vez que se loguea un usuario al iniciar sesión
     *
     * @access public
     * @param Usuario logueado
     */
    public function ultimo_acceso($id_usuario = NULL){
        $data = array();
        $data['ultimo_acceso'] = date('Y-m-d H:i:s');
        $data['ip'] = $this->input->ip_address();
        $this->db->limit(1);
        $this->db->update("usuarios", $data ,array('id_usuario' => $id_usuario));
    }

    /**
     * Validar que el usuario y rol logueado sea valido y pueda acceder al módulo
     *
     * @access public
     * @param Módulo seteado en el controlador
     * @return array
     */
    public function usuario_valido($id_modulo = 0) {
        $this->db->select('roles.id_rol, permisos.id_modulo, permisos.id_proceso');
        $this->db->from('usuarios');
        $this->db->join('permisos','permisos.id_rol = usuarios.id_rol');
        $this->db->join("roles", "roles.id_rol = usuarios.id_rol", "inner");
        $this->db->where('usuarios.id_rol', $this->session->userdata('id_rol'));
        $this->db->where('permisos.id_modulo', $id_modulo);
        $this->db->where('usuarios.estado','ACTIVO');
        $this->db->where('roles.estado','activo');
        $this->db->order_by('permisos.id_modulo, permisos.id_proceso','ASC');
        return $this->db->get();
    }

}