<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Archivo extends MY_Controller{
    
    public function __construct() {
        parent:: __construct();
        $this->load->helper('form');
        $this->load->helper('file');
        $this->load->library('form_validation');
        $this->load->model('archivo_model');
        $this->load->model('proyecto_model');        
        $this->load->model('presupuesto_model');        
        $this->load->model('tarea_model');        
        $this->load->model('usuario_model');
        $this->load->library('user_agent');
    }
    
    public function crear($codigoProyecto, $codigoCarpeta = ''){ 
        $proyecto = new Proyecto_model;
        if($proyecto->estado($codigoProyecto) != 'Cerrado'){
            $this->form_validation->set_rules('nombreArchivo', 'Nombre de archivo', 'trim|required|alpha_numeric|min_length[3]|xss_clean');

            $this->form_validation->set_message('required', '%s no puede estar vacio');
            $this->form_validation->set_message('alpha_numeric', '%s no puede contener caaracteres especiales');
            $this->form_validation->set_message('min_length', '%s debe tener mínimo %s caracteres');
            $this->form_validation->set_message('xss_clean', ' %s no es válido');

            if($this->form_validation->run() == TRUE){
                $archivo = new Archivo_model;
                if(!$archivo->inicializar('proyecto', $codigoProyecto, $codigoCarpeta)){
                    $this->listar($codigoProyecto, $codigoCarpeta, array('error'=>'No se ha podido crear el archivo'));
                }
                else{
                    $this->listar($codigoProyecto, $codigoCarpeta);
                }
            }
            else{
                $this->listar($codigoProyecto, $codigoCarpeta);
            }            
        }
        else{
            $this->listar($codigoProyecto, $codigoCarpeta, array('error'=>'El proyecto está cerrado. No se pueden crear nuevos archivos'));
        }        
    }
    
    public function editar($codigoProyecto, $codigoArchivo){ 
        $this->form_validation->set_error_delimiters('<div class="alert alert-error"> <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <h4>Error</h4>', '</div>');
        $this->pagina = 'editar archivo';
        $this->carpeta = 'empleado';
        $this->menu = 'menu_empleado_proyecto';
        $this->estilo = array($this->pagina, 'proyectos', 'archivos','jquery-te-1.3.3',  'jquery-ui');
        $this->javascript = array('jquery-te-1.3.3.min','editor','sincroEditor');
        $this->titulo = 'archivos proyecto';
        $this->submenu = 'menu_proyecto';
        
        if($this->uri->segment(1) == 'admin'){
            $this->permisos('admin');            
            $datos['user'] = 'admin';            
        }
        else{
            $this->permisos('empleado');
            $datos['user'] = 'empleados'; 
        }
        
              
        $datos['boton'] = array( 'class'=> 'btn btn-info', 'name'=>'button', 'id' => 'boton_arhivo');
        
        if(Proyecto_model::existe($codigoProyecto)){ 
            $datos['codigoProyecto'] = $codigoProyecto;
            $proyecto = new Proyecto_model;
            $proyecto->datos($codigoProyecto);
            if($proyecto->pertenece($this->session->userdata('email'))){ 
                if(Archivo_model::existe($codigoArchivo)){
                    $datos['codigoArchivo'] = $codigoArchivo;
                    $archivo = new Archivo_model;
                    $codigoPadre = $archivo->pertenece($codigoArchivo);
                    $ruta = $path = str_replace('http://localhost/bareaarquitectos', realpath(getcwd()),$archivo->ruta($codigoArchivo));
                    $contenido = read_file($ruta);
                    if(!$contenido){
                        $datos['error'] = 'No se ha podido leer el contenido del archivo';
                    }
                    $padres = array();
                    while($codigoPadre != NULL){
                        $padres[$codigoPadre] = $archivo->nombre($codigoPadre);
                        $codigoPadre = $archivo->pertenece($codigoPadre);
                    }
                    
                    if(!empty($padres)){
                        $datos['enlaces'] = $padres;
                    }
                    $datos['nombreArchivo'] =  $archivo->nombre($codigoArchivo).$archivo->extension($codigoArchivo);
                    $datos['codigo'] = $codigoProyecto;            
                    $datos['nombreProyecto'] = $proyecto->nombre();
                    $datos['formulario'] = array(
                        'contenido' => array('class' => 'editor', 'id' => "contenido-$codigoProyecto-$codigoArchivo", 'name'=>"contenido", 'label' => 'contenido', 'value' => $contenido)
                    );
                    $this->form_validation->set_rules('contenido', 'Contenido', 'trim|required|min_length[3]');
        
                    $this->form_validation->set_message('required', 'El campo %s no puede estar vacio');
                    $this->form_validation->set_message('min_legth', 'El campo %s debe tener mínmo 3 caracteres');
                    $this->form_validation->set_message('xss_clean', 'El campo %s no es válido');       


                    if($this->form_validation->run() == TRUE){
                        $contenido = $this->input->post('contenido');
                        $datos['formulario'] = array(
                            'contenido' => array('class' => 'editor', 'id' => "contenido-$codigoProyecto-$codigoArchivo", 'name'=>'contenido', 'label' => 'contenido', 'value' => $contenido)
                        );                        
                        if(write_file($ruta, $contenido)){
                            $datos['exito'] = 'Se han realizado los cambios en el archivo.';
                            $info = get_file_info($ruta);
                            $archivo->tamanyo($codigoArchivo, $info['size']);
                        }
                        else{
                            $this->error = array(
                                    'nivel'=>'2',
                                    'mensaje'=>'No se han podido realizar los cambios en el archivo.'
                                );
                        }
                    }
                }
                else
                    $this->error = array(
                                    'nivel'=>'1',
                                    'mensaje'=>'El archivo indicado no existe.'
                                );
            }
            else{
                 $this->error = array(
                            'nivel'=>'1',
                            'mensaje'=>'No puedes ver el contenido del proyecto.'
                        );
            }              
        }
        else{
             $this->error = array(
                            'nivel'=>'1',
                            'mensaje'=>'El proyecto indicado no existe.'
                        );
        }
        $this->mostrar($datos);   
    }
    
    public function registrar($codigo, $codigoCarpeta = '') {
        
        if(Proyecto_model::existe($codigo)){
            $proyecto = new Proyecto_model;
            if($proyecto->estado($codigo) != 'Cerrado'){
                if($this->session->userdata('usuario') == 'admin' || 
                    Proyecto_model::existeEmpleado($codigo, $this->session->userdata('email'))){
                    if(!is_dir(getcwd().'/archivos/proyectos/')){           
                        mkdir(getcwd().'/archivos/proyectos/',  0755);     
                    }
                    if(!is_dir(getcwd().'/archivos/proyectos/'.$codigo)){           
                        mkdir(getcwd().'/archivos/proyectos/'.$codigo,  0755);     
                    }
                    $archivo = new Archivo_model;
                    $cod = '';
                    //Si queremos registrar una carpeta
                    if($this->input->post('nombreCarpeta') != ''){
                        if($codigoCarpeta != ''){
                            $aux = $archivo->inicializar('carpeta',$codigo, $codigoCarpeta);
                            $cod = $archivo->codigo();
                        }
                        else{
                            $aux = $archivo->inicializar('carpeta',$codigo);
                            $cod = $archivo->codigo();
                        }
                    }
                    else{                        
                        if($codigoCarpeta == ''){
                            $aux = $archivo->inicializar('proyecto',$codigo);
                            $cod = $archivo->codigo();
                        }
                        else{
                            $aux = $archivo->inicializar('proyecto',$codigo, $codigoCarpeta);
                            $cod = $archivo->codigo();
                        }
                    }
                    
                    if(!is_array($aux)){
                        if($codigoCarpeta == '')
                            $this->listar($codigo);
                        else
                            $this->listar($codigo, $codigoCarpeta);
                        $empleados = array();
                        $e = new Usuario_model;
                        foreach(Proyecto_model::empleadosProyecto($codigo) as $empl){
                            if($e->tipo($empl->EmailEmpleado) == 'empleado')
                                $empleados[$empl->EmailEmpleado] = 'empleados';
                            else
                                $empleados[$empl->EmailEmpleado] = $e->tipo($empl->EmailEmpleado);
                        }
                        $numArchivos = 1;
                        if(isset($_FILES['archivos'])){
                            $numArchivos = count($_FILES['archivos']['tmp_name']);
                        }
                        for($i = $numArchivos; $i != 0; $i--){
                            $pusher = array(
                                'codigo'    => $cod,
                                'carpeta'   => $codigoCarpeta,
                                'proyecto'  => $codigo,
                                'nombre'    => $archivo->nombre($cod),
                                'ruta'      => $archivo->ruta($cod),
                                'tamanyo'   => $archivo->tamanyo($cod),
                                'extension' => $archivo->extension($cod),
                                'fechaCompleta' => date('d-m-Y',strtotime($archivo->fecha($cod))),
                                'fecha'     => date('d M Y',strtotime($archivo->fecha($cod))),
                                'hora'      => date('H:i A',strtotime($archivo->fecha($cod))),
                                'empleado'  => $this->session->userdata('nombre').' '.$this->session->userdata('apellidos'),
                                'empleados' => $empleados

                            );
                            $this->pusher->trigger('private-notificaciones-empleado', 'crear-archivo', $pusher);
                            $cod--;
                        }
                    }
                    else{
                        if($codigoCarpeta == '')
                            $this->listar($codigo,'', $aux);
                        else
                            $this->listar($codigo,$codigoCarpeta, $aux);
                    }
                    
                    
                    if($codigoCarpeta != ''){
                        if($this->session->userdata('usuario') == 'admin')
                            redirect("admin/proyecto/archivos/$codigo/$codigoCarpeta");
                        else
                            redirect("empleados/proyecto/archivos/$codigo/$codigoCarpeta");
                    }
                    else{
                        if($this->session->userdata('usuario') == 'admin')
                            redirect("admin/proyecto/archivos/$codigo");
                        else
                            redirect("empleados/proyecto/archivos/$codigo");
                    }
                }
                else{
                    $error = array('error' => 'No puedes registrar archivos en este proyecto');
                    $this->listar($codigo,'', $error);
                }
            }
            else{
                $error = array('error' => 'El proyecto esta cerrado');
                $this->listar($codigo,'', $error);
            }
        }
        else{
            $this->mostrar($datos);
        }
    }
    
    public function listar($codigo, $codigoCarpeta = '', $error=""){ 
        $this->pagina = 'archivos';
        $this->carpeta = 'empleado';
        $this->menu = 'menu_empleado_proyecto';
        $this->estilo = array($this->pagina, 'proyectos','bootstrap-lightbox');
        $this->titulo = 'archivos proyecto';
        if($this->uri->segment(1) == 'admin'){
            $this->permisos('admin');            
            $datos['user'] = 'admin';            
        }
        else{
            $this->permisos('empleado');
            $datos['user'] = 'empleados'; 
        }
            
        if(Proyecto_model::existe($codigo)){
            $proyecto = new Proyecto_model;
            $proyecto->datos($codigo);
            if($proyecto->pertenece($this->session->userdata('email'))){ 
                if($codigoCarpeta != ''){
                    $archivos = Archivo_model::obtenerArchivosCarpeta($codigoCarpeta);
                    
                    $archivo = new Archivo_model;
                    $codigoPadre = $archivo->pertenece($codigoCarpeta);
                    $padres = array();
                    while($codigoPadre != NULL){
                        $padres[$codigoPadre] = $archivo->nombre($codigoPadre);
                        $codigoPadre = $archivo->pertenece($codigoPadre);
                    }
                    if(!empty($padres)){
                        $datos['enlaces'] = $padres;
                    }
                    $datos['codigoCarpeta'] = $codigoCarpeta;
                    $datos['nombreCarpeta'] = $archivo->nombre($codigoCarpeta);
                }
                else{
                    $archivos = Archivo_model::obtener($codigo);
                   
                }  
                
                $datos['nombreProyecto'] = $proyecto->nombre();
                $datos['archivos'] = $archivos;
                $datos['codigo'] = $codigo;            
                $this->submenu = 'menu_proyecto';           
                $this->javascript = array('foco', 'bootstrap-lightbox', 'tooltip');
                $datos['boton'] = array( 'class'=> 'btn btn-info', 'name'=>'button');
                $datos['estado'] = $proyecto->estado();
                if($error != ''){
                    if(is_array($error))
                        $this->error = array(
                            'nivel' => '1',
                            'mensaje'=>$error['error']
                        );
                    else{
                         $this->error = array(
                            'nivel' => '2',
                            'mensaje'=> 'No se ha podido eliminar el archivo'
                         );
                    }
                }
            }
            else{
                $this->error = array(
                            'nivel' => '1',
                            'mensaje'=> 'No tienes acceso a este proyecto'
                 );
            }
        }
        else{
             $this->error = array(
                            'nivel' => '1',
                            'mensaje'=> 'El proyecto indicado no existe'
                 );
        }
        $this->mostrar($datos);
    }
    
    
    public function borrar($codigoProyecto, $codigoArchivo = '', $codigoCarpeta = ''){
        $usuario = $this->session->userdata('usuario');
        if(Proyecto_model::existe($codigoProyecto)){ 
            $proyecto = new Proyecto_model;
            if($proyecto->estado($codigoProyecto) != 'Cerrado'){
                
                if($codigoArchivo != ''){ 
                    if(Archivo_model::existe($codigoArchivo)){ 
                        $archivo = new Archivo_model;
                                                
                        if($this->session->userdata('usuario') == 'admin' || $archivo->emailEmpleado($codigoArchivo) == $this->session->userdata('email')){
                            if($archivo->eliminar($codigoArchivo)){
                               if($usuario == 'admin')
                                    if($codigoCarpeta != '')
                                        redirect("$usuario/proyecto/archivos/$codigoProyecto/$codigoCarpeta");
                                    else    
                                        redirect("$usuario/proyecto/archivos/$codigoProyecto");
                                elseif($usuario == 'empleado')
                                    if($codigoCarpeta != '')
                                        redirect("empleados/proyecto/archivos/$codigoProyecto/$codigoCarpeta");
                                    else    
                                        redirect("empleados/proyecto/archivos/$codigoProyecto");
                            }
                            else{
                                $error = array('error' => 'No se ha podido eliminar.');
                                $this->listar($codigoProyecto, $codigoCarpeta, $error);
                            }
                        }
                        else{
                                $error = array('error' => 'No se ha podido eliminar el archivo');
                                $this->listar($codigoProyecto, $codigoCarpeta, $error);
                        }
                    }
                }
                else{
                    
                    if(Archivo_model::eliminarProyecto($codigoProyecto)){
                        if($usuario == 'admin')
                            redirect("$usuario/proyecto/archivos/$codigoProyecto");
                        elseif($usuario == 'empleado')
                            redirect("empleados/proyecto/archivos/$codigoProyecto");
                    }
                    else{
                        $error = array('error' => 'No se han podido eliminar los archivos del proyecto.');
                        $this->listar($codigoProyecto, $codigoCarpeta, $error);
                    }
                }
            }
            else{
                $error = array('error' => 'El proyecto esta cerrado');
                $this->listar($codigoProyecto, $codigoCarpeta, $error);
            }
        }
        else{
                $error = array('error' => 'El proyecto no existe');
                $this->listar($codigoProyecto, $codigoCarpeta, $error);
        }
    }
    
    public function descargar($codigo){
        if(Archivo_model::existe($codigo)){ 
            $aux = FALSE;
            $archivo = new Archivo_model;
            $archivo->datos($codigo);
            $file = str_replace('http://localhost', realpath(__DIR__ . '/../../../'),$archivo->ruta($codigo));
            
            if($archivo->codigoTarea() != NULL || $archivo->codigoRespuesta() != NULL ){ 
                if($this->session->userdata('usuario') == 'admin' 
                  || Tarea_model::existeEmpleado($archivo->codigoTarea($codigo), $this->session->userdata('email'))){
                    $aux = TRUE;
                }
            }
            else if($this->session->userdata('usuario') == 'admin' 
                || $archivo->emailEmpleado() == $this->session->userdata('email') 
                || Proyecto_model::existeEmpleado($archivo->codigoProyecto($codigo), $this->session->userdata('email'))){
                $aux = TRUE;
            }
            elseif($this->session->userdata('usuario') == 'cliente'){
                if($archivo->CodigoProyecto != NULL){
                    $proyecto = new Proyecto_model;
                    $codigoP = $proyecto->codigoPresupuesto($archivo->CodigoProyecto);
                    
                    $presupuesto = new Presupuesto_model;
                    if($codigoP != '')
                        if($presupuesto->email($codigoP) == $this->session->userdata('email'))
                            $aux = TRUE;
                    
                }
                else{
                    $codigoP = $archivo->codigoPresupuesto($codigo);
                    $presupuesto = new Presupuesto_model;
                    if($codigoP != '')
                        if($presupuesto->email($codigoP) == $this->session->userdata('email'))
                            $aux = TRUE;
                }
            }
            
            if($aux){ 
                if (file_exists($file)) {
                    $extension = $archivo->Extension;
                    if($extension == 'pdf')
                        header('Content-type: application/pdf');
                    elseif($extension == 'jpg' || $extension == 'png' || $extension == 'jpeg')
                        header('Content-type: application/jpg');
                    header('Content-Disposition: attachment; filename='.basename($file));
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));
                    ob_clean();
                    flush();
                    readfile($file);            
                }
                else{
                    echo "El archivo no existe";
                }
            }
            else{
                echo 'No puedes descargar el archivo';
            }
        }
        else{
            echo 'El archivo no existe';
        }
            
    }
    
    public function descargarZip($codigoProyecto, $codigoCarpeta = ''){ 
        $this->load->library('zip');
        
        if(Proyecto_model::existe($codigoProyecto)){
            $proyecto = new Proyecto_model;
            $nombre = $proyecto->nombre($codigoProyecto);
            if($this->session->userdata('usuario') == 'admin' 
                || Proyecto_model::existeEmpleado($codigoProyecto, $this->session->userdata('email'))){
                if($codigoCarpeta == ''){
                    $path =  getcwd()."/archivos/proyectos/$codigoProyecto/";                    
                    
                }
                else{
                    if(Archivo_model::existe($codigoCarpeta)){
                        $archivo = new Archivo_model;                    
                        $path = str_replace('http://localhost/bareaarquitectos', realpath(getcwd()),$archivo->ruta($codigoCarpeta));                        
                    }
                }
                if(file_exists($path)){
                    $this->zip->read_dir($path, FALSE);
                    $this->zip->download($nombre);
                }            
           }
        }
    }
    

    public function sincronizar(){
        if(empty($_POST)){
            redirect('404');
        }
        else{
            $nombre = $_POST['id'];
            $aux = explode('-', $nombre);
            $empleados = array();
            $e = new Usuario_model;
            foreach(Proyecto_model::empleadosProyecto($aux[1]) as $empl){
                if($e->tipo($empl->EmailEmpleado) == 'empleado')
                    $empleados[$empl->EmailEmpleado] = 'empleados';
                else
                    $empleados[$empl->EmailEmpleado] = $e->tipo($empl->EmailEmpleado);
            }
            $pusher = array(
                'texto' => $_POST['texto'],
                'id'    => $_POST['id'],
                'empleados' => $empleados,
                'usuario' => $this->session->userdata('email')
            );
            $this->pusher->trigger('editor','sincronizacion',$pusher);
        }
    }
    
}

?>