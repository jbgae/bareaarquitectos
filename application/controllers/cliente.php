<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'controllers/usuario.php');

class Cliente extends Usuario{
    
    public function __construct() {
        parent:: __construct();
        
        $this->load->library('cart');
        $this->load->model('cliente_model');
        $this->load->model('provincia_model', 'Provincia');
        $this->load->model('ciudad_model', 'Ciudad');
        $this->load->library('pagination');
        $this->load->library('form_validation');
        
    }
    
    
    private function _validar(){
        //Establecemos las reglas de validación del formulario
        $this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('primerApellido', 'Primer Apellido', 'trim|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('segundoApellido', 'Segundo Apellido', 'trim|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('fNacimiento', 'Fecha Nacimiento', 'callback_fecha_check');
        $this->form_validation->set_rules('direccion', 'Dirección', 'trim|min_length[3]|xss_clean');
        $this->form_validation->set_rules('provincia', 'Provincia', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('ciudad', 'Ciudad', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('telefono', 'Teléfono', 'trim|exact_length[9]|numeric|xss_clean');
        if($this->uri->segment(1) == 'registrar'){
            $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[3]|valid_email|xss_clean|is_unique[Usuario.Email]');
            $this->form_validation->set_rules('pass', 'Contraseña', 'trim|required|min_length[6]|xss_clean');
            $this->form_validation->set_rules('passconf', 'Contraseña', 'trim|required|min_length[6]|xss_clean|matches[pass]');
            $this->form_validation->set_rules('politica', 'Política de privacidad', 'trim|required');
        }
        else{
            $this->form_validation->set_rules('email', 'Email', 'trim|min_length[3]|valid_email|xss_clean');
            $this->form_validation->set_rules('pass', 'Contraseña', 'trim|min_length[6]|xss_clean');
            $this->form_validation->set_rules('passconf', 'Contraseña', 'trim|min_length[6]|xss_clean|matches[pass]');            
        }
        
        //Establecemos los mensajes de error del formulario
        $this->form_validation->set_message('required', '%s no puede estar vacio');
        $this->form_validation->set_message('min_length', '%s debe tener mínimo %s caracteres');
        $this->form_validation->set_message('valid_email', '%s no es válido');
        $this->form_validation->set_message('xss_clean', ' %s no es válido');
        $this->form_validation->set_message('exact_legth', '%s debe tener %s caracteres');
        $this->form_validation->set_message('numeric', '%s debe contener dígitos');
        $this->form_validation->set_message('is_unique', '%s ya esta registrado');
        $this->form_validation->set_message('matches', 'Las contraseñas no coinciden');
        
        return $this->form_validation->run();
    }


    public function datos(){
        $this->pagina = 'datos personales';
        $this->carpeta = 'cliente';
        $this->titulo = $this->pagina;
        $this->estilo = "datos%20personales";
        $this->javascript =  array('jquery-ui','confirmacion', 'tamanyo');
        
        $this->permisos('cliente');
                
        $cliente = new Cliente_model;        
        $datos['cliente'] = $cliente->datos($this->session->userdata('email'));
                               
        $this->mostrar($datos);
    }
    
    
    public function registrar(){ 
        $this->titulo = 'Registrar cliente';
        //Si nos encontramos en la página registrar
        if($this->uri->segment(1) == 'registrar'){
            $this->pagina ='registrar';
            $this->carpeta = 'cliente';          
            $this->estilo = array($this->pagina, 'jquery-ui');
            $this->javascript = array('ciudades', 'jquery.validate.min','validarCliente', 'tamanyo',);
            
            $datos['boton'] = $this->boton_registro();
            
        } 
        //Si nos encontramos en la sección del administrador para registrar un nuevo cliente
        else{
            $this->pagina = 'crear cliente';
            $this->carpeta = 'empleado';
            $this->menu = 'menu_empleado_clientes';
            $this->estilo = array('jquery-te-1.3.3', 'registrar', 'jquery-ui');
            $this->javascript = array('jquery-ui', 'fecha', 'ciudades', 'jquery.validate.min','validarCliente');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"> <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <h4>Error</h4>', '</div>');
            
            

            $datos['boton'] = array( 'class'=> 'btn btn-info', 'name'=>'button', 'id' => 'boton_cliente');
            if(Cliente_model::numero() == 0){
                $this->error = array(
                        'nivel'=> '2',    
                        'mensaje' => "<h4>Error.</h4> Actualmente no existe ningún cliente.  Si lo desea puede empezar a registrar clientes"
                    );
            }
            if($this->uri->segment(1) == 'admin'){
                $this->permisos('admin');
                $datos['user'] = 'admin';
                
            }
            else{
                $this->permisos('empleado');
                $datos['user'] = 'empleados';
            }
        }
        
        $datos['provincias'] = $this->Provincia->obtener();
        $datos['ciudades'] = $this->Ciudad->obtener();
        
        //Cargamos el formulario
        $datos['formulario'] = $this->formulario_registro();
        
        if($this->uri->segment(1) == 'registrar'){
            $politica = array(
                'label'=>array('accesskey'=>'*', 'name'=>'He leído y acepto la'. anchor("politica", "Política de privacidad (<u>*</u>)", array("title"=>"Aviso de la política de privacidad"))  ),
                'input'=>array('class' => 'politica','id'=>'politica', 'name' => 'politica', 'value'=>'accept'),
                'requerido'=>'TRUE'
            );

            array_push($datos['formulario'], $politica);
        }        
        
        if($this->_validar()){
            $cliente = new Cliente_model;
            if($cliente->inicializar()){
                $datosEmail = array(
                        'direccion' => 'barea@arquitectosdecadiz.com ',
                        'nombre'    => 'Barea Arquitectos',
                        'asunto'    => 'Confirmación proceso registro',
                        'texto'     => '<FONT FACE="arial" SIZE=4>
                                    Para emprezar a usar el portal <font color="green">BAREA ARQUITECTOS</font> usted necesita activar su dirección de e-mail. Se hace por razones de seguridad.  
                                    Por favor, siga el siguiente enlace o acceda al enlace copiando y pegándolo en el navegador web:<br> 
                                    <a href="' . base_url() . 'validar/' . urlencode($this->input->post('email')) . ' "> ' . base_url() . 'privado/validar/' . urlencode($this->input->post('email')) . '</a>
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
                        'destino' => strtolower($this->input->post('email'))
                );  
                //Enviamos email de confirmación
                $this->my_phpmailer->Enviar($datosEmail); 
                
                if($this->uri->segment(1) == 'registrar'){
                    $datos['mensaje'] = '<div class="alert alert-success span9"><button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h4>Bien</h4> Se ha enviado un email a su dirección de correo electrónico para confirmar el proceso de registro.</div>';
                }
                else{
                   $this->exito = 'El cliente ha sido registrado satisfactoriamente. En breves momentos recibirá un email de confirmación.';
                }
            }            
            else{
                if($this->uri->segment(1) == 'registrar'){
                    $datos['mensaje'] = '<div class="alert alert-error span9">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h4>Error</h4>
                    El proceso de registro no se ha realizado satisfactoriamente, por favor inténtelo de nuevo más tarde
                    </div>';
                   
                }
                else{
                    $this->error = array(
                            'nivel' => '2',
                            'mensaje' =>'No se ha podido completar el registro por favor intentelo de nuevo más tarde'
                        );
                }
            }           
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
        }
        
        $this->mostrar($datos);
        
    }
    
    
    public function registrarAjax(){
        
        if(!$this->input->is_ajax_request()){
            redirect('404');
        }
        else{            
            if(!$this->_validar()){
                $error = json_encode(validation_errors());
                $error = str_replace('"', "", $error);
                $error = str_replace('<\/span>\n', "", $error);                 
                echo '<div class="text-error">'.$error.'</div>';
            }
            else{
                $cliente = new Cliente_model;
                if($cliente->inicializar()){
                    $datosEmail = array(
                            'direccion' => 'barea@arquitectosdecadiz.com ',
                            'nombre'    => 'Barea Arquitectos',
                            'asunto'    => 'Confirmación proceso registro',
                            'texto'     => '<FONT FACE="arial" SIZE=4>
                                        Para emprezar a usar el portal <font color="green">BAREA ARQUITECTOS</font> usted necesita activar su dirección de e-mail. Se hace por razones de seguridad.  
                                        Por favor, siga el siguiente enlace o acceda al enlace copiando y pegándolo en el navegador web:<br> 
                                        <a href="' . base_url() . 'validar/' . urlencode($this->input->post('email')) . ' "> ' . base_url() . 'privado/validar/' . urlencode($this->input->post('email')) . '</a>
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
                            'destino' => strtolower($this->input->post('email'))
                    );  
                    //Enviamos email de confirmación
                    $this->my_phpmailer->Enviar($datosEmail);  
                    echo '<div class="alert alert-success span9">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h4>Éxito</h4>
                    Se ha enviado un email a su dirección de correo electrónico para confirmar el proceso de registro.
                    </div>';
                }
                else{
                   echo '<div class="alert alert-error span9">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h4>Error</h4>
                    El proceso de registro no se ha realizado satisfactoriamente, por favor inténtelo de nuevo más tarde
                    </div>';
                } 
               
            }
        }
    } 
    
    public function validar($email){ 
        $this->pagina = 'mensaje';
        $this->carpeta = 'cliente'; 
        $this->titulo = 'validar cliente';
        $this->estilo = $this->pagina;
        $this->javascript = array('tamanyo', 'confirmacion');
               
        $email  = urldecode($email);
                
        if(Usuario_model::existe($email)){
            $usuario = new Usuario_model();
            $usuario->datos($email);            
            $act = array('Validado' => 1);
            $usuario->actualizar($email,$act);
            
            $datos['mensaje'] = '<div class="alert alert-success span9">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h4>Éxito</h4>
                    Se ha validado correctamente su cuenta.
                    </div>';
        }
        else{
            $datos['mensaje'] = '<div class="alert alert-error span9">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h4>Error</h4>
                    El proceso de validación no se ha realizado satisfactoriamente, por favor inténtelo de nuevo más tarde
                    </div>';
        }   
        
        $this->mostrar($datos);
    }
    
    public function modificar($email = ''){ 
        $cliente = new Cliente_model;    
        $this->titulo = 'modificar cliente';
        if($this->uri->segment(2) == 'modificar'){
            $this->pagina = 'modificar datos';
            $this->carpeta = 'cliente';
            $this->estilo = array('registrar', 'jquery-ui');
            $this->javascript =  array('jquery.validate.min','validarCliente',  'ciudades', 'tamanyo');
                        
            $this->permisos('cliente');
            
            $datos['boton'] = $this->boton_registro();
            $cliente->datos($this->session->userdata('email'));
            $email = $this->session->userdata('email');
            
        }
        else{
            $this->pagina = 'crear cliente';
            $this->carpeta = 'empleado';
            $this->estilo = array('jquery-te-1.3.3', 'registrar', 'jquery-ui');
            $this->javascript = array('jquery-ui', 'fecha', 'ciudades', 'jquery.validate.min','validarClienteMod');
            $this->menu = 'menu_empleado_clientes';
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"> <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <h4>Error</h4>', '</div>');
            
            $datos['boton'] = array( 'class'=> 'btn btn-info', 'name'=>'button', 'id' => 'boton_cliente');
            $datos['actualizar'] = TRUE;
            $email = urldecode($email);
            if(Cliente_model::existe($email)){
                $datos['email'] = $email;
                $cliente->datos($email);
            }

            if($this->uri->segment(1) == 'admin'){
                $this->permisos('admin');
                $datos['user'] = 'admin';            
            }
            else{
                $this->permisos('empleado');
                $datos['user'] = 'empleados';
            }
        }
        
        if(Cliente_model::existe($cliente->email())){
            $datos['provincias'] = $this->Provincia->obtener();
            $datos['ciudades'] = $this->Ciudad->ciudades($cliente->provincia($email, TRUE));
            $formulario = $this->formulario_registro();

            $formulario['nombre']['input']['value'] = $cliente->nombre();
            $formulario['apellidoPaterno']['input']['value'] = $cliente->primerApellido();
            $formulario['apellidoMaterno']['input']['value'] = $cliente->segundoApellido();
            $formulario['fechaNacimiento']['input']['value'] =  $cliente->fechaNacimiento();
            $formulario['direccion']['input']['value'] = $cliente->direccion();
            $formulario['provincia']['input']['value'] = $cliente->provincia($email,TRUE);
            $formulario['ciudad']['input']['value'] = $cliente->ciudad($email, TRUE);
            $formulario['telefono']['input']['value'] = $cliente->telefono();
            $formulario['email']['input']['value'] = $cliente->email();

            $datos['formulario'] = $formulario;
            

            foreach($datos['formulario'] as &$input){ 
                if($input['input']['class'] != 'password' && $input['input']['class'] != 'passconf')
                    if($input['input']['value'] == 'Desconocido' || $input['input']['value'] == 'Desconocida' || $input['input']['value'] == 'Desconoci' ){                
                        $input['input']['value'] = '';
                    }
                    
            }

            if($this->_validar()){ 
                if($cliente->actualizar($email)){
                    
                    $cliente->datos($email);
                    $datos['ciudades'] = $this->Ciudad->ciudades($cliente->provincia($email, TRUE));
                    
                    $formulario['nombre']['input']['value'] = $cliente->nombre();
                    $formulario['apellidoPaterno']['input']['value'] = $cliente->primerApellido();
                    $formulario['apellidoMaterno']['input']['value'] = $cliente->segundoApellido();
                    $formulario['fechaNacimiento']['input']['value'] =  $cliente->fechaNacimiento();
                    $formulario['direccion']['input']['value'] = $cliente->direccion();
                    $formulario['provincia']['input']['value'] = $cliente->provincia($email, TRUE);
                    $formulario['ciudad']['input']['value'] = $cliente->ciudad($email, TRUE);
                    $formulario['telefono']['input']['value'] = $cliente->telefono();
                    $formulario['email']['input']['value'] = $cliente->email();

                    $datos['formulario'] = $formulario;
                    foreach($datos['formulario'] as &$input){ 
                        if($input['input']['class'] != 'password' && $input['input']['class'] != 'passconf')
                            if($input['input']['value'] == 'Desconocido' || $input['input']['value'] == 'Desconocida'){                
                                $input['input']['value'] = '';
                            }
                    }

                    if($this->uri->segment(3) == 'modificar'){    
                        $datos['mensaje'] = '<div class="alert alert-success span9">
                                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                                            <h4>Ok.</h4> Sus datos personales se han actualizado correctamente.</div>';
                    }
                    else{
                       $this->exito = 'Los datos del cliente han sido modificados satisfactoriamente.';
                    }
                }    
                else{
                    if($this->uri->segment(3) == 'modificar'){
                        $datos['mensaje'] = '<div class="alert alert-error span9">
                                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                                            <h4>Error.</h4> Sus datos personales no se han actualizado correctamente.</div>';
                    }
                    else{
                        $this->error = array(
                                'nivel' =>'2',
                                 'mensaje' =>'No se ha podido completar el registro por favor intentelo de nuevo más tarde'
                            );
                    }
                }    
            }
            
        }
        else{
            $this->error = array( 
                'nivel' => '1',
                'mensaje' => 'El cliente indicado no existe'
            );
            
        }
        $this->mostrar($datos);
    }    
    
    
    public function restablecer(){
        $this->pagina ='restablecer';
        $this->carpeta = 'cliente';
        $this->titulo = 'Restablecer password';
        $this->estilo = 'captcha';
        $this->javascript = array('jquery.validate.min','validarCaptcha', 'tamanyo');
        
        $datos['imagen'] = $this->captcha_model->crear_captcha();
        $datos['formulario'] = array(
            'email' => array(
                'label'=>array('accesskey'=>'E', 'name'=>'<u>E</u>mail'),
                'input'=>array('name' => 'email', 'id' => 'email', 'maxlength' => '30', 'size'=>'20','autofocus'=>'autofocus')
            ),
            'captcha' => array(
                'label'=>array('accesskey'=>'H', 'name'=>'Captc<u>h</u>a'),
                'input'=>array('name' => 'captcha', 'id'=>'captcha', 'maxlength' =>'30', 'size'=>'20')
            ),
        );
        $datos['boton'] = array( 'name' => 'button', 'id' => 'boton_restablecer', 'value' => 'Enviar', 'class' => 'btn btn-primary');                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
        
        
        
        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[3]|valid_email|xss_clean');
        $this->form_validation->set_rules('captcha', 'Captcha', 'trim|required|xss_clean');
        
        $this->form_validation->set_message('required', '%s no puede estar vacio');
        $this->form_validation->set_message('min_length', '%s debe tener mínimo %s caracteres');
        $this->form_validation->set_message('valid_email', '%s no es válido');
        $this->form_validation->set_message('xss_clean', ' %s no es válido');
        
        if($this->form_validation->run() == TRUE){
            if(!(Cliente_model::existe($this->input->post('email')))){
                $datos['errorUsuario'] = 'El email introducido no existe';
            }
            elseif(!($this->captcha_model->verificar_captcha($this->input->post('captcha')))){
                $datos['errorCaptcha'] = 'El captcha introducido no es valido';
            }
            else{
                $cliente = new Cliente_model;
                if($cliente->validado($this->input->post('email'))){
                    //Generamos nueva contraseña 
                    $str = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
                    $cad = "";
                    for($i=0;$i<7;$i++) {
                        $cad .= substr($str,rand(0,62),1);
                    }
                    $pass = array('Pass' => md5($cad));

                    $cliente->actualizar($this->input->post('email'),$pass);

                    //Creamos los datos del email y lo mandamos
                    $datosEmail = array(
                            'direccion' => 'barea@arquitectosdecadiz.com ',
                            'nombre'    => 'Barea Arquitectos',
                            'asunto'    => 'Barea Arquitectos: Modificación Contraseña',
                            'texto'     => '<FONT FACE="arial" SIZE=4>
                                        La nueva contraseña es:' . $cad . 
                                        '</FONT>
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
                            'destino' => strtolower($this->input->post('email'))
                    );  
                    $this->my_phpmailer->Enviar($datosEmail); 

                    $datos['mensaje'] = "<div class = 'valido'>Se le ha enviado una nueva contraseña a su dirección de correo electrónico</div>"; 
                }
                else{
                    $datos['errorUsuario'] = 'El email introducido no está validado';
                }
            }
            
        }
        $this->mostrar($datos);
    }
    
    
    public function inicio(){
        $this->permisos('cliente');
        $this->pagina = 'inicio';
        $this->carpeta = 'cliente';        
        $this->titulo = 'inicio';
        $this->estilo = 'clientes';
        $this->javascript = array('confirmacion','menu_cliente', 'jquery-ui', 'tamanyo');

        $this->mostrar();
    }
    
    
    public function cerrar(){
        $this->permisos('cliente');
        $this->cerrarSesion();
    }
    
    
    public function listar($campo = 'Nombre', $orden = 'asc', $limit='5', $offset = 0){
        $this->pagina = 'clientes';
        $this->carpeta = 'empleado';
        $this->menu = 'menu_empleado_clientes';
        $this->titulo = 'clientes';
        $this->estilo = 'tablas';
        $this->javascript=array('marcar_checkbox', 'confirmacion');
        
        if($this->uri->segment(1) == 'admin'){
            $this->permisos('admin');
            $datos['user'] = 'admin';            
        }    
        elseif($this->uri->segment(1) == 'empleados'){
            $this->permisos('empleado');
            $datos['user'] = 'empleados'; 
        }   
        
        $datos['buscador'] = array('class' => 'search-query input-medium', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'autofocus'=>'autofocus');
        $datos['boton'] = array('class'=>'btn', 'id'=>'buscador', 'name'=>'button', 'value'=>'Buscar');
        $datos['borrar']= array('class'=>'btn btn-danger','id'=>'borrar', 'value'=>'Borrar selección','data-confirm'=>"¿Estás seguro?");
                
        if(Cliente_model::numero() == 0){
            $this->registrar();
        }
        else{
            $num  = Cliente_model::numero();
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
                    'Email' => 'Email',
                    'FechaAltaSistema' => 'Alta',
                    'FechaUltimoAcceso' => 'Último acceso',
                    'Presupuestos' => 'Presupuestos',
                    'Proyectos' => 'Proyectos'
            );
            
            
            $clientes = Cliente_model::obtener($campo, $orden, $offset, $limit);
                      
            $datos['clientes'] = $clientes;
                        
            
            $config = array();
            if($this->uri->segment(1) == 'admin'){
                $config['base_url'] = base_url(). "admin/clientes/".$campo."/".$orden."/".$limit."/";
            }
            else if($this->uri->segment(1) == 'empleados'){
                $config['base_url'] = base_url(). "empleados/clientes/".$campo."/".$orden."/".$limit."/";
            }
            $config['total_rows'] = $num;
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
        $this->pagina = 'clientes';
        $this->carpeta = 'empleado';
        $this->menu = 'menu_empleado_clientes';
        $this->titulo = 'buscar cliente';
        $this->javascript = array('marcar_checkbox', 'confirmacion', 'redireccion');
        $this->estilo = array($this->pagina, 'tablas');
        
        if($this->uri->segment(1) == 'admin'){
            $this->permisos('admin');
            $datos['user'] = 'admin';            
        }
        
        else{
            $this->permisos('empleado');
            $datos['user'] = 'empleados';            
        }
        
        $datos['busqueda'] = TRUE;        
        

        if ($busqueda != '')
            $datos['buscador'] = array('class' => 'search-query input-medium', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'value'=>urldecode($busqueda), 'autofocus'=>'autofocus');
        else
            $datos['buscador'] = array('class' => 'search-query input-medium', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'value'=>$this->input->post('buscador'), 'autofocus'=>'autofocus');
        $datos['boton'] = array('class'=>'btn', 'id'=>'buscador', 'name'=>'button', 'value'=>'Buscar');
        $datos['borrar']= array('class'=>'btn btn-danger','id'=>'borrar', 'value'=>'Borrar selección','data-confirm'=>"¿Estás seguro?");
        $datos['fields'] = array(
                    'Nombre' => 'Nombre',
                    'Email' => 'Email',
                    'FechaAltaSistema' => 'Alta',
                    'FechaUltimoAcceso' => 'Último acceso',
                    'Presupuestos' => 'Presupuestos',
                    'Proyectos' => 'Proyectos'
            );
        
        $this->form_validation->set_rules('buscador', 'Buscador', 'trim|required|xss_clean');
        $this->form_validation->set_message('required', '%s no puede estar vacio');
        $this->form_validation->set_message('xss_clean', ' %s no es una búsqueda válida');        
        
        if($this->form_validation->run() == FALSE){
            if($busqueda != ''){ 
                $busqueda = urldecode($busqueda);
                $opciones = $this->seleccion(Cliente_model::busqueda_cantidad($busqueda));
                $datos['opciones'] = $opciones;
                if($this->input->post('cantidad') != ''){
                    if($opciones[$this->input->post('cantidad')] == 'Todo'){
                        $limit = Cliente_model::busqueda_cantidad($busqueda);
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
                $datos['clientes'] = Cliente_model::buscar($busqueda, $campo, $orden, $offset, $limit);
                $datos['numero'] = Cliente_model::busqueda_cantidad($busqueda);
                
                $config = array();
                
                if($this->uri->segment(1) == 'admin'){
                    $config['base_url'] = base_url(). "admin/clientes/buscar/".$campo."/".$orden."/".$limit."/".$busqueda."/";
                }
                else{
                    $config['base_url'] = base_url(). "empleados/clientes/buscar/".$campo."/".$orden."/".$limit."/".$busqueda."/";
                }
                $config['total_rows'] = Cliente_model::busqueda_cantidad($busqueda);
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
            
            $opciones = $this->seleccion(Cliente_model::busqueda_cantidad($busqueda));
            $datos['opciones'] = $opciones;

            if($this->input->post('cantidad') != ''){
                if($opciones[$this->input->post('cantidad')] == 'Todo'){
                    $limit = Cliente_model::numero();
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
            $datos['clientes'] = Cliente_model::buscar($busqueda, $campo, $orden, $offset, $limit);
            $datos['numero'] = Cliente_model::busqueda_cantidad($busqueda);
            
            $config = array();
            if($this->uri->segment(1) == 'admin')
                $config['base_url'] = base_url(). "admin/clientes/buscar/".$campo."/".$orden."/".$limit."/".$busqueda."/";
            else
                $config['base_url'] = base_url(). "empleados/clientes/buscar/".$campo."/".$orden."/".$limit."/".$busqueda."/";
            
            $config['total_rows'] = Cliente_model::busqueda_cantidad($busqueda);
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

    
}

?>
