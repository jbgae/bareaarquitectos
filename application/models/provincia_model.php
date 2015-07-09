<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Provincia_model extends CI_Model{
    
    public function __construct() {
        parent::__construct();        
        $this->load->database();
    }
    
    public function obtener(){        
        $query = $this->db->get('Provincia');        
        $provincia = array('0'=>'');        
        foreach ($query->result_array() as $row){
            $provincia[$row['Codigo']] = $row['Provincia'];
        }
        return $provincia;       
    }
    
    public function provincia($codigo){
        $aux = 'Desconocida';
        
        if($codigo != 0 && $codigo != ''){
            $this->db->select('Provincia');
            $this->db->from('Provincia');
            $this->db->where('Codigo', $codigo);
            $query = $this->db->get();

            $provincia = $query->result();

            $aux = $provincia[0]->Provincia;
        }
        
        return $aux;
    }
}
?>