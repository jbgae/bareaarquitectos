<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'controllers/usuario.php');

class Empleado extends Usuario{
    
    public function __construct() {        
        parent:: __construct();

        $this->load->library('pagination');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('empleado_model');
        $this->load->model('chat_model');
        $this->load->model('evento_model');
        $this->load->model('archivo_model');
        $this->load->model('proyecto_model');
        $this->load->model('tarea_model');
        $this->load->model('respuesta_model');
        $this->load->model('notas_model');
        $this->load->model('provincia_model', 'Provincia');
        $this->load->model('ciudad_model', 'Ciudad');
        
        $this->form_validation->set_error_delimiters('<div class="alert alert-error"> <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <h4>Error</h4>', '</div>');
    }
    
    
    private function _validar(){
        $this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('primerApellido', 'Primer Apellido', 'trim|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('segundoApellido', 'Segundo Apellido', 'trim|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('fNacimiento', 'Fecha Nacimiento', 'callback_fecha_check');
        $this->form_validation->set_rules('direccion', 'Dirección', 'trim|min_length[3]|xss_clean');
        $this->form_validation->set_rules('provincia', 'Provincia', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('ciudad', 'Ciudad', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('telefono', 'Teléfono', 'trim|exact_length[9]|numeric|xss_clean');
        if($this->uri->segment('2') != 'datos' && $this->uri->segment('3') != 'editar' ){
            $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[3]|valid_email|is_unique[Usuario.Email]|xss_clean');
            $this->form_validation->set_rules('pass', 'Contraseña', 'trim|required|min_length[6]|xss_clean');
            $this->form_validation->set_rules('passconf', 'Contraseña', 'trim|required|min_length[6]|xss_clean|matches[pass]');
        }
        else{
            $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[3]|valid_email|xss_clean');
            $this->form_validation->set_rules('pass', 'Contraseña', 'trim|min_length[6]|xss_clean');
            $this->form_validation->set_rules('passconf', 'Contraseña', 'trim|min_length[6]|xss_clean|matches[pass]');            
        }
        $this->form_validation->set_rules('cargo', 'Cargo', 'trim|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('salario', 'salario', 'trim|xss_clean|numeric');
        $this->form_validation->set_rules('fAlta', 'Fecha contratación', 'callback_fecha_check');
        $this->form_validation->set_rules('fBaja', 'Fecha baja', 'callback_fecha_check');
        
        $this->form_validation->set_message('required', '%s no puede estar vacio');
        $this->form_validation->set_message('is_unique', '%s ya existe');
        $this->form_validation->set_message('min_length', '%s debe tener mínimo %s caracteres');
        $this->form_validation->set_message('valid_email', '%s no es válido');
        $this->form_validation->set_message('xss_clean', ' %s no es válido');
        $this->form_validation->set_message('exact_legth', '%s debe tener %s caracteres');
        $this->form_validation->set_message('numeric', '%s debe contener dígitos');
        $this->form_validation->set_message('matches', 'Las contraseñas no coinciden');
        
        return $this->form_validation->run();
    }
    
    public function novedades(){ 
        $this->permisos('empleado');
        $datos['user'] = 'empleados';
        
        $this->pagina = 'novedades';
        $this->carpeta = 'empleado';        
        $this->titulo = 'Novedades';
        $this->estilo = 'novedades';
        $this->javascript = 'confirmacion';
        $this->menu = 'menu_empleado_general';
        
        
        $datos['citas'] = Evento_model::eventosFuturos($this->session->userdata('email'));
        //$datos['novedades']['proyectos'] = Proyecto_model::numProyectosEmpleado($this->session->userdata('email'));
        $datos['novedades']['tareas'] = Tarea_model::numeroTareasNuevas($this->session->userdata('email'),$this->session->userdata('ultimoAcceso'));
        $datos['novedades']['respuestas'] = Respuesta_model::numeroRespuestasNuevas($this->session->userdata('email'),$this->session->userdata('ultimoAcceso'));
        if($datos['novedades']['tareas'] != 0)
            $datos['tareas'] = Tarea_model::tareasNuevas($this->session->userdata('email'), $this->session->userdata('ultimoAcceso'));
         if($datos['novedades']['respuestas'] != 0)
            $datos['respuestas'] = Respuesta_model::respuestasNuevas($this->session->userdata('email'),$this->session->userdata('ultimoAcceso'));
         if(!empty($datos['respuestas'])){
            foreach($datos['respuestas'] as $resp){
                $tarea = new Tarea_model;
                $resp->CodigoProyecto = $tarea->codigoProyecto($resp->CodigoTarea);
            }
         }
        
        $datos['novedades']['notas'] = Notas_model::numeroNotasNuevas($this->session->userdata('email'), $this->session->userdata('ultimoAcceso'));
        if($datos['novedades']['notas'] != 0)
            $datos['notas'] = Notas_model::notasNuevas($this->session->userdata('email'),$this->session->userdata('ultimoAcceso'));
        
        $datos['novedades']['archivos'] = Archivo_model::numeroArchivosNuevos($this->session->userdata('email'),$this->session->userdata('ultimoAcceso'));
        if($datos['novedades']['archivos'] != 0)
            $datos['archivos'] = Archivo_model::archivosNuevos($this->session->userdata('email'),$this->session->userdata('ultimoAcceso'));
        
        $datos['novedades']['proyectos'] = $datos['novedades']['tareas'] +$datos['novedades']['respuestas'] + $datos['novedades']['notas'] + $datos['novedades']['archivos'];
        
        $this->mostrar($datos);
    }
    
    
    public function registrar(){
        $this->permisos('admin');        
        
        $this->pagina = 'crear empleado';
        $this->carpeta = 'administrador';
        $this->titulo = 'registrar empleado';
        $this->estilo = array('jquery-te-1.3.3', 'registrar', 'jquery-ui');
        $this->javascript = array('jquery-ui', 'fecha', 'ciudades', 'jquery.validate.min', 'validarEmpleado');
        $this->menu = 'menu_admin_empleados';
        
        $datos['imagen'] = '';
        $datos['provincias'] = $this->Provincia->obtener();
        $datos['ciudades'] = $this->Ciudad->obtener();
        
        $formulario = $this->formulario_registro();
        $formulario['cargo'] = array(
                'label'=>array('accesskey'=>'', 'name'=>'Cargo'),
                'input'=>array('class'=>'cargo', 'name'=>'cargo', 'id'=>'cargo', 'maxlength'=>'60', 'size'=>'15'),
                'requerido'=>TRUE
        );
        $formulario['salario'] = array(
                'label'=>array('accesskey'=>'', 'name'=>'Salario'),
                'input'=>array('class'=>'salario','id'=>'salario', 'name'=>'salario', 'maxlength'=>'60', 'size'=>'15'),
                'requerido'=>FALSE
        );
        $formulario['fechaContratacion'] = array(
            'label'=>array('accesskey'=>'', 'name'=>'Fecha contratación'),
            'input'=>array('class'=>'fechaContratacion','id'=>'fAlta', 'name'=>'fAlta', 'maxlength'=>'60', 'size'=>'15'),
            'requerido'=>FALSE
        );
        $formulario['fechaFinContrato']= array(
            'label'=>array('accesskey'=>'', 'name'=>'Fin de contrato'),
            'input'=>array('class'=>'fechaFinContrato','id'=>'fBaja', 'name'=>'fBaja', 'maxlength'=>'60', 'size'=>'15'),
            'requerido'=>FALSE
        );
        
        $datos['formulario'] = $formulario;
        
        $datos['boton'] = array( 'class'=> 'btn btn-info', 'name'=>'button', 'id' => 'boton_empleado');
        
        if(Empleado_model::numero() == 0){
            $this->error = array(
                    'nivel' => '2',
                    'mensaje'=> "Actualmente no existe ningún empleado. Si lo desea puede empezar a registrar empleados."
            );
        }      
        
        if($this->_validar()){          
            $empleado = new Empleado_model;
            if($empleado->inicializar()){
                $archivo = new Archivo_model;
                $archivo->inicializar('foto');
                $this->exito = 'El empleado ha sido registrado satisfactoriamente';
            }
            else{
                $datos['formulario']['nombre']['input']['value'] = $this->input->post('nombre');
                $datos['formulario']['apellidoPaterno']['input']['value'] = $this->input->post('primerApellido');
                $datos['formulario']['apellidoMaterno']['input']['value'] = $this->input->post('segundoApellido');
                $datos['formulario']['fechaNacimiento']['input']['value'] = $this->input->post('fNacimiento');
                $datos['formulario']['direccion']['input']['value'] = $this->input->post('direccion');
                $datos['formulario']['ciudad']['input']['value'] = $this->input->post('ciudad');
                $datos['formulario']['provincia']['input']['value'] = $this->input->post('provincia');
                $datos['formulario']['telefono']['input']['value'] = $this->input->post('telefono');
                $datos['formulario']['email']['input']['value'] = $this->input->post('email');
                $datos['formulario']['cargo']['input']['value'] =$this->input->post('cargo');
                $datos['formulario']['salario']['input']['value'] = $this->input->post('salario');
                $datos['formulario']['fechaContratacion']['input']['value'] = $this->input->post('fAlta');
                $datos['formulario']['fechaFinContrato']['input']['value']= $this->input->post('fBaja');
                
                $this->error = array(
                    'nivel'=>'2',
                    'mensaje'=>'No se ha podido completar el registro por favor inténtelo de nuevo más tarde'
                );
            }           
        }
        
        $this->mostrar($datos);
    }   

    
    public function modificar($email=''){ 
        $empleado = new Empleado_model;
        $this->titulo = 'modificar empleado';
        $this->estilo = array('jquery-te-1.3.3', 'registrar', 'jquery-ui');
        $this->javascript = array('jquery-ui', 'fecha', 'ciudades', 'jquery.validate.min', 'validarEmpleadoAct');
        
        if($this->uri->segment(1) == 'empleado'){ 
            $this->permisos('empleado');
            $this->pagina = 'crear empleado';
            $this->carpeta = 'administrador';
            $datos['user'] = 'empleado';
            $datos['datos'] = TRUE;

            $empleado->datos($this->session->userdata('email'));
            $email = $empleado->email();
            $datos['email'] = $email;
        }
        elseif($this->session->userdata('usuario') == 'admin' && $this->uri->segment(2) == 'datos'){ 
            $this->permisos('admin');
            $this->pagina = 'crear empleado';
            $this->carpeta = 'administrador';
            $datos['user'] = 'admin';
            $datos['datos'] = TRUE;

            $empleado->datos($this->session->userdata('email'));
            $email = $empleado->email();
            $datos['email'] = $email;
        }
        else{
            $this->permisos('admin');
            $this->form_validation->set_error_delimiters('<span class="text-error">', '</span>');

            $email = urldecode($email);
            $this->pagina = 'crear empleado';
            $this->carpeta = 'administrador';
            
            $this->menu = 'menu_admin_empleados';
            $datos['email'] = $email;
            $datos['actualizar'] = TRUE;
            $empleado->datos($email);
        }
        if(Empleado_model::existe($empleado->email())){
            $datos['provincias'] = $this->Provincia->obtener();
            $datos['ciudades'] = $this->Ciudad->ciudades($empleado->provincia($email, TRUE));
            $datos['imagen'] = $empleado->foto($email);
            $formulario = $this->formulario_registro();
            $formulario['nombre']['input']['value'] = $empleado->nombre();
            $formulario['apellidoPaterno']['input']['value'] = $empleado->primerApellido();
            $formulario['apellidoMaterno']['input']['value'] = $empleado->segundoApellido();
            $formulario['fechaNacimiento']['input']['value'] =  $empleado->fechaNacimiento();
            $formulario['direccion']['input']['value'] = $empleado->direccion();
            $formulario['provincia']['input']['value'] = $empleado->provincia($email, TRUE);
            $formulario['ciudad']['input']['value'] = $empleado->ciudad($email, TRUE);
            $formulario['telefono']['input']['value'] = $empleado->telefono();
            $formulario['email']['input']['value'] = $empleado->email();
            $formulario['cargo'] = array(
                    'label'=>array('accesskey'=>'', 'name'=>'Cargo'), 
                    'input'=>array('class'=>'cargo','id'=>'cargo', 'name'=>'cargo', 'maxlength'=>'60', 'size'=>'15', 'value'=>$empleado->cargo()),
                    'requerido'=>TRUE
            );
            
            $formulario['password']['requerido'] = FALSE;
            $formulario['passconf']['requerido'] = FALSE;

            $formulario['salario'] = array(
                'label'=>array('accesskey'=>'', 'name'=>'Salario'),
                'input'=>array('class'=>'salario','id'=>'salario', 'name'=>'salario', 'maxlength'=>'60', 'size'=>'15', 'value'=>$empleado->salario()),
                'requerido'=>FALSE
            );

            $formulario['fechaContratacion'] = array(
                'label'=>array('accesskey'=>'', 'name'=>'Fecha de contratación'),
                'input'=>array('class'=>'fechaContratacion','id'=>'fAlta', 'name'=>'fAlta', 'maxlength'=>'60', 'size'=>'15', 'value'=>$empleado->fechaContratacion()),
                'requerido'=>FALSE
            );

            $formulario['fechaFinContrato']= array(
                'label'=>array('accesskey'=>'', 'name'=>'Fecha fin de contrato'),
                'input'=>array('class'=>'fechaFinContrato','id'=>'fBaja', 'name'=>'fBaja', 'maxlength'=>'60', 'size'=>'15', 'value'=>$empleado->fechaFinContrato()),
                'requerido'=>FALSE
            );
            
            $datos['formulario'] = $formulario;

            foreach($datos['formulario'] as &$input){ 
                if($input['input']['class'] != 'password' && $input['input']['class'] != 'passconf')
                    if($input['input']['value'] == 'Desconocido' || $input['input']['value'] == 'Desconocida' ){                
                        $input['input']['value'] = '';
                    }
                    if($input['input']['class'] == 'salario' && $input['input']['value'] == 0){
                        $input['input']['value'] = '';
                    }
            }
            
            $datos['boton'] = array( 'class'=> 'btn btn-info', 'name'=>'button', 'id' => 'boton_empleado');
            $email = $empleado->email();
            
            if($this->_validar()){ 
                $codigo = '';
                $archivo = new Archivo_model;

                if($empleado->foto($email, TRUE) != 0){ 
                    $archivo->datos($empleado->foto($email, TRUE));
                    $archivo->actualizar($empleado->foto($email, TRUE), 'foto');
                }
                else{
                    if($_FILES['archivo']['name'] != ''){
                        if($archivo->inicializar('foto')){            
                            $codigo = $archivo->codigo();
                        }
                    }
                }
                if($empleado->actualizar($email, $codigo)){
                   $empleado->datos($email);
                   $datos['provincias'] = $this->Provincia->obtener();
                   $datos['ciudades'] = $this->Ciudad->ciudades($empleado->provincia($email, TRUE));
                   $datos['imagen'] = $empleado->foto($email);
                   $formulario = $this->formulario_registro();
                   $formulario['nombre']['input']['value'] = $empleado->nombre();
                   $formulario['apellidoPaterno']['input']['value'] = $empleado->primerApellido();
                   $formulario['apellidoMaterno']['input']['value'] = $empleado->segundoApellido();
                   $formulario['fechaNacimiento']['input']['value'] =  $empleado->fechaNacimiento();
                   $formulario['direccion']['input']['value'] = $empleado->direccion();
                   $formulario['provincia']['input']['value'] = $empleado->provincia($email, TRUE);
                   $formulario['ciudad']['input']['value'] = $empleado->ciudad($email, TRUE);
                   $formulario['telefono']['input']['value'] = $empleado->telefono();
                   $formulario['email']['input']['value'] = $empleado->email();
                   
                   $formulario['cargo'] = array(
                           'label'=>array('accesskey'=>'', 'name'=>'Cargo'),  
                           'input'=>array('class'=>'cargo','label'=>'Cargo', 'name'=>'cargo', 'maxlength'=>'60', 'size'=>'15', 'value'=>$empleado->cargo()),
                           'requerido'=>TRUE
                   );
                   
                   $formulario['salario'] = array(
                       'label'=>array('accesskey'=>'', 'name'=>'Salario'),
                       'input'=>array('class'=>'salario','label'=>'Salario', 'name'=>'salario', 'maxlength'=>'60', 'size'=>'15', 'value'=>$empleado->salario()),
                       'requerido'=>FALSE
                   );
                   
                   $formulario['fechaContratacion'] = array(
                       'label'=>array('accesskey'=>'', 'name'=>'Fecha de contratación'),
                       'input'=>array('class'=>'fechaContratacion','label'=>'Fecha contratación', 'name'=>'fAlta', 'maxlength'=>'60', 'size'=>'15', 'value'=>$empleado->fechaContratacion()),
                       'requerido'=>FALSE
                   );
                   
                   $formulario['fechaFinContrato']= array(
                       'label'=>array('accesskey'=>'', 'name'=>'Fecha fin de contrato'),
                       'input'=>array('class'=>'fechaFinContrato','label'=>'Fin de contrato', 'name'=>'fBaja', 'maxlength'=>'60', 'size'=>'15', 'value'=>$empleado->fechaFinContrato()),
                       'requerido'=>FALSE
                   );
                   
                   $datos['formulario'] = $formulario;
                    foreach($datos['formulario'] as &$input){
                       if($input['input']['class'] != 'password' && $input['input']['class'] != 'passconf')
                           if($input['input']['value'] == 'Desconocido' || $input['input']['value'] == 'Desconocida'){                
                               $input['input']['value'] = '';
                           }
                   }
                    
                   $this->exito = 'El empleado ha sido actualizado satisfactoriamente';  
                }   
                else{
                    $this->error = array(
                        'nivel' => '2',
                        'mensaje'=>'No se ha podido completar la actualización por favor inténtelo de nuevo más tarde'
                        );
                }    
            }
           
        }
        else{
            $this->error = array(
                        'nivel' => '1',
                        'mensaje'=>'No existe el empleado indicado.'
                        );
        }
        $this->mostrar($datos);
    } 

    
    public function listar($campo = 'Nombre', $orden = 'asc', $limit='5', $offset = 0){
        $this->permisos('admin');
                
        $this->pagina = 'empleados';
        $this->carpeta = 'administrador';
       
        $this->menu = 'menu_admin_empleados';
        $this->titulo = $this->pagina;
        $this->estilo = 'tablas';
        $this->javascript=array('marcar_checkbox');
        $datos['buscador'] = array('class' => 'search-query input-medium', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar','autofocus'=>'autofocus');
        $datos['boton'] = array('class'=>'btn', 'id'=>'buscador', 'name'=>'button', 'value'=>'Buscar');
        $datos['borrar']= array('class'=>'btn btn-danger confirm-toggle','id'=>'borrar', 'value'=>'Borrar selección','data-confirm'=>"¿Estás seguro?");
        
        
        if(Empleado_model::numero() == 0){
           $this->registrar();
        }
        else{
            $num = Empleado_model::numero();
            $datos['numero'] = $num; 
            $opciones = $this->seleccion($num);
            
            $datos['opciones'] = $opciones;

            if($this->input->post('cantidad') != ''){
                if($opciones[$this->input->post('cantidad')] == 'Todo'){
                    $limit = $num;
                }    
                else{
                    $limit = $opciones[$this->input->post('cantidad')];      
                }
            }    
            
            $datos['elementos'] = $limit;
            
            if($this->input->post('cantidad') != ''){
                $datos['limit']= $this->input->post('cantidad');
            }
            else{
                $aux = 0;
                if($limit % 5 != 0)
                    $aux = 1;
                $datos['limit'] = floor($limit / 5) - 1 + $aux;
            }
            
            $datos['fields'] = array(
                    'Nombre' => 'Nombre',
                    'Cargo' => 'Cargo',
                    'Salario' => 'Salario',
                    'FechaContratacion' => 'Alta',
                    'FechaFinContrato' => 'Baja',
                    'FechaUltimoAcceso' => 'Último acceso',
                    'Email' => 'Email'
            );
            
            
            $datos['empleados'] = Empleado_model::obtener($campo, $orden, $offset, $limit);
     
            $config = array();
            $config['base_url'] = base_url(). "admin/empleados/".$campo."/".$orden."/".$limit."/";
            $config['total_rows'] =$num;
            $config['per_page'] = $limit;
            $config['uri_segment'] = 6;
            $config['prev_link'] = 'anterior';
            $config['next_link'] = 'siguiente';
            $config['first_link'] = '<<';
            $config['last_link'] = '>>'; 
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="disabled"><a href="#">';
            $config['cur_tag_close'] = '</a></li>';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';
            $this->pagination->initialize($config);
            $datos['links'] = $this->pagination->create_links();

            $datos['campo'] = $campo;
            $datos['orden'] = $orden;

            $this->mostrar($datos);
        }            
    }
    
    
    public function buscar($campo = 'Nombre', $orden = 'asc', $limit='5', $busqueda = '', $offset = 0){
        $this->permisos('admin');
                
        $this->pagina = 'empleados';
        $this->carpeta = 'administrador';
        $this->titulo = 'buscar empleado';
        $this->menu = 'menu_admin_empleados';
        $this->estilo = 'tablas';
        $this->javascript=array('marcar_checkbox', 'redireccion');
        
        $datos['busqueda'] = TRUE;
        
        if ($busqueda != '')
            $datos['buscador'] = array('class' => 'search-query input-medium', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'value'=>urldecode($busqueda),'autofocus'=>'autofocus');
        else
            $datos['buscador'] = array('class' => 'search-query input-medium', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'value'=>$this->input->post('buscador'),'autofocus'=>'autofocus');
        $datos['boton'] = array('class'=>'btn', 'id'=>'buscador', 'name'=>'button', 'value'=>'Buscar');
        $datos['borrar']= array('class'=>'btn btn-danger','id'=>'borrar', 'value'=>'Borrar selección','data-confirm'=>"¿Estás seguro?");
        $datos['fields'] = array(
                    'Nombre' => 'Nombre',
                    'Cargo' => 'Cargo',
                    'Salario' => 'Salario',
                    'FechaContratacion' => 'Alta',
                    'FechaFinContrato' => 'Baja',
                    'FechaUltimoAcceso' => 'Último acceso',
                    'Email' => 'Email'
            );
        
        $this->form_validation->set_rules('buscador', 'Buscador', 'trim|required|xss_clean');
        $this->form_validation->set_message('required', '%s no puede estar vacio');
        $this->form_validation->set_message('xss_clean', ' %s no es una búsqueda válida');        
        
        if($this->form_validation->run() == FALSE){
            if($busqueda != ''){ 
                $busqueda = urldecode($busqueda);
                $busq_cant = Empleado_model::busqueda_cantidad($busqueda);
                $opciones = $this->seleccion($busq_cant);
                $datos['opciones'] = $opciones;
                if($this->input->post('cantidad') != ''){
                    if($opciones[$this->input->post('cantidad')] == 'Todo'){
                        $limit = $busq_cant;
                    }    
                    else{
                        $limit = $opciones[$this->input->post('cantidad')];      
                    }
                }   

                $datos['elementos'] = $limit;

                if($this->input->post('cantidad') != ''){
                    $datos['limit']= $this->input->post('cantidad');
                }
                else{
                    $aux = 0;
                    if($limit % 5 != 0)
                        $aux = 1;
                    $datos['limit'] = floor($limit / 5) - 1 + $aux;
                }
                $datos['busq'] = $busqueda;
                $datos['empleados'] = Empleado_model::buscar($busqueda, $campo, $orden, $offset, $limit);
                $datos['numero'] = $busq_cant;
                
                $config = array();
                $config['base_url'] = base_url(). "admin/empleados/buscar/".$campo."/".$orden."/".$limit."/".$busqueda."/";
                $config['total_rows'] = $busq_cant;
                $config['per_page'] = $limit;
                $config['uri_segment'] = 8;
                $config['prev_link'] = 'anterior';
                $config['next_link'] = 'siguiente';
                $config['first_link'] = '<<';
                $config['last_link'] = '>>'; 
                $config['num_tag_open'] = '<li>';
                $config['num_tag_close'] = '</li>';
                $config['cur_tag_open'] = '<li class="disabled"><a href="#">';
                $config['cur_tag_close'] = '</a></li>';
                $config['prev_tag_open'] = '<li>';
                $config['prev_tag_close'] = '</li>';
                $config['next_tag_open'] = '<li>';
                $config['next_tag_close'] = '</li>';
                $config['first_tag_open'] = '<li>';
                $config['first_tag_close'] = '</li>';
                $config['last_tag_open'] = '<li>';
                $config['last_tag_close'] = '</li>';
                $this->pagination->initialize($config);
                $datos['links'] = $this->pagination->create_links();

                $datos['campo'] = $campo;
                $datos['orden'] = $orden;
            }
            else{
                $datos['numero'] = 0;
                $datos['opciones'] = array(0);
                $datos['campo'] = $campo;
                $datos['orden'] = $orden;
                $datos['buscar'] = '';
                $datos['limit'] = 0;
                $datos['elementos'] = 0;
                $datos['busq']= '';
                $datos['vacio'] = TRUE;
            }
            $this->mostrar($datos);
        }
        
        else{
            $busqueda = $this->input->post('buscador');
            $busq_cant = Empleado_model::busqueda_cantidad($busqueda);
            $opciones = $this->seleccion($busq_cant);
            
            $datos['opciones'] = $opciones;

            if($this->input->post('cantidad') != ''){
                if($opciones[$this->input->post('cantidad')] == 'Todo'){
                    $limit = Empleado_model::numero();
                }    
                else{
                    $limit = $opciones[$this->input->post('cantidad')];      
                }
            }    
            
            $datos['elementos'] = $limit;
            
            if($this->input->post('cantidad') != ''){
                $datos['limit']= $this->input->post('cantidad');
            }
            else{
                $aux = 0;
                if($limit % 5 != 0)
                    $aux = 1;
                $datos['limit'] = floor($limit / 5) - 1 + $aux;
            }
            
            
            $datos['busq'] = $busqueda;
            $datos['empleados'] = Empleado_model::buscar($busqueda, $campo, $orden, $offset, $limit);
            $datos['numero'] = $busq_cant;
            
            $config = array();
            $config['base_url'] = base_url(). "admin/empleados/buscar/".$campo."/".$orden."/".$limit."/".$busqueda."/";
            $config['total_rows'] = $busq_cant;
            $config['per_page'] = $limit;
            $config['uri_segment'] = 7;
            $config['prev_link'] = 'anterior';
            $config['next_link'] = 'siguiente';
            $config['first_link'] = '<<';
            $config['last_link'] = '>>'; 
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="disabled"><a href="#">';
            $config['cur_tag_close'] = '</a></li>';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';
            $this->pagination->initialize($config);
            $datos['links'] = $this->pagination->create_links();

            $datos['campo'] = $campo;
            $datos['orden'] = $orden;

            $this->mostrar($datos);
        }       

    }
    
   
    public function cerrar(){
        $this->cerrarSesion();
    }
    
    public function email($email){
        $this->pagina = 'email empleado';
        $this->carpeta = 'empleado';
        $this->titulo = 'Enviar email';
        $this->estilo = array('contacto', 'jquery-te-1.3.3');
        $this->javascript = array('editor', 'jquery-te-1.3.3.min');
        
        if($this->uri->segment(1) == 'admin'){
            $this->permisos('admin');
            $datos['user'] = 'admin';
            if($this->uri->segment(2) == 'empleados'){
                $this->menu = 'menu_admin_'.$this->uri->segment(2); 
            }
            else{
                $this->menu = 'menu_empleado_'.$this->uri->segment(2);
            }
        }
        else{
            $this->permisos('empleado');
            $datos['user'] = 'empleados';
            $this->menu = 'menu_empleado_'.$this->uri->segment(2);
        }
        $this->form_validation->set_error_delimiters('<div class="alert alert-error"> <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <h4>Error</h4>', '</div>');
        
        
        $email = urldecode($email);
        
        $datos['dir'] = 'empleados';        
        $datos['email'] = $email;
       
        
        $datos['formulario'] = array(
            'asunto' => array('class'=>'input-xlarge', 'id'=> 'asunto' ,'name'=>'asunto', 'label' => 'Asunto', 'maxlength'=> '150', 'type' => 'text', 'value' => $this->input->post('asunto')),
            'contenido' => array('class' => 'editor', 'id' => 'contenido', 'name'=>'contenido', 'label' => 'contenido', 'value' => $this->input->post('contenido'))
        );
        
        $datos['boton'] = array( 'class'=> 'btn btn-info', 'name'=>'button', 'id' => 'boton_noticia');
        
        $this->form_validation->set_rules('asunto', 'Asunto', 'trim|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('contenido', 'Contenido', 'trim|required|min_length[3]');
        
        $this->form_validation->set_message('required', 'El campo %s no puede estar vacio');
        $this->form_validation->set_message('min_legth', 'El campo %s debe tener mínmo 3 caracteres');
        $this->form_validation->set_message('xss_clean', 'El campo %s no es válido');
        
        if($this->form_validation->run() == TRUE){
            $datosEmail = array(
                        'direccion' => 'barea@arquitectosdecadiz.com ',
                        'nombre'    => 'Barea Arquitectos',
                        'asunto'    => $this->input->post('asunto'),
                        'texto'     => $this->input->post('contenido'),
                        'destino' => $email
            );  
                
            $this->my_phpmailer->Enviar($datosEmail);
            $this->exito = 'El email se ha enviado satisfactoriamente';          
              
        }
        $this->mostrar($datos);
    }
    
    public function borrar($email = ''){
        
        if($email != ''){ 
            $email  = urldecode($email); 
            if(Empleado_model::existe($email)){
                $empleado = new Empleado_model;
                $empleado->datos($email);
                
                $codigo = $empleado->foto($email, TRUE);
                if(Archivo_model::existe($codigo)){
                    $archivo = new Archivo_model;
                    $archivo->datos($codigo);
                    $nombreArchivo = array_pop(explode("/",$archivo->ruta()));
                    
                    $borrado = unlink(realpath(__DIR__ . '/../../images/fotos/thumb/'.$nombreArchivo));
                    if($borrado){
                        $archivo->eliminar();
                    }
                }
                $empleado->eliminar();
            }
        }
        else{
            if($this->input->post('checkbox') != ''){            
                $emails = $this->input->post('checkbox');
                foreach($emails as $email){
                    if(Empleado_model::existe(urldecode($email))){
                        $empleado = new Empleado_model;
                        $empleado->datos($email);
                        $codigo = $empleado->foto($email, TRUE);
                        if(Archivo_model::existe($codigo)){
                            $archivo = new Archivo_model;
                            $archivo->datos($codigo);
                            $nombreArchivo = array_pop(explode("/",$archivo->ruta()));
                            $borrado = unlink(realpath(__DIR__ . '/../../images/fotos/thumb/'.$nombreArchivo));
                            if($borrado){
                                $archivo->eliminar();
                            }
                        }
                        $empleado->eliminar();
                    }
                }
            }
        }
        
        if($this->uri->segment(2) == 'clientes'){
            redirect('admin/clientes');
        }
        elseif($this->uri->segment(2) == 'empleados'){
            redirect('admin/empleados');
        }     
    }
    
    public function autorizacion(){
        if ($this->session->userdata('usuario') == 'empleado' || $this->session->userdata('usuario') == 'admin' ){
          echo $this->pusher->socket_auth($_POST['channel_name'], $_POST['socket_id']);
        }
        else{
          header('', true, 403);
          echo "Acceso prohibido";
        } 

    }
    
    public function notificaciones(){
        if(!$this->input->is_ajax_request()){
            redirect('404');
        }
        else{
            $empleado = new Empleado_model;
            $empleado->datos($this->session->userdata('email'));
            
            $notificaciones = array();

            $notificaciones['eventos'] = Evento_model::eventos(date('Y-m-d'), $this->session->userdata('email')); 
            $notificaciones['chat'] = Chat_model::numero_mensajes_nuevos($this->session->userdata('ultimoAcceso'), $this->session->userdata('email')); 
            $notificaciones['proyectos'] = Proyecto_model::numProyectosEmpleado($this->session->userdata('email'));
            $notificaciones['tareas'] = Tarea_model::numeroTareasNuevas($this->session->userdata('email'), $this->session->userdata('ultimoAcceso'));
            $notificaciones['respuestas'] = Respuesta_model::numeroRespuestasNuevas($this->session->userdata('email'),$this->session->userdata('ultimoAcceso'));
            $notificaciones['notas'] = Notas_model::numeroNotasNuevas($this->session->userdata('email'), $this->session->userdata('ultimoAcceso'));
            $notificaciones['archivos'] = Archivo_model::numeroArchivosNuevos($this->session->userdata('email'),$this->session->userdata('ultimoAcceso'));
            $notificaciones['novedades'] = $notificaciones['tareas'] + $notificaciones['respuestas'] + $notificaciones['notas'] +$notificaciones['archivos'];

            echo json_encode($notificaciones);
        }
    }

}    
?>
