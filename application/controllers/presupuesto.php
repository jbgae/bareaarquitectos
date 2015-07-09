<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Presupuesto extends MY_Controller{
    
    public function __construct() {
        parent:: __construct();
        
        $this->load->library('pagination');
        $this->load->library('Presupuestopdf'); 
        $this->load->library('form_validation');
        $this->load->model('presupuesto_model');
        $this->load->model('archivo_model');
        $this->load->model('usuario_model');
        $this->load->model('cliente_model', 'Cliente');
        $this->load->model('lineas_presupuesto_model','LineasPresupuesto');
        $this->load->model('ciudad_model','Ciudad');
        $this->load->model('provincia_model','Provincia');
        $this->load->library('My_PHPMailer');
        
         $this->form_validation->set_error_delimiters('<div class="alert alert-error"> <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <h4>Error</h4>', '</div>');
    }    

    private function _validar(){
        $this->form_validation->set_rules('tipo', 'Tipo de obra', 'trim|required|xss_clean');
        $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean');
        $this->form_validation->set_rules('superficie', 'Superficie', 'trim|numeric|xss_clean');
        if($this->uri->segment(4) == ''){
            $this->form_validation->set_rules('direccion', 'Dirección', 'trim|required|min_length[3]|xss_clean');
        }
        $this->form_validation->set_rules('ciudad', 'Ciudad', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('provincia', 'Provincia', 'trim|required|numeric|xss_clean');
        if($this->uri->segment(1) == 'admin'){
            $this->form_validation->set_rules('pem', 'Pem', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('coeficiente', 'Coeficiente', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('coeficienteSeguridad', 'Coeficiente de seguridad', 'trim|required|numeric|xss_clean');
        }    
        
        $this->form_validation->set_message('required', '%s no puede estar vacio');
        $this->form_validation->set_message('min_length', '%s debe tener mínimo %s caracteres');
        $this->form_validation->set_message('xss_clean', ' %s no es válido');
        $this->form_validation->set_message('numeric', '%s debe contener dígitos');
        
        return $this->form_validation->run();
    }
    
    
    public function registrar(){ 
        
        $this->menu = 'menu_admin_presupuestos';
        $datos['opciones'] = array('0'=>' ','1'=>'Obra Nueva','2'=>'Peritación', '3'=>'Rehabilitación', '4'=>'Adecuación de local', '5'=>'Tasación', '6'=>'Informe', '7'=>'Auditoría energética');
                
        $datos['provincias'] = $this->Provincia->obtener();
        $datos['provincias'][0]=' ';
        $datos['ciudades'] = $this->Ciudad->obtener();
        
        
        if($this->uri->segment(1) == 'cliente'){ 
            $this->load->library('cart');
            $this->permisos('cliente');
            
            $this->pagina = 'presupuesto';
            $this->carpeta = 'cliente';            
            $this->titulo = 'Solicitar presupuesto';
            $this->estilo = array('registrar', 'presupuesto', 'jquery-te-1.3.3');
            $this->javascript =  array('jquery-ui', 'ciudades', 'tamanyo', 'jquery.validate.min', 'validarPresupuesto');
            
            $datos['boton'] = array('class'=>'btn', 'value'=>'Solicitar presupuesto');

            $datos['formulario']= array(
                'direccion' => array(
                    'label' => 'Dirección',
                    'input' => array('class'=>'direccion','id'=>'direccion', 'name'=>'direccion', 'autofocus'=>'autofocus'),
                    'requerido'=> TRUE
                ),
                'provincia' => array(
                    'label' => 'Provincia',
                    'input' => array('class'=>'provincia', 'id'=>'provincia','name'=>'provincia'),
                    'requerido'=> TRUE
                ),
                'ciudad'    => array(
                    'label' => 'Ciudad',
                    'input' => array('class'=>'ciudad','id'=>'ciudad', 'name'=>'ciudad'),
                    'requerido'=> TRUE,
                ),
                'tipo'      => array(
                    'label' => 'Tipo de obra',
                    'input' => array('class'=>'tipo', 'id'=>'tipo','name'=>'tipo', 'autofocus'=>'autofocus'),
                    'requerido' => TRUE
                ),
                'superficie'=> array(
                    'label'=>'Superficie',
                    'input'=>array('class'=>'superficie input-small', 'id'=>'supericie','name'=>'superficie'),
                    'requerido' => FALSE
                ),
                'descripcion'=> array(
                    'label' =>'Descripción',
                    'input' =>array('class'=>'descripcion span12 editor', 'id'=>'descripcion','name'=>'descripcion'),
                    'requerido' => FALSE
                 )
            );
            
        }
        else if($this->uri->segment(1) == 'admin'){
            $this->permisos('admin');
            
            $this->pagina = 'crear presupuesto';
            $this->carpeta = 'administrador'; 
            $this->titulo = 'Crear presupuesto';
            $this->estilo = array('registrar', 'presupuesto', 'jquery-te-1.3.3');
            $this->javascript = array('presupuesto', 'calcular presupuesto', 'ciudades', 'fecha', 'registroCliente', 'jquery.validate.min', 'validarPresupuesto', 'editor', 'jquery-te-1.3.3.min');
            $this->menu = 'menu_admin_presupuestos';
            
            $datos['boton'] = array( 'class'=> 'btn btn-info', 'name'=>'button', 'id' => 'boton_presupuesto');
            $datos['formulario']= array(
                'descripcion'=> array('class'=>'descripcion span12 editor', 'name'=>'descripcion', 'label'=>'Descripción','requerido' => FALSE),
                'direccion' =>array('class'=>'direccion', 'name'=>'direccion', 'label'=>'Dirección','requerido'=> TRUE, 'autofocus'=>'autofocus'),
                'nombre'    =>array('class'=>'nombre', 'name' => 'nombre', 'label'=> 'Cliente', 'requerido' => TRUE),
                'provincia' =>array('class'=>'provincia', 'name'=>'provincia','label'=>'Provincia','requerido'=> TRUE),            
                'ciudad'    =>array('class'=>'ciudad', 'name'=>'ciudad', 'label'=>'Ciudad','requerido'=> TRUE),
                'tipo'      => array('class'=>'tipo', 'name'=>'tipo', 'label'=>'Tipo de obra', 'autofocus'=>'autofocus', 'requerido'=> TRUE),
                'superficie'=> array('class'=>'superficie input-small', 'name'=>'superficie', 'label'=>'Superficie','requerido' => FALSE),
                'pem'       => array('class'=>'pem input-small', 'id'=>'pem','name'=>'pem', 'label'=>'Pem', 'placeholder'=>'PEM','requerido' => TRUE),
                'coeficiente' => array('class'=>'coeficiente input-small','id'=>'coeficiente', 'name'=>'coeficiente', 'label'=>'Coeficiente', 'placeholder'=>'Coeficiente','requerido' => TRUE),
                'coeficienteSeguridad' => array('class'=>'coeficienteSeguridad input-small','id'=>'coeficienteSeguridad', 'name'=>'coeficienteSeguridad', 'label'=>'C. de seguridad', 'placeholder'=>'C.S.','requerido' => FALSE)
            );      
                
            $clientes = Cliente_model::obtener('Nombre', 'asc');
            $datos['clientes'] = array();
            $email = array();
            foreach ($clientes as $cliente){
                $datos['clientes'][$cliente->Email] = $cliente->Nombre . ' ' . $cliente->ApellidoP . ' '. $cliente->ApellidoM;
            }   
        }
        /*Para formulario cliente*/
        $datos['boton2'] = array( 'class'=> 'btn btn-info', 'name'=>'button', 'id' => 'boton_cliente');
        $datos['formularioRegistro'] = array(
            'nombre'=>array('id'=>'nombre','class'=>'nombre','label'=>'Nombre', 'name'=>'nombre', 'maxlength'=>'60', 'size'=>'15', 'requerido'=>TRUE),
            'apellidoPaterno'=>array('id'=>'apellidoP','class'=>'apellidoP','label'=>'Primer apellido', 'name'=>'primerApellido', 'maxlength'=>'60', 'size'=>'15', 'requerido'=>TRUE),
            'apellidoMaterno'=>array('id'=>'apellidoM','class'=>'apellidoM','label'=>'Segundo apellido', 'name'=>'segundoApellido', 'maxlength'=>'60', 'size'=>'15', 'requerido'=>TRUE),
            'fechaNacimiento'=>array('id'=>'fechaN','class'=>'fechaN','label'=>'Fecha de nacimiento', 'name'=>'fNacimiento', 'maxlength'=>'10', 'size'=>'15', 'requerido'=>TRUE),
            'email'=>array('id'=>'email','class'=>'email','label'=>'Email', 'name'=>'email', 'maxlength'=>'50', 'size'=>'15', 'requerido'=>TRUE),
            'password'=>array('id'=>'password','class'=>'password','label'=>'Contraseña', 'name'=>'pass', 'maxlength'=>'20', 'size'=>'15', 'autocomplete'=>'off', 'requerido'=>TRUE),
            'passconf'=>array('id'=>'passconf','class'=>'passconf','label'=>'Repita la contraseña', 'name'=>'passconf', 'maxlength'=>'20', 'size'=>'15','autocomplete'=>'off', 'requerido'=>TRUE),
        );
        
               
        if($this->_validar()){  
            $presupuesto = new Presupuesto_model;
            
            if($this->uri->segment(1) == 'cliente'){
                if($presupuesto->inicializar($this->session->userdata('email'))){
                    $id = $this->db->insert_id();
                    $presupuesto->datos($id);
                    $datos['valido'] = 'La solicitud de presupuesto ha sido registrado con éxito. En breve le daremos respuesta. Gracias por confiar en nosotros.';
                    $data = array(
                        'codigo' => $id,
                        'cliente'  => htmlentities(strip_tags($this->session->userdata('nombre').' '.$this->session->userdata('apellidos'))),
                        'email'    => htmlentities(strip_tags($this->session->userdata('email'))),
                        'estado'   => 'Abierto',
                        'fecha'    => date('d-m-Y H:i:s',strtotime($presupuesto->fechaSolicitud())),
                        'solicitadoFecha' => date('d M Y',strtotime($presupuesto->fechaSolicitud())),
                        'solicitadoHora' => date('H:i A',strtotime($presupuesto->fechaSolicitud())),
                        'tipo'     => $datos['opciones'][$presupuesto->tipo()],
                        'direccion'=> $presupuesto->direccion(),
                        'ciudad'   => $presupuesto->ciudad(),
                        'provincia'=> $presupuesto->provincia()
                    );
                    $this->pusher->trigger('private-notificaciones', 'presupuesto-enviar', $data);
                }
                else{
                    $datos['error'] = 'La solicitud de presupuesto no ha podido registrarse con éxito. Por favor inténtelo de nuevo más tarde.';    
                }
            }
            else if($this->uri->segment(1) == 'admin'){
                if($presupuesto->inicializar()){
                   $codigoPresupuesto = $presupuesto->codigo();  
                   $archivo = new Archivo_model;
                   $archivo->inicializar('presupuesto', $codigoPresupuesto); 
                   if($this->LineasPresupuesto->inicializar($codigoPresupuesto) ){                        
                     $this->guardar($codigoPresupuesto);
                      $datosEmail = array(
                            'direccion' => 'barea@arquitectosdecadiz.com ',
                            'nombre'    => 'Barea Arquitectos',
                            'asunto'    => 'Solicitud presupuesto estudio arquitectura Barea Arquitectos',
                            'texto'     => '<FONT FACE="arial" SIZE=4>
                                        Le informamos que el presupuesto que solicitó esta listo. 
                                        Podrá acceder a él desde el área de clientes de la página web.
                                        <a href="' . base_url() . 'privado/ "> ' . base_url() . 'privado </a>
                                        </FONT>
                                        <br><br>
                                        <FONT FACE="arial" SIZE=1>
                                        BAREA ARQUITECTOS SL <br> c/ Vega, 1, 1ºA <br> 11130_Chiclana de la Frontera (Cádiz) <br> Tlf.: 956403042/636686745 <br> barea@arquitectosdecadiz.com <br><br>
                                        La presente información se envía únicamente para la persona a la que va dirigida y puede contener información de carácter confidencial 
                                        o privilegiada. Cualquier modificación, retransmisión, difusión u otro uso de 
                                        esta información por personas o entidades distintas a la persona a la que va dirigida está prohibida. Si usted lo ha recibido por error 
                                        por favor contacte con el remitente y borre el mensaje de cualquier ordenador. BAREA ARQUITECTOS S.L. no asume la responsabilidad derivada 
                                        del hecho de que terceras personas puedan llegar a conocer el contenido de este mensaje durante su transmisión.
                                        </FONT>
                                        ',
                            'destino' => strtolower($this->input->post('nombre'))
                    );  
                    //Enviamos email de confirmación
                    $this->my_phpmailer->Enviar($datosEmail); 
                    $this->exito = "El presupuesto se ha creado satisfactoriamente.";                            
                   }
                   else{
                     $this->error = array (
                         'nivel'=>'2',
                         'mensaje' =>"El presupuesto no se ha registrado."
                     );                        
                   }
                 }  
                 else{
                     $this->error = array (
                         'nivel'=>'2',
                         'mensaje' =>"El presupuesto no se ha registrado."
                     );                       
                 }  
            }            
        }
        $this->mostrar($datos);
    }
    
    
    public function modificar($codigo){ 
        $this->permisos('admin');

        $datos['opciones'] = array('0'=>'','1'=>'Obra Nueva','2'=>'Peritación', '3'=>'Rehabilitación', '4'=>'Adecuación de local', '5'=>'Tasación', '6'=>'Informe', '7'=>'Auditoría energética');
        $datos['formulario']= array(
            'tipo' => array('class'=>'tipo', 'name'=>'tipo', 'label'=>'Tipo de obra', 'requerido'=> TRUE),
            'superficie'=> array('class'=>'superficie input-small', 'name'=>'superficie', 'label'=>'Superficie','requerido' => FALSE),
            'direccion'=>array('class'=>'direccion uneditable-input', 'name'=>'direccion', 'label'=>'Dirección','requerido'=> TRUE),
            'provincia'=>array('class'=>'provincia', 'name'=>'provincia','label'=>'Provincia','requerido'=> TRUE),            
            'ciudad'=>array('class'=>'ciudad', 'name'=>'ciudad', 'label'=>'Ciudad','requerido'=> TRUE),
            'descripcion'=> array('class'=>'descripcion span12 editor', 'name'=>'descripcion', 'label'=>'Descripción','requerido' => TRUE)
        );  
        
        /*Para formulario cliente*/
        $datos['boton2'] = array( 'class'=> 'btn btn-info', 'name'=>'button', 'id' => 'boton_cliente');
        $datos['formularioRegistro'] = array(
            'nombre'=>array('id'=>'nombre','class'=>'nombre','label'=>'Nombre', 'name'=>'nombre', 'maxlength'=>'60', 'size'=>'15', 'requerido'=>TRUE),
            'apellidoPaterno'=>array('id'=>'apellidoP','class'=>'apellidoP','label'=>'Primer apellido', 'name'=>'primerApellido', 'maxlength'=>'60', 'size'=>'15', 'requerido'=>TRUE),
            'apellidoMaterno'=>array('id'=>'apellidoM','class'=>'apellidoM','label'=>'Segundo apellido', 'name'=>'segundoApellido', 'maxlength'=>'60', 'size'=>'15', 'requerido'=>TRUE),
            'fechaNacimiento'=>array('id'=>'fechaN','class'=>'fechaN','label'=>'Fecha de nacimiento', 'name'=>'fNacimiento', 'maxlength'=>'10', 'size'=>'15', 'requerido'=>TRUE),
            'email'=>array('id'=>'email','class'=>'email','label'=>'Email', 'name'=>'email', 'maxlength'=>'50', 'size'=>'15' , 'value'=>'', 'requerido'=>TRUE),
            'password'=>array('id'=>'password','class'=>'password','label'=>'Contraseña', 'name'=>'pass', 'maxlength'=>'20', 'size'=>'15', 'autocomplete'=>'off', 'requerido'=>TRUE),
            'passconf'=>array('id'=>'passconf','class'=>'passconf','label'=>'Repita la contraseña', 'name'=>'passconf', 'maxlength'=>'20', 'size'=>'15','autocomplete'=>'off', 'requerido'=>TRUE),
        );

        $this->pagina = 'crear presupuesto';
        $this->carpeta = 'administrador'; 
        $this->form_validation->set_error_delimiters('<div class="text-error">', '</div>');
        $this->titulo = 'presupuesto';
        $this->estilo = array('registrar', 'presupuesto', 'jquery-te-1.3.3');
        $this->javascript = array('presupuesto', 'calcular presupuesto', 'ciudades', 'fecha', 'registroCliente', 'jquery.validate.min', 'validarPresupuesto', 'editor', 'jquery-te-1.3.3.min',);
        $this->menu = 'menu_admin_presupuestos';
        $datos['boton'] = array( 'class'=> 'btn btn-info', 'name'=>'button', 'id' => 'boton_presupuesto');

        $datos['codigo'] = $codigo;
        
        if(Presupuesto_model::existe($codigo)){
            $datos['actualizar'] = TRUE;
            $presupuesto = new Presupuesto_model;
            $presupuesto->datos($codigo);
            
            $datos['provincias'] = $this->Provincia->obtener();
            $datos['ciudades'] = $this->Ciudad->ciudades($presupuesto->provincia($codigo, TRUE));

            $datos['formulario']['tipo']['value'] = $presupuesto->tipo();
            $datos['formulario']['superficie']['value'] = $presupuesto->superficie();
            $datos['formulario']['direccion']['value'] = $presupuesto->direccion();
            
            $datos['formulario']['provincia']['value'] = $presupuesto->provincia($codigo,TRUE);
            $datos['formulario']['ciudad']['value'] = $presupuesto->ciudad($codigo, TRUE);

            $datos['formulario']['descripcion']['value'] = $presupuesto->descripcion();
            $datos['formulario']['nombre'] = array('class'=>'uneditable-input', 'name' => 'nombre', 'label'=> 'Cliente', 'requerido' => TRUE, 'value'=> $presupuesto->nombreCliente($codigo));
            $datos['formulario']['pem'] = array('class'=>'pem input-small', 'name'=>'pem', 'label'=>'Pem', 'placeholder'=>'PEM','requerido' => FALSE);
            $datos['formulario']['coeficiente'] = array('class'=>'coeficiente input-small', 'name'=>'coeficiente', 'label'=>'Coeficiente', 'placeholder'=>'Coeficiente','requerido' => FALSE);
            $datos['formulario']['coeficienteSeguridad'] = array('class'=>'coeficienteSeguridad input-small', 'name'=>'coeficienteSeguridad', 'label'=>'Coeficiente de seguridad', 'placeholder'=>'C.S.','requerido' => FALSE);
            
            if($this->_validar()){
                if($presupuesto->actualizar($codigo)){
                   $codigoPresupuesto = $codigo;  
                   $archivo = new Archivo_model;
                   $archivo->inicializar('presupuesto', $codigoPresupuesto);  
                   if($this->LineasPresupuesto->inicializar($codigo) ){ 
                       $this->guardar($codigo);
                       $this->exito = "El presupuesto se ha creado satisfactoriamente.";
                   }
                }
                else{
                    $this->error = array(
                        'nivel' => '2',
                        'mensaje' => "El presupuesto no se ha registrado."
                    );
                }
            }
        }
        else{
            $this->error = array(
                'nivel' => '1',
                'mensaje' => "El presupuesto no existe."
            );
        }
        $this->mostrar($datos);

    }
    
    
    public function guardar($codigo){
        $path = getcwd();
        if(!is_dir(realpath("$path/archivos/presupuestos"))){           
            mkdir("$path/archivos/presupuestos", 0755);     
        }
        
        $presupuesto = new Presupuesto_model;
        $presupuesto->datos($codigo);
        $path = str_replace('http://localhost', realpath(__DIR__ . '/../../../'),$presupuesto->ruta($codigo)); 
                
        $datos_lineas = $this->LineasPresupuesto->obtener($codigo);
        $pdf = new Presupuestopdf();
        $pdf->SetMargins(20, 25 , 30); 
        $pdf->SetAutoPageBreak(true,25);  
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times','',12);
        $pdf->datos_presupuesto($presupuesto);
        $pdf->honorarios($datos_lineas);
        $pdf->Output($path  ,'F');
    }
    
    
    public function listar($campo = 'Estado', $orden = 'asc', $limit='5', $offset = 0){
        
        $this->titulo = 'presupuestos';
        if($this->uri->segment(1) == 'cliente'){
            $this->permisos('cliente');
            $this->load->library('cart');
            
            $this->pagina = 'listado presupuesto';
            $this->carpeta = 'cliente';
            
            $this->estilo = '';
            $this->javascript =  array('menu_cliente', 'jquery-ui', 'tamanyo', 'confirmacion');
            
            $datos['presupuestos'] = Presupuesto_model::cliente($this->session->userdata('email'));  
            $this->mostrar($datos);
        }    
        else if($this->uri->segment(1) == 'admin'){
            $this->permisos('admin');

            $this->pagina = 'presupuestos';
            $this->carpeta = 'administrador';

            $this->menu = 'menu_admin_presupuestos';
            $this->estilo = 'tablas';
            $this->javascript=array('marcar_checkbox', 'confirmacion');
            $datos['buscador'] = array('class' => 'search-query input-medium', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar','autofocus'=>'autofocus');
            $datos['boton'] = array('class'=>'btn', 'id'=>'buscador', 'name'=>'button', 'value'=>'Buscar');
            $datos['borrar']= array('class'=>'btn btn-danger','id'=>'borrar', 'value'=>'Borrar selección','data-confirm'=>"¿Estás seguro?");

            if(Presupuesto_model::numero() == 0){
                $this->registrar();
            }
            else{
                $opciones = $this->seleccion(Presupuesto_model::numero());
                $datos['opciones'] = $opciones;
                $datos['numero'] = $opciones;

                if($this->input->post('cantidad') != ''){
                    if($opciones[$this->input->post('cantidad')] == 'Todo'){
                        $limit = Presupuesto_model::numero();
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
                        'Nombre' => 'Cliente',
                        'EmailCliente' => 'Email',
                        'Estado' => 'Estado',
                        'FechaSolicitud' => 'Solicitado',
                        'Tipo' => 'Tipo',
                        'Direccion' => 'Dirección',
                        'Ciudad' => 'Ciudad',
                        'Provincia' => 'Provincia'
                );


                $datos['presupuestos'] = Presupuesto_model::obtener($campo, $orden, $offset, $limit);
                


                $config = array();
                $config['base_url'] = base_url(). "admin/presupuesto/".$campo."/".$orden."/".$limit."/";
                $config['total_rows'] = Presupuesto_model::numero();
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
    }    

    
    
    public function buscar($campo = 'Nombre', $orden = 'asc', $limit='5', $busqueda = '', $offset = 0){
        $this->permisos('admin');
                
        $this->pagina = 'presupuestos';
        $this->carpeta = 'administrador';
        
        $datos['busqueda'] = TRUE;
        $this->titulo = 'buscar presupuesto';
        $this->menu = 'menu_admin_presupuestos';
        $this->estilo = array('tablas');
        $this->javascript=array('marcar_checkbox', 'confirmacion','redireccion');
        if ($busqueda != '')
            $datos['buscador'] = array('class' => 'search-query input-medium', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'value'=>urldecode($busqueda),'autofocus'=>'autofocus');
        else
            $datos['buscador'] = array('class' => 'search-query input-medium', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'value'=>$this->input->post('buscador'),'autofocus'=>'autofocus');
        $datos['boton'] = array('class'=>'btn', 'id'=>'buscador', 'name'=>'button', 'value'=>'Buscar');
        $datos['borrar']= array('class'=>'btn btn-danger','id'=>'borrar', 'value'=>'Borrar selección','data-confirm'=>"¿Estás seguro?");
        $datos['fields'] = array(
                    'Nombre' => 'Cliente',
                    'EmailCliente' => 'Email',
                    'Estado' => 'Estado',
                    'FechaSolicitud' => 'Solicitado',
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
                $opciones = $this->seleccion(Presupuesto_model::busqueda_cantidad($busqueda));
                $datos['opciones'] = $opciones;
                if($this->input->post('cantidad') != ''){
                    if($opciones[$this->input->post('cantidad')] == 'Todo'){
                        $limit = Presupuesto_model::busqueda_cantidad($busqueda);
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
                $datos['presupuestos'] = Presupuesto_model::buscar($busqueda, $campo, $orden, $offset, $limit);
                $datos['numero'] = Presupuesto_model::busqueda_cantidad($busqueda);
                
                $config = array();
                $config['base_url'] = base_url(). "admin/presupuesto/buscar/".$campo."/".$orden."/".$limit."/".$busqueda."/";
                $config['total_rows'] = Presupuesto_model::busqueda_cantidad($busqueda);
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
            $this->mostrar($this->pagina, $datos, $this->carpeta);
        }
        
        else{
            $busqueda = $this->input->post('buscador');
            
            $opciones = $this->seleccion(Presupuesto_model::busqueda_cantidad($busqueda));
            $datos['opciones'] = $opciones;

            if($this->input->post('cantidad') != ''){
                if($opciones[$this->input->post('cantidad')] == 'Todo'){
                    $limit = Presupuesto_model::numero();
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
            $datos['presupuestos'] = Presupuesto_model::buscar($busqueda, $campo, $orden, $offset, $limit);
            $datos['numero'] = Presupuesto_model::busqueda_cantidad($busqueda);
            
            $config = array();
            $config['base_url'] = base_url(). "admin/presupuesto/buscar/".$campo."/".$orden."/".$limit."/".$busqueda."/";
            $config['total_rows'] = Presupuesto_model::busqueda_cantidad($busqueda);
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
    
    
     public function borrar($codigo = ''){
        $presupuesto = new Presupuesto_model;       
         
        if($codigo != ''){ 
            if(Presupuesto_model::existe($codigo)){
                $presupuesto->datos($codigo);
                $codigo = urldecode($codigo); 

                $codigoArchivo = $presupuesto->codigoArchivo($codigo);  
                if(Archivo_model::existe($codigoArchivo)){ 
                    $archivo = new Archivo_model; 
                    $archivo->datos($codigoArchivo); 
                    $archivo->eliminar(); 
                }

                if($presupuesto->eliminar($codigo)){
                    redirect('admin/presupuesto');                
                } 
            }
        }
        else{
            if($this->input->post('checkbox') != ''){            
                $codigos = $this->input->post('checkbox');
                foreach($codigos as $codigo){
                    if(Presupuesto_model::existe($codigo)){
                        $presupuesto->datos($codigo);
                        $codigo = urldecode($codigo);

                        $codigoArchivo = $presupuesto->codigoArchivo($codigo);
                        if(Archivo_model::existe($codigoArchivo)){ 
                            $archivo = new Archivo_model;
                            $archivo->datos($codigoArchivo);
                            $archivo->eliminar();
                        }
                        $presupuesto->eliminar($codigo);
                    }
                }               
            }            
            redirect('admin/presupuesto');                
        }        
    }    
    
}


?>
