<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Texto_model extends CI_Model{
    
    var $Texto = '';
    
    public function __construct() {
        parent::__construct();        
        $this->load->database();
    }
    
    public function actualizar($nombre, $posicion){
        $aux = FALSE;
        
        $this->db->set('Texto',  $this->input->post('texto')); 
        $this->db->where('NombrePagina', $nombre);
        $this->db->where('Posicion', $posicion);
        if($this->db->update('Texto'))               
            $aux = TRUE;
        return $aux;
        
    }
}

?>