<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ciudad extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('ciudad_model', 'Ciudad');
        $this->load->model('provincia_model', 'Provincia');
    }
    
    
    public function ciudades(){
        $options = "";
        if($this->input->post('provincia')){
            $provincia = $this->input->post('provincia');
            $ciudades = $this->Ciudad->ciudades($provincia);
            foreach($ciudades as $ciudad){
                ?>
                    <option value="<?=$ciudad->Codigo;?>"><?=$ciudad->Ciudad;?></option>
                <?php
            }
        }
    }
}
?>