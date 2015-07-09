<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Usuario_model extends CI_Model{
    
    
    var $Email = '';
    var $Nombre = '';
    var $ApellidoP = '';
    var $ApellidoM = '';
    var $FechaNacimiento = '';
    var $Direccion = '';
    var $Ciudad = '';
    var $Provincia = '';
    var $Telefono = '';
    var $FechaAltaSistema = '';
    var $FechaUltimoAcceso = '';
    var $Tipo = '';
    var $Validado = '';
    var $NumeroIntentos = '';
    var $FechaUltimoIntento = '';
    var $Pass = '';
    
    private static $db;
    
    
        
    public function __construct() {
        parent::__construct();        
        self::$db = &get_instance()->db;
    }
    
    
    
    public function inicializar($tipo = 'cliente', $datos = ''){
        $aux = FALSE;
       
        if($datos == ''){
            if(!$this->existe(strtolower($this->input->post('email')))){
                $this->Email = strtolower($this->input->post('email'));
                $this->Nombre = ucwords(strtolower($this->input->post('nombre')));
                $this->ApellidoP = ucwords(strtolower($this->input->post('primerApellido')));
                $this->ApellidoM = ucwords(strtolower($this->input->post('segundoApellido')));
                $this->FechaNacimiento = date('Y-m-d',strtotime($this->input->post('fNacimiento')));
                $this->FechaAltaSistema = date('Y/m/d h:i:s');
                $this->FechaUltimoAcceso = date('Y/m/d h:i:s');
                $this->Tipo = $tipo;
                $this->Validado = 0;
                $this->NumeroIntentos = 0;
                $this->FechaUltimoIntento = date('Y/m/d h:i:s');
                $this->Pass = md5($this->input->post('pass'));

                if($this->input->post('direccion') != '')
                    $this->Direccion = $this->input->post('direccion');

                if($this->input->post('ciudad') != 0)
                    $this->Ciudad = $this->input->post('ciudad');

                if($this->input->post('provincia') != 0)
                    $this->Provincia = $this->input->post('provincia');

                if($this->input->post('telefono') != '')
                    $this->Telefono = $this->input->post('telefono');

                if($this->db->insert('Usuario', $this)){
                    $aux = TRUE;
                } 
            }
        }
        else{
            if((!empty($datos))){
                $datos['tipo'] = $tipo;
                if($this->db->insert('Usuario', $datos)){
                    $aux = TRUE;
                }
            }
        }
        
        return $aux;        
    }
    
    
    
    public function datos($email){
        
        $query = $this->db->get_where('Usuarios', array('Email' => $email));
        $usuario = $query->result();

        $this->Email = $email;
        $this->Nombre = $usuario[0]->Nombre; 
        $this->ApellidoP = $usuario[0]->ApellidoP; 
        $this->ApellidoM = $usuario[0]->ApellidoM; 
        $this->FechaNacimiento = $usuario[0]->FechaNacimiento; 
        $this->Direccion = $usuario[0]->Direccion; 
        $this->Ciudad = $usuario[0]->Ciudad; 
        $this->Provincia = $usuario[0]->Provincia;
        $this->Telefono = $usuario[0]->Telefono;
        $this->FechaAltaSistema = $usuario[0]->FechaAltaSistema; 
        $this->FechaUltimoAcceso = $usuario[0]->FechaUltimoAcceso; 
        $this->Tipo = $usuario[0]->Tipo; 
        $this->Validado = $usuario[0]->Validado; 
        $this->NumeroIntentos = $usuario[0]->NumeroIntentos; 
        $this->FechaUltimoIntento = $usuario[0]->FechaUltimoIntento; 
        $this->Pass = $usuario[0]->Pass; 

        return $this;      
    }
    
    
    
    public function email($email = ''){
        if($email != ''){
            $this->Email = $email;
        }
       
        return $this->Email;
    }
    
    
       
    public function nombre($email = ''){
        $aux = '';
        
        if($email != ''){
            if($this->existe($email)){
                $this->db->select('Nombre');
                $this->db->from('Usuarios');
                $this->db->where('Email', $email);
                $query = $this->db->get();

                $usuario = $query->result();

                $aux = $usuario[0]->Nombre;
            }
        }
        else{
            $aux = $this->Nombre;
        }
        
        return $aux;
    }
    
    
    
    public function primerApellido($email = ''){
        $aux = '';
        
        if($email != ''){
            if($this->existe($email)){
                $this->db->select('ApellidoP');
                $this->db->from('Usuarios');
                $this->db->where('Email', $email);
                $query = $this->db->get();

                $usuario = $query->result();

                $aux = $usuario[0]->ApellidoP;            
            }
        }
        else{
            $aux = $this->ApellidoP;
        }    
        
        return $aux;
    }
    
    
    
    public function segundoApellido($email = ''){
        $aux = '';
        
        if($email != ''){
            if($this->existe($email)){
                $this->db->select('ApellidoM');
                $this->db->from('Usuarios');
                $this->db->where('Email', $email);
                $query = $this->db->get();

                $usuario = $query->result();

                $aux = $usuario[0]->ApellidoM;
            }        
        }
        else{
            $aux = $this->ApellidoM;
        }
        
        return $aux;
    }
    
    
    
    public function fechaNacimiento($email = ''){
        $aux = '';
    
        if($email != ''){
            if($this->existe($email)){
                $this->db->select('FechaNacimiento');
                $this->db->from('Usuarios');
                $this->db->where('Email', $email);
                $query = $this->db->get();

                $usuario = $query->result();

                $aux = $usuario[0]->FechaNacimiento;
            }
        }
        else{
            $aux = $this->FechaNacimiento;
        }
        
        return date('d/m/Y', strtotime($aux));
    }
    
    
    
    public function pass($email = ''){
        $aux = '';

        if($email != ''){
            if($this->existe($email)){
                $this->db->select('Pass');
                $this->db->from('Usuarios');
                $this->db->where('Email', $email);
                $query = $this->db->get();

                $usuario = $query->result();

                $aux = $usuario[0]->Pass;
            }
        }
        else{
            $aux = $this->Pass;
        }
        
        return $aux;
    }
    
    
    
    public function fechaAlta($email = ''){
        $aux = '';
        
        if($email != ''){
            if($this->existe($email)){
                $this->db->select('FechaAltaSistema');
                $this->db->from('Usuarios');
                $this->db->where('Email', $email);
                $query = $this->db->get();

                $usuario = $query->result();

                $aux = $usuario[0]->FechaAltaSistema;
            }
        }
        else{
            $aux = $this->FechaAltaSistema;
        }
        
        return date('d/m/Y H:i:s', strtotime($aux));
    }
    
    
    
    public function fechaUltimoAcceso($email = ''){
        $aux = '';  
        if($email != ''){
            if($this->existe($email)){
                $this->db->select('FechaUltimoAcceso');
                $this->db->from('Usuarios');
                $this->db->where('Email', $email);
                $query = $this->db->get();

                $usuario = $query->result();

                $aux = $usuario[0]->FechaUltimoAcceso;
            }    
        }
        else{
            $aux = $this->FechaUltimoAcceso;
        }
        
        return date('d/m/Y H:i:s', strtotime($aux));
    }
    
    
    public function validado($email = ''){
        $aux = '';
        
        if($email != ''){
            if($this->existe($email)){
                $this->db->select('Validado');
                $this->db->from('Usuarios');
                $this->db->where('Email', $email);
                $query = $this->db->get();

                $usuario = $query->result();

                $aux = $usuario[0]->Validado;            
            }
        }
        else{
            $aux = $this->Validado;
        }
        
        return $aux;
    }
    
    
    public function tipo($email = ''){
        $aux = '';
        
        if($email != ''){
            if($this->existe($email)){
                $this->db->select('Tipo');
                $this->db->from('Usuarios');
                $this->db->where('Email', $email);
                $query = $this->db->get();

                $usuario = $query->result();

                $aux = $usuario[0]->Tipo;
            }
        }
        else{
            $aux = $this->Tipo;
        }
        
        return $aux;
    }
    
    
    public function numeroIntentos($email = ''){
        $aux = '';
        
        if($email != ''){
            if($this->existe($email)){
                $this->db->select('NumeroIntentos');
                $this->db->from('Usuarios');
                $this->db->where('Email', $email);
                $query = $this->db->get();

                $usuario = $query->result();

                $aux = $usuario[0]->NumeroIntentos;
            }
        }
        else{
            $aux = $this->NumeroIntentos;
        }
        
        return $aux;
    }
    
    
    public function fechaUltimoIntento($email = ''){
        $aux = '';
        
        if($email != ''){
            if($this->existe($email)){
                $this->db->select('FechaUltimoIntento');
                $this->db->from('Usuarios');
                $this->db->where('Email', $email);
                $query = $this->db->get();

                $usuario = $query->result();

                $aux = $usuario[0]->FechaUltimoIntento;
            }
        }
        else{
            $aux = $this->FechaUltimoIntento;
        }
        
        return date('d/m/Y H:i:s', strtotime($aux));
    }
    
    
    public function direccion($email = ''){
        $aux = '';
        
        if($email != ''){
            if($this->existe($email)){
                $this->db->select('Direccion');
                $this->db->from('Usuarios');
                $this->db->where('Email', $email);
                $query = $this->db->get();

                $usuario = $query->result();

                $aux = $usuario[0]->Direccion;
            }
        }
        else{
            $aux = $this->Direccion;
        }
        
        return $aux;
    }
    
    
    public function ciudad($email = '', $num = FALSE){
        $aux = '';
        
        if($email != ''){
            if($this->existe($email)){
                $this->db->select('Ciudad');
                if($num == TRUE)
                    $this->db->from('Usuario');
                else
                    $this->db->from('Usuarios');
                $this->db->where('Email', $email);
                $query = $this->db->get();

                $usuario = $query->result();

                return $usuario[0]->Ciudad;
            }
        }
        else{
            $aux = $this->Ciudad;
        }
        
        return $aux;
    }
    
    
    public function provincia($email = '', $num = FALSE){
        $aux = '';
        
        if($email != ''){
            if($this->existe($email)){
                $this->db->select('Provincia');
                if($num == TRUE)
                    $this->db->from('Usuario');
                else
                    $this->db->from('Usuarios');
                $this->db->where('Email', $email);
                $query = $this->db->get();

                $usuario = $query->result();

                $aux = $usuario[0]->Provincia;
            }
        }
        else{
            $aux = $this->Provincia;
        }
        
        return $aux;
    }
    
    
    public function telefono($email = ''){
        $aux = '';
        
        if($email != ''){
            if($this->existe($email)){
                $this->db->select('Telefono');
                $this->db->from('Usuarios');
                $this->db->where('Email', $email);
                $query = $this->db->get();

                $usuario = $query->result();

                $aux = $usuario[0]->Telefono;
            }
        }
        else{
            $aux = $this->Telefono;
        }
        return $aux;
    }
    
    
    
    public function login($email, $pass){
        $aux = FALSE;
        
        if($this->existe($email)){
            if(md5($pass) == $this->pass($email)){
                $aux = TRUE; 
            }
        }
       
        return $aux;
    }
    
    
    
    static function existe($email){
        $aux = FALSE;
        
        if($email != ''){
            $query = self::$db->get_where("Usuario", array('Email'=>$email));

            if($query->num_rows() > 0){
                $aux = TRUE;
            }
        }
        return $aux;
    }
    
    
    
    public function actualizar($email, $datos = ''){
        $aux = FALSE;        
        
        if($datos != ''){
            if(!empty($datos) ){
                if($this->db->update('Usuario', $datos, array('Email'=> $email))){
                    $aux = TRUE;
                }            
            }
        }
        else{
            $this->Email = strtolower($email);
            $this->Nombre = ucwords(strtolower($this->input->post('nombre')));
            $this->ApellidoP = ucwords(strtolower($this->input->post('primerApellido')));
            $this->ApellidoM = ucwords(strtolower($this->input->post('segundoApellido')));
            $this->FechaNacimiento = date('Y-m-d',strtotime($this->input->post('fNacimiento')));
            if($this->input->post('pass')!= '')
                $this->Pass = md5($this->input->post('pass'));

            if($this->input->post('direccion') != '')
                $this->Direccion = $this->input->post('direccion');

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

            if($this->db->update('Usuario', $this, array('Email'=> $email))){
                $aux = TRUE;
            }
        }
        
        return $aux;
    }   
    
    
    
    public function eliminar($email = ''){
        $aux = FALSE;
        
        if($email != ''){
            if($this->existe($email)){
                if($this->db->delete('Usuario', array('Email' => $email))){
                    $aux = TRUE;
                }
            }
        }
        else{ 
            if($this->existe($this->Email)){ 
                if($this->db->delete('Usuario', array('Email' => $this->Email))){
                    $aux = TRUE;
                }
            }
        }                
                
        return $aux;
    }
    
    static function admin(){
        $admin = array();
        self::$db->select('Email');
        self::$db->from('Administrador');
        $query = self::$db->get();
        $aux = $query->result();
        if(!empty($aux)){
            foreach($aux as $ad)
                $admin[$ad->Email] = "admin";
        }
        return $admin;
    }
}

?>
