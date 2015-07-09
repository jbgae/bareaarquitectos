<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'controllers/proyecto.php');

class Tarea extends Proyecto{
    
    public function __construct() {
        parent:: __construct();
        $this->load->model('tarea_model');
        $this->load->model('respuesta_model');
        $this->load->model('archivo_model');
        $this->load->model('empleado_model');
    }
    
    
    public function registrar($codigo){ 
        
        $this->permisos('admin');
        $datos['user'] = 'admin';
        $this->form_validation->set_error_delimiters('<div class="alert alert-error"> <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <h4>Error</h4>', '</div>');
        $this->pagina = 'crear tarea';
        $this->carpeta = 'administrador';
        $this->titulo = 'crear tarea';
        $this->estilo = array('jquery-te-1.3.3', 'jquery-ui','proyectos', 'notas', 'tareas', $this->pagina);
        $this->javascript = array('fecha','editor', 'jquery-te-1.3.3.min','jquery-ui');
        $this->menu = 'menu_admin_proyecto';
                
        if(Proyecto_model::existe($codigo)){
            $proyecto = new Proyecto_model;
            $proyecto->datos($codigo);
            $datos['estado'] = $proyecto->estado();
            if($proyecto->pertenece($this->session->userdata('email'))){
                $datos['codigo'] = $codigo;
                $this->submenu = 'menu_proyecto';
                $datos['nombreProyecto'] = $proyecto->nombre($codigo);
                $datos['tareas'] = Tarea_model::obtener($this->session->userdata('email'), $codigo);
                $datos['opciones'] = array('ejecucion' => 'Ejecución', 'cerrado'=>'Cerrado');
                $empleadosProyecto = Proyecto_model::empleadosProyecto($codigo);
                $datos['empleadosProyecto'] = array();
                foreach($empleadosProyecto as $empleado){
                    if($empleado->EmailEmpleado != $this->session->userdata('email'))
                    $datos['empleadosProyecto'][$empleado->EmailEmpleado] = $empleado->Nombre.' '.$empleado->ApellidoP.' '.$empleado->ApellidoM;
                }

                $datos['formulario'] = array(
                    'titulo' => array('class'=>'input-xlarge', 'id'=>'titulo', 'name'=>'titulo', 'label'=> 'Título', 'maxlength'=> '150', 'type' => 'text', 'value' => $this->input->post('titulo'), 'autofocus'=>'autofocus'),
                    'contenido' => array('class'=>'editor', 'id'=>'contenido', 'name'=>'contenido', 'label'=> '¿Qué hacer?', 'type' => 'text', 'value' => $this->input->post('contenido')),
                    'asignado' => array('class'=>'asignado', 'id'=>'asignado', 'name'=>'asignado', 'label'=>'Asignar a'),
                    'estado' => array('class'=>'estado', 'id'=>'estado', 'name'=>'estado', 'label'=> 'Estado', 'value' => $this->input->post('estado')),
                    'fechaLimite' => array('class'=>'fechaLimite input-small', 'id'=>'fechaLimite', 'name'=>'fechaLimite', 'label'=> 'Fecha Límite', 'value' => $this->input->post('fechaLimite')),
                );

                $datos['boton'] = array( 'class'=> 'btn btn-info', 'name'=>'button', 'id' => 'boton_tareas');

                $this->form_validation->set_rules('titulo', 'Titulo', 'trim|required|min_length[3]|xss_clean');
                $this->form_validation->set_rules('contenido', 'Contenido', 'trim|required|min_length[3]');

                $this->form_validation->set_message('required', 'El campo %s no puede estar vacio');
                $this->form_validation->set_message('min_legth', 'El campo %s debe tener mínmo 3 caracteres');
                $this->form_validation->set_message('xss_clean', 'El campo %s no es válido');       

                if($this->form_validation->run() == TRUE){                    
                    $tarea = new Tarea_model;
                    $codigoTarea = $tarea->inicializar($codigo);
                    if($codigoTarea){
                        $archivo = new Archivo_model; 
                        $archivo->inicializar('tarea', $codigoTarea);                   

                        $datos['codigo'] = $codigo;
                        $this->submenu = 'menu_proyecto';
                        $datos['nombreProyecto'] = $proyecto->nombre($codigo);
                        $datos['tareas'] = Tarea_model::obtener($this->session->userdata('email'), $codigo);
                        $datos['opciones'] = array('ejecucion' => 'Ejecución', 'cerrado'=>'Cerrado');
                        $empleadosProyecto = Proyecto_model::empleadosProyecto($codigo);
                        $datos['empleadosProyecto'] = array();
                        foreach($empleadosProyecto as $empleado){
                            $datos['empleadosProyecto'][$empleado->EmailEmpleado] = $empleado->Nombre.' '.$empleado->ApellidoP.' '.$empleado->ApellidoM;
                        }
                        $this->exito = 'La tarea ha sido registrada satisfactoriamente.';
                        
                        $data = array(
                            'codigo' => $codigo,
                            'codigoTarea' => $codigoTarea,
                            'admin'  => $this->session->userdata('nombre').' '.$this->session->userdata('apellidos'),
                            'empleado' => $this->input->post('asignado'),
                            'fecha'  => date('d M Y',strtotime($tarea->fechaCreacion())),
                            'hora'=> date('H:i A',strtotime($tarea->fechaCreacion())),
                            'titulo' => $tarea->titulo(),                            
                        );
                        $this->pusher->trigger('private-notificaciones', 'tarea-enviar', $data);
                        
                        $datos['tareas'] = Tarea_model::obtener($this->session->userdata('email'), $codigo);
                    }
                }
            }
            
        }
        else{
            $this->error = array(
                'nivel'=>'1',
                'mensaje'=>'El proyecto indicado no existe'
            );
        }
        $this->mostrar($datos); 
    }
    
    
     public function verTarea($codigoProyecto, $codigoTarea){
        $this->pagina = 'tarea';
        $this->carpeta = 'empleado';
        $this->titulo = "tarea";
        $this->estilo = array('jquery-te-1.3.3', '../jquery-ui', 'proyectos', 'notas','tareas');
        $this->javascript = array('editor', 'jquery-te-1.3.3.min', 'notas', 'select', 'tooltip');
        $this->menu = 'menu_empleado_proyecto';       
        $this->form_validation->set_error_delimiters('<div class="alert alert-error"> <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <h4>Error</h4>', '</div>');
         
        if($this->uri->segment('1') == 'admin'){
            $this->permisos('admin');
            $datos['user'] = 'admin';
        }
        else{
            $this->permisos('empleado');
            $datos['user'] = 'empleados';
        }  
        
        if(Proyecto_model::existe($codigoProyecto)){            
            $datos['codigo'] = $codigoProyecto;
            $proyecto = new Proyecto_model;
            $proyecto->datos($codigoProyecto);
            $datos['nombreProyecto'] = $proyecto->nombre();
            $datos['estado'] = $proyecto->estado();
            if($proyecto->pertenece($this->session->userdata('email'))){                        
                if(Tarea_model::existe($codigoTarea) && $this->_permisosTareas($codigoProyecto, $codigoTarea) ){ 
                    $this->submenu = 'menu_proyecto';
                    $tarea = new Tarea_model;
                    $datos['tarea'] = $tarea->datos($codigoTarea); 

                    $empleado = new Empleado_model;
                    $datos['foto'] = $empleado->foto($tarea->email());


                    $archivos = Archivo_model::obtener($codigoTarea, 'tarea');

                    if(!empty($archivos)){
                        $i = 0;
                        foreach($archivos as $archivo){
                            $datos['archivo'][$i]['ruta'] = $archivo->Ruta;
                            $datos['archivo'][$i]['nombre'] = array_pop(explode("/",$archivo->Ruta));
                            $datos['archivo'][$i]['codigo'] = $archivo->Codigo;
                            $i++;
                        }
                    }
                    $respuestas = Respuesta_model::obtener($codigoTarea); 
                    if(!empty($respuestas)){
                        $datos['respuestas'] = $respuestas;
                        foreach($respuestas as $respuesta){
                            $datos['fotoRespuesta'][$respuesta->Email] = $empleado->foto($respuesta->Email);
                            $archivosRespuesta = Archivo_model::obtener($respuesta->Codigo, 'respuesta');
                            $i = 0;
                            if(!empty($archivosRespuesta)){
                                foreach($archivosRespuesta as $archivo){                             
                                    $datos['archivoRespuesta'][$respuesta->Codigo][$i]['nombre'] = array_pop(explode("/",$archivo->Ruta));
                                    $datos['archivoRespuesta'][$respuesta->Codigo][$i]['codigo'] = $archivo->Codigo;
                                    $i++;
                                }
                            }
                        }                    
                    }     

                    $datos['tareas'] = Tarea_model::obtener($this->session->userdata('email'),$codigoProyecto); 

                    $datos['opciones'] = array('ejecucion' => 'Ejecución', 'cerrado'=>'Cerrado');  
                    $datos['formulario'] = array(
                        'contenido' => array('class'=>'editor', 'id'=>'contenido', 'name'=>'contenido', 'label'=> 'Responder:', 'type' => 'text'),                    
                        'estado' => array('class'=>'estado', 'id'=>'estado', 'name'=>'estado', 'label'=> 'Estado'),
                    );
                    $datos['boton'] = array( 'class'=> 'btn btn-info', 'name'=>'button', 'id' => 'boton_tareas');

                    $this->form_validation->set_rules('contenido', 'Contenido', 'trim|min_length[3]');

                    $this->form_validation->set_message('required', 'El campo %s no puede estar vacio');
                    $this->form_validation->set_message('min_legth', 'El campo %s debe tener mínmo 3 caracteres');
                    $this->form_validation->set_message('xss_clean', 'El campo %s no es válido');       

                    if($this->form_validation->run() == TRUE){
                        if($this->input->post('estado') == 'cerrado'){
                            $tarea->estado($codigoTarea, TRUE);
                            $datos['tarea'] = $tarea->datos($codigoTarea); 
                        }
                        else{
                            $respuesta = new Respuesta_model();
                            $respuesta->inicializar($codigoTarea);
                            $codigoRespuesta = $respuesta->codigo();

                            if($codigoRespuesta){
                                $archivo = new Archivo_model; 
                                $archivo->inicializar('respuesta', $codigoRespuesta);
                                $empleado = new Empleado_model;
                                if($empleado->foto($this->session->userdata('email')) == '')
                                    $foto = base_url()."images/indice.jpeg";
                                else
                                    $foto = $empleado->foto($this->session->userdata('email'));

                                $data = array(
                                    'codigo' => $codigoProyecto,
                                    'codigoTarea' => $codigoTarea,
                                    'codigoRespuesta' => $codigoRespuesta,
                                    'resp' => $this->input->post('contenido'),
                                    'empleado' => $this->session->userdata('nombre').' '.$this->session->userdata('apellidos'),
                                    'email' => $this->session->userdata('email'),
                                    'foto' => $foto,
                                    'empleados' => Tarea_model::empleadosTarea($codigoTarea),
                                    'user' => $this->session->userdata('usuario'),
                                    'fecha'  => date('d M Y',strtotime($tarea->fechaCreacion())),
                                    'hora'=> date('H:i A',strtotime($tarea->fechaCreacion())),
                                    'fechaCompleta' => date('d-m-Y H:i:s',strtotime($tarea->fechaCreacion())),
                                    'titulo' => ucfirst($tarea->titulo($codigoTarea)),                            
                                );
                                $this->pusher->trigger('private-notificaciones-empleado', 'respuesta-enviar', $data);

                                $respuestas = Respuesta_model::obtener($codigoTarea); 
                                if(!empty($respuestas)){
                                    $datos['respuestas'] = $respuestas;
                                    foreach($respuestas as $respuesta){
                                        $datos['fotoRespuesta'][$respuesta->Email] = $empleado->foto($respuesta->Email);
                                        $archivosRespuesta = Archivo_model::obtener($respuesta->Codigo, 'respuesta');
                                        if(!empty($archivosRespuesta)){
                                            $i = 0;
                                            foreach($archivosRespuesta as $archivo){                             
                                                $datos['archivoRespuesta'][$i][$respuesta->Codigo]['nombre'] = array_pop(explode("/",$archivo->Ruta));
                                                $datos['archivoRespuesta'][$i][$respuesta->Codigo]['codigo'] = $archivo->Codigo;
                                                $i++;
                                            }
                                        }
                                    }
                                } 
                            } 
                        }
                    }                
                }            
                else{
                    $this->error = array(
                        'nivel' => '1',
                        'mensaje' => 'La tarea indicada no existe'

                    );
                }
            }
            else{
                $this->error = array(
                        'nivel' => '1',
                        'mensaje' => 'No puedes acceder al proyecto'

                    );
            }
        }
        else{
           $this->error = array(
                    'nivel' => '1',
                    'mensaje' => 'El proyecto indicado no existe'
                    
                );
        }
        $this->mostrar($datos);
    }
    
    public function tareas($codigoProyecto){
        $this->permisos('empleado');
        $this->pagina = 'tareas';
        $this->carpeta = 'empleado';
        $this->menu = 'menu_empleado_proyecto';
        $this->submenu = 'menu_proyecto';
        $this->estilo = array( 'proyectos', 'notas','tareas');
        $this->javascript = array( 'notas', 'select', 'tooltip','confirmacion');

        $datos['user'] = 'empleados';
        
        if(Proyecto_model::existe($codigoProyecto)){
            
            $datos['codigo'] = $codigoProyecto;
            $proyecto = new Proyecto_model;
            $proyecto->datos($codigoProyecto);
            if($proyecto->pertenece($this->session->userdata('email'))){
                $datos['nombreProyecto'] = $proyecto->nombre();
                $datos['tareas'] = Tarea_model::obtener($this->session->userdata('email'),$codigoProyecto);
            }
            else{
                $this->error = array(
                        'nivel' => '1',
                        'mensaje' => 'No puedes acceder al proyecto'

                    );
            }
        }
        $this->mostrar($datos);
    }
    
    
        
    public function editar($codigoProyecto, $codigoTarea){
        $this->permisos('admin');
        $datos['user'] = 'admin';
        $this->pagina = 'crear tarea';
        $this->carpeta = 'administrador';
        $this->titulo = 'editar tarea';
        $this->estilo = array('jquery-te-1.3.3', 'jquery-ui', 'proyectos','notas', 'tareas');
        $this->javascript = array('fecha','editor', 'jquery-te-1.3.3.min','jquery-ui');
        $this->menu = 'menu_admin_proyecto';
        $this->submenu = 'menu_proyecto';
        $datos['actualizar'] = TRUE;
        $this->form_validation->set_error_delimiters('<div class="text-error">', '</div>');
        
        if(Proyecto_model::existe($codigoProyecto)){ 
            $datos['codigo'] = $codigoProyecto;
            $proyecto = new Proyecto_model;
            $proyecto->datos($codigoProyecto);
            $datos['estado'] = $proyecto->estado();
            $datos['nombreProyecto'] = $proyecto->nombre();
            $datos['empleadosProyectos'] = array();
            $datos['opciones'] = array('ejecucion' => 'Ejecución', 'cerrado'=>'Cerrado');
                        
            if(Tarea_model::existe($codigoTarea) && $this->_permisosTareas($codigoProyecto, $codigoTarea)){               
                $tarea = new Tarea_model;
                $datos['tarea'] = $tarea->datos($codigoTarea);               
                $datos['tareas'] = Tarea_model::obtener($this->session->userdata('email'),$codigoProyecto);                
                            
                
                $datos['formulario'] = array(
                    'titulo' => array('class'=>'input-xlarge', 'id'=>'titulo', 'name'=>'titulo', 'label'=> 'Título', 'maxlength'=> '150', 'type' => 'text', 'value' => $tarea->titulo(), 'autofocus'=>'autofocus'),
                    'contenido' => array('class'=>'editor', 'id'=>'contenido', 'name'=>'contenido', 'label'=> '¿Qué hacer?', 'type' => 'text', 'value' => $tarea->contenido()),
                    'asignado' => array('class'=>'uneditable-input', 'id'=>'asignado', 'name'=>'asignado', 'label'=>'Asignar a', 'value' => $tarea->asignado($codigoTarea, TRUE)),
                    'estado' => array('class'=>'estado', 'id'=>'estado', 'name'=>'estado', 'label'=> 'Estado', 'value' => $this->input->post('estado')),
                    'fechaLimite' => array('class'=>'fechaLimite input-small', 'id'=>'fechaLimite', 'name'=>'fechaLimite', 'label'=> 'Fecha Límite', 'value' => $this->input->post('fechaLimite')),
                );

                $datos['boton'] = array( 'class'=> 'btn btn-info', 'name'=>'button', 'id' => 'boton_tareas');

                $this->form_validation->set_rules('titulo', 'Titulo', 'trim|required|min_length[3]|xss_clean');
                $this->form_validation->set_rules('contenido', 'Contenido', 'trim|required|min_length[3]');

                $this->form_validation->set_message('required', 'El campo %s no puede estar vacio');
                $this->form_validation->set_message('min_legth', 'El campo %s debe tener mínmo 3 caracteres');
                $this->form_validation->set_message('xss_clean', 'El campo %s no es válido');       

                if($this->form_validation->run() == TRUE){
                    if($tarea->actualizar()){
                       $datos['tarea'] = $tarea->datos($codigoTarea);  
                       $datos['tareas'] = Tarea_model::obtener($this->session->userdata('email'),$codigoProyecto);                                    
                       $archivo = new Archivo_model; 
                       $archivo->inicializar('tarea', $codigoTarea);  
                       $this->exito = 'La tarea ha sido actualizada satisfactoriamente.';
                       $datos['formulario'] = array(
                            'titulo' => array('class'=>'input-xlarge', 'id'=>'titulo', 'name'=>'titulo', 'label'=> 'Título', 'maxlength'=> '150', 'type' => 'text', 'value' => $tarea->titulo()),
                            'contenido' => array('class'=>'editor', 'id'=>'contenido', 'name'=>'contenido', 'label'=> '¿Qué hacer?', 'type' => 'text', 'value' => $tarea->contenido()),
                            'asignado' => array('class'=>'asignado', 'id'=>'asignado', 'name'=>'asignado', 'label'=>'Asignar a', 'value' => $tarea->asignado($codigoTarea, TRUE)),
                            'estado' => array('class'=>'estado', 'id'=>'estado', 'name'=>'estado', 'label'=> 'Estado', 'value' => $this->input->post('estado')),
                            'fechaLimite' => array('class'=>'fechaLimite input-small', 'id'=>'fechaLimite', 'name'=>'fechaLimite', 'label'=> 'Fecha Límite', 'value' => $this->input->post('fechaLimite')),
                        );
                       
                    }            
                    else{
                        $this->error = array(
                                'nivel'=>'2',
                                'mensaje'=>'No se ha podido actualizar la tarea, por favor inténtelo de nuevo más tarde'
                            );
                    }                
                }
            }
            else{
                $this->error = array(
                    'nivel' => '1',
                    'mensaje'=>'La tarea indicada no existe'
                );
            }
        }
        else{
            $this->error = array(
                'nivel' => '1',
                'mensaje'=>'El proyecto indicado no existe'
            );
        }
        $this->mostrar($datos);
    }
    
    
    public function borrar($codigoProyecto,$codigoTarea){
        if($this->_permisosTareas($codigoProyecto, $codigoTarea)){
            $tarea = new Tarea_model;
            
            $archivos = Archivo_model::obtener($codigoTarea,'tarea');
            
            if(!empty($archivos)){
                foreach($archivos as $arch){ 
                    $archivo = new Archivo_model;
                    $archivo->eliminar($arch->Codigo);
                }
            }
                     
            $respuestas = Respuesta_model::obtener($codigoTarea);

            if(!empty($respuestas)){
                foreach($respuestas as $respuesta){ 
                    $archivos = Archivo_model::obtener($respuesta->Codigo,'respuesta');
                    if(!empty($archivos)){
                        foreach($archivos as $arch){
                            $archivo = new Archivo_model;
                            $archivo->eliminar($arch->Codigo);
                        }
                    }
                }
            }

            $tarea->borrar($codigoTarea);
            redirect("admin/proyecto/tareas/$codigoProyecto");
        }
    }
    
    public function borrarRespuesta($codigoProyecto,$codigoTarea, $codigoRespuesta){
        
        if(Respuesta_model::existe($codigoRespuesta)){
            $respuesta = new Respuesta_model;
            if($this->session->userdata('email') == $respuesta->email($codigoRespuesta)){
                $archivos = Archivo_model::obtener($respuesta->Codigo,'respuesta');
                if(!empty($archivos)){
                    foreach($archivos as $arch){
                        $archivo = new Archivo_model;
                        $archivo->eliminar($arch->Codigo);
                    }
                }
                $respuesta->borrar($codigoRespuesta);
            }
        }
        if($this->session->userdata('usuario') == 'admin')
            redirect("admin/proyecto/tarea/$codigoProyecto/$codigoTarea");
        else    
           redirect("empleados/proyecto/tarea/$codigoProyecto/$codigoTarea");
    }
    
    private function _permisosTareas($codigoProyecto, $codigoTarea){
        $aux = FALSE;
        if(Proyecto_model::existe($codigoProyecto)){
            if(Tarea_model::existe($codigoTarea)){             
                if(Tarea_model::existeEmpleado($codigoTarea, $this->session->userdata('email'))){
                    $aux = TRUE;
                }
                else{
                    if($this->session->userdata('usuario') == 'admin')
                        $aux = TRUE;
                }
            }
        }        
        return $aux;
    }
    
}

?>