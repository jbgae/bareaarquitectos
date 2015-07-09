<?php

class Captcha_model extends CI_Model{
    
    public function __construct() {
        parent::__construct();        
        $this->load->database();
        $this->load->helper('captcha');
    }
    
    public function crear_captcha(){
        
        $datos_captcha = array(
            'word' => '',
            'img_path' => './captcha/',
            'img_url' => base_url() . 'captcha/',
            'img_width' => '225',
            'img_height' => '50',
            'expiration' => '3600'
        );

        $captcha = create_captcha($datos_captcha);
            
        
        $datos = array(
            'captcha_time' => $captcha['time'],
            'ip_address' => $this->input->ip_address(),
            'word'=>$captcha['word']
        );
        
        $query = $this->db->insert_string('captcha', $datos);
        $this->db->query($query);
        
        return $captcha['image'];
    }
    
    public function verificar_captcha($word){
        // Primero, borrar las captchas viejas
        $expiration = time()-7200; // Límite de dos horas
        $this->db->query("DELETE FROM captcha WHERE captcha_time < ".$expiration);
        // Luego, ver si existe un captcha:
        $sql = "SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";
        $binds = array($word, $this->input->ip_address(), $expiration);
        $query = $this->db->query($sql, $binds);
        $row = $query->row();
        if ($row->count == 0){
            return FALSE;
        }
        else{
            return TRUE;
        }  
    }
    
}    
?>
