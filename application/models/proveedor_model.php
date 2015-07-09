<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proveedor_model extends Empresa_Model{
    
    var $Cif = '';
    var $Servicios = '';
    
    private static $db;
    
    public function __construct() {
        parent::__construct();        
        self::$db = &get_instance()->db;
    }
    
    public function inicializar(){
        $aux = FALSE;
        
        $empresa = new Empresa_model();
        if($empresa->inicializar()){ 
            $this->Cif = $this->input->post('cif');
            if($this->input->post('servicios') != '')
                $this->Servicios = $this->input->post('servicios');
            else
                $this->Servicios = NULL;
            $this->db->set('Cif', $this->Cif);
            $this->db->set('Servicios', $this->Servicios);
            if($this->db->insert('Proveedor')){
                $aux = TRUE;
            }
        }
        
        return $aux;
    }
    
    public function datos($cif){        
        $query = $this->db->get_where('Proveedores', array('Cif'=>$cif));
        $proveedor = $query->result();
        
        $this->Cif = $cif;
        $this->RazonSocial = $proveedor[0]->RazonSocial;
        $this->Direccion = $proveedor[0]->Direccion;
        $this->Ciudad = $proveedor[0]->Ciudad;
        $this->Provincia = $proveedor[0]->Provincia;
        $this->Email = $proveedor[0]->Email;
        $this->Telefono = $proveedor[0]->Telefono;
        $this->Fax =$proveedor[0]->Fax;
        $this->Descripcion = $proveedor[0]->Descripcion;
        $this->Web = $proveedor[0]->Web;
        $this->Servicios = $proveedor[0]->Servicios;
        
        return $this;            
    }
    
    public function servicios($cif = ''){
        $aux = '';
        
        if($cif != ''){
            if($this->existe($cif)){
                $this->db->select('Servicios');
                $this->db->from('Proveedores');
                $this->db->where('Cif', $cif);
                $query = $this->db->get();

                $empresa = $query->result();

                $aux = $empresa[0]->Servicios;
            }
        }
        else{
            $aux = $this->Servicios;
        }
        
        return $aux;
    }
    
    
    static function numero(){
        return self::$db->count_all('Proveedores');
    }
    
    
    static function obtener($campo, $orden, $offset = '', $limite = '') {
		
        $orden = ($orden == 'desc') ? 'desc' : 'asc';
        $sort_columns = array('Cif','RazonSocial', 'Direccion', 'CodigoPostal', 'Ciudad', 'Provincia', 'Email', 'Telefono', 'Web', 'Servicios');
        $campo = (in_array($campo, $sort_columns)) ? $campo : 'Cif';

        self::$db->select('*');
        self::$db->from('Proveedores');
        if($limite != '')
            self::$db->limit($limite, $offset);
        self::$db->order_by($campo, $orden);
        $query = self::$db->get();

        $proveedores = $query->result();

        return $proveedores;
    }
    
    
    static function buscar($dato, $campo, $orden, $offset, $limite){
        
        self::$db -> select('*');
        self::$db -> like('Descripcion', $dato);
        self::$db -> like('Cif', $dato);
        self::$db -> or_like('RazonSocial', $dato);
        self::$db -> or_like('Direccion', $dato);
        self::$db -> or_like('Ciudad', $dato);
        self::$db -> or_like('Provincia', $dato);
        self::$db -> limit($limite, $offset);
        self::$db -> order_by($campo, $orden);
        
        $query = self::$db->get('Proveedores');        
        return $query->result();
    }
    
    
    
    static function busqueda_cantidad($dato){
        self::$db -> select('*');
        self::$db -> like('Descripcion', $dato);
        self::$db -> like('Cif', $dato);
        self::$db -> or_like('RazonSocial', $dato);
        self::$db -> or_like('Direccion', $dato);
        self::$db -> or_like('Ciudad', $dato);
        self::$db -> or_like('Provincia', $dato);
        self::$db ->from('Proveedores');
        return self::$db -> count_all_results();
    }
    
    public function actualizar($cif){        
        $aux = FALSE;
        $empresa = new Empresa_model;
        $empresa->datos($cif);
        
        if($empresa->actualizar($cif)){
            if($this->input->post('servicios') != '')
                $this->Servicios = $this->input->post('servicios');
            else
                $this->Servicios = NULL;

            $this->db->set('Servicios', $this->Servicios);
            $this->db->where('Cif', $cif);
            if($this->db->update('Proveedor')){
                $aux = TRUE;
            }
        }
        return $aux;
    }
    
     
}

?>