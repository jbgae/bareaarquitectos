<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'controllers/empleado.php');

class Admin extends Empleado{
    
    public function __construct() {
        parent:: __construct();
        
        
        $this->load->model('evento_model');
        $this->load->model('presupuesto_model');
        $this->load->model('chat_model');
        $this->load->model('cliente_model');
        $this->load->model('tarea_model');
        $this->load->model('respuesta_model');
        $this->load->model('archivo_model');
        $this->load->model('notas_model');
        $this->load->model('noticias_model', 'Noticias');
        
    }
    
    public function novedades(){
        $this->permisos('admin');
        $this->pagina  = 'novedades';
        $this->titulo  = 'novedades';
        $this->estilo  = array('backend', 'novedades');
        $this->carpeta = 'administrador';  
        $this->menu    = 'menu_admin_general';
        $this->submenu = '';
        
        $datos['novedades']['presupuestos'] = Presupuesto_model::numero_abiertos();
        if($datos['novedades']['presupuestos'] != 0)
            $datos['presupuestos'] = Presupuesto_model::abiertos(); 
        
        $datos['novedades']['proyectos'] = Presupuesto_model::numero_aceptados();
        if($datos['novedades']['proyectos'] != 0)
            $datos['proyectos'] = Presupuesto_model::aceptados();
        
        
        $datos['novedades']['tareas'] = Tarea_model::numeroTareasNuevas($this->session->userdata('email'), $this->session->userdata('ultimoAcceso'));
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

        $datos['eventos'] = Evento_model::eventosFuturos($this->session->userdata('email'));
        $datos['clientes'] = Cliente_model::recientes(3);
        $datos['noticias'] = $this->Noticias->noticias_recientes(3);

        $this->mostrar($datos);
    }
    

    public function notificaciones(){
        if(!$this->input->is_ajax_request()){
            redirect('404');
        }
        else{
            $notificaciones = array();

            $notificaciones['presupuestos'] = Presupuesto_model::numero_abiertos();
            $evento = new Evento_model;
            $notificaciones['eventos'] = count($evento->evento(date('Y-m-d'), $this->session->userdata('email'))); 
            $notificaciones['proyectos'] = Presupuesto_model::numero_aceptados();
            $notificaciones['tareas'] = Tarea_model::numeroTareasNuevas($this->session->userdata('email'), $this->session->userdata('ultimoAcceso'));
            $notificaciones['respuestas'] = Respuesta_model::numeroRespuestasNuevas($this->session->userdata('email'),$this->session->userdata('ultimoAcceso'));
            $notificaciones['notas'] = Notas_model::numeroNotasNuevas($this->session->userdata('email'), $this->session->userdata('ultimoAcceso'));
            $notificaciones['archivos'] = Archivo_model::numeroArchivosNuevos($this->session->userdata('email'),$this->session->userdata('ultimoAcceso'));
            $notificaciones['novedades'] = $notificaciones['presupuestos'] + $notificaciones['proyectos'] + $notificaciones['tareas'] + $notificaciones['respuestas'] + $notificaciones['notas'] +$notificaciones['archivos'];
            $notificaciones['chat'] = Chat_model::numero_mensajes_nuevos($this->session->userdata('ultimoAcceso'), $this->session->userdata('email'));
            
            echo json_encode($notificaciones);
        }
    }
    
    public function autorizacion(){
        if ($this->session->userdata('usuario') == 'admin'){
          echo $this->pusher->socket_auth($_POST['channel_name'], $_POST['socket_id']);
        }
        else{
          header('', true, 403);
          echo "Acceso prohibido";
        } 

    }
    
   
   
    
}

?>
