<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Respuesta_model extends CI_Model{

    var $Codigo = '';
    var $CodigoTarea = '';
    var $Contenido = '';
    var $Email = '';
    var $Fecha = '';
    
    private static $db;
    
    public function __construct() {
        parent::__construct(); 
        self::$db = &get_instance()->db;
    }
    
    public function inicializar($codigoTarea){
         $aux = FALSE;
        
        $this->CodigoTarea = $codigoTarea;
        $this->Contenido = $this->input->post('contenido');
        $this->Fecha = date('Y-m-d H:i:s');
        $this->Email = $this->session->userdata('email');
   
        if($this->db->insert('Respuesta', $this)){
            $aux = TRUE;
        }          
        
        return $aux;
    }
    
    public function datos($codigo){                            
        $query = $this->db->get_where('Respuestas', array('Codigo'=>$codigo));
        $respuesta = $query->result();
        $this->Codigo = $codigo;
        $this->CodigoTarea = $respuesta[0]->CodigoTarea;        
        $this->Contenido = $respuesta[0]->Contenido;        
        $this->Fecha = $respuesta[0]->Fecha;        
        $this->Email = $respuesta[0]->Email;        
        
        return $this;   
    }
    
    
    public function codigo(){
        return $this->db->insert_id();
    }
    
    public function email($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
           if($this->existe($codigo)){        
                $this->db->select('Email');
                $this->db->from('Respuesta');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $tarea = $query->result();

                $aux = $tarea[0]->Email;
           }
        }
        else{
            $aux = $this->Email;
        }
        
        return $aux;
    }
    
    public function codigoTarea($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
           if($this->existe($codigo)){        
                $this->db->select('CodigoTarea');
                $this->db->from('Respuesta');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $tarea = $query->result();

                $aux = $tarea[0]->CodigoTarea;
           }
        }
        else{
            $aux = $this->CodigoTarea;
        }
        
        return $aux;
    }
    
    public function fecha($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
           if($this->existe($codigo)){        
                $this->db->select('Fecha');
                $this->db->from('Respuesta');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $tarea = $query->result();

                $aux = $tarea[0]->CodigoTarea;
           }
        }
        else{
            $aux = $this->Fecha;
        }
        
        return $aux;
    }
    
    static function obtener($codigoTarea){
        self::$db->select('*');
        self::$db->from('Respuestas');
        self::$db->where('CodigoTarea', $codigoTarea);
        self::$db->order_by('Fecha', 'asc');
        
        $query = self::$db->get();
        $respuestas = $query->result();
        
        return $respuestas;
    }
    
    static function existe($codigo){
        $aux = FALSE;
        $query = self::$db->get_where('Respuesta', array('Codigo'=>$codigo));        
        if($query->num_rows() > 0){
            $aux = TRUE;
        }
        
        return $aux;
    }
    
    
     public function borrar($codigo){
        $aux = FALSE;
        
        if($this->existe($codigo)){
            if($this->db->delete('Respuesta', array('Codigo' => $codigo))){
                $aux = TRUE;
            }
        }
      
        return $aux;
    }
    
    static function numeroRespuestasNuevas($email, $fecha){
        $fecha = str_replace('/', '-', $fecha);
        $date = new DateTime($fecha); 
        
        self::$db->select('Codigo');
        self::$db->from('Tarea');
        self::$db->where('Estado', 'ejecucion');
        $query = self::$db->get();
        $tareas = $query->result();
        $aux = array();
        foreach($tareas as $tarea){            
                array_push($aux, $tarea->Codigo);
        }
        
        if(!empty($aux)){
            self::$db->select('CodigoTarea');
            self::$db->from('EmpleadoTarea');
            self::$db->where('EmailEmpleado', $email);
            self::$db->where_in('CodigoTarea', $aux);
            $query = self::$db->get();
            $tareas = $query->result();
            $aux = array();
            $aux1 = array();
            foreach($tareas as $tarea){
                array_push($aux1, $tarea->CodigoTarea);
            }

            if(!empty($aux1)){
                self::$db->select('Codigo');
                self::$db->from('Respuestas');
                self::$db->where('Fecha >=', $date->format('Y-m-d H:i:s'));
                self::$db->where_in('CodigoTarea', $aux1);
                self::$db->where('Email !=', $email);

                return self::$db->count_all_results();
            }
        }
        else{
            return 0;
        }
    }
    
    static function respuestasNuevas($email, $fecha){
        $fecha = str_replace('/', '-', $fecha);
        $date = new DateTime($fecha);
        $respuestas = array();
        
        self::$db->select('Codigo');
        self::$db->from('Tarea');
        self::$db->where('Estado', 'ejecucion');
        $query = self::$db->get();
        $tareas = $query->result();
        $aux = array();
        foreach($tareas as $tarea){            
                array_push($aux, $tarea->Codigo);
        }
        
        if(!empty($aux)){
            self::$db->select('CodigoTarea');
            self::$db->from('EmpleadoTarea');
            self::$db->where('EmailEmpleado', $email);
            self::$db->where_in('CodigoTarea', $aux);
            $query = self::$db->get();
            $tareas = $query->result();
            $aux = array();
            foreach($tareas as $tarea){            
                    array_push($aux, $tarea->CodigoTarea);
            }

            self::$db->select('*');
            self::$db->from('Respuestas');
            self::$db->where('Fecha >=', $date->format('Y-m-d H:i:s'));
            self::$db->where_in('CodigoTarea', $aux);
            self::$db->where('Email !=', $email);

            $query = self::$db->get();        

            $respuestas = $query->result();
        }
        
        return $respuestas;
    }
}
?>