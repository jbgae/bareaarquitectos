<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cliente_model extends Usuario_model{
    
    private static $db;
    
    public function __construct() {
        parent::__construct();        
        self::$db = &get_instance()->db;
    }
    
    
    public function datos($email){        
        Usuario_model::datos($email);
        
        $query = $this->db->get_where('Clientes', array('Email'=>$email)); 
        $cliente = $query->result();

        return $cliente[0];
    }
    
    
    
    static function existe($email){
        $aux = FALSE;
        
        if($email != ''){
            $query = self::$db->get_where('Clientes', array('Email'=>$email));        
            if($query->num_rows() > 0){
                $aux = TRUE;
            }
        }
        
        return $aux;
    }
    
    
    static function recientes($limite){
        self::$db->select('Nombre, ApellidoP, ApellidoM, FechaAltaSistema');
        self::$db->from('Clientes');
        self::$db->order_by("FechaAltaSistema", "desc");
        self::$db->limit($limite);
        
        $query = self::$db->get(); 
        
        return $query->result();
    }
    
    
    static function buscar($dato, $campo, $orden, $offset, $limite){
        
        self::$db->select('*');
        self::$db->like('Nombre', $dato);
        self::$db->or_like('ApellidoP', $dato);
        self::$db->or_like('ApellidoM', $dato);
        self::$db->or_like('Direccion', $dato);
        self::$db->or_like('Ciudad', $dato);
        self::$db->or_like('Provincia', $dato);
        self::$db->or_like('CONCAT(Nombre, " ", ApellidoP, " ", ApellidoM)', $dato);
        self::$db->or_like('CONCAT(Nombre, " ", ApellidoP)', $dato);
        self::$db->or_like('CONCAT(ApellidoP, " ", ApellidoM)', $dato);
        self::$db->limit($limite, $offset);
        self::$db->order_by($campo, $orden);
        
        $query = self::$db->get('Clientes');        
        return $query->result();
    }
    
    
    static function busqueda_cantidad($dato){
        self::$db->like('Nombre', $dato);
        self::$db->or_like('ApellidoP', $dato);
        self::$db->or_like('ApellidoM', $dato);
        self::$db->or_like('Direccion', $dato);
        self::$db->or_like('Ciudad', $dato);
        self::$db->or_like('Provincia', $dato);
        self::$db->or_like('CONCAT(Nombre, " ", ApellidoP, " ", ApellidoM)', $dato);
        self::$db->or_like('CONCAT(Nombre, " ", ApellidoP)', $dato);
        self::$db->or_like('CONCAT(ApellidoP, " ", ApellidoM)', $dato);
        self::$db->from('Clientes');
        return self::$db->count_all_results();
    }
    
    
    static function numero(){
        return self::$db->count_all('Clientes');
    }
    
    
    static function obtener($campo, $orden, $offset = '', $limite = '') {
		
        $orden = ($orden == 'desc') ? 'desc' : 'asc';
        $sort_columns = array('Email', 'Nombre', 'FechaAltaSistema', 'FechaUltimoAcceso', 'Presupuestos', 'Proyectos');
        $campo = (in_array($campo, $sort_columns)) ? $campo : 'Nombre';

        self::$db->select('Clientes.*');
        self::$db->from(' Clientes');
        
        if($limite != '')
            self::$db->limit($limite, $offset);
        self::$db->order_by($campo, $orden);
        
        $query = self::$db->get();

        $clientes = $query->result();
        
        return $clientes;
    }
    
    
}    
?>