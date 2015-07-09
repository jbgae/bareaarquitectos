<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Notas_model extends CI_Model{
    
    var $Titulo = '';
    var $Contenido = '';
    var $FechaCreacion = '';
    var $EmailEmpleado = '';
    var $CodigoProyecto = '';
    var $Permisos = '';
    
    private static $db;
    
    public function __construct() {
        parent::__construct();        
        self::$db = &get_instance()->db;
    }
    
    
    public function inicializar($codigo){
        $aux = FALSE;
               
        $this->Titulo = $this->input->post('titulo');
        $this->Contenido = $this->input->post('contenido');
        $this->FechaCreacion = date('Y-m-d H:i:s');
        $this->EmailEmpleado = $this->session->userdata('email'); 
        $this->CodigoProyecto = $codigo;
        $this->Permisos = $this->input->post('permisos');
        
        if($this->db->insert('Nota', $this)){
            $aux = TRUE;
            
            $codigoNota = $this->db->insert_id();
            if($this->Permisos == 'personalizado'){
                $empleados = $this->input->post('empleados');
                if(!empty($empleados)){
                    foreach($empleados as $empleado){
                        $datos = array(
                            'CodigoProyecto' => $codigo,
                            'EmailEmpleado'=> $empleado,
                            'CodigoNota' => $codigoNota 
                        );
                        $this->db->insert('NotaEmpleados', $datos);
                    }
                }
                else{
                    $this->borrar($codigoNota);
                    $aux = FALSE;
                }
            }
        }
                
        return $aux;
    }

    public function datos($codigo){        
        $query = $this->db->get_where('Notas', array('Codigo'=>$codigo));
        $nota = $query->result();
        
        $this->Codigo = $codigo;
        $this->CodigoProyecto = $nota[0]->CodigoProyecto;
        $this->Contenido = $nota[0]->Contenido;
        $this->EmailEmpleado = $nota[0]->EmailEmpleado;
        $this->FechaCreacion = $nota[0]->FechaCreacion;
        $this->Permisos = $nota[0]->Permisos;
        $this->Titulo = $nota[0]->Titulo;
        $this->Nombre = $nota[0]->Nombre;
        $this->ApellidoP = $nota[0]->ApellidoP;
        $this->ApellidoM = $nota[0]->ApellidoM;
        
        return $this;   
    }
    
     public function codigo(){
        return $this->db->insert_id('Nota');
    }
    
    public function titulo($codigo = ''){
         $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('Titulo');
                $this->db->from('Notas');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $nota = $query->result();

                $aux = $nota[0]->Titulo;
            }
        }
        else{
            $aux = $this->Titulo;
        }
        
        return $aux;
    }
    
    
    public function contenido($codigo = ''){
         $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('Contenido');
                $this->db->from('Notas');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $nota = $query->result();

                $aux = $nota[0]->Contenido;
            }
        }
        else{
            $aux = $this->Contenido;
        }
        
        return $aux;
    }
    
    
    public function fecha($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('FechaCreacion');
                $this->db->from('Notas');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $nota = $query->result();

                $aux = $nota[0]->FechaCreacion;
            }
        }
        else{
            $aux = $this->FechaCreacion;
        }
        
        return $aux;
    }
    
    
    public function email($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('EmailEmpleado');
                $this->db->from('Notas');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $nota = $query->result();

                $aux = $nota[0]->EmailEmpleado;
            }
        }
        else{
            $aux = $this->EmailEmpleado;
        }
        
        return $aux;
    }
    
    
    public function codigoProyecto($codigo= ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('CodigoProyecto');
                $this->db->from('Notas');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $nota = $query->result();

                $aux = $nota[0]->CodigoProyecto;
            }
        }
        else{
            $aux = $this->CodigoProyecto;
        }
        
        return $aux;
    }
    
    
    public function permisos($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('Permisos');
                $this->db->from('Notas');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $nota = $query->result();

                $aux = $nota[0]->Permisos;
            }
        }
        else{
            $aux = $this->Permisos;
        }
        
        return $aux;
    }
    
    
    
    public function obtener($codigo){
        $query = $this->db->get_where('Nota', array('EmailEmpleado'=>$this->session->userdata('email'),'CodigoProyecto'=>$codigo ));//array(, 'Permisos'=>'privado', 'EmailEmpleado'=>$this->session->userdata('email')));
        $notasPropias = $query->result();
        
        
        $this->db->select('*');
        $this->db->from('Nota');
        $this->db->where('CodigoProyecto', $codigo);
        $this->db->where('Permisos', 'publico');
        $this->db->where_not_in('EmailEmpleado', $this->session->userdata('email'));
        $query = $this->db->get();
        $notasPublicas = $query->result();
        
        //$this->db->get_where('Nota', array('CodigoProyecto'=>$codigo, 'Permisos'=>'publico'));
        //$query = $this->db->where_not()
        
        $aux = array();
        $codigos = array();
        
        $this->db->select("Codigo");
        $this->db->from('Nota');
        $this->db->where('CodigoProyecto', $codigo);
        $query = $this->db->get();
        $cod = $query->result_array();
       

        if(!empty($cod)){
            $this->db->select("CodigoNota");
            $this->db->from('NotaEmpleados');
            $this->db->where_in('CodigoNota',$cod[0]);
            $this->db->where('Email',$this->session->userdata('email'));
            $query = $this->db->get();
            $codigos = $query->result_array();
        }
        
        
        if(!empty($codigos)){
            foreach($codigos as $codigo){
                array_push($aux, $codigo['CodigoNota']);
            }               
        }
        $notasPersonalizadas = array();
        
        if(!empty($aux)){
            $this->db->select('*');
            $this->db->from('Nota');
            $this->db->where('Permisos', 'personalizado');
            $this->db->where_in('Codigo', $aux);    

            $query = $this->db->get();

            $notasPersonalizadas = $query->result();
            
        }      
              
        $notas = array_merge($notasPropias ,$notasPublicas);   
        $notas = array_merge($notas ,$notasPersonalizadas);
        
                
        return $notas;
    }
    
    static function numeroNotasNuevas($email, $fecha){
        $fecha = str_replace('/', '-', $fecha);
        $date = new DateTime($fecha); 
        
        self::$db->select('CodigoNota');
        self::$db->from('NotasEmpleados');
        self::$db->where('Email', $email);
        $query = self::$db->get();
        $notas = $query->result();
        $aux = array();
        if(!empty($notas)){
            foreach ($notas as $nota) {
                array_push($aux, $nota->CodigoNota);
            }
        }
               
        self::$db->select('Codigo');
        self::$db->from('Nota');
        self::$db->where('FechaCreacion >=', $date->format('Y-m-d H:i:s'));
        self::$db->where('EmailEmpleado !=', $email);
        
        if(!empty($aux)){
            self::$db->where_in('Codigo', $aux);
            self::$db->or_where('Permisos', 'publico');
        }
        else{
            self::$db->where('Permisos', 'publico');
        }
           
        return self::$db->count_all_results();
    }
    
    static function notasNuevas($email, $fecha){ 
        $fecha = str_replace('/', '-', $fecha);
        $date = new DateTime($fecha); 
        
        self::$db->select('CodigoNota');
        self::$db->from('NotasEmpleados');
        self::$db->where('Email', $email);
        $query = self::$db->get();
        $notas = $query->result();
        $aux = array();
        if(!empty($notas)){
            foreach ($notas as $nota) {
                array_push($aux, $nota->CodigoNota);
            }
        }

            
        self::$db->select('*');
        self::$db->from('Notas');
        self::$db->where('FechaCreacion >=', $date->format('Y-m-d H:i:s'));
        self::$db->where('EmailEmpleado !=', $email);
        if(!empty($aux)){
            self::$db->where_in('Codigo', $aux);
            self::$db->or_where('Permisos', 'publico');
        }
        else{
            self::$db->where('Permisos', 'publico');
        }                  
               
        $query = self::$db->get();        
                             
        $notas = $query->result();
        
        return $notas;
    }
    
    
    
    public function borrar($codigo = ''){
        $aux = FALSE;
        
        if($codigo != ''){
            if($this->existe($codigo)){
                if($this->db->delete('Nota', array('Codigo' => $codigo))){
                    $aux = TRUE;
                }
            }
        }
        else{
            if($this->db->delete('Nota', array('Codigo' => $this->Codigo))){
                $aux = TRUE;
            }
        }
    }
    
    
    public function actualizar(){
        $aux = FALSE;
        
        $this->Titulo = $this->input->post('titulo');
        $this->Contenido = $this->input->post('contenido');
        $this->FechaCreacion = date('Y-m-d H:i:s');
        $this->EmailEmpleado = $this->session->userdata('email');
        $this->Permisos = $this->input->post('permisos');
        
        //$this->db->set('Codigo', $this->Codigo);
        $this->db->set('Titulo', $this->Titulo);
        $this->db->set('Contenido', $this->Contenido);
        $this->db->set('FechaCreacion', $this->FechaCreacion);
        $this->db->set('EmailEmpleado', $this->EmailEmpleado);
        $this->db->set('Permisos', $this->Permisos);
        
        $this->db->where('Codigo', $this->Codigo);
        if($this->db->update('Nota')){
            $aux = TRUE;
            $empleados = $this->input->post('empleados');
            if(!empty($empleados)){
                foreach($empleados as $empleado){
                    $datos = array(
                        'EmailEmpleado'=> $empleado,
                        'CodigoNota' => $this->Codigo 
                    );
                    $this->db->insert('NotaEmpleados', $datos);
                }
            }
            $eliminar = $this->input->post('select1');
            if(!empty($eliminar)){
                foreach($eliminar as $empleado){
                    $this->db->delete('NotaEmpleados', array('CodigoNota' => $this->Codigo,'EmailEmpleado'=> $empleado));
                }
            }
        }
                
        return $aux;
    }
    
    
    static function existe($codigo){
        $aux = FALSE;
        $query = self::$db->get_where('Nota', array('Codigo'=>$codigo));        
        if($query->num_rows() > 0){
            $aux = TRUE;
        }
        
        return $aux;
    }
    
    
    static function comprobar($codigoNota, $email){
        $aux = FALSE;
        $query = self::$db->get_where('NotasEmpleados', array('CodigoNota'=>$codigoNota, 'EmailEmpleado'=>$email));        
        if($query->num_rows() > 0){
            $aux = TRUE;
        }
        
        return $aux;
    }
    
    static function empleados($codigoNota){
        $empleados = '';
        
        if(Notas_model::existe($codigoNota)){
            self::$db->select('*');
            self::$db->from('NotaEmpleados');
            self::$db->where('CodigoNota', $codigoNota);
            $query = self::$db->get();

            $empleados = $query->result();
        }    
        return $empleados;
    }
    
    public function empleadosNoNota($email, $codigoProyecto){
        if(!empty($email)){
            $this->db->select('Nombre, ApellidoP, ApellidoM, EmailEmpleado');
            $this->db->from('ProyectosEmpleados');
            $this->db->where('CodigoProyecto', $codigoProyecto);
            $this->db->where_not_in("EmailEmpleado", $email);

            $query =$this->db->get();

            $empleados = $query->result();

            return $empleados;
        }
        else{
            $this->db->select('Nombre, ApellidoP, ApellidoM, EmailEmpleado');
            $this->db->from('ProyectosEmpleados');
            $this->db->where('CodigoProyecto', $codigoProyecto);
            $query = $this->db->get();

            $empleados = $query->result();
            
            return $empleados;
        }
    }
}