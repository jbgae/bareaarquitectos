<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Noticias_model extends CI_Model{
    
    var $Titulo = '';
    var $Contenido = '';
    var $FechaCreacion = '';
    var $EmailAdministrador = '';
    
    private static $db;
    
    public function __construct() {
        parent::__construct();        
        self::$db = &get_instance()->db;
    }
    
    
    public function inicializar(){
        $aux = FALSE;
        
        $this->db->set('Titulo', $this->input->post('titulo'));
        $this->db->set('Contenido', $this->input->post('contenido'));
        $this->db->set('FechaCreacion', date('Y-m-d H:i:s'));
        $this->db->set('EmailAdministrador', $this->session->userdata('email'));
        
        if($this->db->insert('Noticia')){
            $aux = TRUE;
        }
                
        return $aux;
    }
    
    
    public function datos($id){        
        $query = $this->db->get_where('Noticias', array('Codigo'=>$id));
        $noticia = $query->result();
        
        $this->Titulo = $noticia[0]->Titulo;
        $this->Contenido = $noticia[0]->Contenido;
        $this->FechaCreacion = $noticia[0]->FechaCreacion;
        $this->EmailAdministrador = $noticia[0]->EmailAdministrador;
        
        return $this;            
    }
    
    
    public function titulo($id = ''){
        $aux = '';
        if($id != ''){
            $this->db->select('Titulo');
            $this->db->from('Noticias');
            $this->db->where('Codigo', $id);
            $query = $this->db->get();

            $noticia = $query->result();

            $aux = $noticia[0]->Titulo;
        }
        else{
            $aux = $this->Titulo;
        }
        
        return $aux;
    }
    
    
    public function contenido($id = ''){
        $aux = '';
        
        if($id != ''){
            $this->db->select('Contenido');
            $this->db->from('Noticias');
            $this->db->where('Codigo', $id);
            $query = $this->db->get();

            $noticia = $query->result();

            $ux = $noticia[0]->Contenido;
        }
        else{
            $aux = $this->Contenido;
        }
        
        return $aux;
    }
    
    
    public function fecha($id = ''){
        $aux = '';
        
        if($id != ''){
            $this->db->select('FechaCreacion');
            $this->db->from('Noticias');
            $this->db->where('Codigo', $id);
            $query = $this->db->get();

            $noticia = $query->result();

            $aux = $noticia[0]->FechaCreacion;
        }
        else{
            $aux = $this->FechaCreacion;
        }
        return $aux;
    }
    
    
    public function escritor($id){
        $this->db->select('Nombre, ApellidoP, ApellidoM');
        $this->db->from('Noticias');
        $this->db->where('Codigo', $id);
        $query = $this->db->get();
        
        $noticia = $query->result();

        return $noticia[0]->Nombre . ' ' . $noticia[0]->ApellidoP . ' ' . $noticia[0]->ApellidoM;
    }


    
    static function numero(){
        return self::$db->count_all('Noticias');
    }
    

    
    static function obtener($campo, $orden, $offset = '', $limite = '', $cortado = FALSE) {
		
        $orden = ($orden == 'desc') ? 'desc' : 'asc';
        $sort_columns = array('Titulo', 'Contenido', 'FechaCreacion', 'Escritor');
        $campo = (in_array($campo, $sort_columns)) ? $campo : 'Titulo';

        self::$db->select('*');
        self::$db->from('Noticias');
        if($limite != '')
            self::$db->limit($limite, $offset);
        self::$db->order_by($campo, $orden);
        $query = self::$db->get();

        $noticias = $query->result();

        if($cortado)
            Noticias_model::_limitar_caracteres (50, $noticias);
            
        return $noticias;
    }
    

    
   /* public function buscar_noticias($dato, $limit){
        $this -> db -> like('Titulo', $dato);
        $this -> db -> or_like('Contenido', $dato); 
        $query = $this->db->get('Noticias');
        return $this->_limitar_caracteres($limit, $query->result_array());
    }*/
    
    static function buscar($dato, $campo, $orden, $offset, $limite, $cortado = FALSE){
        
        self::$db-> select('*');
        self::$db-> like('Titulo', $dato);
        self::$db-> or_like('Contenido', $dato);
        self::$db-> or_like('Nombre', $dato);
        self::$db-> or_like('ApellidoP', $dato);
        self::$db-> or_like('ApellidoM', $dato);
        self::$db-> or_like('CONCAT(Nombre, " ", ApellidoP, " ", ApellidoM)', $dato);
        self::$db-> or_like('CONCAT(Nombre, " ", ApellidoP)', $dato);
        self::$db-> or_like('CONCAT(ApellidoP, " ", ApellidoM)', $dato);
        self::$db-> limit($limite, $offset);
        self::$db-> order_by($campo, $orden);
        
        $query = self::$db->get('Noticias');
        $noticias = $query->result();
        
        if($cortado){
            Noticias_model::_limitar_caracteres(30, $noticias);
        }
        
        return $noticias;
    }
    
    
    public function busqueda_cantidad($dato){
        self::$db-> select('*');
        self::$db-> like('Titulo', $dato);
        self::$db-> or_like('Contenido', $dato);
        self::$db-> or_like('Nombre', $dato);
        self::$db-> or_like('ApellidoP', $dato);
        self::$db-> or_like('ApellidoM', $dato);
        self::$db-> or_like('CONCAT(Nombre, " ", ApellidoP, " ", ApellidoM)', $dato);
        self::$db-> or_like('CONCAT(Nombre, " ", ApellidoP)', $dato);
        self::$db-> or_like('CONCAT(ApellidoP, " ", ApellidoM)', $dato);
        self::$db ->from('Noticias');
        return self::$db -> count_all_results();
    }
    
    
    public function actualizar($id){
        $aux = FALSE;
        
        $this->db->set('Titulo', $this->input->post('titulo'));
        $this->db->set('Contenido', $this->input->post('contenido'));
        $this->db->set('FechaCreacion', date('Y-m-d H:i:s'));
        $this->db->set('EmailAdministrador', $this->session->userdata('email'));
        
        $this->db->where('Codigo', $id);
        
        if($this->db->update('Noticia')){
            $aux = TRUE;
        }
                
        return $aux;
    }
    
    public function borrar($id = ''){
        if ($id == '')
            $id = $this->input->post('checkbox');
        
        if(!is_array($id)){
            $this->db->delete('Noticia', array('Codigo' => $id)); 
        }
        else{
            foreach ($id as $codigo) {
                $this->db->delete('Noticia', array('Codigo' => $codigo));
            }
        }
    }
    
    static function noticias_recientes($limit){
        self::$db->select('Titulo, FechaCreacion, Nombre, ApellidoP, ApellidoM');
        self::$db->from('Noticias');
        self::$db->order_by("FechaCreacion", "desc");
        self::$db->limit($limit);
        $query = self::$db->get(); 
        return $query->result();
    }
    
    private function _limitar_caracteres($palabras, $noticias){
        
        foreach($noticias as &$noticia){
            $aux = explode(" ", $noticia->Contenido);
            $cadena = '';
            if(count($aux) > $palabras){
                for($i = 0; $i < $palabras; $i++){
                    $cadena .= $aux[$i]. " ";
                }
                $noticia->ContenidoCortado = strip_tags($cadena);
                $noticia->ContenidoCortado .= '...';
            }
            $cadena = '';
        }
        return $noticias;
    }
    
}

?>