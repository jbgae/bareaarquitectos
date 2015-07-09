<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proyecto extends MY_Controller{
    
    public function __construct() {
        parent:: __construct();
        
        $this->load->helper('form');
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->library('Proyectopdf');
        $this->load->model('proyecto_model');
        $this->load->model('presupuesto_model');
        $this->load->model('usuario_model');
        $this->load->model('cliente_model');
        $this->load->model('empleado_model');
        $this->load->model('empresa_model');
        $this->load->model('constructora_model');
        $this->load->model('proveedor_model');
        $this->load->model('archivo_model');
        $this->load->model('tarea_model');
        $this->load->model('material_model');
        
        $this->form_validation->set_error_delimiters('<span class="text-error">', '</span>');
                        
//        $this->output->enable_profiler('TRUE');        
    }    

    
    public function registrar($codigo){
        $this->pagina = 'crear proyecto';
        $this->carpeta = 'administrador'; 
        $this->estilo = array('registrar', 'jquery-ui');
        $this->javascript = array('jquery-ui', 'fecha', 'select', 'registroConstructora', 'validarProyecto', 'jquery.validate.min');
        $this->titulo = 'registrar proyecto';
        $this->menu = 'menu_admin_proyecto';
        
        $this->permisos('admin');  
                
        $datos['user'] = 'admin';
        $datos['codigo'] = $codigo;
        $empleados = Empleado_model::obtener('Nombre', 'asc', 0, Empleado_model::numero());
        $datos['empleados'] = array();
        
        foreach ($empleados as $empleado){
            $datos['empleados'][$empleado->Email] = $empleado->Nombre . ' ' . $empleado->ApellidoP . ' '. $empleado->ApellidoM;
        }     
        
        if(!Proyecto_model::existe($codigo)){
            $presupuesto = new Presupuesto_model;
            $presupuesto->datos($codigo);   
            
            $constructoras = Constructora_model::obtener('RazonSocial', 'asc');
            $datos['constructoras'] = array('0'=>'');
            foreach ($constructoras as $constructora){
                $datos['constructoras'][$constructora->Cif] = $constructora->RazonSocial;
            }            
                      
            $datos['opciones'] = array('0'=>'','1'=>'Obra Nueva','2'=>'Peritación', '3'=>'Rehabilitación', '4'=>'Adecuación de local', '5'=>'Tasación', '6'=>'Informe', '7'=>'Auditoría energética');
            $datos['formulario']= array(
                'nombreProyecto' => array('class'=>'nombre', 'name'=>'nombre', 'label'=>'Nombre del proyecto', 'requerido'=>TRUE),
                'fechaFin'=> array('class'=>'fechaFin', 'name'=>'fechaFin', 'label'=>'Fecha fin prevista', 'requerido'=>FALSE),           
                'constructora'=> array('class'=>'constructora', 'name'=>'constructora', 'label'=>'Empresa constructora', 'requerido'=>FALSE),           
                'tipo'=> array('class'=>'uneditable-input', 'name'=>'tipo', 'label'=>'Tipo', 'value'=> $datos['opciones'][$presupuesto->tipo()], 'requerido'=>FALSE ),                
                'superficie'=> array('class'=>'uneditable-input', 'name'=>'superficie', 'label'=>'Superficie', 'value'=>$presupuesto->superficie(), 'requerido'=>FALSE),
                'direccion'=>array('class'=>'uneditable-input', 'name'=>'direccion', 'label'=>'Dirección', 'value'=>$presupuesto->direccion(), 'requerido'=>FALSE),
                'ciudad'=>array('class'=>'uneditable-input', 'name'=>'ciudad', 'label'=>'Ciudad', 'value'=>$presupuesto->ciudad(), 'requerido'=>FALSE),
                'provincia'=>array('class'=>'uneditable-input', 'name'=>'provincia','label'=>'Provincia', 'value'=>$presupuesto->provincia(), 'requerido'=>FALSE),
                'cliente'=>array('class'=>'uneditable-input', 'name'=>'cliente','label'=>'Cliente', 'value'=>$presupuesto->nombreCliente($codigo), 'requerido'=>FALSE),
            );                      
           
            $datos['boton'] = array( 'class'=> 'btn btn-info', 'name'=>'button', 'id' => 'boton_presupuesto');
            
            $datos['formularioRegistro'] = array(
                'cif'=>array('id'=>'cif','class'=>'cif', 'name'=>'cif', 'label'=>'CIF', 'maxlength'=>'9', 'size'=>'15' ,'requerido'=> TRUE),
                'razonSocial'=>array('razon'=>'razon','class'=>'razon', 'name'=>'razon', 'label'=>'Razón Social', 'maxlength'=>'20', 'size'=>'15','requerido'=> TRUE)
            );

            $this->form_validation->set_rules('nombre', 'Nombre de proyecto', 'trim|required|xss_clean');
            $this->form_validation->set_rules('fechaFin', 'Fecha fin prevista', 'trim|xss_clean');
            
            $this->form_validation->set_message('required', '%s no puede estar vacio');
            $this->form_validation->set_message('min_length', '%s debe tener mínimo %s caracteres');
            $this->form_validation->set_message('xss_clean', ' %s no es válido');

            if($this->form_validation->run() == TRUE){
                $proyecto = new Proyecto_model;
                if($proyecto->inicializar($codigo)){
                    $codigoProyecto = $proyecto->codigo();
                    $empleados = $this->input->post('empleados');
                    $nombre = $proyecto->nombre($codigoProyecto);
                    
                    $data = array('Estado' => 'Cerrado');
                    $presupuesto->actualizar($codigo, $data);
                    $this->exito = 'El proyecto se ha registrado con éxito.';
                    
                    $presupuesto = new Presupuesto_model;
                    $opciones = array('0'=>'','1'=>'Obra Nueva','2'=>'Peritación', '3'=>'Rehabilitación', '4'=>'Adecuación de local', '5'=>'Tasación', '6'=>'Informe', '7'=>'Auditoría energética');
                    $f = $proyecto->fechaFinPrevista($codigo);
                    if($f == NULL){
                        $f = '---------';
                    }
                    $presupuesto = new Presupuesto_model;
                    $data = array(
                       'codigoProyecto' => $codigoProyecto,
                       'nombre'         => $proyecto->nombre($codigoProyecto),
                       'progreso'       => $this->_progreso($codigoProyecto),
                       'estado'         => 'Ejecución',
                       'comienzo'       => $proyecto->fechaComienzo($codigoProyecto),
                       'fin'            => $f,
                       'tipo'           => $opciones[$presupuesto->tipo($proyecto->codigoPresupuesto($codigoProyecto))],
                       'direccion'      => $presupuesto->direccion($proyecto->codigoPresupuesto($codigoProyecto)),
                       'ciudad'         => $presupuesto->ciudad($proyecto->codigoPresupuesto($codigoProyecto)),
                       'provincia'      => $presupuesto->provincia($proyecto->codigoPresupuesto($codigoProyecto)),
                       'empleados'      => $empleados,
                    );
                    
                    $this->pusher->trigger('private-notificaciones','proyecto-crear',$data);
                }
                else{
                    $this->error = array(
                        'nivel' =>'2',
                        'mensaje' => '<h4>Error.</h4> El proyecto no ha podido registrarse con éxito. Pro favor inténtelo de nuevo más tarde.'
                    );    
                }                                      
            }
        }
        else{
            $this->error = array(
                'nivel'=>'1',
                'mensaje'=>"No se puede crear el proyecto indicado, debido a que ya existe"
            ); 
            
        }
        $this->mostrar($datos);
    }
    
    
    public function listar($campo = 'Estado', $orden = 'asc', $limit='5', $offset = 0){
        if($this->uri->segment(1) == 'cliente'){
            $this->permisos('cliente');
            $this->load->library('cart');
            
            $this->pagina = 'listado proyectos';
            $this->carpeta = 'cliente';
            $this->titulo = 'proyectos';
            $this->estilo = 'listado%20presupuesto';
            $this->javascript =  array('menu_cliente', 'jquery-ui', 'tamanyo', 'confirmacion');
            
            $proyectos = Proyecto_model::cliente($this->session->userdata('email'));  
            
            $datos['proyectos'] = $proyectos;
            
        }    
        else{
            $this->pagina = 'proyectos';
            $this->carpeta = 'empleado';
            $this->titulo = 'proyectos';
            $this->estilo = 'tablas';            
            $this->javascript = array('marcar_checkbox','solicitudProyecto');
            
            $datos['buscador'] = array('class' => 'search-query input-medium', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'autofocus'=>'autofocus');
            $datos['boton'] = array('class'=>'btn', 'id'=>'buscador', 'name'=>'button', 'value'=>'Buscar');
            $datos['borrar']= array('class'=>'btn btn-danger','id'=>'borrar', 'value'=>'Borrar selección','data-confirm'=>"¿Estás seguro?");


            if($this->uri->segment(1) == 'admin'){
                $this->permisos('admin');
                $datos['user'] = 'admin';
                $numeroProyectos  = Proyecto_model::numero();
            }

            if($this->uri->segment(1) == 'empleados'){
                $this->permisos('empleado');
                $datos['user'] = 'empleados';
                $numeroProyectos = Proyecto_model::numero($this->session->userdata('email'));
            }           

            $opciones = $this->seleccion($numeroProyectos);
            $datos['opciones'] = $opciones;
            $datos['numero'] = $opciones;

            if($this->input->post('cantidad') != ''){
                if($opciones[$this->input->post('cantidad')] == 'Todo'){
                    $limit = $numeroProyectos;
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
                    'NombreProyecto' => 'Proyecto',
                    'Estado'=>'Estado',
                    'Progreso'=>'Progreso',
                    'FechaComienzo' => 'Comienzo',
                    'FechaFinPrevista' => 'Fin previsto',
                    'Tipo' => 'Tipo',
                    'Direccion' => 'Dirección',
                    'Ciudad' => 'Ciudad',
                    'Provincia' => 'Provincia'
            );

            $datos['numPresupuestos'] = Presupuesto_model::numero_aceptados();
            $datos['presupuestos'] = Presupuesto_model::aceptados();
            //print_r($datos['presupuestos']);
            if($this->uri->segment(1) == 'admin'){
                $proyectos = Proyecto_model::obtener($campo, $orden, $offset, $limit);
            }
            else{
                $proyectos = Proyecto_model::obtener($campo, $orden, $offset, $limit, $this->session->userdata('email'));
            }
            foreach($proyectos as $proyecto){
                $proyecto->Progreso = $this->_progreso($proyecto->Codigo);
            }

            $datos['proyectos'] = $proyectos;

            $config = array();
            if($this->uri->segment(1) == 'admin')
                $config['base_url'] = base_url(). "admin/proyecto/".$campo."/".$orden."/".$limit."/";
            else    
                $config['base_url'] = base_url(). "empleados/proyecto/".$campo."/".$orden."/".$limit."/";

            $config['total_rows'] = $numeroProyectos;
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
        } 
        //}
        
        $this->mostrar($datos);
    }    

    public function buscar($campo = 'Estado', $orden = 'asc', $limit='5', $busqueda = '', $offset = 0){
        
        if($this->uri->segment(1) == 'admin'){
            $this->permisos('admin');
            $datos['user'] = 'admin';
            $numeroProyectos  = Proyecto_model::numero();
        }
        
        if($this->uri->segment(1) == 'empleados'){
            $this->permisos('empleado');            
            $datos['user'] = 'empleados';
            $numeroProyectos = Proyecto_model::numero($this->session->userdata('email'));
        }
                
        $this->pagina = 'proyectos';
        $this->carpeta = 'empleado';
        $this->titulo = 'buscar proyectos';
        $this->estilo = 'tablas';
        $this->javascript = array('marcar_checkbox', 'confirmacion', 'redireccion');
        
        $datos['busqueda'] = TRUE;
        
        if ($busqueda != '')
            $datos['buscador'] = array('class' => 'search-query input-medium', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'value'=>urldecode($busqueda),'autofocus'=>'autofocus');
        else
            $datos['buscador'] = array('class' => 'search-query input-medium', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'value'=>$this->input->post('buscador'), 'autofocus'=>'autofocus');
        $datos['boton'] = array('class'=>'btn', 'id'=>'buscador', 'name'=>'button', 'value'=>'Buscar');
        $datos['borrar']= array('class'=>'btn btn-danger','id'=>'borrar', 'value'=>'Borrar selección','data-confirm'=>"¿Estás seguro?");
        $datos['fields'] = array(
                        'NombreProyecto' => 'Proyecto',
                        'Estado'=>'Estado',
                        'Progreso'=>'Progreso',
                        'FechaComienzo' => 'Comienzo',
                        'FechaFinPrevista' => 'Fin previsto',
                        'Tipo' => 'Tipo',
                        'Direccion' => 'Dirección',
                        'Ciudad' => 'Ciudad',
                        'Provincia' => 'Provincia'
        );
        
        $this->form_validation->set_rules('buscador', 'Buscador', 'trim|required|xss_clean');
        $this->form_validation->set_message('required', '%s no puede estar vacio');
        $this->form_validation->set_message('xss_clean', ' %s no es una búsqueda válida');        
        
        if($this->form_validation->run() == FALSE){
            if($busqueda != ''){ 
                $busqueda = urldecode($busqueda);
                $cantidad = Proyecto_model::busqueda_cantidad($busqueda);
                $opciones = $this->seleccion();
                $datos['opciones'] = $opciones;
                if($this->input->post('cantidad') != ''){
                    if($opciones[$this->input->post('cantidad')] == 'Todo'){
                        $limit = $cantidad;
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
                
                $proyectos = $this->Proyecto->buscar($busqueda, $campo, $orden, $offset, $limit);
                foreach($proyectos as $proyecto){
                    $proyecto->Progreso = $this->_progreso($proyecto->Codigo);
                }
                
                $datos['proyectos'] = $proyectos;
                $datos['numero'] = $cantidad;
                
                $config = array();
                $config['base_url'] = base_url(). "admin/proyecto/buscar/".$campo."/".$orden."/".$limit."/".$busqueda."/";
                $config['total_rows'] = $cantidad;
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
           
        }
        
        else{
            $busqueda = $this->input->post('buscador');
            $cantidad = Proyecto_model::busqueda_cantidad($busqueda);
            $opciones = $this->seleccion($cantidad);
            $datos['opciones'] = $opciones;

            if($this->input->post('cantidad') != ''){
                if($opciones[$this->input->post('cantidad')] == 'Todo'){
                    $limit = $this->Proyecto->numero();
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
            
            $proyectos = Proyecto_model::buscar($busqueda, $campo, $orden, $offset, $limit);
            foreach($proyectos as $proyecto){
                $proyecto->Progreso = $this->_progreso($proyecto->Codigo);
            }

            $datos['proyectos'] = $proyectos;
            $datos['numero'] = $cantidad;
            
            $config = array();
            $config['base_url'] = base_url(). "admin/proyecto/buscar/".$campo."/".$orden."/".$limit."/".$busqueda."/";
            $config['total_rows'] = $cantidad;
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
        }
         $this->mostrar($datos);
    }
    
    
     public function borrar($codigo = ''){
        if($codigo != ''){
            $codigo = urldecode($codigo);
            if(Proyecto_model::existe($codigo)){
                Archivo_model::eliminarProyecto($codigo);
                $proyecto = new Proyecto_model;
                if($proyecto->eliminar($codigo)){                
                    redirect('admin/proyecto');                
                }
            }
        }
        else{
            if($this->input->post('checkbox') != ''){    
                $codigos = $this->input->post('checkbox');
                foreach($codigos as $codigo){
                    if(Proyecto_model::existe($codigo)){
                        Archivo_model::eliminarProyecto($codigo);
                        $proyecto = new Proyecto_model;
                        $proyecto->eliminar($codigo); 
                    }
                }
            }             
            redirect('admin/proyecto');                 
        }        
    }
    
    public function empleados($codigo){
        $this->permisos('admin');
        $datos['user'] = 'admin';
        
        $this->pagina = 'empleados proyecto';
        $this->carpeta = 'administrador';
        $this->titulo = 'empleados proyecto';
        $this->menu = 'menu_admin_proyecto';
        $this->estilo = array('proyectos','jquery-ui','empleados-proyecto');
        $this->javascript = array('jquery-ui', 'fecha', 'select');            
        $this->submenu = 'menu_proyecto';
        
        $datos['codigo'] = $codigo;
        $datos['boton'] = array( 'class'=> 'btn btn-info', 'name'=>'button', 'id' => 'boton_presupuesto');
        
        if(Proyecto_model::existe($codigo)){
            $proyecto = new Proyecto_model;
            $proyecto->datos($codigo);
            
            $datos['nombreProyecto'] = $proyecto->nombre();
            $empleadosProyectos= Proyecto_model::empleadosProyecto($codigo);
            $empl = array();
            $datos['empleadosProyectos'] = array();
            foreach ($empleadosProyectos as $empleadoP){
                $datos['empleadosProyectos'][$empleadoP->EmailEmpleado] = $empleadoP->Nombre . ' ' . $empleadoP->ApellidoP . ' '. $empleadoP->ApellidoM;
                array_push($empl, $empleadoP->EmailEmpleado);
            }

            $empleados = Proyecto_model::empleadosNoProyecto($empl);

            $datos['empleados'] = array();
            if(!empty($empleados)){
                foreach ($empleados as $empleado){
                    $datos['empleados'][$empleado->Email] = $empleado->Nombre . ' ' . $empleado->ApellidoP . ' '. $empleado->ApellidoM;
                }
            }    

            if($this->input->post('empleados') && $proyecto->estado() != 'Cerrado'){

               if($proyecto->insertarEmpleadoProyecto($this->input->post('empleados'))){         
                   $empleadosProyectos= Proyecto_model::empleadosProyecto($codigo);
                   $empl = array();
                   $datos['empleadosProyectos'] = array();
                   foreach ($empleadosProyectos as $empleadoP){
                       $datos['empleadosProyectos'][$empleadoP->EmailEmpleado] = $empleadoP->Nombre . ' ' . $empleadoP->ApellidoP . ' '. $empleadoP->ApellidoM;
                       array_push($empl, $empleadoP->EmailEmpleado);
                   }
                   $empleados = Proyecto_model::empleadosNoProyecto($empl);
                   $datos['empleados'] = array();
                   if(!empty($empleados)){
                       foreach ($empleados as $empleado){
                           $datos['empleados'][$empleado->Email] = $empleado->Nombre . ' ' . $empleado->ApellidoP . ' '. $empleado->ApellidoM;
                       }
                   }
                   
                   $codigoProyecto = $codigo;
                   $empleados = $this->input->post('empleados');
                   $opciones = array('0'=>'','1'=>'Obra Nueva','2'=>'Peritación', '3'=>'Rehabilitación', '4'=>'Adecuación de local', '5'=>'Tasación', '6'=>'Informe', '7'=>'Auditoría energética');
                   $f = $proyecto->fechaFinPrevista($codigo);
                   if($f == NULL){
                       $f = '---------';
                   }
                   $presupuesto = new Presupuesto_model;
                   $data = array(
                       'codigoProyecto' => $codigoProyecto,
                       'nombre'         => $proyecto->nombre($codigoProyecto),
                       'progreso'       => $this->_progreso($codigoProyecto),
                       'estado'         => 'Ejecución',
                       'comienzo'       => $proyecto->fechaComienzo($codigoProyecto),
                       'fin'            => $f,
                       'tipo'           => $opciones[$presupuesto->tipo($proyecto->codigoPresupuesto($codigoProyecto))],
                       'direccion'      => $presupuesto->direccion($proyecto->codigoPresupuesto($codigoProyecto)),
                       'ciudad'         => $presupuesto->ciudad($proyecto->codigoPresupuesto($codigoProyecto)),
                       'provincia'      => $presupuesto->provincia($proyecto->codigoPresupuesto($codigoProyecto)),
                       'empleados'      => $empleados,
                   );
                   
                   $this->pusher->trigger('private-notificaciones','proyecto-crear',$data);
              
               }
            }
            if($this->input->post('select1') && $proyecto->estado() != 'Cerrado'){
                if($proyecto->eliminarEmpleadoProyecto($this->input->post('select1'))){
                   $empleadosProyectos= Proyecto_model::empleadosProyecto($codigo);
                   $empl = array();
                   $datos['empleadosProyectos'] = array();
                   foreach ($empleadosProyectos as $empleadoP){
                       $datos['empleadosProyectos'][$empleadoP->EmailEmpleado] = $empleadoP->Nombre . ' ' . $empleadoP->ApellidoP . ' '. $empleadoP->ApellidoM;
                       array_push($empl, $empleadoP->EmailEmpleado);
                   }
                   $empleados = Proyecto_model::empleadosNoProyecto($empl);
                   $datos['empleados'] = array();
                   if(!empty($empleados)){
                       foreach ($empleados as $empleado){
                           $datos['empleados'][$empleado->Email] = $empleado->Nombre . ' ' . $empleado->ApellidoP . ' '. $empleado->ApellidoM;
                       }
                   }
                }
            }
            
        }
        else{
            $this->error = array(
                'nivel' =>'1',
                'mensaje' => 'El proyecto indicado no existe'
            );
        }
        $this->mostrar($datos);
    }
    
    public function informacion($codigo){
        
        $this->permisos('admin');
        $this->pagina ='proyecto';
        $this->carpeta = 'administrador';
        $this->titulo = 'Información proyecto';        
        $this->menu = 'menu_admin_proyecto';
        $this->submenu = 'menu_proyecto';
        $this->estilo = array('jquery-te-1.3.3', 'jquery-ui', 'proyectos', $this->pagina); 
        $this->javascript = array('editor','proyecto', 'campos', 'jquery-te-1.3.3.min');
        
        $datos['user'] = 'admin';
        if(Proyecto_model::existe($codigo)){
            $opciones = array('0'=>'','1'=>'Obra Nueva','2'=>'Peritación', '3'=>'Rehabilitación', '4'=>'Adecuación de local', '5'=>'Tasación', '6'=>'Informe', '7'=>'Auditoría energética');
            $datos['codigo'] = $codigo;
            $datos['estados'] = array('0'=>'Ejecución','1'=>'Cerrado');
            $proyecto = new Proyecto_model;            
            $datos['proyecto'] = $proyecto->datos($codigo);
            
            $datos['estado'] = $proyecto->estado(); 
            $archivo = new Archivo_model; 
            $datos['mem'] = $archivo->memProyecto($codigo);
            $datos['mostrar'] = array(
                  'name'        => 'mostrar',
                  'id'          => 'mostrar',
                  'value'       => 'accept',
                  'checked'     => $proyecto->visible()
            );
            $datos['nombreProyecto'] = $proyecto->nombre();        
            $datos['empleadosProyectos']= Proyecto_model::empleadosProyecto($codigo);
            
            $presupuesto = new Presupuesto_model;
            $datos['presupuesto'] = $presupuesto->datos($proyecto->codigoPresupuesto());            
            $datos['presupuesto']->Tipo = $opciones[$datos['presupuesto']->Tipo];
            $datos['precio'] = $presupuesto->precio();
            $datos['editor'] = array('class' => 'editor', 'id' => 'contenido', 'name'=>'contenido', 'label' => 'contenido', 'value' => $proyecto->contenido($codigo));
            $cliente = new Cliente_model;
            $datos['cliente'] = $cliente->datos($presupuesto->email());
            
            $archivos = Archivo_model::archivosProyecto($codigo);
            $datos['archivos'] = array();
            $datos['imagenes'] = array();
            foreach($archivos as $archivo){
                if(substr($archivo->Ruta, -1) != '/'){
                    $ext = substr(strrchr($archivo->Ruta, "."),1);
                    if($ext == 'jpg' || $ext == 'JPG' ||$ext == 'jpeg' || $ext == 'JPEG' || $ext == 'png' || $ext == 'PNG'){
                        array_push($datos['imagenes'], $archivo);
                    }
                    else{
                        array_push($datos['archivos'], $archivo);
                    }
                }
            }
            
            if(Empresa_model::existe($proyecto->constructora())){
                $constructora = new Constructora_model;
                $datos['constructora'] = $proyecto->constructora();
                
                $constructoras = Constructora_model::obtener('RazonSocial', 'asc');
                $datos['constructoras'] = array('0'=>'');
                foreach ($constructoras as $constructora){
                    $datos['constructoras'][$constructora->Cif] = $constructora->RazonSocial;
                }
            }
            else{
                $constructoras = Constructora_model::obtener('RazonSocial', 'asc');
                $datos['constructoras'] = array('0'=>'');
                foreach ($constructoras as $constructora){
                    $datos['constructoras'][$constructora->Cif] = $constructora->RazonSocial;
                }
                
            }
            $proveedores = Proveedor_model::obtener('Cif', 'asc');
            $datos['proveedores'] = array('0'=>'');
            foreach($proveedores as $proveedor){
                $datos['proveedores'][$proveedor->Cif] = $proveedor->RazonSocial;
            }
            
            $empleados = Proyecto_model::empleadosProyecto($codigo);

            $datos['empleados'] = array();        
            foreach($empleados as $aux){
                $empleado = new Empleado_model;
                $empleado->Foto = $empleado->foto($aux->EmailEmpleado);
                array_push($datos['empleados'],$empleado->datos($aux->EmailEmpleado));
            }
            
            $tareas = Tarea_model::obtener($this->session->userdata('email'), $codigo);
            $datos['tareas'] = $tareas;
            
            $materiales = Material_model::obtener($codigo);
            $datos['materiales'] = $materiales;
            
            $this->form_validation->set_rules('material[]', 'material', 'trim|min_length[3]|xss_clean');
            $this->form_validation->set_rules('contenido', 'material', 'trim|min_length[3]|xss_clean');
            
            $this->form_validation->set_message('min_length', '%s debe tener mínimo %s caracteres');
            $this->form_validation->set_message('xss_clean', ' %s no es válido');

            if ($this->form_validation->run() == TRUE) {
                if($this->input->post('estado') != TRUE){
                    $estado = 'Ejecución';
                    $datos['estado'] = $estado;
                }
                else{
                   $estado = 'Cerrado';
                   $datos['estado'] = $estado;
                }
                
                $data = array('Visible' => FALSE, 'Estado'=>$estado);
                $proyecto->actualizar($codigo, $data);
                Archivo_model::ocultar($codigo);
                
                if($this->input->post('mem') != ''){
                    $codigoArchivo = $this->input->post('mem');
                    $data = array('Codigo' => $codigoArchivo[0],'CodigoProyecto'=>$codigo ,'ArchivoProyecto'=>TRUE);
                    $archivo = new Archivo_model;
                    $archivo->memProyecto($codigo, $data, TRUE);
                    $datos['mem'] = $codigoArchivo[0];
                }
                if($this->input->post('contenido')!=''){ 
                    $data = array('Contenido' => $this->input->post('contenido'));
                    $proyecto->actualizar($codigo, $data);
                    $datos['editor']['value'] = $this->input->post('contenido');
                }

                $aux = $this->input->post('mostrar');
                
                if($aux == 'accept'){
                    $data = array('Visible' => TRUE);
                    $proyecto->actualizar($codigo, $data);
                    Archivo_model::ocultar($codigo);
                    $aux = $this->input->post();
                    $value = 'accept';
                    foreach ($aux as $key => $value) {
                        if($key != 'mostrar'){
                            $archivo = new Archivo_model;
                            $archivo->visible($key, TRUE);
                        }                        
                    }
                    
                }

                if($this->input->post('constructora') != 0){
                    $aux = $this->input->post();
                    $data = array('CifConstructora' => $this->input->post('constructora'));
                    $datos['constructora'] = $this->input->post('constructora');
                    $proyecto->actualizar($codigo, $data);
                }
                if($this->input->post('proveedor')!= '' && $this->input->post('material')!= ''){
                    $mat = $this->input->post('material');
                    $prov = $this->input->post('proveedor');
                    for($i=0;$i != count($this->input->post('proveedor')); $i++){
                      $material = new Material_model;
                      $material->inicializar($mat[$i],$prov[$i], $codigo);
                    }
                }      
            }
            else{
                $datos['errores'] = validation_errors();
            }           
            
            $datos['mostrar']['checked'] = $proyecto->visible($codigo);
            $archivos = Archivo_model::archivosProyecto($codigo);
            $materiales = Material_model::obtener($codigo);
            $datos['materiales'] = $materiales;
            $datos['archivos'] = array();
            $datos['imagenes'] = array();
            foreach($archivos as $archivo){
                if(substr($archivo->Ruta, -1) != '/'){
                    $ext = substr(strrchr($archivo->Ruta, "."),1);
                    if($ext == 'jpg' || $ext == 'JPG' ||$ext == 'jpeg' || $ext == 'JPEG' || $ext == 'png' || $ext == 'PNG'){
                        array_push($datos['imagenes'], $archivo);
                    }
                    else{
                        array_push($datos['archivos'], $archivo);
                    }
                }
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
    
    
    public function informe($codigo){
        $this->permisos('admin');
        $proyecto = new Proyecto_model;
        $proyecto->datos($codigo);
        
        $presupuesto = new Presupuesto_model;
        $presupuesto->datos($proyecto->codigoPresupuesto());
        $tareas = Tarea_model::obtener($this->session->userdata('email'), $codigo);
        $empleados = Proyecto_model::empleadosProyecto($codigo);
                       
        $pdf = new Proyectopdf();
        $pdf->AddPage();
        $pdf->SetMargins(20, 25 , 30); 
        $pdf->SetAutoPageBreak(true,25);  
        $pdf->AliasNbPages();        
        $pdf->SetFont('Times','',12);
        $pdf->Cabecera($proyecto->NombreProyecto);
        $pdf->Cuerpo($proyecto, $presupuesto, $tareas, $empleados);
        $pdf->Pie();
        $pdf->Output();
    }
    
    public function proyectos(){
        $this->pagina = 'proyectos';
        $this->titulo = 'proyectos';
        $this->estilo = array('listProyectos','bootstrap-lightbox');
        $this->javascript = array('bootstrap-lightbox','proyectos', 'tamanyo');
        
        
        $offset = $this->uri->segment(2);
        $limit = 2;
        $visibles = array();
        if(Proyecto_model::numeroVisibles() > 0)
            $visibles = Proyecto_model::visibles($offset, $limit);
        if(empty($visibles)){
            $datos['mensaje'] = 'Actualmente no existen proyectos disponibles para mostrar.';
        }
        else{
            $opciones = array('0'=>'','1'=>'Obra Nueva','2'=>'Peritación', '3'=>'Rehabilitación', '4'=>'Adecuación de local', '5'=>'Tasación', '6'=>'Informe', '7'=>'Auditoría energética');
            $proyectos = array();
            $presupuesto = new Presupuesto_model;            
            
            foreach($visibles as $visible){ 
                $presupuesto->datos($visible->CodigoPresupuesto);
                
                $imagenes = Archivo_model::obtenerVisibles($visible->Codigo);
                
                $proyectos[$visible->Codigo] = array(
                    'Nombre' => $visible->NombreProyecto,
                    'Ciudad'=> $presupuesto->ciudad(),
                    'Provincia'=> $presupuesto->provincia(),
                    'Tipo' => $opciones[$visible->Tipo],
                    'Descripcion' => $visible->Contenido,
                    'imagenes'=> $imagenes,
                );
            }
            
            
            $config = array();
            $config['base_url'] = base_url(). "proyectos";
            $config['total_rows'] = Proyecto_model::numeroVisibles();
            $config['per_page'] = $limit;
            $config['uri_segment'] = 2;
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
            
            $datos['proyectos'] = $proyectos;
        }
        
        $this->mostrar($datos);
    }
    
     private function _progreso($codigo){
        $aux = '1'; 
        if(Proyecto_model::existe($codigo)){
            $proyecto = new Proyecto_model;
            if($proyecto->estado($codigo) == 'Cerrado'){
                $aux = '100';
            }
            else{
                
            }
        }        
        
        return $aux;
    }
    
    
}


?>
