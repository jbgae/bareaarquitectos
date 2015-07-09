<?php

class Chat_model extends CI_Model{
    
    var $EmailEmpleado = '';
    var $Mensaje = '';
    var $Fecha = '';
    
    private static $db;
    
    public function __construct() {
        parent::__construct();        
        self::$db = &get_instance()->db;
    }
    
    public function anyadir_mensaje(){
        $aux = FALSE;
        
        $this->EmailEmpleado = $this->session->userdata('email');
        $this->Mensaje = $this->input->post('mensaje');
        $this->Fecha = date('Y-m-d H:i:s');
        
        if($this->db->insert('Chat', $this)){
            $aux = TRUE;
        }
        
        return $aux;       
    }

    
    static function obtener_mensajes($fechaAcceso){
        $fechaAcceso = str_replace('/', '-', $fechaAcceso);
        self::$db->select('*');
        self::$db->from('Chat_mensajes');
        self::$db->where('Fecha <=', date('Y-d-m H:i:s',strtotime($fechaAcceso)));
        $query = self::$db->get();
        
        $mensajes = $query->result();
        
        return $mensajes;
        
    }
    
    static function obtener_mensajes_nuevos($fechaAcceso){
        $fechaAcceso = str_replace('/', '-', $fechaAcceso);
        self::$db->select('*');
        self::$db->from('Chat_mensajes');
        self::$db->where('Fecha >=', date('Y-m-d H:i:s',strtotime($fechaAcceso)));
        $query = self::$db->get();
        
        $mensajes = $query->result();
        
        return $mensajes;
        
    }
    
    static function numero_mensajes_nuevos($fechaAcceso, $email){
        $fechaAcceso = str_replace('/', '-', $fechaAcceso);
        self::$db->select('*');
        self::$db->from('Chat_mensajes');
        self::$db->where('Fecha >=', date('Y-m-d H:i:s',strtotime($fechaAcceso)));
        self::$db->where('EmailEmpleado !=', $email);
        $query = self::$db->get();
        
        $mensajes = $query->result();
        
        return count($mensajes);
    }
    /*
    static function obtener_usuarios(){
        $users = array();
        
        self::$db->select('user_data, last_activity');
        self::$db->from('ci_sessions');
        self::$db->where('user_data <>' ,'');
                
        $query = self::$db->get();
   
        $usuarios = $query->result();

        if(!empty($usuarios)){

            $online = array();
            $ausente = array();
            foreach($usuarios as $usuario){
                $u_datos = unserialize($usuario->user_data);
                if(Chat_model::_calcularMinutos($usuario->last_activity) <= 5){
                    array_push($online, $u_datos['nombre'].' '.$u_datos['apellidos']);
                }
            }
            $users = array($online, $ausente);
        }
        
        return $users;
        
    }

    
    private function _calcularMinutos($timestamps){
        $ahora = new DateTime();
        $fecha = new DateTime();
        $fecha->setTimestamp("$timestamps");
        
        $dif = $ahora->diff($fecha);
        return $dif->format('%i') +( $dif->format('%h') * 60 ) + ($dif->format('%a') * 60 * 24);        
    }*/
    
}
?>