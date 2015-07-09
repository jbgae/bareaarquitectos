<?php

class Evento_model extends CI_Model{
    
    var $Fecha = '';
    var $Tipo = '';
    var $Cita = '';
    var $EmailEmpleado = '';
    var $Descripcion = '';
    
    private static $db;
    
    public function __construct() {
        parent::__construct();        
        self::$db = &get_instance()->db;
    }
    
    public function inicializar(){
        $aux = FALSE;
        
        if($this->input->post('hora') != '')
            $fecha = $this->input->post('Fecha').' '.$this->input->post('hora').':00';
        else
            $fecha = $this->input->post('Fecha').' 00:00:00';
        $this->Fecha = date('Y-m-d H:i:s', strtotime($fecha));
        $this->Tipo = $this->input->post('Tipo');
        $this->Cita = $this->input->post('Cita');
        $this->EmailEmpleado = $this->session->userdata('email');
        $this->Descripcion  = $this->input->post('Descripcion');
        
        if($this -> db -> insert('Evento', $this)){
            $aux = TRUE;
        }
        
        return $aux;
    }
    
    
    public function datos($codigo){
        $query = $this->db->get_where('Evento', array('Email'=>$codigo));
        $evento = $query->result();
        
        $this->Codigo = $codigo;
        $this->Fecha = $evento[0]->Fecha;
        $this->Tipo = $evento[0]->Tipo;
        $this->Cita = $evento[0]->Cita;
        $this->EmailEmpleado = $evento[0]->EmailEmpleado;
        $this->Descripcion = $evento[0]->Descripcion;
        
        return $this;
    }
    
    public function codigo(){
        return $this->db->insert_id();
    }
    
    public function descripcion($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){        
                $this->db->select('Descripcion');
                $this->db->from('Evento');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $evento = $query->result();

                $aux = $evento[0]->Descripcion;
            }
        }
        else{
            $aux = $this->Descripcion;
        }
        return $aux;
    }
    
    public function evento($fecha, $email){
        
        $this->db->select('*');
        $this->db->from('Evento');
        $this->db->where('date(Fecha)', $fecha);
        $this->db->where('EmailEmpleado', $email);
        $this->db->order_by('Fecha', 'desc');
        $query = $this->db->get();

        $evento = $query->result();

        return $evento;
        
        
    }
    
    static function eventos($fecha, $email){
        
        $query = self::$db->get_where('Evento', array('date(Fecha)'=>$fecha, 'EmailEmpleado'=> $email));
        $count = $query->num_rows();        
        
        return $count;
    }
    
    public function cargar($year, $month, $email, $limit='', $day = ''){
        
        $this->borrar(date('Y-m-d'));
        if($limit == ''){
            if($day == '')
                $query = $this->db->select('*')->from('Evento')->like('Fecha', "$year-$month", 'after')->like('EmailEmpleado', $email)->order_by('Fecha','desc')->get();          
            else
                $query = $this->db->query('select * FROM Evento WHERE week(Fecha,3) LIKE week(\''.$year.'-'.$month.'-'.$day.' 00:00:00\', 3) AND EmailEmpleado like "'. $email .'" ORDER BY Fecha DESC');
            
            $eventos = array();
            $aux = array();
            
            if($day == ''){
                foreach($query->result() as $row){
                    $dia = substr($row->Fecha, 8, 2);
                    if($dia < 10)
                        $dia = $dia % 10;
                    if(array_key_exists($dia, $eventos)){ 
                        if(is_array($eventos[$dia]))                               
                            if($this->descripcion($row->Codigo) != '')    
                                array_push($eventos[$dia],'<span class="text-info"><a href="#" id="tooltip" rel="tooltip" data-tigger="hover" data-animation="true" data-placement="top" data-toggle="tooltip" data-title="'. $this->descripcion($row->Codigo).'">'. ucfirst($row->Cita) . '</a></span>' );
                            else
                                array_push($eventos[$dia],'<span class="text-info"">'. ucfirst($row->Cita) . '</span>' );
                        else{
                            $aux = $eventos[$dia];
                            $eventos[$dia] = array();
                            array_push($eventos[$dia],$aux);
                            if($this->descripcion($row->Codigo) != '')    
                                array_push($eventos[$dia],'<span class="text-info"><a href="#" id="tooltip" rel="tooltip" data-tigger="hover" data-animation="true" data-placement="top" data-toggle="tooltip" data-title="'. $this->descripcion($row->Codigo).'">'. ucfirst($row->Cita) . '</a></span>' );
                            else
                                array_push($eventos[$dia],'<span class="text-info">'. ucfirst($row->Cita) . '</span>' );
                        }
                    }
                    else{
                        if($this->descripcion($row->Codigo) != '')
                            $eventos[$dia] = '<span class="text-info"><a href="#" id="tooltip" rel="tooltip" data-tigger="hover" data-animation="true" data-placement="bottom" data-toggle="tooltip" data-title="'. $this->descripcion($row->Codigo).'">'. ucfirst($row->Cita) . '</a></span>';
                        else
                            $eventos[$dia] = '<span class="text-info">'. ucfirst($row->Cita) . '</span>';    
                    }            
                }
            }
            else{
                foreach($query->result() as $row){
                    $dia = substr($row->Fecha, 8, 2);
                    if($dia < 10)
                        $dia = $dia % 10;
                    if(array_key_exists($dia, $eventos)){ 
                        if(is_array($eventos[$dia]))                               
                            if($this->descripcion($row->Codigo) != '')    
                                array_push($eventos[$dia],'<div class="cita"><h5>'. ucfirst($row->Cita) . ' <span>'.date('H:i',strtotime($row->Fecha)).'</span></h5> <div>'. ucfirst($this->descripcion($row->Codigo)).'</div></div>' );
                            else
                                array_push($eventos[$dia],'<div class="cita"><h5>'. ucfirst($row->Cita) . ' <span>'.date('H:i',strtotime($row->Fecha)).'</span></h5> </div>');
                        else{
                            $aux = $eventos[$dia];
                            $eventos[$dia] = array();
                            array_push($eventos[$dia],$aux);
                            if($this->descripcion($row->Codigo) != '')    
                                array_push($eventos[$dia],'<div class="cita"><h5>'. ucfirst($row->Cita) . ' <span>'.date('H:i',strtotime($row->Fecha)).'</span></h5> <div>'. ucfirst($this->descripcion($row->Codigo)).'</div></div>' );
                            else
                                array_push($eventos[$dia],'<div class="cita"><h5>'. ucfirst($row->Cita) . ' <span>'.date('H:i',strtotime($row->Fecha)).'</span></h5></div>' );
                        }
                    }
                    else{
                        if($this->descripcion($row->Codigo) != '')
                            $eventos[$dia] = '<div class="cita"><h5>'. ucfirst($row->Cita) . ' <span>'.date('H:i',strtotime($row->Fecha)).'</span></h5> <div>'.ucfirst($this->descripcion($row->Codigo)).'</div></div>';
                        else
                            $eventos[$dia] = '<div class="cita"><h5>'. ucfirst($row->Cita) . ' <span>'.date('H:i',strtotime($row->Fecha)).'</span></h5></div>';    
                    }            
                }
            }
        }   
        else{ 
            $query = $this->db->select('*')->from('Evento')->like('EmailEmpleado', $email)->limit($limit)->order_by('Fecha','asc')->get();
            $eventos = $query->result();
        }        
        
        return $eventos;
    }
    
    
    public function borrar($hoy){
        $this->db->where('Fecha <', $hoy);
        $this->db->delete('Evento'); 
    }
    
    static function existe($codigo){
        $aux = FALSE;        
        $query = self::$db->get_where('Evento', array('Codigo'=>$codigo));        
        if($query->num_rows() > 0){
            $aux = TRUE;
        }
        
        return $aux;
    }
    
    static function eventosFuturos($email){
        self::$db->select('*');
        self::$db->from('Evento');
        self::$db->where('EmailEmpleado',$email);
        self::$db->where('Fecha >=',date('Y-m-d'));
        
        $query = self::$db->get();
        
        return $query->result();
    }
}

?>