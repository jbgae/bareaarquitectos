<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chat extends MY_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model('chat_model');
        $this->load->model('usuario_model');
        $this->load->model('empleado_model');
        
        $this->load->library('form_validation');
        
    }
    
    public function index(){ 

        $this->pagina = 'chat'; 
        $this->carpeta = 'empleado';
        $this->titulo = 'Chat';
        $this->estilo = array('chat');
        $this->javascript = array('chat');      
        
        if($this->uri->segment(1) == 'admin')
            $this->permisos('admin');
        else
            $this->permisos('empleado');
                
        $datos['formulario'] = array(
            'input' => array('id'=>'mensaje', 'name' => 'mensaje', 'autofocus'=>'autofocus', 'class'=>'span12')
        );

        $this->mostrar($datos);
    }
    
    public function mensaje(){
        
        $this->form_validation->set_rules('mensaje', 'Mensaje', 'trim|required|min_length[1]|xss_clean');
             
        $this->form_validation->set_message('required', '%s no puede estar vacio');
        $this->form_validation->set_message('min_length', '%s debe tener mínimo %s caracteres');
        $this->form_validation->set_message('xss_clean', ' %s no es válido');

        if($this->form_validation->run() == TRUE){ 
            $chat = new Chat_model;
            $chat->anyadir_mensaje();         
            
            $empleado = new Empleado_model;
            $foto = $empleado->foto($this->session->userdata('email'));
            if($foto == ''){
                $foto = base_url().'images/indice.jpeg';
            }
            
            $data = array(
                'fecha' =>' el '. date('d-m-Y') .' a las '.date('H:i:s').':',
                'foto' => $foto,
                'nombre' => htmlentities(strip_tags($this->session->userdata('nombre').' '.$this->session->userdata('apellidos'))),
                'mensaje' => htmlentities(strip_tags($this->input->post('mensaje'))),
                'email' => $this->session->userdata('email')
            );
	 
            $this->pusher->trigger('private-chat', 'enviar-mensaje', $data);
       }
   
    }
    
    public function obtener_mensaje(){
        if(!$this->input->is_ajax_request()){
            redirect('404');
        }
        else
            echo $this->_obtener_mensaje();
    }
    
    public function obtener_mensajes_nuevos(){
        if(!$this->input->is_ajax_request()){
            redirect('404');
        }
        else
            echo $this->_obtener_mensaje(TRUE);
    }

    
    private function _obtener_mensaje($nuevos = FALSE){
        $usuario = new Usuario_model;
        $fechaAcceso = $usuario->fechaUltimoAcceso($this->session->userdata('email'));
        if($nuevos)
            $mensajes = Chat_model::obtener_mensajes_nuevos($fechaAcceso, $this->session->userdata('email'));
        else
            $mensajes = Chat_model::obtener_mensajes($fechaAcceso);
       
        if(count($mensajes) > 0){
            $chat = '<ul>';
            foreach($mensajes as $mensaje){
                $empleado = new Empleado_model;
                $foto = $empleado->foto($mensaje->EmailEmpleado);
                if($foto == ''){
                    $foto = base_url().'images/indice.jpeg';
                }
                $span_class = ($this->session->userdata('email') == $mensaje->EmailEmpleado ) ? 'class="usuario_actual"' : '';
                $chat.= '<li>'.
                            '<img src="'. $foto. '" alt="foto usuario">'.
                            '<div class="encabezado">'.
                                '<span '.$span_class.'>'.
                                    $mensaje->Nombre .' '. $mensaje->ApellidoP.' '.$mensaje->ApellidoM .
                                '</span> el '.
                                date('d-m-Y',strtotime($mensaje->Fecha)) .' a las '.date('H:i:s',strtotime($mensaje->Fecha)).':'.
                            '</div>'.
                            '<div class="contenido">'.
                                $mensaje->Mensaje.
                            '</div>'.
                        '</li>';
            }
            $chat .= '</ul>';
            
            $resultado = array('estado'=>'ok', 'contenido'=> $chat);
            return json_encode($resultado);
        }
        else{
            $resultado = array('estado'=>'ok', 'contenido'=>'');
            return json_encode($resultado);
        }
    }
    
    public function autorizacion(){
        if ($this->session->userdata('usuario') == 'admin' || $this->session->userdata('usuario') == 'empleado'){
          echo $this->pusher->socket_auth($_POST['channel_name'], $_POST['socket_id']);
        }
        else{
          header('', true, 403);
          echo "Acceso prohibido";
        } 

    }
}
?>
