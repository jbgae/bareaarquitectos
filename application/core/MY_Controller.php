<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller{

    var $pagina = '';
    var $titulo = '';
    var $estilo = '';
    var $javascript = '';
    var $carpeta = '';
    var $menu = '';
    var $submenu = '';
    var $error = '';
    var $exito = '';
    
    public function __construct() {
        parent:: __construct();
        $this->load->model('presupuesto_model');
        $this->load->model('tarea_model');
        $this->load->model('respuesta_model');
        $this->load->model('archivo_model');
        $this->load->model('notas_model');
        $this->load->library('session');
        $this->load->library('pusher');
        $this->load->dbutil();
    }
    
    
    public function mostrar($datos = ''){
        if($this->_comprobarBD()){
            $this->load->view('plantillas/mantenimiento.php');
        }
        
        else{
            $cabecera = array(
                    'titulo'    =>  $this->titulo,
                    'estilo'    =>  $this->estilo,
                    'nombre'    =>  $this->session->userdata('nombre') . ' ' . $this->session->userdata('apellidos'),
                    'usuario'   =>  $this->session->userdata('usuario'),
                    'carpeta'   =>  $this->carpeta
            );

            if($this->carpeta == '' || $this->carpeta == 'cliente'){

                if($this->javascript != ''){
                    $cabecera['javascript'] = $this->javascript;
                }
                $this->load->view('plantillas/cabecera_paginas.php', $cabecera);
                $this->load->view('plantillas/header_paginas.php', $cabecera);

                if($this->carpeta == 'cliente'){
                    $ruta = "application/views/$this->carpeta/$this->pagina.php";
                    if(!file_exists($ruta)){
                        show_404();
                    }
                    $noMenu = array('privado','restablecer','registrar', 'mensaje');
                    if(!in_array($this->pagina, $noMenu)){
                        $this->load->view('plantillas/submenu_cliente.php');
                        $this->load->view('plantillas/carro.php');

                    }
                    $this->load->view("$this->carpeta/$this->pagina", $datos);
                }
                else{
                    $ruta = "application/views/$this->pagina.php";
                    if(!file_exists($ruta)){
                        show_404();
                    }
                    else{
                        $noMenu = array('accesibilidad','busqueda','inicio', 'proyectos', 'noticias','noticia', 'contacto', 'politica','404');
                        if(!in_array($this->pagina, $noMenu))
                            $this->load->view('plantillas/submenu_pagina.php');

                        $this->load->view($this->pagina, $datos);
                    }

                }
                $this->load->view('plantillas/pie_paginas.php', $datos);

            }
            else{
                $ruta = "application/views/$this->carpeta/$this->pagina.php";
                if(!file_exists($ruta)){
                     show_404();
                }
                else{
                    if($this->carpeta == 'administrador'|| $this->carpeta == 'empleado'){ 
                        if($this->javascript != ''){
                            if(is_array($this->javascript)){
                                array_push($this->javascript, 'notificaciones');
                                array_push($this->javascript, 'notificaciones-empleado');
                                array_push($this->javascript, 'init');                                
                                $cabecera['javascript'] = $this->javascript;
                            }
                            else{
                                $cabecera['javascript'] = array($this->javascript, 'notificaciones','notificaciones-empleado', 'init');
                            }
                        }
                        else{
                            $cabecera['javascript'] = array('notificaciones','notificaciones-empleado','init');
                        }
                        $this->load->view('plantillas/cabecera_paginas.php', $cabecera);
                        if($this->pagina != 'privado'){
                            $this->load->view('plantillas/header_backend.php', $datos);
                            if($this->session->userdata('usuario') == 'admin'){
                                $this->load->view('plantillas/menu_lateral_admin.php');
                            }
                            else if($this->session->userdata('usuario') == 'empleado'){
                                $this->load->view('plantillas/menu_lateral_empleado');
                            }
                        }

                        if(is_array($this->error) && $this->error['nivel'] == '1'){                        
                            $this->load->view('plantillas/error_backend', $this->error['mensaje']);
                        }
                        else{
                            if($this->pagina != 'privado'){                
                                if($this->menu != '')
                                    $this->load->view('plantillas/'.$this->menu);                                

                                $this->load->view('plantillas/content');

                                if($this->submenu != '')
                                    $this->load->view('plantillas/'.$this->submenu);

                                if(is_array($this->error) && $this->error['nivel'] != '1'){
                                    $this->load->view('plantillas/errores_backend', $this->error['mensaje']);
                                }
                                if($this->exito != ''){
                                    $this->load->view('plantillas/exitos_backend', $this->exito);
                                }

                            }

                            $this->load->view("$this->carpeta/$this->pagina", $datos);
                            $this->load->view('plantillas/proyecto');

                        }
                    }
                }
            }
        }
    }
    
    
    public function permisos($user){
        if($this->session->userdata('logged_in') == TRUE){ 
            if($user != $this->session->userdata('usuario')){ 
                if($this->session->userdata('usuario') == 'admin'){
                    redirect('admin/novedades'); 
                }
                if($this->session->userdata('usuario') == 'empleado'){
                    redirect('empleados/novedades');
                }
                if($this->session->userdata('usuario') == 'cliente'){
                    redirect('cliente/inicio');
                }
            }
        }
        else{
            redirect('privado');
        }
    }
    
    public function seleccion($cantidad){
        $aux = 0;
        $opciones = array();
        while($cantidad > 5){
            $cantidad  = $cantidad - 5;
            $aux = $aux + 5;
            $opciones[] = $aux;
        }
        array_push($opciones, 'Todo');
        return $opciones;
    }

    
    private function _comprobarBD(){
        $aux = FALSE;        

        $tablas   = array('Provincia', 'Ciudad', 'Consultor','Usuario', 
            'Empresa','ci_sessions', 'captcha','Constructora', 'Empleado',
            'Evento','Proveedor','Administrador','Chat','Noticia','Pagina',
            'Presupuesto','LineaPresupuesto','Proyecto','ProyectoEmpleados',
            'Tarea', 'Texto','Respuesta','Archivo','Material', 'EmpleadoTarea', 
            'Nota', 'NotaEmpleados','Empresas', 'Notas', 'Noticias', 'Presupuestos',
            'Proyectos','Respuestas', 'Usuarios', 'Archivos', 'Constructoras',
            'Empleados','Materiales', 'NotasEmpleados', 'Paginas', 'Proveedores',                     
            'ProyectosEmpleados', 'Tareas', 'Consultores', 'Clientes', 'Chat_mensajes'
        );
        
        if ($this->dbutil->database_exists('EstudioArquitectura')){
            foreach($tablas as $tabla){
                if(!$aux){
                    if(!$this->db->table_exists($tabla)){
                        $aux = TRUE;
                        log_message("debug", "La tabla $tabla no se encuentra disponible.");
                    }
                }
            }
        }
        else{
            log_message("debug", "La base de datos no existe.");
        }
        
        return $aux;
    }
    
   
}

?>
