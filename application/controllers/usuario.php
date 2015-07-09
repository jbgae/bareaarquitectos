<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario extends MY_Controller{

    public function __construct() {
        parent:: __construct();
        
        $this->load->library('My_PHPMailer');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="alert alert-error"> <button type="button" class="close" data-dismiss="alert">&times;</button><h4>Error</h4>', '</div>');                $this->load->model('usuario_model');
        $this->load->model('captcha_model');
        
    }
    
    
    public function iniciarSesion(){ 
        $this->pagina = 'privado';
        
        if($this->uri->segment(1) == 'admin' || $this->uri->segment(1) == 'administrador')
            $user = 'administrador';
        else if($this->uri->segment(1) == 'empleado')
            $user = 'empleado';
        else
            $user = 'cliente';
            
        $this->carpeta = $user;
        $this->titulo = 'Iniciar sesión';        
        $this->javascript = array('jquery.validate.min','validarInicioSesion','tamanyo'); 
        
        if($user == 'administrador' || $user == 'empleado'){
            $this->estilo = 'login';
            $datos['imagen'] = $this->captcha_model->crear_captcha();
        }
        else{
            $this->estilo = 'clientes';
        }   
        
        $boton = array('name'=>'button', 'id'=>'boton_sesion', 'value'=>'Enviar', 'class'=>'btn btn-primary');
        $formularioSesion = array(
            'email'=>array(
                    'label' => array('accesskey'=>'E', 'name'=>'<u>E</u>mail'),
                    'input' => array('class'=>'email','name'=>'email','id'=>'email', 'maxlength'=>'50', 'size'=>'20', 'value'=> '','autofocus'=>'autofocus')
            ),
            'password'=>array(
                'label'=> array('accesskey'=>'D', 'name'=>'Passwor<u>d</u>'),
                'input'=>array( 'class'=>'password', 'name'=>'pass','id'=>'pass', 'maxlength'=>'20', 'size'=>'20',  'autocomplete'=>'off')
            )
        );
        
        $email = $this->input->post('email');
        $datos['boton'] = $boton;
        $datos['formulario'] =  $formularioSesion;
        $datos['formulario']['email']['input']['value'] = $email;
              
       
        if($user == 'administrador' || $user == 'empleado'){
            $datos['formulario']['captcha'] = array(
                'input' => array( 'class'=>'captcha', 'name'=>'captcha','id'=>'captcha', 'maxlength'=>'20', 'size'=>'20',  'autocomplete'=>'off')
            );
        }       
           
        if($this->_validarSesion($user)){                 
            $usuario = new Usuario_model;
            $usuario->datos($email);                             

            if(($user == $usuario->tipo())|| ($user == 'administrador' && $usuario->tipo() == 'admin')){
                $ultimoAcceso = $usuario->fechaUltimoAcceso();
                $act = array(
                    'Email' => $usuario->email(),
                    'NumeroIntentos' => 0,
                    'FechaUltimoAcceso' => date('Y/m/d H:i:s')
                );
                $usuario->actualizar($usuario->email(), $act);

                $datosUsuario = array(
                    'nombre'    => $usuario->nombre(),
                    'apellidos' => $usuario->primerApellido() .' '. $usuario->segundoApellido(),
                    'email'     => $usuario->email(),
                    'usuario'   => $usuario->tipo(),
                    'ultimoAcceso' => $ultimoAcceso,
                    'logged_in' => TRUE
                );                

                $this->session->set_userdata($datosUsuario);

                switch ($usuario->tipo()) {
                    case 'admin':
                            redirect("administrador/novedades");
                            break;
                    case 'empleado':
                            redirect("empleado/novedades");
                            break; 
                    case 'cliente':
                            redirect("cliente/inicio");
                            break;                             
                }
                 
            }
            else{
                
                $datos['mensaje'] = '<div class="alert alert-error">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h4>Error</h4>
                        No puedes acceder al sistema desde aquí.
                    </div>';
            }     
        }
        
        
        $this->mostrar($datos);
    }
    
    public function cerrarSesion(){
        $this->session->unset_userdata('ultimoAcceso');
        $this->session->unset_userdata('usuario');
        
        $this->session->sess_destroy();
        redirect('inicio');
    }
    
    
    public function borrar($email = ''){
        
        if($email != ''){ 
            if(Usuario_model::existe(urldecode($email))){ 
                $usuario = new Usuario_model;
                $usuario->email(urldecode($email));
                $usuario->eliminar();
            }
        }
        else{  
            if($this->input->post('checkbox') != ''){            
                $emails = $this->input->post('checkbox');
                
                foreach($emails as $email){
                    if(Usuario_model::existe(urldecode($email))){
                        $usuario = new Usuario_model;
                        $usuario->email($email);
                        $usuario->eliminar();
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

    

    
    
    public function formulario_registro(){
        $formulario_registro = array(           
            'nombre'=>array(
                    'label' => array('accesskey'=>'M', 'name'=>'No<u>m</u>bre'),
                    'input' => array('class'=>'nombre','name'=>'nombre','id'=>'nombre', 'maxlength'=>'60', 'size'=>'15',  'autofocus'=>'autofocus'),
                    'requerido'=>TRUE
            ),
            
            'apellidoPaterno'=>array(
                    'label' => array('accesskey'=>'L', 'name'=>'Primer ape<u>l</u>lido'),
                    'input' => array('class'=>'apellidoP','name'=>'primerApellido','id'=>'primerApellido', 'maxlength'=>'60', 'size'=>'15'),
                    'requerido'=>TRUE
            ),
                       
            'apellidoMaterno'=>array(
                    'label' => array('accesskey'=>'G', 'name'=>'Se<u>g</u>undo apellido'),
                    'input' => array('class'=>'apellidoM','name'=>'segundoApellido','id'=>'segundoApellido', 'maxlength'=>'60', 'size'=>'15'),
                    'requerido'=>TRUE
            ),
            
            'fechaNacimiento'=>array(
                    'label' => array('accesskey'=>'A', 'name'=>'Fech<u>a</u> de nacimiento'),
                    'input' => array('class'=>'fechaN','name'=>'fNacimiento','id'=>'fNacimiento', 'maxlength'=>'10', 'size'=>'15', 'placeholder'=>'mm/dd/aaaa'),
                    'requerido'=>TRUE
            ),
            
            'direccion'=>array(
                    'label' => array('accesskey'=>'', 'name'=>'Dirección'),
                    'input' => array('class'=>'direccion','name'=>'direccion','id'=>'direccion', 'maxlength'=>'60', 'size'=>'15'),
                    'requerido'=>FALSE
            ),
            
            'provincia'=>array(
                    'label' => array('accesskey'=>'', 'name'=>'Provincia'),
                    'input' => array('class'=>'provincia','name'=>'provincia','id'=>'provincia', 'maxlength'=>'60', 'size'=>'15'),
                    'requerido'=>FALSE
            ),
            
            'ciudad'=>array(
                    'label' => array('accesskey'=>'', 'name'=>'Ciudad'),
                    'input' => array('class'=>'ciudad','name'=>'ciudad','id'=>'ciudad', 'maxlength'=>'60', 'size'=>'15' ),
                    'requerido'=>FALSE
            ),
            
            'telefono'=>array(
                    'label' => array('accesskey'=>'', 'name'=>'Teléfono'),
                    'input' => array('class'=>'telefono','name'=>'telefono','id'=>'telefono', 'maxlength'=>'9', 'size'=>'10'),
                    'requerido'=>FALSE
            ),
            
            'email'=>array(
                    'label' => array('accesskey'=>'E', 'name'=>'<u>E</u>mail'),
                    'input' => array('class'=>'email','name'=>'email','id'=>'email', 'maxlength'=>'50', 'size'=>'15'),
                    'requerido'=>TRUE
            ),
                        
            'password'=>array(
                'label'=> array('accesskey'=>'D', 'name'=>'Passwor<u>d</u>'),
                'input'=>array( 'class'=>'password', 'name'=>'pass','id'=>'pass', 'maxlength'=>'20', 'size'=>'15',  'autocomplete'=>'off'),
                'requerido'=>TRUE
            ),
            
            'passconf'=>array(
                'label'=> array('accesskey'=>'F', 'name'=>'Con<u>f</u>irme el password'),
                'input'=>array( 'class'=>'passconf', 'name'=>'passconf','id'=>'passconf', 'maxlength'=>'20', 'size'=>'15',  'autocomplete'=>'off'),
                'requerido'=>TRUE
            ),            
           
        );
        
        return $formulario_registro;
    }
    
    
    public function boton_registro(){
        $boton_registro = array(
            'name'=>'button', 
            'id'=>'boton_registro', 
            'value'=>'Enviar', 
            'class'=>'btn btn-primary',
            'data-confirm'=>'¿Desea continuar?'
        );
        
        return $boton_registro;
    }
    
    public function autorizacion(){        
        if ($this->session->userdata('usuario') == 'admin' || $this->session->userdata('usuario') == 'empleado'){
            $id = str_replace('@', '', $this->session->userdata('email'));
            $id = str_replace('.', '', $id);
            $presence_data = array('nombre' => $this->session->userdata('nombre'). ' '.$this->session->userdata('apellidos'), 'id'=>$id);
            echo $this->pusher->presence_auth($_POST['channel_name'], $_POST['socket_id'], $this->session->userdata('email'), $presence_data);
        }
        else{
          header('', true, 403);
          echo( "Acceso prohibido" );
        }  
    }
        
    public function sesion(){
         echo json_encode($this->session->userdata('email'));
    }
    
    
    /*Reglas de validación del formulario de iniciar sesión*/
    private function _validarSesion($user){
        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[3]|valid_email|xss_clean|callback_existeUsuario|callback_confirmarPassword|callback_numeroIntentos|callback_usuarioValidado');
        $this->form_validation->set_rules('pass', 'Contraseña', 'trim|required|min_length[6]|xss_clean');
        if($user == 'administrador' || $user == 'empleado'){
            $this->form_validation->set_rules('captcha', 'Captcha', 'trim|required|min_length[6]|xss_clean|callback_verificarCaptcha');
        }
        
        $this->form_validation->set_message('existeUsuario', 'No existe ningún usuario con el email indicado.');
        $this->form_validation->set_message('usuarioValidado', 'El email introducido no esta validado.');
        $this->form_validation->set_message('numeroIntentos', 'La cuenta esta bloqueada temporalmente.');
        $this->form_validation->set_message('confirmarPassword', 'El email o la contraseña son incorrectas.');
        if($user == 'administrador' || $user == 'empleado'){
            $this->form_validation->set_message('verificarCaptcha', 'El captcha introducido no es correcto.');
        }
        $this->form_validation->set_message('required', '%s no puede estar vacio');
        $this->form_validation->set_message('min_length', '%s debe tener mínimo %s caracteres');
        $this->form_validation->set_message('valid_email', '%s no es válido');
        $this->form_validation->set_message('xss_clean', ' %s no es válido');
        
        return $this->form_validation->run();
    }
    
    /*Callbacks para validación de formulario de inicio de sesión */
    public function existeUsuario($email){ 
        $aux = FALSE;
        if(Usuario_model::existe($email))
            $aux = TRUE;
        return $aux;
    }
    
    public function usuarioValidado($email){
        $usuario =  new Usuario_model;
        return $usuario->validado($email);
    }
    
    public function numeroIntentos($email){
        $aux = FALSE;
        $usuario = new Usuario_model;
        
        $fecha_esp = str_replace("/", "-", $usuario->fechaUltimoIntento($email));
        $fecha1 =  strtotime($fecha_esp); 
        $fecha2 = strtotime(date('Y/m/d H:i:s'));
        $resultado = $fecha2 - $fecha1;
        
        $horas = $resultado / 60 / 60;
        
        if($usuario->numeroIntentos($email) <= 3){
            $aux =TRUE;
        }      
        else if($usuario->numeroIntentos($email) > 3 && $horas >= 2){
            $act = array('Email' => $email, 'NumeroIntentos' => 0);
            $usuario->actualizar($email, $act);
            $aux = TRUE;
        }
       
                    
        return $aux;        
    }
    
    public function confirmarPassword($email){
        $aux = $this->existeUsuario($email);
        if($aux){
            $usuario = new Usuario_model;
            if(md5($this->input->post('pass')) != $usuario->pass($email)){
                $act = array(
                    'Email' => $email,
                    'NumeroIntentos' => $usuario->numeroIntentos($email) + 1, 
                    'FechaUltimoIntento' => date('Y/m/d H:i:s')
                );
                $usuario->actualizar($email,$act);
                $aux = FALSE;    
            }     
        }
        return $aux;
    }
    
    public function verificarCaptcha($captcha){
        return $this->captcha_model->verificar_captcha($captcha);
    }
}

?>