<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Empresa_model extends CI_Model{
    
    var $Cif = '';
    var $RazonSocial = '';
    var $Direccion = '';
    var $Ciudad = '';
    var $Provincia = '';
    var $Email = '';
    var $Telefono = '';
    var $Fax = '';
    var $Descripcion = '';
    var $Web = '';
    
    private static $db;
    
    public function __construct() {
        parent::__construct();        
        self::$db = &get_instance()->db;
    }
    
    public function inicializar(){
        $aux = FALSE;
        
        if(!$this->existe($this->input->post('cif'))){
            $this->Cif = $this->input->post('cif');
            $this->RazonSocial = ucwords(strtolower($this->input->post('razon')));

            if($this->input->post('direccion') != '')
                $this->Direccion = $this->input->post('direccion');
            else
                $this->Direccion = NULL;

            if($this->input->post('ciudad') != 0)
                $this->Ciudad = $this->input->post('ciudad');
            else
                $this->Ciudad = NULL;

            if($this->input->post('provincia') != 0)
                $this->Provincia = $this->input->post('provincia');
            else
                $this->Provincia = NULL;

            if($this->input->post('email') != '')
                $this->Email = $this->input->post('email');
            else
                $this->Email = NULL;

            if($this->input->post('telefono') != '')
                $this->Telefono = $this->input->post('telefono');
            else
                $this->Telefono = NULL;

            if($this->input->post('fax') != '')
                $this->Fax = $this->input->post('fax');
            else
                $this->Fax = NULL;

            if($this->input->post('descripcion') != '')
                $this->Descripcion = $this->input->post('descripcion');
            else
                $this->Descripcion = NULL;

            if($this->input->post('web') != '')
                $this->Web = $this->input->post('web');
            else
                $this->Web = NULL;

            if($this->db->insert('Empresa', $this)){
                $aux = TRUE;
            }   
        }
        
        return $aux;        
    }
    
    
    public function datos($cif){
        $query = $this->db->get_where('Empresa', array('Cif' => $cif));
        $empresa = $query->result();        
        
        $this->Cif = $cif;
        $this->RazonSocial = $empresa[0]->RazonSocial;
        $this->Direccion = $empresa[0]->Direccion;
        $this->Ciudad = $empresa[0]->Ciudad;
        $this->Provincia = $empresa[0]->Provincia;
        $this->Email = $empresa[0]->Email;
        $this->Telefono = $empresa[0]->Telefono;
        $this->Fax = $empresa[0]->Fax;
        $this->Descripcion = $empresa[0]->Descripcion;
        $this->Web = $empresa[0]->Web;

        return $this;
    }
    
    
    public function cif($cif = ''){
        if($cif != ''){
            $this->Cif = $cif;
        }
        else{
            return $this->Cif;
        }
    }
    
    
     public function razonSocial($cif = ''){
        $aux = '';
        
        if($cif != ''){
            if($this->existe($cif)){
                $this->db->select('RazonSocial');
                $this->db->from('Empresas');
                $this->db->where('Cif', $cif);
                $query = $this->db->get();

                $usuario = $query->result();

                $aux = $usuario[0]->RazonSocial;
            }
        }
        else{
            $aux = $this->RazonSocial;
        }
        
        return $aux;
    }
    
    
    public function direccion($cif = ''){
        $aux = '';
        
        if($cif != ''){
            if($this->existe($email)){
                $this->db->select('Direccion');
                $this->db->from('Empresas');
                $this->db->where('Cif', $cif);
                $query = $this->db->get();

                $empresa = $query->result();

                $aux = $empresa[0]->Direccion;
            }
        }
        else{
            $aux = $this->Direccion;
        }
        
        return $aux;
    }
    
    
    public function ciudad($cif = '', $num = FALSE){
        $aux = ''; 
        
        if($cif != ''){
            if($this->existe($cif)){
                $this->db->select('Ciudad');
                if($num == TRUE) 
                    $this->db->from('Empresa');
                else
                    $this->db->from('Empresas');
                $this->db->where('Cif', $cif);
                $query = $this->db->get();

                $empresa = $query->result();
                
                return $empresa[0]->Ciudad;
            }
        }
        else{
            $aux = $this->Ciudad;
        }
        
        return $aux;
    }
    
    
    public function provincia($cif = '', $num = FALSE){
        $aux = '';
        
        if($cif != ''){
            if($this->existe($cif)){
                $this->db->select('Provincia');
                if($num == TRUE)
                    $this->db->from('Empresa');
                else
                    $this->db->from('Empresas');
                $this->db->where('Cif', $cif);
                $query = $this->db->get();

                $empresa = $query->result();

                $aux = $empresa[0]->Provincia;
            }
        }
        else{
            $aux = $this->Provincia;
        }
        
        return $aux;
    }
    
    
    public function telefono($cif = ''){
        $aux = '';
        
        if($cif != ''){
            if($this->existe($cif)){
                $this->db->select('Telefono');
                $this->db->from('Empresas');
                $this->db->where('Cif', $cif);
                $query = $this->db->get();

                $empresa = $query->result();

                $aux = $empresa->Telefono;
            }
        }
        else{
            $aux = $this->Telefono;
        }
        
        return $aux;
    }
    
    
    public function email($cif = ''){
        $aux = '';
        
        if($cif != ''){
            if($this->existe($cif)){
                $this->db->select('Email');
                $this->db->from('Empresas');
                $this->db->where('Cif', $cif);
                $query = $this->db->get();

                $empresa = $query->result();

                $aux = $empresa->Email;
            }
        }
        else{
            $aux = $this->Email;
        }
        
        return $aux;
    }
    
    
    public function fax($cif = ''){
        $aux = '';
        
        if($cif != ''){
            if($this->existe($cif)){
                $this->db->select('Fax');
                $this->db->from('Empresas');
                $this->db->where('Cif', $cif);
                $query = $this->db->get();

                $empresa = $query->result();

                $aux = $empresa->Fax;
            }
        }
        else{
            $aux = $this->Fax;
        }
        
        return $aux;
    }
    
    
    public function descripcion($cif = ''){
        $aux = '';
        
        if($cif != ''){
            if($this->existe($cif)){
                $this->db->select('Descripcion');
                $this->db->from('Empresas');
                $this->db->where('Cif', $cif);
                $query = $this->db->get();

                $empresa = $query->result();

                $aux = $empresa->Descripcion;
            }
        }
        else{
            $aux = $this->Descripcion;
        }
        
        return $aux;
    }
    
    
    public function web($cif = ''){
        $aux = '';
        
        if($cif != ''){
            if($this->existe($cif)){
                $this->db->select('Web');
                $this->db->from('Empresas');
                $this->db->where('Cif', $cif);
                $query = $this->db->get();

                $empresa = $query->result();

                $aux = $empresa->Web;
            }
        }
        else{
            $aux = $this->Web;
        }
        
        return $aux;
    }
    
    
    
    static function existe($cif){        
        $query = self::$db->get_where("Empresa", array('Cif'=>$cif));
        
        if($query->num_rows() > 0){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }
    
    
    
    public function actualizar($cif){
        $aux = FALSE;
        
        $this->RazonSocial = ucwords(strtolower($this->input->post('razon')));
        
        if($this->input->post('direccion') != '')
            $this->Direccion = $this->input->post('direccion');
        else
            $this->Direccion = NULL;

        if($this->input->post('ciudad') != 0)
            $this->Ciudad = $this->input->post('ciudad');
        else
            $this->Ciudad = NULL;
        
        if($this->input->post('provincia') != 0)
            $this->Provincia = $this->input->post('provincia');
        else
            $this->Provincia = NULL;

        if($this->input->post('telefono') != '')
            $this->Telefono = $this->input->post('telefono');
        else
            $this->Telefono = NULL;
        
        if($this->input->post('email') != '')
            $this->Email = $this->input->post('email');
        else
            $this->Email = NULL;
        
        if($this->input->post('fax') != '')
            $this->Fax = $this->input->post('fax');
        else
            $this->Fax = NULL;
        
        if($this->input->post('descripcion  ') != '')
            $this->Descripcion = $this->input->post('descripcion');
        else
            $this->Descripcion = NULL;
        
        if($this->input->post('web') != '')
            $this->Web = $this->input->post('web');
        else
            $this->Web = NULL;
      
        if($this->db->update('Empresa', $this, array('Cif'=>$cif))){
            $aux = TRUE;
        }   
        
        return $aux;  
    }
    
    
    
    public function eliminar($cif = ''){
        $aux = FALSE;
        
        if($cif != ''){
            if($this->existe($cif)){
                if($this->db->delete('Empresa', array('Cif' => $cif))){
                    $aux = TRUE;
                }
            }
        }
        else{
            if($this->existe($this->Cif)){
                if($this->db->delete('Empresa', array('Cif' => $this->Cif))){
                    $aux = TRUE;
                }
            }
        }                
                
        return $aux;
    }
       
}

?>
