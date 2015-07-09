    <?php

class Consultor_model extends CI_Model{
    
    var $Nombre = '';
    var $ApellidoP = '';
    var $ApellidoM = '';
    var $Email = '';
    var $Direccion = '';
    var $Provincia = '';
    var $Ciudad = '';
    var $Fax= '';
    var $Telefono = '';
    var $Especialidad = '';
    
    private static $db;
    
    public function __construct() {
        parent::__construct();        
         self::$db = &get_instance()->db;
    }
    
    public function inicializar(){
        $aux = FALSE;
        
        $this->Nombre = $this->input->post('nombre');
        $this->ApellidoP = $this->input->post('primerApellido');
        $this->ApellidoM = $this->input->post('segundoApellido');
        $this->Email = $this->input->post('email');
        
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
        
        if($this->input->post('fax') != '')
            $this->Fax = $this->input->post('fax');
        else
            $this->Fax = NULL;
        
        if($this->input->post('especialidad') != '')
            $this->Especialidad = $this->input->post('especialidad');
        else
            $this->Especialidad = NULL;
        
        
        if($this -> db -> insert('Consultor', $this)){
            $aux = TRUE;
        }
             
        return $aux;
    }
    
    
    
    public function datos($id){        
        $query = $this->db->get_where('Consultores', array('Identificador'=>$id));
        $consultor = $query->result();
        
        $this->Identificador = $id;
        $this->Nombre = $consultor[0]->Nombre; 
        $this->ApellidoP = $consultor[0]->ApellidoP; 
        $this->ApellidoM = $consultor[0]->ApellidoM; 
        $this->Direccion = $consultor[0]->Direccion; 
        $this->Ciudad = $consultor[0]->Ciudad; 
        $this->Provincia = $consultor[0]->Provincia;
        $this->Telefono = $consultor[0]->Telefono;
        $this->Email = $consultor[0]->Email; 
        $this->Fax = $consultor[0]->Fax;
        $this->Especialidad = $consultor[0]->Especialidad;
        
        return $this;            
    }
    
    public function codigo($id = ''){
        if($id != ''){
            $this->Identificador = $id;
        }
        else{
            return $this->db->insert_id();
        }    
    }
    
    
    public function nombre($id = ''){
        $aux = '';
        
        if($id != ''){
            if($this->existe($id)){
                $this->db->select('Nombre');
                $this->db->from('Consultores');
                $this->db->where('Identificador', $id);
                $query = $this->db->get();

                $consultor = $query->result();

                $aux = $consultor[0]->Nombre;
            }
        }
        else{
            $aux = $this->Nombre;
        }
        
        return $aux;
    }
    
    
    
    public function primerApellido($id = ''){
        $aux = '';
        
        if($id != ''){
            if($this->existe($id)){
                $this->db->select('ApellidoP');
                $this->db->from('Consultores');
                $this->db->where('Identificador', $id);
                $query = $this->db->get();

                $consultor = $query->result();

                $aux = $consultor[0]->ApellidoP;
            }
        }
        else{
            $aux = $this->ApellidoP;
        }
        
        return $aux;
    }
    
    
    public function segundoApellido($id = ''){
        $aux = '';
        
        if($id != ''){
            if($this->existe($id)){
                $this->db->select('ApellidoM');
                $this->db->from('Consultores');
                $this->db->where('Identificador', $id);
                $query = $this->db->get();

                $consultor = $query->result();

                $aux = $consultor[0]->ApellidoM;
            }
        }
        else{
            $aux = $this->ApellidoM;
        }
        
        return $aux;
    }
    
    
    public function direccion($id = ''){
        $aux = '';
        
        if($id != ''){
            if($this->existe($id)){
                $this->db->select('Direccion');
                $this->db->from('Consultores');
                $this->db->where('Identificador', $id);
                $query = $this->db->get();

                $consultor = $query->result();

                $aux =  $consultor[0]->Direccion;
            }
        }
        else{
            $aux = $this->Direccion;
        }
        
        return $aux;
    }
    
    
    
    public function ciudad($id = '', $num = FALSE){
        $aux = '';
        
        if($id != ''){
            if($this->existe($id)){
                $this->db->select('Ciudad');
                if($num == TRUE)
                    $this->db->from('Consultor');
                else
                    $this->db->from('Consultores');
                $this->db->where('Identificador', $id);
                $query = $this->db->get();

                $usuario = $query->result();

                $aux = $usuario[0]->Ciudad;
            }
        }
        else{
            $aux = $this->Ciudad;
        }
        
        return $aux;
    }
    
    
    public function provincia($id = '', $num = FALSE){
        $aux = '';
        
        if($id != ''){
            if($this->existe($id)){
                $this->db->select('Provincia');
                if($num == TRUE)
                    $this->db->from('Consultor');
                else
                    $this->db->from('Consultores');
                $this->db->where('Identificador', $id);
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
    
    
    public function telefono($id = ''){
        $aux = '';
        
        if($id != ''){
            if($this->existe($id)){
                $this->db->select('Telefono');
                $this->db->from('Consultores');
                $this->db->where('Identificador', $id);
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
    
    
    public function email($id = ''){
        $aux = '';
        
        if($id != ''){
            if($this->existe($id)){
                $this->db->select('Email');
                $this->db->from('Consultores');
                $this->db->where('Identificador', $id);
                $query = $this->db->get();

                $consultor = $query->result();

                $aux = $consultor[0]->Email;
            }
        }
        else{
            $aux = $this->Email;
        }
        
        return $aux;
    }
    
    
    public function fax($id = ''){
        $aux = '';
        
        if($id != ''){
            if($this->existe($id)){
                $this->db->select('Email');
                $this->db->from('Consultores');
                $this->db->where('Identificador', $id);
                $query = $this->db->get();

                $consultor = $query->result();

                $aux = $consultor[0]->Fax;
            }
        }
        else{
            $this->Fax;
        }
        
        return $aux;
    }
    
    
    public function especialidad($id = ''){
        $aux = '';
        
        if($id != ''){
            if($this->existe($id)){
                $this->db->select('Especialidad');
                $this->db->from('Consultores');
                $this->db->where('Identificador', $id);
                $query = $this->db->get();

                $consultor = $query->result();

                $aux = $consultor[0]->Especialidad;
            }
        }
        else{
            $aux = $this->Especialidad;
        }
        
        return $aux;
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
        
        $query = self::$db->get('Consultores');        
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
        self::$db->from('Consultores');
        return self::$db->count_all_results();
    }
    
    static function numero(){
        return self::$db->count_all('Consultores');
    }
    
    static function obtener($campo, $orden, $offset = '', $limite = '') {
		
        $orden = ($orden == 'desc') ? 'desc' : 'asc';
        $sort_columns = array('Email', 'Nombre', 'Direccion', 'Ciudad', 'CodigoPostal', 'Provincia', 'FechaAltaSistema', 'Telefono');
        $campo = (in_array($campo, $sort_columns)) ? $campo : 'Nombre';

        self::$db->select('*');
        self::$db->from('Consultores');
        if($limite != '')
            self::$db->limit($limite, $offset);
        self::$db->order_by($campo, $orden);
        $query = self::$db->get();

        $clientes = $query->result();

        return $clientes;
    }    
    
    
    static function existe($id){
        $aux = FALSE;
        
        $query = self::$db->get_where("Consultor", array('Identificador'=>$id));        
        if($query->num_rows() > 0){
            $aux = TRUE;
        }
        
        return $aux;
    }
    
    
    public function actualizar($id){ 
        $aux = FALSE;
        
        $this->Nombre = $this->input->post('nombre');
        $this->ApellidoP = $this->input->post('primerApellido');
        $this->ApellidoM = $this->input->post('segundoApellido');
        $this->Email = $this->input->post('email');
        
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
        
        if($this->input->post('fax') != '')
            $this->Fax = $this->input->post('fax');
        else
            $this->Fax = NULL;
        
        if($this->input->post('especialidad') != '')
            $this->Especialidad = $this->input->post('especialidad');
        else
            $this->Especialidad = NULL;
        
        $this->db->where('Identificador', $id);
        if($this->db->update('Consultor', $this)){
            $aux = TRUE;
        }
        
        return $aux;
    }
    
    
    public function eliminar($id = ''){
        $aux = FALSE;
        
        if($id != ''){
            if($this->existe($id)){
                if($this->db->delete("Consultor", array('Identificador' => $id))){
                    return TRUE;
                }
            }
        }
        else{
            if($this->existe($this->Identificador)){
                if($this->db->delete("Consultor", array('Identificador' => $this->Identificador))){
                    $aux = TRUE;
                }
            }
        }            
        return $aux;
    }
    
}

?>