<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Empleado_model extends Usuario_model{
    
    var $Email = '';
    var $Cargo = '';
    var $Salario = '';
    var $FechaContratacion = '';
    var $FechaFinContrato = '';
    
    
    private static $db;
    
    public function __construct() {
        parent::__construct();        
        self::$db = &get_instance()->db;
    }
    
    
    public function inicializar(){
        $aux = FALSE;    
   
        $usuario = new Usuario_model(); 
        if($usuario->inicializar('empleado')){  
            $this->Email = strtolower($this->input->post('email'));
            
            if($this->input->post('cargo') != '')
                $this->Cargo = ucwords(strtolower($this->input->post('cargo')));
            
            if($this->input->post('salario') != '')
                $this->Salario = $this->input->post('salario');
            else
                $this->Salario = NULL;
            
            if($this->input->post('fAlta'))
                $this->FechaContratacion = date('Y-m-d',strtotime($this->input->post('fAlta')));
            else
                 $this->FechaContratacion = NULL;
            
            if($this->input->post('fBaja'))
                $this->FechaFinContrato = date('Y-m-d',strtotime($this->input->post('fBaja')));                   
            else
                $this->FechaFinContrato = NULL;
                    
            $this->db->set('Email', $this->Email);
            $this->db->set('Cargo', $this->Cargo);
            $this->db->set('Salario', $this->Salario);
            $this->db->set('FechaContratacion', $this->FechaContratacion);
            $this->db->set('FechaFinContrato', $this->FechaFinContrato);
            
            if($this->db->insert('Empleado')){
                $aux = TRUE;
            }
        }
        
        return $aux;
    }
    
    
    public function datos($email){
        Usuario_model::datos($email);
        
        $query = $this->db->get_where('Empleados', array('Email'=>$email));
        $empleado = $query->result();
        
        $this->Cargo = $empleado[0]->Cargo;
        $this->Salario = $empleado[0]->Salario;
        $this->FechaContratacion = $empleado[0]->FechaContratacion;
        $this->FechaFinContrato = $empleado[0]->FechaFinContrato;
        
        return $this;   
    }
    
    static function existe($email){
        $aux = FALSE;        
        $query = self::$db->get_where('Empleados', array('Email'=>$email));        
        if($query->num_rows() > 0){
            $aux = TRUE;
        }
        
        return $aux;
    }
    
    
    public function cargo($email = ''){
        $aux = '';
        
        if($email != ''){
            if($this->existe($email)){
                $this->db->select('Cargo');
                $this->db->from('Empleados');
                $this->db->where('Email', $email);
                $query = $this->db->get();

                $empleado = $query->result();

                $aux = $empleado[0]->Cargo;
            }
        }
        else{
            $aux = $this->Cargo;
        }
        
        return $aux;
    }
    
    public function salario($email = ''){
        $aux = '';
        
        if($email != ''){
            if($this->existe($email)){
                $this->db->select('Salario');
                $this->db->from('Empleados');
                $this->db->where('Email', $email);
                $query = $this->db->get();

                $empleado = $query->result();

                $aux = $empleado[0]->Salario;
            }
        }
        else{
            $aux = $this->Salario;
        }
        
        return $aux;
    }
    
    
    public function fechaContratacion($email = ''){
        $aux = '';
        
        if($email != ''){
            if($this->existe($email)){        
                $this->db->select('FechaContratacion');
                $this->db->from('Empleados');
                $this->db->where('Email', $email);
                $query = $this->db->get();

                $empleado = $query->result();

                $aux = $empleado[0]->FechaContratacion;
            }
        }
        else{
            $aux = $this->FechaContratacion;
        }
        
        return $aux;
    }
    
    
    public function fechaFinContrato($email = ''){
        $aux = '';
        
        if($email != ''){
            if($this->existe($email)){  
                $this->db->select('FechaFinContrato');
                $this->db->from('Empleados');
                $this->db->where('Email', $email);
                $query = $this->db->get();

                $empleado = $query->result();

                $aux = $empleado[0]->FechaFinContrato;
            }
        }
        else{
            $aux = $this->FechaFinContrato;
        }
        
        return $aux;
    }
    
    public function foto($email = '', $num = FALSE ){
        $aux = '';
        
        if($email != '' && !($num)){ 
            if($this->existe($email)){  
                $this->db->select('Ruta');
                if($num)
                    $this->db->from('Archivo');
                else
                    $this->db->from('Archivos');
                $this->db->where('EmailEmpleado', $email);
                $this->db->where('FotoEmpleado', TRUE);
                $query = $this->db->get();

                $empleado = $query->result();
                
                if(!empty($empleado))
                    $aux = $empleado[0]->Ruta;
                
             }
        }
        else{
            $this->db->select('Codigo');
            if($num)
                $this->db->from('Archivo');
            else
                $this->db->from('Archivos');
            $this->db->where('EmailEmpleado', $this->Email);
            $this->db->where('FotoEmpleado', TRUE);
            $query = $this->db->get();

            $empleado = $query->result();

            if(!empty($empleado))
                $aux = $empleado[0]->Codigo;
        }

        return $aux;
        
    }
    
    
    public function actualizar($email){
        $aux = FALSE;
        $usuario = new Usuario_model;
        $usuario->datos($email);
        
        if($usuario->actualizar($email)){
           if($this->input->post('cargo') != '')
                $this->Cargo =  $this->input->post('cargo');
           
           if($this->input->post('salario') != '')
               $this->Salario = $this->input->post('salario');
           else
               $this->Salario = NULL;
           
           if($this->input->post('fechaContratacion') != '')
               $this->FechaContratacion = $this->input->post('fechaContratacion');
           else
               $this->FechaContratacion = NULL;
           
           if($this->input->post('fechaFinContrato') != '')
               $this->FechaFinContrato = $this->input->post('fechaFinContrato');
           else
               $this->FechaFinContrato = NULL;
            
            $this->db->set('Cargo', $this->Cargo);
            $this->db->set('Salario', $this->Salario);
            $this->db->set('FechaContratacion', $this->FechaContratacion);
            $this->db->set('FechaFinContrato', $this->FechaFinContrato);

            $this->db->where('Email', $email);
            if($this->db->update('Empleado')){
                $aux = TRUE;
            }
        }
        return $aux;
    }
    
    
    
    static function buscar($dato, $campo, $orden, $offset, $limite){
        
        self::$db->select('*');
        self::$db->like('Nombre', $dato);
        self::$db->or_like('ApellidoP', $dato);
        self::$db->or_like('ApellidoM', $dato);
        self::$db->or_like('Cargo', $dato);
        self::$db->or_like('CONCAT(Nombre, " ", ApellidoP, " ", ApellidoM)', $dato);
        self::$db->or_like('CONCAT(Nombre, " ", ApellidoP)', $dato);
        self::$db->or_like('CONCAT(ApellidoP, " ", ApellidoM)', $dato);
        self::$db->limit($limite, $offset);
        self::$db->order_by($campo, $orden);
        
        $query = self::$db->get('Empleados');
        $empleados =  $query->result();
        
        foreach($empleados as $empleado){
            self::$db->select('Ruta');
            self::$db->from('Archivo');
            self::$db->where('EmailEmpleado', $empleado->Email);
            self::$db->where('FotoEmpleado', TRUE);
            $query = self::$db->get();

            $empl = $query->result();

            if(!empty($empl))
                $empleado->Ruta = $empl[0]->Ruta;
            else
                $empleado->Ruta = '';
        }
        
        return $empleados;
    }
    
   
    static function busqueda_cantidad($dato){
        self::$db->select('*');
        self::$db->like('Nombre', $dato);
        self::$db->or_like('ApellidoP', $dato);
        self::$db->or_like('ApellidoM', $dato);
        self::$db->or_like('Cargo', $dato);
        self::$db->or_like('CONCAT(Nombre, " ", ApellidoP, " ", ApellidoM)', $dato);
        self::$db->or_like('CONCAT(Nombre, " ", ApellidoP)', $dato);
        self::$db->or_like('CONCAT(ApellidoP, " ", ApellidoM)', $dato);
        self::$db->from('Empleados');
        return self::$db->count_all_results();
    }
    
    
    static function numero(){
        return self::$db->count_all('Empleados');
    }
    
    static function obtener($campo, $orden, $offset, $limite) {
		
        $orden = ($orden == 'desc') ? 'desc' : 'asc';
        $sort_columns = array('Email', 'Nombre', 'Cargo', 'Salario', 'FechaContratacion', 'FechaFinContrato', 'FechaUltimoAcceso', 'Telefono');
        $campo = (in_array($campo, $sort_columns)) ? $campo : 'Nombre';

        self::$db->select('*');
        self::$db->from('Empleados');
        self::$db->limit($limite, $offset);
        self::$db->order_by($campo, $orden);
        self::$db->where('Tipo =', 'empleado');
        $query = self::$db->get();

        $empleados = $query->result();
        
        foreach($empleados as $empleado){
            self::$db->select('Ruta');
            self::$db->from('Archivo');
            self::$db->where('EmailEmpleado', $empleado->Email);
            self::$db->where('FotoEmpleado', TRUE);
            $query = self::$db->get();

            $empl = $query->result();

            if(!empty($empl))
                $empleado->Ruta = $empl[0]->Ruta;
            else
                $empleado->Ruta = '';
        }
        
        return $empleados;
    }   
    
    
    
}    
?>