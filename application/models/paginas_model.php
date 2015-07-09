<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paginas_model extends CI_Model{
      
    var $EmailAdmin = '';
    
    private static $db;
    
    public function __construct() {
        parent::__construct();        
        self::$db = &get_instance()->db;
    }
    
    
    public function actualizar($nombre){
        $aux = FALSE;
        $this->db->set('EmailAdmin',  $this->session->userdata('email')); 
        $this->db->where('Nombre', $nombre);
        if($this->db->update('Pagina'))
            $aux = TRUE;
        return $aux;
    }
    
    
    public function datos($nombre, $posicion){
        $query = $this->db->get_where('Paginas', array('NombrePagina'=>$nombre, 'Posicion'=> $posicion));
        $pagina = $query->result();
        return $pagina; 
    }
    
    
    public function email($nombre){
        $this->db->select('Email');
        $this->db->from('Pagina');
        $this->db->where('NombrePagina', $nombre);
        $query = $this->db->get();
        
        $pagina = $query->result();

        return $pagina[0]->Email;
    }
    
    
    public function nombre($nombre){
        $this->db->select('Nombre');
        $this->db->from('Pagina');
        $this->db->where('NombrePagina', $nombre);
        $query = $this->db->get();
        
        $pagina = $query->result();

        return $pagina[0]->Nombre;
    }
    
    
    static function numero(){
        return self::$db->count_all('Paginas');
    }
    
    
    private function _limitar_caracteres($palabras, $paginas){
        
        foreach($paginas as &$pagina){
            $aux = explode(" ", strip_tags($pagina->Texto));
            $cadena = '';
            if(count($aux) > $palabras){
                for($i = 0; $i < $palabras; $i++){
                    $cadena .= $aux[$i]. " ";
                }
                $pagina->TextoCortado = $cadena;
                $pagina->TextoCortado .= '...';
            }
            $cadena = '';
        }
        return $paginas;
    }
    
    public function texto($id){
        $texto = '';
        
        $this->db->select('*');
        $this->db->from('Paginas');
        $this->db->where('NombrePagina', $id);
        $query = $this->db->get();       
        
        if($query->num_rows() > 0){
            if($query->num_rows() == 1){
                $text = $query->result();
                $texto = $text['0']->Texto;
            }
            else{
                $texto = array();
                foreach($query->result() as $text){
                    $texto[$text->Posicion] = $text->Texto;
                }
            }    
        }
        return $texto;
    }
    
    
    
    static function obtener($campo, $orden, $offset = '', $limite = '', $cortado = FALSE) {
		
        $orden = ($orden == 'desc') ? 'desc' : 'asc';
        $sort_columns = array('NombrePagina', 'Posicion', 'Texto', 'Nombre');
        $campo = (in_array($campo, $sort_columns)) ? $campo : 'NombrePagina';

        self::$db->select('*');
        self::$db->from('Paginas');
        if($limite != '')
            self::$db->limit($limite, $offset);
        self::$db->order_by($campo, $orden);
        $query = self::$db->get();
             
        $paginas = $query->result();
        
        if($cortado){
            Paginas_model::_limitar_caracteres(30, $paginas);
        }

        return $paginas;
    } 
    
    
    static function buscar($dato, $campo, $orden, $offset, $limite, $cortado = FALSE){
        
        self::$db-> select('*');
        self::$db-> like('NombrePagina', $dato);
        self::$db-> or_like('Posicion', $dato);
        self::$db-> or_like('Texto', $dato);
        self::$db-> or_like('Nombre', $dato);
        self::$db-> or_like('ApellidoP', $dato);
        self::$db-> or_like('ApellidoM', $dato);
        self::$db-> or_like('CONCAT(Nombre, " ", ApellidoP, " ", ApellidoM)', $dato);
        self::$db-> or_like('CONCAT(Nombre, " ", ApellidoP)', $dato);
        self::$db-> or_like('CONCAT(ApellidoP, " ", ApellidoM)', $dato);
        self::$db-> limit($limite, $offset);
        self::$db-> order_by($campo, $orden);
        
        $query = self::$db->get('Paginas');
        $paginas = $query->result();
        
        if($cortado){
            Paginas_model::_limitar_caracteres(30, $paginas);
        }
        
        return $paginas;
    }
    
    
    static function busqueda_cantidad($dato){
        
        self::$db-> like('NombrePagina', $dato);
        self::$db-> or_like('Posicion', $dato);
        self::$db-> or_like('Texto', $dato);
        self::$db-> or_like('Nombre', $dato);
        self::$db-> or_like('ApellidoP', $dato);
        self::$db-> or_like('ApellidoM', $dato);
        self::$db-> or_like('CONCAT(Nombre, " ", ApellidoP, " ", ApellidoM)', $dato);
        self::$db-> or_like('CONCAT(Nombre, " ", ApellidoP)', $dato);
        self::$db-> or_like('CONCAT(ApellidoP, " ", ApellidoM)', $dato);
        self::$db->from('Paginas');
        return self::$db-> count_all_results();
    }
    

}
?>
