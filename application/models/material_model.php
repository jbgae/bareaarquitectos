<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Material_model extends CI_Model{
    
    var $Nombre = '';
    var $CifProveedor = '';
    var $CodigoProyecto = '';
    
    private static $db;
    
    public function __construct() {
        parent::__construct();
        self::$db = &get_instance()->db;
    }
    
    public function inicializar($material, $cif, $codigo){
        $aux = FALSE;
        
        $this->Nombre = $material;
        $this->CifProveedor = $cif;
        $this->CodigoProyecto = $codigo;
        
        if($this->db->insert('Material', $this)){
            $aux = TRUE;
        }
        
        return $aux;
    }
    
    static function obtener($codigoProyecto){
        self::$db->select('Codigo,Nombre, RazonSocial');
        self::$db->from('Materiales');
        self::$db->where('CodigoProyecto', $codigoProyecto);
        
        $query = self::$db->get();       
                             
        $material = $query->result();
        
        return $material;
    }
    
    public function eliminar($codigo){
        $aux = FALSE;
        
        if($this->db->delete('Material', array('Codigo' => $codigo))){
            $aux = TRUE;
        }
        
        return $aux;
    }
    

}

?>