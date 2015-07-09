<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proyecto_model extends CI_Model{
    
    var $NombreProyecto = '';
    var $CodigoPresupuesto = '';
    var $FechaComienzo = '';
    var $FechaFinPrevista = '';
    var $EmailAdmin = '';
    var $CifConstructora = '';
    var $Estado = '';
    var $Visible = '';
    var $Contenido = '';


    private static $db;
    
    public function __construct() {
        parent::__construct();        
        self::$db = &get_instance()->db;
    }
    

    public function inicializar($codigo){
        $aux = FALSE;
               
        $this->NombreProyecto = $this->input->post('nombre');
        if( $this->input->post('constructora') != '0')
            $this->CifConstructora = $this->input->post('constructora');
        else
            $this->CifConstructora = NULL;
        $this->CodigoPresupuesto = $codigo;
        $this->FechaComienzo = date('Y-m-d H:i:s');
        
        if($this->input->post('fechaFin') != '')
            $this->FechaFinPrevista = date('Y-m-d', strtotime($this->input->post('fechaFin')));
        else
            $this->FechaFinPrevista = NULL;
        $this->EmailAdmin = $this->session->userdata('email');     
        $this->Estado = 'Ejecución';
        $this->Visible = FALSE;
        $this->Contenido = '';
        
        if($this->db->insert('Proyecto', $this)){
            $aux = TRUE;
        } 
        
        if($aux){
            $codigo = $this->codigo();
            $empleados = $this->input->post('empleados');
           
            $data = array();
            if(is_array($empleados)){
                foreach ($empleados as $empleado) {
                    if($aux){
                        $data['CodigoProyecto'] = $codigo;
                        $data['EmailEmpleado'] = $empleado;
                        if($this->db->insert('ProyectoEmpleados', $data)){
                            $aux = TRUE;
                        }
                        else{
                            $aux = FALSE;
                        }
                    }    
                }
            }
            else{
                if($empleados != ''){
                    $data['CodigoProyecto'] = $codigo;
                    $data['EmailEmpleado'] = $empleado;
                    if($this->db->insert('ProyectoEmpleados', $data)){
                        $aux = TRUE;
                    }
                    else{
                        $aux = FALSE;
                    }
                }
            }
        }    
        
        return $aux;
    }
    
    
    public function datos($codigo){        
        $query = $this->db->get_where('Proyectos', array('Codigo'=>$codigo));
        $proyecto = $query->result();
        
        $this->Codigo = $codigo;
        $this->NombreProyecto = $proyecto[0]->NombreProyecto;
        $this->CodigoPresupuesto = $proyecto[0]->CodigoPresupuesto;
        $this->EmailAdmin = $proyecto[0]->EmailAdmin;
        $this->Estado = $proyecto[0]->Estado;
        $this->FechaComienzo = $proyecto[0]->FechaComienzo;
        $this->FechaFinPrevista = $proyecto[0]->FechaFinPrevista;
        $this->CifConstructora = $proyecto[0]->CifConstructora;
        $this->Visible = $proyecto[0]->Visible;
        $this->Contenido = $proyecto[0]->Contenido;
        
        return $this;   
    }
    
    
    public function codigo(){
        return $this->db->insert_id();
    }
    
    
    public function codigoPresupuesto($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('CodigoPresupuesto');
                $this->db->from('Proyectos');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $proyecto = $query->result();

                $aux = $proyecto[0]->CodigoPresupuesto;
            }
        }
        else{
            $aux = $this->CodigoPresupuesto;
        }
        
        return $aux;
    }
    
    
    public function nombre($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('NombreProyecto');
                $this->db->from('Proyecto');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $proyecto = $query->result();

                $aux = $proyecto[0]->NombreProyecto;
            }
        }
        else{
            $aux = $this->NombreProyecto;
        }
        
        return $aux;
    }
    
    
    public function estado($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('Estado');
                $this->db->from('Proyecto');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $proyecto = $query->result();

                $aux = $proyecto[0]->Estado;
            }
        }
        else{
            $aux = $this->Estado;
        }
        
        return $aux;
    }
    
    
    public function fechaComienzo($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('FechaComienzo');
                $this->db->from('Proyecto');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $proyecto = $query->result();

                $aux = $proyecto[0]->FechaComienzo;
            }
        }
        else{
            $aux = $this->FechaComienzo;
        }
        
        return $aux;
    }
    
    
    public function fechaFinPrevista($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('FechaFinPrevista');
                $this->db->from('Proyecto');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $proyecto = $query->result();

                $aux = $proyecto[0]->FechaFinPrevista;
            }
        }
        else{
            $aux = $this->FechaFinPrevista;
        }
        
        return $aux;
    }
    
    
    public function email($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('EmailAdmin');
                $this->db->from('Proyecto');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $proyecto = $query->result();

                $aux = $proyecto[0]->EmailAdmin;
            }
        }
        else{
                $aux = $this->EmailAdmin;
        }
        
        return $aux;
    }
    
    
    public function constructora($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('CifConstructora');
                $this->db->from('Proyecto');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $proyecto = $query->result();

                $aux = $proyecto[0]->CifConstructora;
            }
        }
        else{
                $aux = $this->CifConstructora;
        }
        
        return $aux;
    }
  
    
    public function visible($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('Visible');
                $this->db->from('Proyecto');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $proyecto = $query->result();

                $aux = $proyecto[0]->Visible;
            }
        }
        else{
                $aux = $this->Visible;
        }
        
        return $aux;
    }
    
    
    public function contenido($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('Contenido');
                $this->db->from('Proyecto');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $proyecto = $query->result();

                $aux = $proyecto[0]->Contenido;
            }
        }
        else{
                $aux = $this->Contenido;
        }
        
        return $aux;
    }
    
    
    public function actualizar($codigo, $datos = ''){
        $aux = FALSE;
        
        if($datos != ''){
            $this->db->where('Codigo', $codigo);
            if($this->db->update('Proyecto', $datos)){
                $aux = TRUE;
            }
        }
        else{
            $this->Nombre = $this->input->post('nombre');
            $this->CodigoPresupuesto = $codigo;
            $this->FechaComienzo = date('Y-m-d H:i:s');
            $this->FechaFinPrevista = date('Y-m-d', strtotime($this->input->post('fechaFin')));
            $this->EmailAdmin = $this->session->userdata('email');     
            $this->Estado = 'Ejecución';
                        
            if($this->db->update('Proyecto', $this, array('Codigo'=> $this->Codigo))){
                $aux = TRUE;
            }
        }
        
        return $aux;
    }
    
    static function cliente($email){
        $aux = array();
                
        if($email != ''){
            self::$db->select('Codigo');
            self::$db->from('Presupuesto');
            self::$db->where('EmailCliente', $email);
            self::$db->where('Estado','Cerrado');
            $query = self::$db->get();
            $presupuestos = $query->result();
            $aux1 = array();
            
            if(!empty($presupuestos)){
                
                foreach ($presupuestos as $presupuesto){
                    array_push($aux1, $presupuesto->Codigo);
                }     
            
                self::$db->select('*');
                self::$db->from('Proyectos');
                self::$db->where_in('CodigoPresupuesto', $aux1);
                self::$db->where('Estado','Cerrado');
                $query = self::$db->get();
                $proyectos = $query->result();                
            }
            if(isset($proyectos)){
                $aux = $proyectos;
                foreach($proyectos as $proyecto){
                    self::$db->select('Codigo');
                    self::$db->from('Archivo');
                    self::$db->where('CodigoProyecto', $proyecto->Codigo);
                    self::$db->where('ArchivoProyecto', '1');
                    $query = self::$db->get();
                    $codigo = $query->result();
                    $proyecto->CodigoArchivo = $codigo[0]->Codigo; 
                }
            }
        }
                
        return $aux;        
    }
    
    
    static function buscar($dato, $campo, $orden, $offset, $limite){
        
        self::$db -> select('*');
        self::$db -> like('NombreProyecto', $dato);
        self::$db -> or_like('FechaComienzo', $dato);
        self::$db -> or_like('FechaFinPrevista', $dato);
        self::$db -> or_like('Tipo', $dato);
        self::$db -> or_like('Estado', $dato);
        self::$db -> or_like('Direccion', $dato);
        self::$db -> or_like('Ciudad', $dato);
        self::$db -> or_like('Provincia', $dato);
        self::$db -> limit($limite, $offset);
        self::$db -> order_by($campo, $orden);
        
        $query = self::$db->get('Proyectos');
        $proyectos = $query->result();
        
        foreach($proyectos as $proyecto){
            $proyecto->Tipo = Proyecto_model::_tipo($proyecto->Tipo);
        }
        
        return $proyectos;
    }
    
    
    static function busqueda_cantidad($dato){
        self::$db -> select('*');
        self::$db -> like('NombreProyecto', $dato);
        self::$db -> or_like('FechaComienzo', $dato);
        self::$db -> or_like('FechaFinPrevista', $dato);
        self::$db -> or_like('Tipo', $dato);
        self::$db -> or_like('Estado', $dato);
        self::$db -> or_like('Direccion', $dato);
        self::$db -> or_like('Ciudad', $dato);
        self::$db -> or_like('Provincia', $dato);
        self::$db ->from('Proyectos');
        return self::$db -> count_all_results();
    }
    
    
    static function numero($email = ''){
        if($email != ''){
            self::$db->like('EmailEmpleado', $email);
            self::$db->from('ProyectoEmpleados');
            return self::$db->count_all_results();
        }
        else{
            return self::$db->count_all('Proyecto');
        }
    }
    
    
    static function ejecucion(){
       $query = self::$db->get_where('Proyectos' , array('Estado' =>'Ejecución'));
       
       return $query->result();
    }
    
    
    static function numero_ejecucion(){
       $query = self::$db->get_where('Presupuestos' , array('Estado' =>'Abierto'));
       
       return $query->num_rows();
    }
    

    static function obtener($campo, $orden, $offset, $limite, $email = ''){
        $orden = ($orden == 'desc') ? 'desc' : 'asc';
        $sort_columns = array('NombreProyecto', 'FechaComienzo', 'FechaFinPrevista', 'Estado', 'Tipo', 'Direccion', 'Ciudad', 'Provincia');
        $campo = (in_array($campo, $sort_columns)) ? $campo : 'NombreProyecto';

        if($email != ''){
            $aux = array();
            self::$db->select('CodigoProyecto');
            self::$db->from('ProyectoEmpleados');
            self::$db->where('EmailEmpleado', $email);
            $query = self::$db->get();
            
            $proyect = $query->result();
        
            foreach($proyect as $p){
                array_push($aux, $p->CodigoProyecto);
            }
            
            $proyectos=array();
        
            if(!empty($aux)){
                self::$db->select('*');
                self::$db->from('Proyectos');
                if($email != ''){
                    self::$db->where_in('Codigo', $aux);
                }
                self::$db->limit($limite, $offset);
                self::$db->order_by($campo, $orden);
                $query = self::$db->get();

                $proyectos = $query->result();

                foreach($proyectos as $proyecto){
                    $proyecto->Tipo = Proyecto_model::_tipo($proyecto->Tipo);
                }
            }
        }
        else{
            $proyectos=array();       
        
            self::$db->select('*');
            self::$db->from('Proyectos');
            if($email != ''){
                self::$db->where_in('Codigo', $aux);
            }
            self::$db->limit($limite, $offset);
            self::$db->order_by($campo, $orden);
            $query = self::$db->get();

            $proyectos = $query->result();

            foreach($proyectos as $proyecto){
                $proyecto->Tipo = Proyecto_model::_tipo($proyecto->Tipo);
            }
        }
        return $proyectos;
    }
    
    
    static function visibles($offset = '', $limite=''){
        self::$db->select('*');
        self::$db->from('Proyectos');
        self::$db->where('Visible', TRUE);
        
        $query = self::$db->get();
        
        $visibles = $query->result();
        
        return $visibles;
    }
    
    static function numeroVisibles(){
        self::$db->select('*');
        self::$db->from('Proyecto');
        self::$db->where('Visible', TRUE);
        $query = self::$db->get();
       
        return $query->num_rows();
    }
    
    
    static function obtenerFechas($year,$month, $day ='' ,$email = ''){
        $proyectos = array();
        
        if($email != ''){
            self::$db->select('CodigoProyecto');
            self::$db->from('ProyectoEmpleados');
            self::$db->where('EmailEmpleado', $email);
            $query = self::$db->get();
            
            $proyectos = $query->result();
            $aux = array();
            foreach ($proyectos as $proyecto) {
                array_push($aux,$proyecto->CodigoProyecto);
            }            
        }
        
        if($email != ''){ 
            if(!empty($aux)){ 
                self::$db->select('NombreProyecto, FechaComienzo, FechaFinPrevista');
                self::$db->from('Proyecto');
                self::$db->where_in("Codigo", $aux);
                if($day == ''){
                    self::$db->where("FechaComienzo >=", date("$year-$month-1"));
                    self::$db->where("FechaComienzo <=", date("$year-$month-31"));
                    self::$db->or_where("FechaFinPrevista >=", date("$year-$month-1"));
                    self::$db->where("FechaFinPrevista <=", date("$year-$month-31"));
                }
                else{
                    self::$db->where("FechaComienzo", date("$year-$month-$day"));
                    self::$db->or_where("FechaFinPrevista", date("$year-$month-$day"));
                }
                $query = self::$db->get();
                $proyectos = $query->result();
            }
        }
        else{
            self::$db->select('NombreProyecto, FechaComienzo, FechaFinPrevista');
            self::$db->from('Proyecto');
            if($day == ''){
                self::$db->where("FechaComienzo >=", date("$year-$month-1"));
                self::$db->where("FechaComienzo <=", date("$year-$month-31"));
                self::$db->or_where("FechaFinPrevista >=", date("$year-$month-1"));
                self::$db->where("FechaFinPrevista <=", date("$year-$month-31"));
            }
            else{
                self::$db->where("FechaComienzo", date("$year-$month-$day"));
                self::$db->or_where("FechaFinPrevista", date("$year-$month-$day"));
            }
            $query = self::$db->get();
            $proyectos = $query->result();
        }      
        
        return $proyectos;
    }
    
    
    public function eliminar($codigo = ''){
        $aux = FALSE;
        
        if($codigo != '')
            if($this->db->delete('Proyecto', array('Codigo' => $codigo)))
                $aux = TRUE;
        return $aux;    
    }
    
    public function insertarEmpleadoProyecto($email){
        $aux = FALSE;
        
        if(is_array($email)){
            foreach($email as $emailEmpleado){
                if(!$this->existeEmpleado($this->Codigo, $emailEmpleado)){
                    $datos['CodigoProyecto'] = $this->Codigo;
                    $datos['EmailEmpleado'] = $emailEmpleado;
                    if($this->db->insert('ProyectoEmpleados', $datos)){
                        $aux = TRUE;
                    }
                }   
            }
        }
        else{
            if($email != ''){
                $datos['CodigoProyecto'] = $this->Codigo;
                $datos['EmailEmpleado'] = $email;
                if(!$this->existeEmpleado($this->Codigo, $emailEmpleado)){
                    if($this->db->insert('ProyectoEmpleados', $datos)){
                        $aux = TRUE;
                    }
                }
            }
        }
        
        return $aux;
    }
    
    
    public function eliminarEmpleadoProyecto($email){
        $aux = FALSE;
        
        if(is_array($email)){
            foreach($email as $emailEmpleado){
                $this->db->where('CodigoProyecto', $this->Codigo);
                if($this->db->delete('ProyectoEmpleados', array('EmailEmpleado'=> $emailEmpleado))){
                    $aux = TRUE;                  
                }   
            }
        }
        else{
            if($email != ''){
                $this->db->where('CodigoProyecto', $this->Codigo);
                if($this->db->delete('ProyectoEmpleados', array('EmailEmpleado'=> $email))){
                    $aux = TRUE;
                }
            }
        }
        
        return $aux;
    }


    static function empleadosProyecto($codigo){
        $query = self::$db->get_where('ProyectosEmpleados', array('CodigoProyecto'=> $codigo));
                
        $empleados = $query->result();
        
        self::$db->select('EmailAdmin');
        self::$db->from('Proyecto');
        self::$db->where('Codigo', $codigo);
        $query = self::$db->get();
        $emailAdmin = $query->result();
        
        self::$db->select('Email, Nombre, ApellidoP, ApellidoM');
        self::$db->from('Usuario');
        self::$db->where('Email', $emailAdmin[0]->EmailAdmin);
        $query = self::$db->get();
        $admin = $query->result();
        $admin[0]->EmailEmpleado = $admin[0]->Email;
                
        array_push($empleados, $admin[0]);
       
        return $empleados;
    }
    
    
    static function empleadosNoProyecto($email){
        if(!empty($email)){
            self::$db->select('Nombre, ApellidoP, ApellidoM, Email');
            self::$db->from('Empleados');
            self::$db->where_not_in("Email", $email);

            $query =self::$db->get();

            $empleados = $query->result();

            return $empleados;
        }
        else{
            self::$db->select('Nombre, ApellidoP, ApellidoM, Email');
            self::$db->from('Empleados');
            $query =self::$db->get();

            $empleados = $query->result();

            return $empleados;
        }
    }
    
    static function existe($codigo){
        $aux = FALSE;
        $query = self::$db->get_where('Proyecto', array('Codigo'=>$codigo));        
        if($query->num_rows() > 0){
            $aux = TRUE;
        }
        
        return $aux;
    }
    
    static function existeEmpleado($codigo, $email){    
        $aux = FALSE;
        $query = self::$db->get_where('ProyectoEmpleados', array('EmailEmpleado'=>$email, 'CodigoProyecto'=>$codigo));        
        if($query->num_rows() > 0){
            $aux = TRUE;
        }
        
        return $aux;
    
    }
    
    static function numProyectosEmpleado($email){

        self::$db->select('*');
        self::$db->from('ProyectoEmpleados');
        self::$db->where('EmailEmpleado', $email);
        
        $query = self::$db->get();
        
        $num = $query->num_rows();
        
        return $num;
    }
    
    static function empleado($email){
        $aux = array('0'=>'Todo');
       
        self::$db->select('Codigo, NombreProyecto');
        self::$db->from('Proyecto');
        self::$db->where('EmailAdmin', $email);
        $query =self::$db->get();
        $codigos = $query->result();
        foreach ($codigos as $codigo){
            $aux[$codigo->Codigo] = $codigo->NombreProyecto;
        }
        

        self::$db->select('CodigoProyecto, NombreProyecto');
        self::$db->from('ProyectosEmpleados');
        self::$db->where('EmailEmpleado', $email);
        $query =self::$db->get();
        $codigos = $query->result();
        
        foreach ($codigos as $codigo){
            $aux[$codigo->CodigoProyecto] = $codigo->NombreProyecto;
        }
        
        return $aux;
           
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
    
    public function pertenece($email){
        $aux = FALSE;
        
        if($this->existe($this->Codigo)){
            if($this->email() == $email)
                $aux = TRUE;
            else{
                $aux = Proyecto_model::existeEmpleado($this->Codigo, $email);
            }
        }
        
        return $aux;
    } 
    
}

?>
