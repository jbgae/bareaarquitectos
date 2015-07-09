<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Presupuesto_model extends CI_Model{
    
    var $Tipo = '';
    var $Estado = '';
    var $Descripcion = '';
    var $Superficie = '';
    var $Direccion = '';
    var $Ciudad = '';
    var $Provincia = '';
    var $FechaSolicitud = '';
    var $EmailCliente = '';
    var $Pem = '';
    var $Coeficiente = '';
    var $CoeficienteSeguridad = '';

    
    private static $db;
    
    public function __construct() {
        parent::__construct();        
        self::$db = &get_instance()->db;
    }
    
    public function inicializar($email = ''){
        $aux = FALSE;
    
        if($email != ''){
            $this->EmailCliente = $email;
            $this->Estado = 'Abierto'; 
        }
        else{
            $this->EmailCliente = strtolower($this->input->post('nombre'));            
            $this->Estado = 'Enviado';
        }
        
        $this->Tipo = $this->input->post('tipo');
        $this->Descripcion = $this->input->post('descripcion');
        $this->Superficie = $this->input->post('superficie');
        $this->Direccion = ucwords(strtolower($this->input->post('direccion')));
        $this->Ciudad = $this->input->post('ciudad');
        $this->Provincia = $this->input->post('provincia');                       
        $this->Pem = $this->input->post('pem');
        $this->Coeficiente = $this->input->post('coeficiente');
        $this->CoeficienteSeguridad = $this->input->post('coeficienteSeguridad');
        $this->FechaSolicitud = date('Y-m-d H:i:s');
        //$this->CodigoArchivo = $codigo;
       
        if($this->db->insert('Presupuesto', $this)){
            $aux = TRUE;
        }    
        
        return $aux;
    }
    

    public function datos($codigo){        
        $query = $this->db->get_where('Presupuestos', array('Codigo'=>$codigo));
        $presupuesto = $query->result();
        
                
        $this->Codigo = $codigo;
        $this->Tipo = $presupuesto[0]->Tipo;
        $this->Estado = $presupuesto[0]->Estado;
        $this->Descripcion = $presupuesto[0]->Descripcion;
        $this->Superficie = $presupuesto[0]->Superficie;
        $this->Direccion = $presupuesto[0]->Direccion;
        $this->Ciudad = $presupuesto[0]->Ciudad;
        $this->Provincia = $presupuesto[0]->Provincia;
        $this->FechaSolicitud =  date('d-m-Y H:i:s', strtotime($presupuesto[0]->FechaSolicitud));
        $this->EmailCliente = $presupuesto[0]->EmailCliente;
        $this->Pem = $presupuesto[0]->Pem;
        $this->Coeficiente = $presupuesto[0]->Coeficiente;
        $this->CoeficienteSeguridad = $presupuesto[0]->CoeficienteSeguridad;
        $this->Nombre = $presupuesto[0]->Nombre;
        $this->ApellidoP = $presupuesto[0]->ApellidoP;
        $this->ApellidoM = $presupuesto[0]->ApellidoM;
        $this->CodigoArchivo = $presupuesto[0]->CodigoArchivo;
        $this->Ruta = $presupuesto[0]->Ruta;
              
        return $this;   
    }
    
    
    public function codigo(){
        return $this->db->insert_id();        
    }
    
    
    public function tipo($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('Tipo');
                $this->db->from('Presupuesto');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $presupuesto = $query->result();

                $aux = $presupuesto[0]->Tipo;
            }
        }
        else{
            $aux = $this->Tipo;
        }
        
        return $aux;
    }
    
    
    public function estado($codigo= ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('Estado');
                $this->db->from('Presupuesto');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $presupuesto = $query->result();

                $aux = $presupuesto[0]->Estado;
            }
        }
        else{
            $aux = $this->Estado;
        }
        
        return $aux;
    }
    
    
    public function descripcion($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('Descripcion');
                $this->db->from('Presupuesto');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $presupuesto = $query->result();

                $aux = $presupuesto[0]->Descripcion;
            }
        }
        else{
            $aux = $this->Descripcion;
        }
        
        return $aux;
    }
    
    
    public function superficie($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('Superficie');
                $this->db->from('Presupuesto');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $presupuesto = $query->result();

                $aux = $presupuesto[0]->Superficie;
            }
        }
        else{
            $aux = $this->Superficie;
        }
        
        return $aux;
    }
    
    
    public function direccion($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('Direccion');
                $this->db->from('Presupuesto');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $presupuesto = $query->result();

                $aux = $presupuesto[0]->Direccion;
            }
        }
        else{
            $aux = $this->Direccion;
        }
        
        return $aux;
    }
    
    
    public function ciudad($codigo = '', $num = FALSE){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('Ciudad');
                if($num == TRUE)
                    $this->db->from('Presupuesto');
                else
                    $this->db->from('Presupuestos');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $presupuesto = $query->result();

                $aux = $presupuesto[0]->Ciudad;
            }
        }
        else{
            $aux = $this->Ciudad;
        }
        
        return $aux;
    }
    
    
    public function provincia($codigo = '', $num = FALSE){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('Provincia');
                if($num == TRUE)
                    $this->db->from('Presupuesto');
                else
                    $this->db->from('Presupuestos');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $presupuesto = $query->result();

                $aux = $presupuesto[0]->Provincia;
            }
        }
        else{
            $aux = $this->Provincia;
        }
        
        return $aux;
    }
    
    
    public function fechaSolicitud($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('FechaSolicitud');
                $this->db->from('Presupuesto');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $presupuesto = $query->result();

                $aux = $presupuesto[0]->FechaSolicitud;
            }
        }
        else{
            $aux = $this->FechaSolicitud;
        }
        
        return $aux;
    }
    
    
    public function email($codigo = ''){
        $aux = '';
        
        if($codigo != ''){ 
            if($this->existe($codigo)){
                $this->db->select('EmailCliente');
                $this->db->from('Presupuesto');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $presupuesto = $query->result();

                $aux = $presupuesto[0]->EmailCliente;
            }
        }
        else{
            $aux = $this->EmailCliente;
        }
        
        return $aux;
    }
   
    
    static function cliente($email){
        $aux = '';
        $estados = array('Enviado', 'Cerrado');
        
        if($email != ''){
            self::$db->select('*');
            self::$db->from('Presupuestos');
            self::$db->where('EmailCliente', $email);
            self::$db->where_in('Estado', $estados);
            $query = self::$db->get();

            $presupuesto = $query->result();

            $aux = $presupuesto;   
            
        }
                
        return $aux;        
    }
    
    
    public function ruta($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('Ruta');
                $this->db->from('Presupuestos');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $presupuesto = $query->result();
                
                $aux = $presupuesto[0]->Ruta;
            }
        }
        else{
            $aux = $this->Ruta;
        }
        
        return $aux;
    }
    
    
    public function nombreCliente($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('Nombre, ApellidoP, ApellidoM');
                $this->db->from('Presupuestos');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $presupuesto = $query->result();

                $aux = $presupuesto[0]->Nombre.' '.$presupuesto[0]->ApellidoP.' '.$presupuesto[0]->ApellidoM;
            }
        }
        else{
            $aux = $this->Nombre.' '. $this->ApellidoP.' '.$this->ApellidoM;
        }
        return $aux;
    }
    
    public function codigoArchivo($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('CodigoArchivo');
                $this->db->from('Presupuestos');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $presupuesto = $query->result();

                $aux = $presupuesto[0]->CodigoArchivo;
            }
        }
        else{
            $aux = $this->CodigoArchivo;
        }
        return $aux;
    }
    
    
    public function pem($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('Pem');
                $this->db->from('Presupuesto');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $presupuesto = $query->result();

                $aux = $presupuesto[0]->Pem;
            }
        }
        else{
            $aux = $this->Pem;
        }
        
        return $aux;
    }
    
    
    public function coeficiente($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('Coeficiente');
                $this->db->from('Presupuesto');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $presupuesto = $query->result();

                $aux = $presupuesto[0]->Coeficiente;
            }
        }
        else{
            $aux = $this->Coeficiente;
        }
        
        return $aux;
    }
    
    
    public function coeficienteSeguridad($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('CoeficienteSeguridad');
                $this->db->from('Presupuesto');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $presupuesto = $query->result();

                $aux = $presupuesto[0]->CoeficienteSeguridad;
            }
        }
        else{
            $aux = $this->CoeficienteSeguridad;
        }
        
        return $aux;
    }
    
    public function precio($codigo = ''){
        $aux = '';
        
        if($codigo == ''){
            $codigo = $this->Codigo;
        }
        if($this->existe($codigo)){
            $this->db->select_sum('Cantidad');
            $this->db->from('LineaPresupuesto');
            $this->db->where('CodigoPresupuesto', $codigo);
            $query = $this->db->get();

            $presupuesto = $query->result();

            $aux = $presupuesto[0]->Cantidad;
        }
        
        return $aux;
        
    }
    
    
    public function actualizar($codigo, $datos = ''){ 
        $aux = FALSE;
        
        if($datos != ''){
            if(!empty($datos) ){
                if($this->db->update('Presupuesto', $datos, array('Codigo'=> $codigo))){
                    $aux =  TRUE;
                }
            }
        }
        else{
            $datos = array(
                'Tipo' => $this->input->post('tipo'),
                'Descripcion' => $this->input->post('descripcion'),
                'Superficie' => $this->input->post('superficie'),
                'Ciudad' => $this->input->post('ciudad'),
                'Provincia' => $this->input->post('provincia'),
                'Estado' => 'Enviado',                
                'Pem' => $this->input->post('pem'),
                'Coeficiente' => $this->input->post('coeficiente'),
                'CoeficienteSeguridad' => $this->input->post('coeficienteSeguridad'),
                'FechaSolicitud' => date('Y-m-d H:i:s')
                );
            //$this->CodigoArchivo = $codigo;
            
            if($this->db->update('Presupuesto', $datos, array('Codigo'=> $codigo))){
                $aux = TRUE;
            }
        }
       
        return $aux;
    }
    
    
    static function existe($codigo){
        $aux = FALSE;
  
        $query = self::$db->get_where('Presupuesto', array('Codigo'=>$codigo));
        
        if($query->num_rows() > 0){
            $aux = TRUE;
        }
        
        return $aux;
    }
    
    static function buscar($dato, $campo, $orden, $offset, $limite){
        
        self::$db-> select('*');
        self::$db-> like('Nombre', $dato);
        self::$db-> or_like('ApellidoP', $dato);
        self::$db-> or_like('ApellidoM', $dato);
        self::$db-> or_like('Direccion', $dato);
        self::$db-> or_like('Ciudad', $dato);
        self::$db-> or_like('Provincia', $dato);
        self::$db-> or_like('Estado', $dato);
        self::$db-> or_like('CONCAT(Nombre, " ", ApellidoP, " ", ApellidoM)', $dato);
        self::$db-> or_like('CONCAT(Nombre, " ", ApellidoP)', $dato);
        self::$db-> or_like('CONCAT(ApellidoP, " ", ApellidoM)', $dato);
        self::$db-> limit($limite, $offset);
        self::$db-> order_by($campo, $orden);
        
        $query = self::$db->get('Presupuestos');
        $presupuestos = $query->result();
        
        foreach($presupuestos as $presupuesto){
            $presupuesto->Tipo = Presupuesto_model::_tipo($presupuesto->Tipo);
        }
        
        return $presupuestos;
    }
    
    
    static function busqueda_cantidad($dato){
        self::$db-> select('*');
        self::$db-> like('Nombre', $dato);
        self::$db-> or_like('ApellidoP', $dato);
        self::$db-> or_like('ApellidoM', $dato);
        self::$db-> or_like('Direccion', $dato);
        self::$db-> or_like('Ciudad', $dato);
        self::$db-> or_like('Provincia', $dato);
        self::$db-> or_like('Estado', $dato);
        self::$db-> or_like('CONCAT(Nombre, " ", ApellidoP, " ", ApellidoM)', $dato);
        self::$db-> or_like('CONCAT(Nombre, " ", ApellidoP)', $dato);
        self::$db-> or_like('CONCAT(ApellidoP, " ", ApellidoM)', $dato);
        self::$db-> from('Presupuestos');
        return self::$db-> count_all_results();
    }
    
    
    static function numero(){
        return self::$db->count_all('Presupuesto');
    }
    
    
    static function abiertos(){
       $query = self::$db->get_where('Presupuestos' , array('Estado' =>'Abierto'));
       
       return $query->result();
    }
    
    
    static function aceptados(){
       $query = self::$db->get_where('Presupuestos' , array('Estado' =>'Aceptado'));
       
       return $query->result();
    }
    
    
    static function numero_abiertos(){
       $query = self::$db->get_where('Presupuesto' , array('Estado' =>'Abierto'));
       
       return $query->num_rows();
    }
    
        
    static function numero_aceptados(){
       $query = self::$db->get_where('Presupuesto' , array('Estado' =>'Aceptado'));
       
       return $query->num_rows();
    }
    

    static function obtener($campo, $orden, $offset, $limite){
        $orden = ($orden == 'desc') ? 'desc' : 'asc';
        $sort_columns = array('EmailCliente', 'Nombre', 'Estado', 'Tipo', 'Direccion', 'FechaCreacion', 'FechaSolicitud', 'Ciudad', 'Provincia');
        $campo = (in_array($campo, $sort_columns)) ? $campo : 'Nombre';

        self::$db->select('*');
        self::$db->from('Presupuestos');
        self::$db->limit($limite, $offset);
        self::$db->order_by($campo, $orden);
        $query = self::$db->get();

        $presupuestos = $query->result();
        
        foreach($presupuestos as $presupuesto){
            $presupuesto->Tipo = Presupuesto_model::_tipo($presupuesto->Tipo);
            $presupuesto->FechaSolicitud = date('d-m-Y H:i:s', strtotime($presupuesto->FechaSolicitud));
        }

        return $presupuestos;
    }
    
    
    
    public function eliminar($codigo){
        if($this->db->delete('Presupuesto', array('Codigo' => $codigo)))
            return TRUE;  
    }
    
    
    private function _tipo($num){
        switch ($num) {
            case 1:
                    return 'Obra Nueva';
                    break;
            case 2:
                    return 'Peritación';
                    break;
            case 3:
                    return 'Rehabilitación';
                    break;
            case 4:
                    return 'Adecuación de local';
                    break;
            case 5:
                    return 'Tasación';
                    break;
            case 6:
                    return 'Informe';
                    break;
            case 7:
                    return 'Auditoría energética';
                    break;
        }
    }
    
    
    
}

?>
