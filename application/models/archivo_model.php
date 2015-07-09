<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Archivo_model extends CI_Model{
    
    var $Codigo            = '';
    var $Ruta              = '';
    var $Nombre            = '';
    var $Extension         = '';
    var $Tamanyo           = '';
    var $Fecha             = '';
    var $EmailEmpleado     = '';
    var $CodigoPresupuesto = NULL;
    var $CodigoProyecto    = NULL;
    var $CodigoTarea       = NULL;
    var $CodigoRespuesta   = NULL;
    var $Visible           = FALSE;
    var $Pertenece         = NULL;
    var $FotoEmpleado      = FALSE;
    var $ArchivoProyecto   = FALSE;
    
    
    private static $db;
    
    public function __construct() {
        parent::__construct(); 
        self::$db = &get_instance()->db;
        $this->load->database();
        $this->load->library('image_lib');
        $this->load->helper('file');
    }
    
    
    public function inicializar($tipo = 'proyecto', $codigo = '', $codigoCarpeta = ''){
        $aux = FALSE;
        $path = getcwd(); 
        
        $this->Fecha = date('Y/m/d H:i:s');
        $this->EmailEmpleado = $this->session->userdata('email');
        
        switch ($tipo) {
            case 'proyecto':
                if(!is_dir(realpath("$path/archivos/proyectos/$codigo"))){           
                    mkdir("$path/archivos/proyectos/$codigo", 0755);     
                }
                $aux = $this-> _inicializarArchivoProyecto($codigo, $codigoCarpeta);
                break;
                
            case 'presupuesto':
                if(!is_dir(realpath("$path/archivos/presupuestos"))){           
                    mkdir("$path/archivos/presupuestos", 0755);     
                }
                $aux = $this-> _inicializarArchivoPresupuesto($codigo);    
                break; 
                
            case 'tarea':
                if(!is_dir(realpath("$path/archivos/tareas"))){           
                    mkdir("$path/archivos/tareas", 0755);     
                }
                $aux = $this->_inicializarArchivoTarea($codigo, $tipo); 
                break;
            
            case 'respuesta':
                if(!is_dir(realpath("$path/archivos/tareas"))){           
                    mkdir("$path/archivos/tareas", 0755);     
                }
                $aux = $this->_inicializarArchivoTarea($codigo, $tipo); 
                break;
            
            case 'carpeta';
                if(!is_dir(realpath("$path/archivos/proyectos/$codigo"))){           
                    mkdir("$path/archivos/proyectos/$codigo", 0755);     
                }
                $aux = $this->_inicializarCarpeta($codigo, $codigoCarpeta); 
                break;
            
            case 'foto':
                if(!is_dir(realpath("$path/images/fotos"))){           
                    mkdir("$path/images/fotos", 0755);     
                }
                $aux = $this->_inicializarFoto();                
                break;

            default:
                break;
        }
        
        return $aux;
    }
    
    
    public function actualizar($codigo, $tipo = 'proyecto', $codigoP = ''){
        $aux = FALSE;
        $path = getcwd(); 
        
        if($tipo == 'foto'){           
            if(!is_dir(realpath("$path/images/fotos"))){           
                mkdir("$path/images/fotos", 0755);     
            }   
         
            $config['upload_path'] =  realpath("$path/images/fotos/" );
            $config['allowed_types'] = 'gif|jpg|jpeg|png|PNG|JPG|JPEG|GIF';
            $config['max_size']	= '0';
            $config['max_width']  = '0';
            $config['max_height']  = '0';
            $this->load->library('upload',$config);
            
            if ($this->upload->do_upload('archivo')){
                $nombreArchivo = array_pop(explode("/",$this->ruta($codigo)));
                if(file_exists("$path/images/fotos/thumb/$nombreArchivo"))
                    unlink(realpath("$path/images/fotos/thumb/$nombreArchivo"));
                
                $archivo = $this->upload->data();     
               
                if($tipo == 'foto'){
                    $config['image_library'] = 'gd2';
                    //CARPETA EN LA QUE ESTÁ LA IMAGEN A REDIMENSIONAR
                    $config['source_image'] = realpath("$path/images/fotos/".$archivo['file_name'] );
                    $config['create_thumb'] = TRUE;
                    $config['maintain_ratio'] = TRUE;
                    //CARPETA EN LA QUE GUARDAMOS LA MINIATURA
                    $config['new_image']=realpath("$path/images/fotos/thumb/");;
                    $config['width'] = 70;
                    $config['height'] = 70;
                    $this->image_lib->initialize($config);
                    if(!$this->image_lib->resize())
                            echo $this->image_lib->display_errors();
                    $this->image_lib->clear();
                    unlink(realpath("$path/images/fotos/".$archivo['file_name'] ));
                }
                
                
                $datos = array(                   
                    'Ruta' => base_url().'images/fotos/thumb/'.$archivo['raw_name'].'_thumb'.$archivo['file_ext'],
                    'Fecha'=> date('Y/m/d H:i:s'),
                    'Nombre' => $archivo['raw_name'],
                    'Extension' => $archivo['file_ext'],
                    'Tamanyo' => $archivo['file_size']
                );
                
                if($this->db->update('Archivo', $datos, array('Codigo'=> $codigo))){
                    $aux = TRUE;
                }
            }
            else{
                
                $this->upload->display_errors();
            }
        }
        elseif($tipo == 'proyecto'){
            if(!is_dir(realpath("$path/archivos/proyectos/$codigoP"))){           
                mkdir(realpath("$path/images/fotos/$codigoP"),  0755);     
            }

            $config['upload_path'] =  realpath("$path/archivos/proyectos/$codigoP/");
            $config['allowed_types'] = 'gif|jpg|jpeg|png|doc|pdf|odt';
            $config['max_size']	= '0';
            $config['max_width']  = '0';
            $config['max_height']  = '0';
            $this->load->initialize($config);
            
            if ($this->upload->do_upload('archivo')){ 
                $archivo = $this->upload->data(); 
                
                $this->Ruta = base_url().'archivos/proyectos/'.$codigoP.'/'.$archivo['file_name'];
                $this->Fecha = date('Y/m/d h:i:s');
                $this->CodigoProyecto = $codigo;
                $this->EmailEmpleado = $this->session->userdata('email');
                $this->CodigoTarea = NULL;
                $this->CodigoRespuesta = NULL;
                
                if($this->db->insert('Archivo', $this)){
                    $aux = TRUE;
                }
            }
        }
         
        
        return $aux;
    }
    
    
    public function datos($codigo){        
        $query = $this->db->get_where('Archivos', array('Codigo'=>$codigo));
        $archivo = $query->result();

        $this->Codigo = $codigo;
        $this->Ruta = $archivo[0]->Ruta;
        $this->Fecha = $archivo[0]->Fecha;
        $this->CodigoProyecto = $archivo[0]->CodigoProyecto;
        $this->CodigoTarea = $archivo[0]->CodigoTarea;
        $this->CodigoRespuesta = $archivo[0]->CodigoRespuesta;
        $this->EmailEmpleado = $archivo[0]->EmailEmpleado;
        $this->Nombre = $archivo[0]->Nombre; 
        $this->Extension = $archivo[0]->Extension; 
        $this->Tamanyo = $archivo[0]->Tamanyo; 
        $this->NombreEmpl = $archivo[0]->NombreEmpl; 
        $this->ApellidoP = $archivo[0]->ApellidoP;
        $this->ApellidoM = $archivo[0]->ApellidoM;
        $this->Visible = $archivo[0]->Visible;
        
        return $this;            
    }
    
    
    public function codigo(){
        return $this->db->insert_id();
    }
    
    
    public function ruta($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('Ruta');
                $this->db->from('Archivo');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $archivo = $query->result();

                $aux = $archivo[0]->Ruta;
            }
        }
        else{
            $aux = $this->Ruta;
        }
        
        return $aux;
    }
    
    
    public function nombre($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('Nombre');
                $this->db->from('Archivo');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $archivo = $query->result();

                $aux = $archivo[0]->Nombre;
            }
        }
        else{
            $aux = $this->Nombre;
        }
        
        return $aux;
    }
    
    public function extension($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('Extension');
                $this->db->from('Archivo');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $archivo = $query->result();

                $aux = $archivo[0]->Extension;
            }
        }
        else{
            $aux = $this->Extension;
        }
        
        return $aux;
    }
    
    public function tamanyo($codigo = '', $tam=''){
        $aux = '';
        if($tam == ''){
            if($codigo != ''){
                if($this->existe($codigo)){
                    $this->db->select('Tamanyo');
                    $this->db->from('Archivo');
                    $this->db->where('Codigo', $codigo);
                    $query = $this->db->get();

                    $archivo = $query->result();

                    $aux = $archivo[0]->Tamanyo;
                }
            }
            else{
                $aux = $this->Extension;
            }
        }
        else{
            $datos = array('Tamanyo' => $tam);
            if($this->db->update('Archivo', $datos, array('Codigo'=> $codigo))){
                    $aux = TRUE;
            }
        }

        return $aux;
    }
    
    
    public function fecha($codigo = ''){
         $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('Fecha');
                $this->db->from('Archivo');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $archivo = $query->result();

                $aux = $archivo[0]->Fecha;
            }
        }
        else{
            $aux = $this->Fecha;
        }
        
        return $aux;
    }
    
    
    public function pertenece($codigo = ''){
         $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('Pertenece');
                $this->db->from('Archivo');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $archivo = $query->result();

                $aux = $archivo[0]->Pertenece;
            }
        }
        else{
            $aux = $this->Pertenece;
        }
        
        return $aux;
    }
    
    
    public function emailEmpleado($codigo = ''){
         $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('EmailEmpleado');
                $this->db->from('Archivo');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $archivo = $query->result();

                $aux = $archivo[0]->EmailEmpleado;
            }
        }
        else{
            $aux = $this->EmailEmpleado;
        }
        
        return $aux;
    }
    
    
    public function codigoProyecto($codigo = ''){
         $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('CodigoProyecto');
                $this->db->from('Archivo');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $archivo = $query->result();

                $aux = $archivo[0]->CodigoProyecto;
            }
        }
        else{
            $aux = $this->CodigoProyecto;
        }
        
        return $aux;
    }
    
    
    public function codigoPresupuesto($codigo = ''){
         $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('CodigoPresupuesto');
                $this->db->from('Archivo');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $archivo = $query->result();

                $aux = $archivo[0]->CodigoPresupuesto;
            }
        }
        else{
            $aux = $this->CodigoPresupuesto;
        }
        
        return $aux;
    }
    
    public function memProyecto($codigoProyecto, $datos = '',$actualizar = FALSE){
        $aux = '';
        
        if($actualizar){
            
            $data = array('ArchivoProyecto' =>FALSE);
            $this->db->where('CodigoProyecto', $codigoProyecto);
            $this->db->update('Archivo', $data); 
            
            $this->db->where('Codigo', $datos['Codigo']);
            if($this->db->update('Archivo', $datos)){
                $aux = TRUE;
            }
        }
        
        elseif($codigoProyecto != ''){
                $this->db->select('Codigo');
                $this->db->from('Archivo');
                $this->db->where('CodigoProyecto', $codigoProyecto);
                $this->db->where('ArchivoProyecto', TRUE);
                $query = $this->db->get();

                $archivo = $query->result();
                
                if(!empty($archivo))
                    $aux = $archivo[0]->Codigo;
        }
        
        return $aux;
    }
    
    public function codigoTarea($codigo = ''){
         $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('CodigoTarea');
                $this->db->from('Archivo');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $archivo = $query->result();

                $aux = $archivo[0]->CodigoTarea;
            }
        }
        else{
            $aux = $this->CodigoTarea;
        }
        
        return $aux;
    }
    
    
    public function codigoRespuesta($codigo = ''){
        $aux = '';
        
        if($codigo != ''){
            if($this->existe($codigo)){
                $this->db->select('CodigoRespuesta');
                $this->db->from('Archivo');
                $this->db->where('Codigo', $codigo);
                $query = $this->db->get();

                $archivo = $query->result();

                $aux = $archivo[0]->CodigoRespuesta;
            }
        }
        else{
            $aux = $this->CodigoRespuesta;
        }
        
        return $aux;
    }
    
    
    public function visible($codigo = '', $aux = FALSE){
        if($aux){
            if($this->existe($codigo)){
                $ruta = $this->ruta($codigo);
                $file = str_replace('http://localhost', realpath(__DIR__ . '/../../../'),$ruta);
                $nombre = array_pop(explode("/",$ruta));
                if(file_exists($file)){
                    if(!is_dir(realpath(getcwd()."/images/fotos/"))){
                       mkdir(getcwd()."/images/fotos/", 0755); 
                    }
                    if(!is_dir(realpath(getcwd()."/images/fotos/proyectos/"))){           
                        mkdir(getcwd()."/images/fotos/proyectos/", 0755);     
                    }
                    if(rename($file, getcwd()."/images/fotos/proyectos/".$nombre)){
                        $datos = array(       
                            'Ruta' => base_url().'images/fotos/proyectos/'.$nombre,
                            'Visible' => TRUE
                        );
                        $this->db->update('Archivo', $datos, array('Codigo'=> $codigo));
                    }
                }
                else{
                    echo 'no existe';
                }
            }
        }
        else{
            $aux = '';

            if($codigo != ''){
                if($this->existe($codigo)){
                    $this->db->select('Visible');
                    $this->db->from('Archivo');
                    $this->db->where('Codigo', $codigo);
                    $query = $this->db->get();

                    $archivo = $query->result();

                    $aux = $archivo[0]->Visible;
                }
            }
            else{
                $aux = $this->Visible;
            }

            return $aux;
        }
    }
       
    
    
    
    static function existe($codigo){
        $aux = FALSE;
  
        $query = self::$db->get_where('Archivo', array('Codigo'=>$codigo));
        
        if($query->num_rows() > 0){
            $aux = TRUE;
        }
        
        return $aux;
    }
    
    
    static function obtener($aux, $tipo = 'proyecto'){
        self::$db->select('*');
        self::$db->from('Archivos');
        if($tipo == 'proyecto'){
            self::$db->where('CodigoProyecto', $aux);
            self::$db->where('Pertenece', NULL);
            self::$db->order_by('Extension');
            self::$db->order_by('Nombre');            
        }    
        elseif($tipo == 'noticia')
            self::$db->where('CodigoNoticia', $aux);
        elseif($tipo == 'pagina')
            self::$db->where('CodigoPagina', $aux);
        elseif($tipo == 'tarea')
            self::$db->where('CodigoTarea', $aux);
        elseif($tipo == 'respuesta')
            self::$db->where('CodigoRespuesta', $aux);
        
        $query = self::$db->get();
        $archivos = $query->result();
        
        
        return $archivos;
    }
    
    static function obtenerArchivosCarpeta($codigo){
        self::$db->select('*');
        self::$db->from('Archivos');
        self::$db->where('Pertenece', $codigo);
        
        $query = self::$db->get();
        $archivos = $query->result();
        
        
        return $archivos;
    }
    
    static function obtenerVisibles ($codigoProyecto){
        self::$db->select('Codigo, Ruta');
        self::$db->from('Archivos');
        self::$db->where('CodigoProyecto', $codigoProyecto);
        self::$db->where('Visible', TRUE);
        
        $query = self::$db->get();
        $archivos = $query->result();
        
        return $archivos;
    }
    
    static function ocultar($codigoProyecto){        
        $datos = array('Visible' => FALSE);
        self::$db->update('Archivo', $datos, array('CodigoProyecto'=> $codigoProyecto));
        
    }
    
    static function numeroArchivosNuevos($email, $fecha){

        self::$db->select('CodigoProyecto');
        self::$db->from('ProyectoEmpleados');
        self::$db->where('EmailEmpleado',$email);

        $query = self::$db->get();
        $proyectos = $query->result();

        $aux = array();
        foreach($proyectos as $proyecto){
            array_push($aux, $proyecto->CodigoProyecto);
        }

        self::$db->select('Codigo');
        self::$db->from('Proyecto');
        self::$db->where('EmailAdmin',$email);
        $query = self::$db->get();
        $proyectos = $query->result();

        foreach($proyectos as $proyecto){
            array_push($aux, $proyecto->Codigo);
        }
       
        if(!empty($aux)){ 
            $fecha = str_replace('/', '-', $fecha);
            $date = new DateTime($fecha);

            self::$db->select('*');
            self::$db->from('Archivos');
            self::$db->where('EmailEmpleado !=', $email);
            self::$db->where('Fecha >=', $date->format('Y-m-d H:i:s'));
            self::$db->where_in('CodigoProyecto', $aux);
            
            return self::$db->count_all_results();
        }
        else{
            return 0;
        }        
    }
    
    static function archivosNuevos($email, $fecha){
        
        self::$db->select('CodigoProyecto');
        self::$db->from('ProyectoEmpleados');
        self::$db->where('EmailEmpleado',$email);

        $query = self::$db->get();
        $proyectos = $query->result();

        $aux = array();
        foreach($proyectos as $proyecto){
            array_push($aux, $proyecto->CodigoProyecto);
        }

        self::$db->select('Codigo');
        self::$db->from('Proyecto');
        self::$db->where('EmailAdmin',$email);
        $query = self::$db->get();
        $proyectos = $query->result();

        foreach($proyectos as $proyecto){
            array_push($aux, $proyecto->Codigo);
        }
        
        $archivos = array();
        if(!empty($aux)){
            $fecha = str_replace('/', '-', $fecha); 
            $date = new DateTime($fecha);

            self::$db->select('*');
            self::$db->from('Archivos');
            self::$db->where('EmailEmpleado !=', $email);
            self::$db->where('Fecha >=', $date->format('Y-m-d H:i:s'));
            //self::$db->where('CodigoProyecto !=', 'NULL');
            self::$db->where_in('CodigoProyecto', $aux);

            $query = self::$db->get();
            $archivos = $query->result();
        }
        
        return $archivos;
    }
    
    
    public function eliminar($codigo = ''){
        $aux = FALSE; 
        if($codigo != ''){ 
            if($this->existe($codigo)){
                $path = getcwd();
                $archivo = $this->datos($codigo);
                $ruta = str_replace('http://localhost/bareaarquitectos', realpath($path),$archivo->ruta());
                $ruta = str_replace('/', DIRECTORY_SEPARATOR, $ruta);
                
                if(file_exists($ruta)){ 
                    if(substr($ruta, -1) != '/'){
                        if(unlink($ruta) && $this->db->delete('Archivo', array('Codigo' => $codigo))){
                            $aux = TRUE;
                        }
                    }
                    else{
                        $this->_borrar_contenido($ruta);
                        if($this->db->delete('Archivo', array('Codigo' => $codigo)))
                            $aux = TRUE;
                    }
                }
                else{
                    
                    if($this->db->delete('Archivo', array('Codigo' => $codigo))){
                        $aux = TRUE;
                    }
                }
            }
        }
        else{
            if($this->existe($this->Codigo)){
                $path = getcwd(); 
                $ruta = str_replace('http://localhost/bareaarquitectos', realpath($path),$this->ruta());
                if(file_exists($ruta)){
                    if(substr($ruta, -1) != '/'){
                        if(unlink($ruta) && $this->db->delete('Archivo', array('Codigo' => $this->Codigo))){ 
                           $aux = TRUE;             
                        }
                    }
                    else{
                        $this->_borrar_contenido($ruta);
                        if($this->db->delete('Archivo', array('Codigo' => $this->Codigo)))
                            $aux = TRUE;
                    }
                }
                else{
                    if($this->db->delete('Archivo', array('Codigo' => $codigo))){
                        $aux = TRUE;
                    }
                }
            }
        }
        return $aux;
    }
    
    static function eliminarProyecto($codigo){
        $aux = FALSE;
        $path = getcwd();
        $ruta = str_replace('http://localhost/bareaarquitectos', realpath($path),"http://localhost/bareaarquitectos/archivos/proyectos/$codigo");
        Archivo_model::_borrar_contenido($ruta);
        
        if(self::$db->delete('Archivo', array('CodigoProyecto' => $codigo)))
            $aux = TRUE; 
        
        return $aux;
    }
    
    
    static function archivosProyecto ($codigo){
        self::$db->select('*');
        self::$db->from('Archivos');
        self::$db->where('CodigoProyecto', $codigo);
        
        $query = self::$db->get();
        
        return $query->result();        
    }
    
    private function _inicializarCarpeta($codigo = '', $codigoCarpeta = ''){
        $nombreCarpeta = $this->input->post('nombreCarpeta');
        $carpetaCreada = FALSE;
        $aux = FALSE;
        $path = getcwd();

        if($codigoCarpeta == ''){
            if(!file_exists("$path/archivos/proyectos/$codigo/$nombreCarpeta"))
                if(mkdir("$path/archivos/proyectos/$codigo/$nombreCarpeta", 0755))
                    $carpetaCreada = TRUE;

            $this->Ruta = base_url().'archivos/proyectos/'.$codigo.'/'.$nombreCarpeta.'/';                          
        }
        else{
            $ruta = str_replace('http://localhost/bareaarquitectos', realpath($path),$this->ruta($codigoCarpeta)); 
            if(!file_exists($ruta.$nombreCarpeta))
                if(mkdir($ruta.$nombreCarpeta))
                        $carpetaCreada = TRUE;
            $this->Pertenece = $codigoCarpeta;
            $this->Ruta = $this->ruta($codigoCarpeta).$nombreCarpeta.'/';
        }
        $this->CodigoProyecto = $codigo;
        $this->Nombre = $nombreCarpeta;
        $this->Tamanyo = '0';
        if($carpetaCreada){
            if($this->db->insert('Archivo', $this)){
                $aux = TRUE;
            }
        }
        
        return $aux;
    }
    
    
    private function _inicializarArchivoPresupuesto($codigo){
        $aux = FALSE;
        
        $path = base_url().'archivos/presupuestos/'.$this->input->post('nombre').'-'.date('d-m-Y H_i_s'). '.pdf';
        $this->Ruta = $path;
        $this->CodigoPresupuesto = $codigo;
        $this->Nombre = $this->input->post('email').'-'.date('d-m-Y H_i_s');
        $this->Extension = '.pdf';
        $this->Tamanyo = get_file_info(getcwd().'archivos/presupuestos/'.$this->input->post('nombre').'-'.date('d-m-Y H_i_s'). '.pdf', 'size') / 1024;

        if($this->db->insert('Archivo', $this)){
            $aux = TRUE;
        }
        
        return $aux;
    }
    
    private function _inicializarFoto($empleado = FALSE){
        $aux  = FALSE;    
        $path = getcwd();
        $config['upload_path'] =  realpath("$path/images/fotos/");
        $config['allowed_types'] = 'gif|jpg|jpeg|png|PNG|JPG|JPEG|GIF';
        $config['max_size']	= '0';
        $config['max_width']  = '0';
        $config['max_height']  = '0';
        $this->load->library('upload',$config);

        if ($this->upload->do_upload('archivo')){ 
            log_message('info','Archivo subido con éxito');
            $archivo = $this->upload->data();     
            $config['image_library'] = 'gd2';
            //CARPETA EN LA QUE ESTÁ LA IMAGEN A REDIMENSIONAR
            $config['source_image'] = realpath("$path/images/fotos/".$archivo['file_name'] );
            $config['create_thumb'] = TRUE;
            $config['maintain_ratio'] = TRUE;
            //CARPETA EN LA QUE GUARDAMOS LA MINIATURA
            if(!is_dir(realpath("$path/images/fotos/thumb/"))){           
                mkdir("$path/images/fotos/thumb/", 0755);     
            }
            $config['new_image']=realpath("$path/images/fotos/thumb/" );
            $config['width'] = 100;
            $config['height'] = 100;
            $this->image_lib->initialize($config);
            if(!$this->image_lib->resize())
                log_message('error', "Error imagen:".$this->image_lib->display_errors());
            $this->image_lib->clear();
            unlink(realpath("$path/images/fotos/".$archivo['file_name'] ));

            $this->Ruta = base_url().'images/fotos/thumb/'.$archivo['raw_name'].'_thumb'.$archivo['file_ext'];
            $this->Nombre = $archivo['raw_name'];
            $this->Extension = $archivo['file_ext'];
            $this->Tamanyo = $archivo['file_size'];
            
            if($this->input->post('email') != ''){
                $this->FotoEmpleado = TRUE; 
                $this->EmailEmpleado = $this->input->post('email');
            }

            if($this->db->insert('Archivo', $this)){
                $aux = TRUE;
            }
        }
        else{
            log_message('error', "Error imagen 2:".$this->upload->display_errors());
        }
        return $aux;
    }
    
    private function _inicializarArchivoProyecto($codigo = '', $codigoCarpeta = ''){
        $aux  = FALSE; 
        $path = getcwd();
        if($this->input->post('nombreArchivo') != ''){
            if($codigoCarpeta == ''){
                $ruta = $path.'/archivos/proyectos/'.$codigo.'/';                    
            }
            else{    
                $ruta = str_replace('http://localhost/bareaarquitectos', realpath($path),$this->ruta($codigoCarpeta)); 
            }
            write_file($ruta.$this->input->post('nombreArchivo'),'');
            $info = get_file_info($ruta.$this->input->post('nombreArchivo'));

            $nombre = explode(".", $info['name']);
            if($codigoCarpeta == '')
                if(isset($nombre[1]))
                    $this->Ruta = base_url().'archivos/proyectos/'.$codigo.'/'.$nombre[0].'.'.$nombre[1];
                else    
                    $this->Ruta = base_url().'archivos/proyectos/'.$codigo.'/'.$nombre[0].'.txt';
            else{
                if(isset($nombre[1]))
                    $this->Ruta = base_url().'archivos/proyectos/'.$codigo.'/'.$nombre[0].'.'.$nombre[1];
                else    
                    $this->Ruta = base_url().'archivos/proyectos/'.$codigo.'/'.$nombre[0].'.txt';
            }

            $this->CodigoProyecto = $codigo;
            $this->Nombre = $nombre[0];
            
            if(isset($nombre[1]))
                $this->Extension = '.'.$nombre[1];
            else
                $this->Extension = '.txt';
            $this->Tamanyo = $info['size'];
            if($codigoCarpeta != '')
                $this->Pertenece = $codigoCarpeta;

            if($this->db->insert('Archivo', $this)){
                $aux = TRUE;
            }
        }
        else{
            if($codigoCarpeta == '')
                $config['upload_path'] =  realpath("$path/archivos/proyectos/$codigo/");
            else{
                $ruta = str_replace('http://localhost/bareaarquitectos', realpath($path),$this->ruta($codigoCarpeta));
                $config['upload_path'] =  realpath($ruta);
            }    
            $config['allowed_types'] = '*';
            $config['max_size']	= '0';
            $config['max_width']  = '0';
            $config['max_height']  = '0';
            $this->load->library('upload',$config);
            $this->upload->initialize($config);

            if(isset($_FILES['archivos'])){
                $numArchivos = count($_FILES['archivos']['tmp_name']);

                for($i = 0; $i < $numArchivos; $i++){
                    $_FILES['userfile']['name'] = $_FILES['archivos']['name'][$i];
                    $_FILES['userfile']['type'] = $_FILES['archivos']['type'][$i];
                    $_FILES['userfile']['tmp_name'] = $_FILES['archivos']['tmp_name'][$i];
                    $_FILES['userfile']['error'] = $_FILES['archivos']['error'][$i];
                    $_FILES['userfile']['size'] = $_FILES['archivos']['size'][$i];

                    if ($this->upload->do_upload()){  
                        $archivo = $this->upload->data(); 

                        if($archivo['file_ext'] == '.jpg' || $archivo['file_ext'] == '.JPG' 
                           || $archivo['file_ext'] == '.png' || $archivo['file_ext'] == '.PNG' 
                           ||$archivo['file_ext'] == '.jpeg' || $archivo['file_ext'] == '.JPEG'){
                            $config['image_library'] = 'gd2';
                            //CARPETA EN LA QUE ESTÁ LA IMAGEN A REDIMENSIONAR
                             if($codigoCarpeta == '')
                                $config['source_image'] = realpath("$path/archivos/proyectos/$codigo/".$archivo['file_name'] );
                             else{
                                $ruta = str_replace('http://localhost/bareaarquitectos', realpath($path),$this->ruta($codigoCarpeta)); 
                                $config['source_image'] = realpath($ruta.$archivo['file_name'] );
                             }
                            $config['maintain_ratio'] = TRUE;
                            $config['width'] = 650;
                            $config['height'] = 650;
                            $this->image_lib->initialize($config);
                            if(!$this->image_lib->resize())
                                $aux = array('error'=>$this->image_lib->display_errors());
                            $this->image_lib->clear();
                        }

                        if(!is_array($aux)){
                            if($codigoCarpeta == '')
                                $this->Ruta = base_url().'archivos/proyectos/'.$codigo.'/'.$archivo['file_name'];
                            else{
                                $this->Ruta = $this->ruta($codigoCarpeta).$archivo['file_name'];
                            }
                            $this->Fecha = date('Y/m/d H:i:s');
                            $this->CodigoProyecto = $codigo;
                            $this->Nombre = $archivo['raw_name'];
                            $this->Extension = $archivo['file_ext'];
                            $this->Tamanyo = $archivo['file_size'];
                            if($codigoCarpeta != '')
                                $this->Pertenece = $codigoCarpeta;

                            if($this->db->insert('Archivo', $this)){
                                $aux = TRUE;
                            }
                        }
                    }
                    else{
                        $aux = array('error'=>$this->upload->display_errors());
                    }
                }

            }
        }
        return $aux;
    }
    
    
    private function _inicializarArchivoTarea($codigo, $tipo){
        $aux = FALSE;
        $config['upload_path'] =  realpath(__DIR__ . '/../../archivos/tareas/' );
        $config['allowed_types'] = 'gif|jpg|jpeg|png|doc|pdf|odt';
        $config['max_size']	= '0';
        $config['max_width']  = '0';
        $config['max_height']  = '0';
        $this->load->library('upload',$config);
        
        if(isset($_FILES['archivos'])){
            $numArchivos = count($_FILES['archivos']['tmp_name']);

            for($i = 0; $i < $numArchivos; $i++){
                $_FILES['userfile']['name'] = $_FILES['archivos']['name'][$i];
                $_FILES['userfile']['type'] = $_FILES['archivos']['type'][$i];
                $_FILES['userfile']['tmp_name'] = $_FILES['archivos']['tmp_name'][$i];
                $_FILES['userfile']['error'] = $_FILES['archivos']['error'][$i];
                $_FILES['userfile']['size'] = $_FILES['archivos']['size'][$i];

                if ($this->upload->do_upload()){  
                    $archivo = $this->upload->data(); 

                    $this->Ruta = base_url().'archivos/tareas/'.$archivo['file_name'];
                    $this->Nombre = $archivo['raw_name'];
                    $this->Extension = $archivo['file_ext'];
                    $this->Tamanyo = $archivo['file_size'];
                    if($tipo == 'tarea'){
                        $this->CodigoTarea = $codigo;
                    }
                    elseif($tipo == 'respuesta'){             
                        $this->CodigoRespuesta = $codigo;
                    }

                    $this->EmailEmpleado = $this->session->userdata('email');

                    if($this->db->insert('Archivo', $this)){
                        $aux = TRUE;
                    }
                }
            }
        }
        return $aux;
    }
    
    private function _borrar_contenido($dir){    
        if(is_dir($dir)){
            $handle = opendir($dir);
            $objects = get_dir_file_info($dir);
            foreach($objects as $object){
                if($object['name'] != "." && $object['name'] != ".."){
                    if(is_dir($dir.DIRECTORY_SEPARATOR.$object['name'])){
                        if (count(@scandir($dir.DIRECTORY_SEPARATOR.$object['name'])) > 2){
                            Archivo_model::_borrar_contenido($dir.DIRECTORY_SEPARATOR.$object['name']);
                        }
                        rmdir($dir.DIRECTORY_SEPARATOR.$object['name']);
                    }
                    else{
                        unlink($dir.DIRECTORY_SEPARATOR.$object['name']); 
                    }                    
                }                
            }
            reset($objects);
            closedir($handle);
            rmdir($dir); 
        }    
    }

    
    
}
?>
