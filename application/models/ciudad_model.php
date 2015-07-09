<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Ciudad_model extends CI_Model{
    
    public function __construct() {
        parent::__construct();        
        $this->load->database();
    }
    
    public function obtener(){        
        $query = $this->db->get('Ciudad');        
        $ciudad = array('0'=>'');        
        foreach ($query->result_array() as $row){
            $ciudad[$row['Codigo']] = $row['Ciudad'];
        }
        return $ciudad;       
    }
    
    public function ciudad($codigo){
        $aux = 'Desconocida';
        
        if($codigo != 0 && $codigo != ''){
            $this->db->select('Ciudad');
            $this->db->from('Ciudad');
            $this->db->where('Codigo', $codigo);
            $query = $this->db->get();

            $ciudad = $query->result();

            $aux = $ciudad[0]->Ciudad;
        }
        
        return $aux;
    }
    
    public function ciudades($provincia){
        $this->db->where('CodigoProvincia',$provincia);
        $this->db->order_by('Ciudad','asc');
        $ciudades = $this->db->get('Ciudad');
        
        if($ciudades->num_rows()>0){ 
            return $ciudades->result();
        }
    }
}
?>