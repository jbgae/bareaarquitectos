<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Constructora_model extends Empresa_Model{
    
    var $Cif = '';
    var $Valoracion = '';
    
    private static $db;
    
    public function __construct() {
        parent::__construct();        
        self::$db = &get_instance()->db;
    }
    
    public function inicializar(){ 
        $aux = FALSE;
        
        $empresa = new Empresa_model;
        if($empresa->inicializar()){  
            $this->Cif = $this->input->post('cif');
            if($this->input->post('valoracion') != '')
                $this->Valoracion = $this->input->post('valoracion');
            else
                $this->Valoracion = NULL;
            $this->db->set('Cif', $this->Cif);
            $this->db->set('Valoracion', $this->Valoracion);
            if($this->db->insert('Constructora')){
                $aux = TRUE;
            }
        }
        
        return $aux;
    }
    
    public function datos($cif){             
        $query = $this->db->get_where('Constructoras', array('Cif'=>$cif));
        $constructora = $query->result();
        
        $this->Cif = $cif;
        $this->RazonSocial = $constructora[0]->RazonSocial;
        $this->Direccion = $constructora[0]->Direccion;
        $this->Ciudad = $constructora[0]->Ciudad;
        $this->Provincia = $constructora[0]->Provincia;
        $this->Email = $constructora[0]->Email;
        $this->Telefono = $constructora[0]->Telefono;
        $this->Fax =$constructora[0]->Fax;
        $this->Descripcion = $constructora[0]->Descripcion;
        $this->Web = $constructora[0]->Web;
        $this->Valoracion = $constructora[0]->Valoracion;
        
        return $this;            
    }
    
    
    public function valoracion($cif = ''){
        $aux = '';
        
        if($cif != ''){
            if($this->existe($cif)){
                $this->db->select('Valoracion');
                $this->db->from('Constructoras');
                $this->db->where('Cif', $cif);
                $query = $this->db->get();

                $empresa = $query->result();

                $aux = $empresa[0]->Valoracion;
            }
        }
        else{
            $aux = $this->Valoracion;
        }
        
        return $aux;
    }
    
    static function numero(){
        return self::$db->count_all('Constructoras');
    }
    
    
    static function obtener($campo, $orden, $offset = '', $limite = '') {
		
        $orden = ($orden == 'desc') ? 'desc' : 'asc';
        $sort_columns = array('Cif','RazonSocial', 'Direccion', 'Ciudad', 'Provincia', 'Email', 'Telefono', 'Valoracion');
        $campo = (in_array($campo, $sort_columns)) ? $campo : 'Cif';

        self::$db->select('*');
        self::$db->from('Constructoras');
        if($limite != '')
            self::$db->limit($limite, $offset);
        self::$db->order_by($campo, $orden);
        $query = self::$db->get();

        $proveedores = $query->result();

        return $proveedores;
    }
    
    
    public function buscar($dato, $campo, $orden, $offset, $limite){
        
        self::$db->select('*');
        self::$db->like('Descripcion', $dato);
        self::$db->like('Cif', $dato);
        self::$db->or_like('RazonSocial', $dato);
        self::$db->or_like('Direccion', $dato);
        self::$db->or_like('Ciudad', $dato);
        self::$db->or_like('Provincia', $dato);
        self::$db->limit($limite, $offset);
        self::$db->order_by($campo, $orden);
        
        $query = self::$db->get('Constructoras');        
        return $query->result();
    }
    
    
    
    public function busqueda_cantidad($dato){
        self::$db->select('*');
        self::$db->like('Descripcion', $dato);
        self::$db->like('Cif', $dato);
        self::$db->or_like('RazonSocial', $dato);
        self::$db->or_like('Direccion', $dato);
        self::$db->or_like('Ciudad', $dato);
        self::$db->or_like('Provincia', $dato);
        self::$db->from('Constructoras');
        return self::$db -> count_all_results();
    }
    
    public function actualizar($cif){
        $aux = FALSE;
        
        $empresa = new Empresa_model;
        $empresa->datos($cif);
        if($empresa->actualizar($cif)){
            if($this->input->post('valoracion') != '')
                $this->Valoracion = $this->input->post('valoracion');
            else
                $this->Valoracion = NULL;
            
            
            $this->db->set('Valoracion', $this->Valoracion);
            $this->db->where('Cif', $cif);
            if($this->db->update('Constructora')){
                $aux = TRUE;
            }
        }
        return $aux;
    }
}

?>