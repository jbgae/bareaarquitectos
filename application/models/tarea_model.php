<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tarea_model extends CI_Model{
    
    var $Titulo = '';
    var $Contenido = '';
    var $Estado = '';
    var $FechaCreacion = '';
    var $FechaLimite = '';
    var $EmailAdmin = '';
    var $CodigoProyecto = '';
    
    private static $db;
    
    public function __construct() {
        parent::__construct();        
        self::$db = &get_instance()->db;
    }
    
   
    public function inicializar($codigo){
        $aux = FALSE;
        
        $this->Titulo = $this->input->post('titulo');
        $this->Contenido = $this->input->post('contenido');
        $this->Estado = $this->input->post('estado');
        $this->FechaCreacion = date('Y-m-d H:i:s');
        if($this->input->post('fechaLimite') != '')
            $this->FechaLimite = date('Y-m-d H:i:s', strtotime($this->input->post('fechaLimite')));

        $this->EmailAdmin = $this->session->userdata('email');
        $this->CodigoProyecto = $codigo;    
        if($this->db->insert('Tarea', $this)){
            $codigoTarea = $this->codigo();
            $data1 = array(
               'CodigoTarea'=> $codigoTarea,
               'EmailEmpleado' => $this->input->post('asignado')
            );
            
            $data2 = array(
               'CodigoTarea'=>$codigoTarea,
               'EmailEmpleado' => $this->session->userdata('email')
            );
            if($this->db->insert('EmpleadoTarea', $data1) && $this->db->insert('EmpleadoTarea', $data2)){
                $aux = $codigoTarea;
            }
        }          
        
        return $aux;
    }
    
    
    public function actualizar(){
        $aux = FALSE;
        
        $this->Titulo = $this->input->post('titulo');
        $this->Contenido = $this->input->post('contenido');
        $this->FechaCreacion = date('Y-m-d H:i:s');
         if($this->input->post('fechaLimite') != '')
            $this->FechaLimite = date('Y-m-d H:i:s', strtotime($this->input->post('fechaLimite')));

        $this->db->set('Titulo', $this->Titulo);
        $this->db->set('Contenido', $this->Contenido);
        $this->db->set('FechaCreacion', $this->FechaCreacion);
        $this->db->set('FechaLimite', $this->FechaLimite);
        
        
        $this->db->where('Codigo', $this->Codigo);
        if($this->db->update('Tarea')){
            $aux = TRUE;
        }
                
        return $aux;
    }
    
     public function datos($codigo){                            
        $query = $this->db->get_where('Tareas', array('Codigo'=>$codigo));
        $tarea = $query->result();
        $this->Codigo = $codigo;
        $this->Titulo = $tarea[0]->Titulo;        
        $this->Contenido = $tarea[0]->Contenido;        
        $this->Estado = $tarea[0]->Estado;        
        $this->FechaCreacion = $tarea[0]->FechaCreacion;        
        $this->FechaLimite = $tarea[0]->FechaLimite;        
        $this->EmailAdmin = $tarea[0]->EmailAdmin;        
        $this->CodigoProyecto = $tarea[0]->CodigoProyecto;
        $this->Nombre = $tarea[0]->Nombre; 
        $this->ApellidoP = $tarea[0]->ApellidoP; 
        $this->ApellidoM = $tarea[0]->ApellidoM; 
        
        return $this;   
    }
    
    
    public function codigo(){
        return $this->db->insert_id('Tarea');
    }
    
    public function titulo($codigo = ''){
        $aux = '';
       
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('Titulo');
                $this->db->from('Tareas');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $tarea = $query->result();

                $aux = $tarea[0]->Titulo;
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
               $this->db->from('Tareas');
               $this->db->where('Codigo', $codigo);
               $query = $this->db->get();

               $tarea = $query->result();

               $aux = $tarea[0]->Contenido;
           }
        }
        else{
            $aux = $this->Contenido;
        }
         
        return $aux;
    }
    
    public function estado($codigo = '', $cambiar = FALSE){
        
        if($cambiar){
            $this->db->set('Estado', 'cerrado');
            $this->db->where('Codigo', $codigo);
            $this->db->update('Tarea');
        }
        else{        
            $aux = '';

            if($codigo != ''){
               if($this->existe($codigo)){        
                    $this->db->select('Estado');
                    $this->db->from('Tareas');
                    $this->db->where('Codigo', $codigo);
                    $query = $this->db->get();

                    $tarea = $query->result();
                    
                    $aux = $tarea[0]->Estado;
               }
            }
            else{
                $aux = $this->Estado;
            }

            return $aux;
        }
    }
    
    public function fechaCreacion($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
           if($this->existe($codigo)){        
                $this->db->select('FechaCreacion');
                $this->db->from('Tareas');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $tarea = $query->result();

                $aux = $tarea[0]->FechaCreacion;
           }
        }
        else{
            $aux = $this->FechaCreacion;
        }
        
        return $aux;
    }
    
    public function fechaLimite($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
           if($this->existe($codigo)){        
                $this->db->select('FechaLimite');
                $this->db->from('Tareas');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $tarea = $query->result();

                $aux = $tarea[0]->FechaLimite;
           }
        }
        else{
            $aux = $this->FechaLimite;
        }
        
        return $aux;
    }
    
    public function email($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
           if($this->existe($codigo)){        
                $this->db->select('EmailAdmin');
                $this->db->from('Tareas');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $tarea = $query->result();

                $aux = $tarea[0]->EmailAdmin;
           }
        }
        else{
            $aux = $this->EmailAdmin;
        }
        
        return $aux;
    }
    
    public function codigoProyecto($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
           if($this->existe($codigo)){
               $this->db->select('CodigoProyecto');
               $this->db->from('Tareas');
               $this->db->where('Codigo', $codigo);
               $query = $this->db->get();

               $tarea = $query->result();

               $aux = $tarea[0]->CodigoProyecto;
           }
        }
        else{
            $aux = $this->CodigoProyecto;
        }
        
        return $aux;
    }
    
    
     
    public function asignado($codigo, $nombre = FALSE){
        $aux = '';

        if($this->existe($codigo)){
            if($nombre){
                 $this->db->select('EmailAdmin');
                 $this->db->from('Tarea');
                 $this->db->where('Codigo',$codigo);
                 $query = $this->db->get();
                 $emailAdmin = $query->result();
                
                 $this->db->select('EmailEmpleado');
                 $this->db->from('EmpleadoTarea');
                 $this->db->where('CodigoTarea', $codigo);
                 $this->db->where_not_in('EmailEmpleado', $emailAdmin[0]->EmailAdmin);
                 $query = $this->db->get();
                 $email = $query->result();
                
                 
                 $this->db->select('Nombre, ApellidoP, ApellidoM');
                 $this->db->from('Usuario');
                 $this->db->where('Email',$email[0]->EmailEmpleado);
                 $query = $this->db->get();
                 $nombre = $query->result();                 
                 
                 $aux = $nombre[0]->Nombre.' '.$nombre[0]->ApellidoP.' '.$nombre[0]->ApellidoM;
            }
            else{
                $this->db->select('EmailAdmin');
                 $this->db->from('Tarea');
                 $this->db->where('Codigo',$codigo);
                 $query = $this->db->get();
                 $emailAdmin = $query->result();
                
                 $this->db->select('EmailEmpleado');
                 $this->db->from('EmpleadoTarea');
                 $this->db->where('CodigoTarea', $codigo);
                 $this->db->where_not_in('EmailEmpleado', $emailAdmin[0]->EmailAdmin);
                 $query = $this->db->get();
                 $email = $query->result();
                
                $aux = $email[0]->Email;
            }
        }
        
        return $aux;
    }
    
    static function obtener($email, $codigo){
        $tareas = ''; 
          
        self::$db->select('CodigoTarea');
        self::$db->from('EmpleadoTarea');
        self::$db->where('EmailEmpleado', $email);
        $query = self::$db->get();
        $codigoTareas = $query->result();
        
        $aux = array();
        foreach($codigoTareas as $cod){
            array_push($aux, $cod->CodigoTarea);
        }
               
        if(!empty($aux)){
            self::$db->select('*');
            self::$db->from('Tareas');
            self::$db->where('CodigoProyecto', $codigo);
            self::$db->where_in('Codigo', $aux);
            
            $query = self::$db->get();
            $tareas = $query->result();
        }
        
        return $tareas;
    }
    
    
    static function obtenerFechas($year, $month, $day = '',$email = '', $proyecto = ''){ 
        $tareas = array();
        
        if($email != ''){
            self::$db->select('CodigoTarea');
            self::$db->from('EmpleadoTarea');
            self::$db->where('EmailEmpleado', $email);
            self::$db->group_by('CodigoTarea');
            $query = self::$db->get();
            
            $tareas = $query->result();
            $aux = array();
            foreach ($tareas as $tarea) {
                array_push($aux,$tarea->CodigoTarea);
            }            
        
            if(!empty($aux)){        
                self::$db->select('Titulo, FechaCreacion, FechaLimite');
                self::$db->from('Tarea');     
                self::$db->where_in("Codigo", $aux);
                if($proyecto != ''){
                    self::$db->where("CodigoProyecto", $proyecto);
                    $query = self::$db->get();
                }
                else{
                    if($day == ''){
                        self::$db->where("FechaCreacion >=", date("$year-$month-1 H:i:s"));
                        self::$db->where("FechaCreacion <=", date("$year-$month-31 H:i:s"));
                        self::$db->or_where("FechaLimite >=", date("$year-$month-1")); 
                        self::$db->where("FechaLimite <=", date("$year-$month-31"));
                    }
                    else{
                        self::$db->where("FechaCreacion", date("$year-$month-$day"));
                        self::$db->or_where("FechaLimite", date("$year-$month-$day"));
                    }
                    $query = self::$db->get();
                }
            }
        }
        else{
            self::$db->select('Titulo, FechaCreacion, FechaLimite');
            self::$db->from('Tarea');     
            if($proyecto != ''){
                self::$db->where("CodigoProyecto", $proyecto);
                $query = self::$db->get();
            }
            else{
                if($day == ''){
                    self::$db->where("FechaCreacion >=", date("$year-$month-1 H:i:s"));
                    self::$db->where("FechaCreacion <=", date("$year-$month-31 H:i:s"));
                    self::$db->or_where("FechaLimite >=", date("$year-$month-1")); 
                    self::$db->where("FechaLimite <=", date("$year-$month-31"));
                }
                else{
                    self::$db->where("FechaCreacion", date("$year-$month-$day"));
                    self::$db->or_where("FechaLimite", date("$year-$month-$day"));
                }
                $query = self::$db->get();
            }
        }
               
        $tareas = $query->result(); 
              
        return $tareas;
    }
    
    
    static function existe($codigo){
        $aux = FALSE;
        $query = self::$db->get_where('Tarea', array('Codigo'=>$codigo));        
        if($query->num_rows() > 0){
            $aux = TRUE;
        }
        
        return $aux;
    }
    
    static function numeroTareasNuevas($email, $fecha){
        $fecha = str_replace('/', '-', $fecha);
        $date = new DateTime($fecha); 
        
        self::$db->select('CodigoTarea');
        self::$db->from('EmpleadoTarea');
        self::$db->where('EmailEmpleado', $email);
        $query = self::$db->get();
        $tareas = $query->result();
        $aux = array();
        $aux1 = array();
        
        foreach($tareas as $tarea){
            self::$db->select('Codigo');
            self::$db->from('Tareas');
            self::$db->where('Codigo', $tarea->CodigoTarea);
            self::$db->where('Estado !=', 'Cerrado');
            
            self::$db->where('EmailAdmin !=', $email);
            $query = self::$db->get();
            $aux1 = $query->result();
            
            if(!empty($aux1)){
                self::$db->select('CodigoTarea');
                self::$db->from('Respuestas');
                self::$db->where('CodigoTarea', $aux1[0]->Codigo);
                self::$db->where('Fecha >=', $date->format('Y-m-d H:i:s'));
                if(self::$db->count_all_results() == 0){
                    array_push($aux, $aux1[0]->Codigo);
                }
            }
        }
                
        return count($aux);
    }
    
    static function tareasNuevas($email, $fecha){
        $fecha = str_replace('/', '-', $fecha);
        $date = new DateTime($fecha); 
        
        self::$db->select('CodigoTarea');
        self::$db->from('EmpleadoTarea');
        self::$db->where('EmailEmpleado', $email);
        $query = self::$db->get();
        $tareas = $query->result();
        $aux = array();
        $aux1 = array();
        
        foreach($tareas as $tarea){
            self::$db->select('*');
            self::$db->from('Tareas');
            self::$db->where('Codigo', $tarea->CodigoTarea);
            self::$db->where('Estado !=', 'Cerrado');
            
            self::$db->where('EmailAdmin !=', $email);
            $query = self::$db->get();
            $aux1 = $query->result();
            
            if(!empty($aux1)){
                self::$db->select('CodigoTarea');
                self::$db->from('Respuestas');
                self::$db->where('CodigoTarea', $aux1[0]->Codigo);
                self::$db->where('Fecha >=', $date->format('Y-m-d H:i:s'));
                if(self::$db->count_all_results() == 0){
                    array_push($aux, $aux1[0]);
                }
            }
        }
                
        return $aux;
    }
    
    
    static function existeEmpleado($codigo, $email){
        $aux = FALSE;
        self::$db->select('*');
        self::$db->from('EmpleadoTarea');
        self::$db->where('EmailEmpleado',$email);
        self::$db->where('CodigoTarea',$codigo);
        
        if(self::$db->count_all_results() > 0){
            $aux = TRUE;
        }
        
        return $aux;
    }
    
    static function empleadosTarea($codigo){
        $aux = array();       
        self::$db->select('EmailEmpleado');
        self::$db->from('EmpleadoTarea');
        self::$db->where('CodigoTarea', $codigo);
        $query = self::$db->get();
        $empl = $query->result();    
        if(!empty($empl)){
            foreach($empl as $e){
                array_push($aux, $e->EmailEmpleado);
            }
        }
        return $aux;
   }
    
    public function borrar($codigo){
        $aux = FALSE;
        
        if($this->existe($codigo)){
            if($this->db->delete('Tarea', array('Codigo' => $codigo))){
                $aux = TRUE;
            }
        }
      
        return $aux;
    }
}
?>