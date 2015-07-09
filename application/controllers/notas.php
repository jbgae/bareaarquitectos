<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'controllers/proyecto.php');

class Notas extends Proyecto{
    
    public function __construct() {
        parent:: __construct();
        $this->load->model('notas_model', 'Notas');
        
    }
    
    public function registrar($codigo) { 
        $this->pagina = 'notas';
        $this->carpeta = 'empleado';
        $this->titulo = 'Crear nota';
        $this->estilo = array('jquery-te-1.3.3', '../jquery-ui', 'proyectos', $this->pagina);
        $this->javascript = array('editor', 'jquery-te-1.3.3.min', 'notas', 'select','confirmacion');
        $this->menu = 'menu_empleado_proyecto';
        $this->submenu = 'menu_proyecto';        
        
        if($this->uri->segment(1) == 'admin'){
            $this->permisos('admin');            
            $datos['user'] = 'admin';            
        }
        else{
            $this->permisos('empleado');
            $datos['user'] = 'empleados'; 
        }
        
        
        $this->form_validation->set_error_delimiters('<div class="text-error">', '</div>');
        if(Proyecto_model::existe($codigo)){
            $proyecto = new Proyecto_model;
            $proyecto->datos($codigo);
            $datos['estado'] = $proyecto->estado();
            if($proyecto->pertenece($this->session->userdata('email'))){    
                $datos['nombreProyecto'] = $proyecto->nombre($codigo);
                $datos['codigo'] = $codigo;            
                $datos['notas'] = $this->Notas->obtener($codigo);
                

                $empleadosProyectos = $proyecto->empleadosProyecto($codigo);

                $datos['empleadosProyectos'] = array();
                $datos['empleadosNotas'] = array();

                foreach ($empleadosProyectos as $empleado){
                    if($empleado->EmailEmpleado != $this->session->userdata('email'))
                        $datos['empleadosProyectos'][$empleado->EmailEmpleado] = $empleado->Nombre . ' ' . $empleado->ApellidoP . ' '. $empleado->ApellidoM;
                }

                $datos['formulario'] = array(
                    'titulo' => array('class'=>'input-xlarge', 'id'=> 'titulo' ,'name'=>'titulo', 'label' => 'titulo', 'maxlength'=> '150', 'type' => 'text', 'value' => $this->input->post('titulo'), 'autofocus' =>'autofocus'),
                    'contenido' => array('class' => 'editor', 'id' => 'contenido', 'name'=>'contenido', 'label' => 'contenido', 'value' => $this->input->post('contenido')),
                    'publico' => array('class' => 'publico', 'id' => 'publico', 'name'=>'permisos', 'label' => '<span>Público.</span> Todos los empleados registrados en el proyecto podrán acceder a ella.', 'value'=>'publico', 'checked'=>TRUE),
                    'privado' => array('class' => 'privado', 'id' => 'privado', 'name'=>'permisos', 'label' => '<span>Privado.</span> Sólamente tú tendrás acceso a ella.', 'value'=>'privado'),
                    'personalizado' => array('class' => 'personalizado', 'id' => 'personalizado', 'name'=>'permisos', 'label' => '<span>Personalizado.</span> Tú eliges quién tendrá acceso a ella.', 'value'=>'personalizado')
                );

                $datos['boton'] = array( 'class'=> 'btn btn-info', 'name'=>'button', 'id' => 'boton_notas');

                $this->form_validation->set_rules('titulo', 'Titulo', 'trim|required|min_length[3]|xss_clean');
                $this->form_validation->set_rules('contenido', 'Contenido', 'trim|required|min_length[3]');

                $this->form_validation->set_message('required', 'El campo %s no puede estar vacio');
                $this->form_validation->set_message('min_legth', 'El campo %s debe tener mínmo 3 caracteres');
                $this->form_validation->set_message('xss_clean', 'El campo %s no es válido');       

                if($this->form_validation->run() == TRUE){
                    $nota = new Notas_model;       
                    if($nota->inicializar($codigo)){
                       $cod = $nota->codigo();
                       if($this->input->post('permisos') == 'publico'){
                          $datos['formulario']['publico']['checked'] = TRUE;  
                       }
                       elseif($this->input->post('permisos') == 'privado'){
                          $datos['formulario']['privado']['checked'] = TRUE;  
                       }
                       elseif($this->input->post('permisos') == 'personalizado'){
                          $datos['formulario']['personalizado']['checked'] = TRUE;
                          $datos['empleadosProyectos'] = array();
                          $datos['empleadosNotas'] = array();
                          $empl = array();
                          $empleadosNotas = Notas_model::empleados($nota->codigo());
                          foreach ($empleadosNotas as $empleado){
                              if($empleado->EmailEmpleado != $this->session->userdata('email')){
                                $datos['empleadosNotas'][$empleado->EmailEmpleado] = $empleado->Nombre . ' ' . $empleado->ApellidoP . ' '. $empleado->ApellidoM;
                                array_push($empl, $empleado->EmailEmpleado);
                              }
                          }

                          $empleados = Notas_model::empleadosNoNota($empl, $codigo);
                          if(!empty($empleados)){
                              foreach ($empleados as $empleado){
                                  if($empleado->EmailEmpleado != $this->session->userdata('email'))
                                    $datos['empleadosProyectos'][$empleado->EmailEmpleado] = $empleado->Nombre . ' ' . $empleado->ApellidoP . ' '. $empleado->ApellidoM;
                              }
                          } 
                       }
                       
                       if($this->input->post('permisos') != 'privado'){
                           if($this->input->post('permisos') == 'publico'){
                               $emplNota = Proyecto_model::empleadosProyecto($codigo);
                               $permiso = 'publico';
                           }
                           else{
                               $emplNota = Notas_model::empleados($cod);
                               $permiso = 'personalizado';
                           }
                           
                           $pusher = array(
                               'codigo'    => $cod,
                               'empleados' => $emplNota,
                               'permisos'  => $permiso,
                               'proyecto'  => $codigo,
                               'empleado'  => $this->session->userdata('nombre'). ' '. $this->session->userdata('apellidos'),
                               'titulo'    => $this->input->post('titulo'),
                               'contenido' => $this->input->post('contenido'),
                               'fecha'     => date('d M Y',strtotime($nota->fecha($cod))),
                               'hora'      => date('H:i A',strtotime($nota->fecha($cod))),
                            
                           );
                           $this->pusher->trigger('private-notificaciones-empleado', 'crear-nota', $pusher);
                       }
                       
                       $this->exito = 'La nota ha sido registrada satisfactoriamente.';
                       $datos['notas'] = Notas_model::obtener($codigo);                       
                    }            
                    else{
                         $this->error = array(
                            'nivel'=>'2',
                            'mensaje' => 'No se ha podido registrar la nota'
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
                'nivel'=>'1',
                'mensaje' => 'El proyecto indicado no existe'
            );
        }
        $this->mostrar($datos);
    }
    
    public function nota($codigoProyecto, $codigoNota){
        $this->pagina = 'nota';
        $this->carpeta = 'empleado';
        $this->titulo = 'Nota';
        $this->estilo = array('jquery-te-1.3.3', '../jquery-ui', 'proyectos', 'notas');
        $this->javascript = array('editor', 'jquery-te-1.3.3.min', 'notas', 'select', 'tooltip');
        $this->menu = 'menu_empleado_proyecto';
        $this->submenu = 'menu_proyecto'; 
        
        if($this->uri->segment(1) == 'admin'){
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
            $empleadosProyectos = Proyecto_model::empleadosProyecto($codigoProyecto);
            $datos['empleadosProyectos'] = array();
            if($proyecto->pertenece($this->session->userdata('email'))){ 
                if(Notas_model::existe($codigoNota) && $this->_permisosNotas($codigoProyecto, $codigoNota, TRUE) ){
                    $nota = new Notas_model;
                    $datos['nota'] = $nota->datos($codigoNota);                
                    $empleadosNotas = Notas_model::empleados($codigoProyecto, $codigoNota);                
                    $datos['empleadosNotas'] = array();
                    $datos['notas'] = Notas_model::obtener($codigoProyecto);                

                    foreach ($empleadosProyectos as $empleado){
                        $datos['empleadosProyectos'][$empleado->EmailEmpleado] = $empleado->Nombre . ' ' . $empleado->ApellidoP . ' '. $empleado->ApellidoM;
                    }
                    if(!empty($empleadosNotas)){
                        foreach($empleadosNotas as $empleado){
                            $datos['empleadosNotas'][$empleado->EmailEmpleado] = $empleado->Nombre . ' ' . $empleado->ApellidoP . ' '. $empleado->ApellidoM;
                        }
                    }
                }
                else{
                    $this->error = array(
                        'nivel'=>'1',
                        'mensaje' => 'La nota indicada no existe'
                    );
                }
            }
            else{
                $this->error = array(
                        'nivel'=>'1',
                        'mensaje' => 'No puedes acceder al proyecto'
                    );
            }
            
        }
        else{
            $this->error = array(
                'nivel'=>'1',
                'mensaje' => 'El proyecto indicado no existe'
            );
        }
        $this->mostrar($datos);       
    }
    
    
    public function editar($codigoProyecto, $codigoNota){
        if($this->uri->segment(1) == 'admin'){
            $this->permisos('admin');
            $datos['user'] = 'admin';
        }
        else{
            $this->permisos('empleado');
            $datos['user'] = 'empleados'; 
        }
        $this->pagina = 'notas';
        $this->carpeta = 'empleado';
        $this->titulo = 'editar nota';
        $this->estilo = array('jquery-te-1.3.3', 'jquery-ui', 'proyectos', $this->pagina);
        $this->javascript = array('editor', 'jquery-te-1.3.3.min', 'notas', 'select');
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
            
            if($proyecto->pertenece($this->session->userdata('email'))){
                if(Notas_model::existe($codigoNota) && $this->_permisosNotas($codigoProyecto, $codigoNota)){               
                    $nota = new Notas_model;
                    $datos['nota'] = $nota->datos($codigoNota);        
                    $empleadosNotas = Notas_model::empleados($codigoProyecto, $codigoNota);                
                    $datos['empleadosNotas'] = array();
                    $datos['notas'] = Notas_model::obtener($codigoProyecto);                
                    $empl = array();
                    if(!empty($empleadosNotas)){
                        foreach ($empleadosNotas as $empleado){
                            if($empleado->EmailEmpleado != $this->session->userdata('email')){
                                $datos['empleadosNotas'][$empleado->EmailEmpleado] = $empleado->Nombre . ' ' . $empleado->ApellidoP . ' '. $empleado->ApellidoM;
                                array_push($empl, $empleado->EmailEmpleado);
                            }    
                        }
                    }

                    $empleados = Notas_model::empleadosNoNota($empl, $codigoProyecto);

                    if(!empty($empleados)){
                        foreach ($empleados as $empleado){
                            if($empleado->EmailEmpleado != $this->session->userdata('email'))
                                $datos['empleadosProyectos'][$empleado->EmailEmpleado] = $empleado->Nombre . ' ' . $empleado->ApellidoP . ' '. $empleado->ApellidoM;
                        }
                    }   

                    $datos['formulario'] = array(
                        'titulo' => array('class'=>'input-xlarge', 'id'=> 'titulo' ,'name'=>'titulo', 'label' => 'titulo', 'maxlength'=> '150', 'type' => 'text', 'value' => $nota->Titulo, 'autofocus'=>'autofocus'),
                        'contenido' => array('class' => 'editor', 'id' => 'contenido', 'name'=>'contenido', 'label' => 'contenido', 'value' => $nota->Contenido),
                        'publico' => array('class' => 'publico', 'id' => 'publico', 'name'=>'permisos', 'label' => '<span>Público.</span> Todos los empleados registrados en el proyecto podrán acceder a ella.', 'value'=>'publico'),
                        'privado' => array('class' => 'privado', 'id' => 'privado', 'name'=>'permisos', 'label' => '<span>Privado.</span> Sólamente tú y los administradores tendrán acceso a ella.', 'value'=>'privado'),
                        'personalizado' => array('class' => 'personalizado', 'id' => 'personalizado', 'name'=>'permisos', 'label' => '<span>Personalizado.</span> Tú eliges quién tendrá acceso a ella.', 'value'=>'personalizado')
                    );
                    if($nota->permisos() == 'publico'){
                      $datos['formulario']['publico']['checked'] = TRUE;  
                    }
                    elseif($nota->permisos() == 'privado'){
                      $datos['formulario']['privado']['checked'] = TRUE;  
                    }
                    elseif($nota->permisos() == 'personalizado'){
                      $datos['formulario']['personalizado']['checked'] = TRUE;  
                    }


                    $datos['boton'] = array( 'class'=> 'btn btn-info', 'name'=>'button', 'id' => 'boton_notas');

                    $this->form_validation->set_rules('titulo', 'Titulo', 'trim|required|min_length[3]|xss_clean');
                    $this->form_validation->set_rules('contenido', 'Contenido', 'trim|required|min_length[3]');

                    $this->form_validation->set_message('required', 'El campo %s no puede estar vacio');
                    $this->form_validation->set_message('min_legth', 'El campo %s debe tener mínmo 3 caracteres');
                    $this->form_validation->set_message('xss_clean', 'El campo %s no es válido');       

                    if($this->form_validation->run() == TRUE){
                        if($nota->actualizar()){
                           $datos['notas'] = Notas_model::obtener($codigoProyecto);

                           $datos['empleadosProyectos'] = array();
                           $datos['empleadosNotas'] = array();
                           $empleadosNotas = Notas_model::empleados($codigoProyecto, $codigoNota);
                           $empl = array();

                           
                           if(!empty($empleadosNotas)){
                               foreach ($empleadosNotas as $empleadoAux){
                                   if($empleadoAux->EmailEmpleado != $this->session->userdata('email')){
                                       $datos['empleadosNotas'][$empleadoAux->EmailEmpleado] = $empleadoAux->Nombre . ' ' . $empleado->ApellidoP . ' '. $empleado->ApellidoM;
                                       array_push($empl, $empleadoAux->EmailEmpleado);
                                   }
                               }
                           }
                           $empleados = Notas_model::empleadosNoNota($empl, $codigoProyecto);

                           if(!empty($empleados)){
                               foreach ($empleados as $empleado){ 
                                   if($empleado->EmailEmpleado != $this->session->userdata('email'))
                                       $datos['empleadosProyectos'][$empleado->EmailEmpleado] = $empleado->Nombre . ' ' . $empleado->ApellidoP . ' '. $empleado->ApellidoM;
                               }
                           }                   

                           $this->exito = 'La nota ha sido actualizada satisfactoriamente.';
                           $datos['formulario'] = array(
                                'titulo' => array('class'=>'input-xlarge', 'id'=> 'titulo' ,'name'=>'titulo', 'label' => 'titulo', 'maxlength'=> '150', 'type' => 'text', 'value' => $this->input->post('titulo')),
                                'contenido' => array('class' => 'editor', 'id' => 'contenido', 'name'=>'contenido', 'label' => 'contenido', 'value' => $this->input->post('contenido')),
                                'publico' => array('class' => 'publico', 'id' => 'publico', 'name'=>'permisos', 'label' => '<span>Público.</span> Todos los empleados registrados en el proyecto podrán acceder a ella.', 'checked'=>'TRUE', 'value'=>'publico'),
                                'privado' => array('class' => 'privado', 'id' => 'privado', 'name'=>'permisos', 'label' => '<span>Privado.</span> Sólamente tú y los administradores tendrán acceso a ella.', 'value'=>'privado'),
                                'personalizado' => array('class' => 'personalizado', 'id' => 'personalizado', 'name'=>'permisos', 'label' => '<span>Personalizado.</span> Tú eliges quién tendrá acceso a ella.', 'value'=>'personalizado')
                           );
                           if($this->input->post('permisos') == 'publico'){
                              $datos['formulario']['publico']['checked'] = TRUE;  
                           }
                           elseif($this->input->post('permisos') == 'privado'){
                              $datos['formulario']['privado']['checked'] = TRUE;  
                           }
                           elseif($this->input->post('permisos') == 'personalizado'){
                              $datos['formulario']['personalizado']['checked'] = TRUE;  
                           }                       
                        }            
                        else{
                            $this->error = array(
                                'nivel'=>'2',
                                'mensaje'=>'No se ha podido actualizar la nota, por favor inténtelo de nuevo más tarde'
                             );
                        }                
                    }
                }
                else{
                    $this->error = array(
                        'nivel'=>'1',
                        'mensaje'=>'La nota indicada no existe'
                     );
                } 
            }
            else{
                $this->error = array(
                        'nivel'=>'1',
                        'mensaje' => 'No puedes acceder al proyecto'
                    );
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
    
    
    public function borrar($codigoProyecto,$codigoNota){
        if($this->_permisosNotas($codigoProyecto, $codigoNota)){
            $nota = new Notas_model;
            $nota->borrar($codigoNota);
            if($this->session->userdata('usuario') == 'admin')
                redirect("admin/proyecto/notas/$codigoProyecto");
            else    
                redirect("empleados/proyecto/notas/$codigoProyecto");
        }
    }
    
    
    private function _permisosNotas($codigoProyecto, $codigoNota, $mostrar = FALSE){
        $aux = FALSE;
        if(Proyecto_model::existe($codigoProyecto)){
            if(Notas_model::existe($codigoNota)){             
                $nota = new Notas_model;
                $nota->datos($codigoNota);
                if($mostrar){
                    if($nota->permisos() == 'privado'){ 
                        if($nota->email() == $this->session->userdata('email')){
                            $aux = TRUE;
                        }
                    }
                    if($nota->permisos() == 'publico'){
                        $aux = TRUE;
                    }
                    elseif($nota->permisos() == 'personalizado'){
                       if($nota->email() == $this->session->userdata('email')){
                           $aux = TRUE;
                       }
                       else{
                           $aux = Notas_model::comprobar($codigoNota, $this->session->userdata('email'));
                       }    
                    }
                }
                else{
                    if($nota->email() == $this->session->userdata('email')){
                        $aux = TRUE;
                    }
                }
            }
        }        
        return $aux;
    }
    
}
    
?>