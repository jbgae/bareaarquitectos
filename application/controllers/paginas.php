<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paginas extends MY_Controller{

    public function __construct() {
        parent:: __construct();
        $this->load->model('texto_model');
        $this->load->model('paginas_model'); 
        
        //$this->output->enable_profiler('TRUE'); 
    }
    
    public function accesibilidad(){
        $this->pagina = 'accesibilidad';
        $this->titulo = 'Accesibilidad';
        $this->estilo = 'accesibilidad';
        $this->javascript = 'tamanyo';

        
        $this->mostrar();
    }
    
    public function mapa(){
        $this->pagina = 'mapa web';
        $this->titulo = 'Mapa web';
        $this->estilo = $this->pagina;
        $this->javascript = 'tamanyo';
        $this->mostrar();
    }
    
    public function inicio(){
        $this->pagina = 'inicio';
        $this->titulo = $this->pagina;
        $this->estilo = array($this->pagina);
        $this->javascript = array('tamanyo', 'carousel');
        
        $this->mostrar();
        
    }
    
    public function estudio(){
        $this->pagina = 'doscolumnas';
        $this->titulo = 'estudio';
        $this->estilo = array($this->pagina);
        $this->javascript = 'tamanyo';
        $text = new Paginas_model;
        $datos['texto'] = $text->texto('inicio');
        $this->mostrar($datos);
        
    }
    
   
    public function contacto(){
        $this->pagina = 'contacto';           
        $this->titulo = 'contacto';
        $this->estilo = array('general', 'contacto');
        $this->javascript = array('jquery.validate.min','validarContacto','tamanyo');
              
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('My_PHPMAiler');
        $this->form_validation->set_error_delimiters('<div class="alert alert-error"> <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <h4>Error</h4>', '</div>');
        
        $datos['formulario'] = array(
            'nombre' => array(
                'label'=>array('accesskey'=>'B', 'name'=>'Nom<u>b</u>re'),
                'input'=>array('class' => 'nombre','id'=>'nombre', 'name' => 'nombre', 'maxlength' => '30', 'size' => '20', 'autofocus'=>'autofocus')
            ),
            'email' =>array(
                'label'=>array('accesskey'=>'E', 'name'=>'<u>E</u>mail'),
                'input'=>array('class' => 'email','id'=>'email', 'name' => 'email', 'maxlength' => '30', 'size' => '20')
            ),               
            'asunto' => array(
                'label'=>array('accesskey'=>'A', 'name'=>'<u>A</u>sunto'),
                'input'=>array('class' => 'asunto','id'=>'asunto', 'name' => 'asunto', 'maxlength' => '30', 'size' => '20')
            ),
            'comentario' => array(
                'label'=>array('accesskey'=>'M', 'name'=>'Co<u>m</u>entario'),
                'input'=>array('class' => 'comentario','id'=>'comentario', 'name' => 'comentario', 'maxlength' => '300')
            ),
            'politica' => array(
                'label'=>array('accesskey'=>'*', 'name'=>'He leído y acepto la <a href= "'.base_url().'politica" title="Aviso de la política de privacidad"> Política de privacidad (<u>*</u>)</a>'),
                'input'=>array('class' => 'politica','id'=>'politica', 'name' => 'politica', 'value'=>'acepted')
            )
                
        );
        $datos['boton'] = array('class'=>'btn btn-primary' ,'name' => 'button', 'id' => 'boton_contacto', 'value' => 'Enviar');
        
        $this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('asunto', 'Asunto', 'trim|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('comentario', 'Comentario', 'trim|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('politica', 'Política de privacidad', 'trim|required');
        
        $this->form_validation->set_message('required', 'El campo %s no puede estar vacio');
        $this->form_validation->set_message('min_legth', 'El campo %s debe tener mínmo 3 caracteres');
        $this->form_validation->set_message('valid_email', 'El campo %s no es válido');
        $this->form_validation->set_message('xss_clean', 'El campo %s no es válido');
        
        
        if($this->form_validation->run() == TRUE){

            $datosEmail = array(
                            'direccion' => strtolower($_POST['email']),
                            'nombre'    => ucwords(strtolower($_POST['nombre'])),
                            'asunto'    => ucwords(strtolower("Mensaje desde la aplicación web: " . $_POST['asunto'])),
                            'texto'     => $_POST['comentario'],
                            'destino'   => 'jabgae@gmail.com'
                          );
           
            if($this->my_phpmailer->Enviar($datosEmail)){                                
                $datos['mensaje'] = '<div class="alert alert-success span9">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h4>Éxito</h4>
                    El mensaje ha sido enviado correctamente. En breve nos pondremos en contacto con usted.
                    </div>';
            }
            else{
                $datos['mensaje'] = '<div class="alert alert-error span9">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h4>Error</h4>
                    No se ha podido enviar el comentario  en este momento, por favor inténtelo de nuevo 
                    más tarde
                    </div>';
            }            
        }
        $this->mostrar($datos);
    }
    
    public function rehabilitacion(){
        $this->pagina = 'trescolumnas';
        $this->titulo = 'rehabilitación';        
        $this->estilo = $this->pagina;
        $this->javascript = 'tamanyo';

        $text = new Paginas_model;
        $texto = $text->texto('rehabilitacion');
        $datos['texto_izq'] = $texto['izquierda'];
        $datos['texto_drcha'] = $texto['derecha'];
        
        $this->mostrar($datos);
    }
    
    public function obra(){
        $this->pagina = 'trescolumnas';
        $this->titulo = 'obra nueva';                
        $this->estilo = $this->pagina;
        $this->javascript = 'tamanyo';
        
        $text = new Paginas_model;
        $texto = $text->texto('obra');
        $datos['texto_izq'] = $texto['izquierda'];
        $datos['texto_drcha'] = $texto['derecha'];
        
        $this->mostrar($datos);
    }
    
    public function locales(){
        $this->pagina = 'trescolumnas';
        $this->titulo = 'locales';        
        $this->estilo = $this->pagina;
        $this->javascript = 'tamanyo';
        
        $text = new Paginas_model;
        $texto = $text->texto('locales');
        $datos['texto_izq'] = $texto['izquierda'];
        $datos['texto_drcha'] = $texto['derecha'];
        
        $this->mostrar($datos);
    }
    
    public function eficiencia(){
        $this->pagina = 'doscolumnas';
        $this->titulo = 'eficiencia energética';
        $this->estilo = $this->pagina;
        $this->javascript = 'tamanyo';
        
        $text = new Paginas_model;
        $datos['texto'] = $text->texto('eficiencia');
        
        $this->mostrar($datos);
    }
    
    public function informes(){
        $this->pagina = 'trescolumnas';                
        $this->estilo = $this->pagina;
        $this->titulo = 'informes';
        $this->javascript = array('comprobarJs','jquery.tinyscrollbar.min', 'scroll', 'tamanyo');
        
        $text = new Paginas_model;
        $texto = $text->texto('informes');
        $datos['texto_izq'] = $texto['izquierda'];
        $datos['texto_drcha'] = $texto['derecha'];
        
        $this->mostrar($datos);
    }
    
    public function subvenciones(){
        $this->pagina = 'doscolumnas';
        $this->titulo = 'gestión de subvenciones';        
        $this->estilo = $this->pagina;
        $this->javascript = 'tamanyo';
        
        $text = new Paginas_model;
        $datos['texto'] = $text->texto('subvenciones');
                
        $this->mostrar($datos);
    }
    
    public function politica(){
        $this->pagina = 'politica';
        $this->titulo = 'política de privacidad';        
        $this->estilo = 'ite';
        $this->javascript = 'tamanyo';
        
        $text = new Paginas_model;
        $datos['texto'] = $text->texto($this->pagina);
        
        $this->mostrar($datos);
    }
    
    public function gestion(){
        $this->pagina = 'doscolumnas';
        $this->titulo = 'gestión de proyectos';        
        $this->estilo = $this->pagina;
        $this->javascript = 'tamanyo';
        
        $text = new Paginas_model;
        $datos['texto'] = $text->texto('proyectos');
        
        $this->mostrar($datos);
    }
    
    public function llave(){
        $this->pagina = 'doscolumnas';        
        $this->titulo = 'Llave en mano';
        $this->estilo = $this->pagina;
        $this->javascript = array( 'tamanyo');
        
        $text = new Paginas_model;
        $datos['texto'] = $text->texto('llave');
        
        $this->mostrar($datos);
    }
    
    public function certificado(){
        $this->pagina = 'doscolumnas';       
        $this->titulo = 'certificado energético';        
        $this->estilo = $this->pagina;
        $this->javascript = array('tamanyo');
        
        $text = new Paginas_model;
        $datos['texto'] = $text->texto('cconertificados');
        
        $this->mostrar($datos);
    }
    
    public function ite(){
        $this->pagina = 'doscolumnas';
        $this->titulo ='ITE';               
        $this->estilo = $this->pagina;
        $this->javascript = 'tamanyo';
        
        $text = new Paginas_model;
        $datos['texto'] = $text->texto('ite');
        
        $this->mostrar($datos);
    }
    
     public function listar($campo = 'NombrePagina', $orden = 'asc', $limit='5', $offset = 0){
        $this->pagina = 'web';
        $this->carpeta = 'administrador';        
        $this->menu = 'menu_admin_paginas';
        $this->titulo = 'Paginas';
        $this->estilo = array($this->pagina, 'tablas');
        $this->javascript='marcar_checkbox'; 
         
        $this->permisos('admin');
        $this->load->library('form_validation');
        $this->load->library('pagination');   
        
        $datos['buscador'] = array('class' => 'search-query input-medium', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'autofocus'=>'autofocus');
        $datos['boton'] = array('class'=>'btn', 'id'=>'buscador', 'name'=>'button', 'value'=>'Buscar');
        $datos['borrar']= array('class'=>'btn btn-danger','id'=>'borrar', 'value'=>'Borrar selección','data-confirm'=>"¿Estás seguro?");
        
        $numero = Paginas_model::numero();
        
        if($numero == 0){
            $error = TRUE;
            $this->registrar($error);
        }
        else{
            $opciones = $this->seleccion($numero);
            $datos['opciones'] = $opciones;
            $datos['numero'] = $opciones;

            if($this->input->post('cantidad') != ''){
                if($opciones[$this->input->post('cantidad')] == 'Todo'){
                    $limit = $numero;
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
                    'NombrePagina' => 'Nombre',
                    'Posicion' => 'Posición',
                    'Texto' => 'Texto',
                    'Administrador' => 'Administrador',
            );
            
            
            $datos['paginas'] = Paginas_model::obtener($campo, $orden, $offset, $limit, true);
 
            $config = array();
            $config['base_url'] = base_url(). "admin/web/".$campo."/".$orden."/".$limit."/";
            $config['total_rows'] = $numero;
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
    
     public function modificar($nombre, $posicion){
        $this->pagina = 'editar web';         
        $this->titulo = 'Modificar pagina';
        $this->estilo = 'jquery-te-1.3.3';
        $this->javascript = array('editor', 'jquery-te-1.3.3.min');
        $this->carpeta = 'administrador';
        $this->menu = 'menu_admin_paginas';
        
        $this->permisos('admin');
        $this->load->library('form_validation');        
        $nombre = urldecode($nombre);
        
        
        $this->paginas = new Paginas_model;
        $page = $this->paginas->datos($nombre, $posicion);   
        
        if($nombre == 'rehabilitacion')
            $datos['nombrePagina'] = 'rehabilitación';
        else
            $datos['nombrePagina'] = $nombre;
        
        $datos['posicion'] = $posicion;
        
        $datos['formulario'] = array(            
            'texto' => array('class' => 'editor', 'id' => "texto-$nombre-$posicion", 'name'=>'texto', 'label' => 'Texto', 'value'=>$page[0]->Texto)
        );
        
        $datos['boton'] = array( 'class'=> 'btn btn-info', 'name'=>'button', 'id' => 'boton_noticia');
        
        $this->form_validation->set_rules('texto', 'Texto', 'trim|required|min_length[3]');
        
        $this->form_validation->set_message('required', 'El campo %s no puede estar vacio');
        $this->form_validation->set_message('min_legth', 'El campo %s debe tener mínmo 3 caracteres');
        $this->form_validation->set_message('xss_clean', 'El campo %s no es válido');
        
        if($this->form_validation->run() == TRUE){
           $texto = new Texto_model; 
           if($texto->actualizar($nombre, $posicion) && $this->paginas->actualizar($nombre)){
                $this->exito = 'La página se ha actualizado satisfactoriamente';
                $page = $this->paginas->datos($nombre, $posicion);
                $datos['formulario'] = array(            
                    'texto' => array('class' => 'editor', 'id' => 'texto', 'name'=>'texto', 'label' => 'Texto', 'value'=>$this->input->post('texto'))
                );
                                             
           }     
        }
        $this->mostrar($datos); 
    }
    
    
    public function buscar($campo = 'NombrePagina', $orden = 'asc', $limit='5', $busqueda = '', $offset = 0){
        $this->titulo ='Búsqueda';
        
        $this->load->library('form_validation');
        $this->load->library('pagination');
           
        if($this->uri->segment(1) == 'admin'){
            $this->permisos('admin');            
            $this->pagina = 'web';
            $this->carpeta = 'administrador';
            $this->menu = 'menu_admin_paginas';
            $this->estilo = array($this->pagina, 'tablas');
            $this->javascript=array('marcar_checkbox', 'redireccion');
            
            $datos['busqueda'] = TRUE;       
        
            if ($busqueda != '')
                $datos['buscador'] = array('class' => 'search-query input-medium', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'value'=>urldecode($busqueda), 'autofocus'=>'autofocus');
            else
                $datos['buscador'] = array('class' => 'search-query input-medium', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'value'=>$this->input->post('buscador'), 'autofocus'=>'autofocus');
            $datos['boton'] = array('class'=>'btn', 'id'=>'buscador', 'name'=>'button', 'value'=>'Buscar');
            $datos['borrar']= array('class'=>'btn btn-danger','id'=>'borrar', 'value'=>'Borrar selección','data-confirm'=>"¿Estás seguro?");
            $datos['fields'] = array(
                    'NombrePagina' => 'Nombre',
                    'Posicion' => 'Posición',
                    'Texto' => 'Texto',
                    'Administrador' => 'Administrador',
            );
        }
        else{ 
            $this->pagina = 'busqueda';
            $this->estilo = array($this->pagina);
            $this->javascript=array('tamanyo', 'confirmacion');
        }
        
                
        $this->form_validation->set_rules('buscador', 'Buscador', 'trim|required|xss_clean');
        $this->form_validation->set_message('required', '%s no puede estar vacio');
        $this->form_validation->set_message('xss_clean', ' %s no es una búsqueda válida');        
        
        if($this->form_validation->run() == FALSE){
            if($busqueda != ''){ 
                $busqueda = urldecode($busqueda);
                $opciones = $this->seleccion(Paginas_model::busqueda_cantidad($busqueda));
                $datos['opciones'] = $opciones;
                if($this->input->post('cantidad') != ''){
                    if($opciones[$this->input->post('cantidad')] == 'Todo'){
                        $limit = Paginas_model::busqueda_cantidad($busqueda);
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
                $datos['paginas'] = Paginas_model::buscar($busqueda, $campo, $orden, $offset, $limit, true);
                $datos['numero'] = Paginas_model::busqueda_cantidad($busqueda);
                
                $config = array();
                
                if($this->uri->segment(1) == 'admin'){
                    $config['base_url'] = base_url(). "admin/web/buscar/".$campo."/".$orden."/".$limit."/".$busqueda."/";
                }
                else{
                    $config['base_url'] = base_url(). "buscar/".$campo."/".$orden."/".$limit."/".$busqueda."/";
                }
                $config['total_rows'] = Paginas_model::busqueda_cantidad($busqueda);
                $config['per_page'] = $limit;
                if($this->uri->segment(1) == 'admin'){
                    $config['uri_segment'] = 8;
                }
                else{
                    $config['uri_segment'] = 6;                    
                }
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
            
            $opciones = $this->seleccion(Paginas_model::busqueda_cantidad($busqueda));
            $datos['opciones'] = $opciones;

            if($this->input->post('cantidad') != ''){
                if($opciones[$this->input->post('cantidad')] == 'Todo'){
                    $limit = Paginas_model::numero();
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
            $datos['paginas'] = Paginas_model::buscar($busqueda, $campo, $orden, $offset, $limit, true);
            $datos['numero'] = Paginas_model::busqueda_cantidad($busqueda);
            
            $config = array();
            if($this->uri->segment(1) == 'admin'){
                $config['base_url'] = base_url(). "admin/web/buscar/".$campo."/".$orden."/".$limit."/".$busqueda."/";
            }
            else{
                $config['base_url'] = base_url(). "buscar/".$campo."/".$orden."/".$limit."/".$busqueda."/";
            }
            $config['total_rows'] = Paginas_model::busqueda_cantidad($busqueda);
            $config['per_page'] = $limit;
            if($this->uri->segment(1) == 'admin'){
                $config['uri_segment'] = 7;
            }
            else{
                $config['uri_segment'] = 5;
            }
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
        if($this->uri->segment(1) == 'admin')
            $this->mostrar($datos);
        else    
            $this->mostrar($datos);
    }
    
    public function my404(){      
        $this->pagina = '404';
        $this->carpeta = '';
        $this->estilo = $this->pagina;
        $this->load->library('form_validation');

        $datos = '';        
        
        if($this->uri->segment(1)=='empleado'||$this->uri->segment(1) == 'admin' || $this->uri->segment(1) == 'administrador' || $this->input->post('buscador') != '' ){
            $this->carpeta = 'empleado';
            $datos['buscador'] = array('class' => 'search-query', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'autofocus'=>'autofocus');
            $datos['boton'] = array('class'=>'btn', 'id'=>'buscador', 'name'=>'button', 'value'=>'Buscar');
                   
        }
        elseif($this->uri->segment(1)=='cliente'){
            $this->carpeta = 'cliente';
        }
        
        $this->form_validation->set_rules('buscador', 'Buscador', 'trim|required|xss_clean');
        $this->form_validation->set_message('required', '%s no puede estar vacio');
        $this->form_validation->set_message('xss_clean', ' %s no es una búsqueda válida');        

        if($this->form_validation->run() == TRUE){
            $busqueda = $this->input->post('buscador');
            $limit = Paginas_model::busqueda_cantidad($busqueda);
            $offset = 0;
            $orden = 'asc';
            $campo = 'NombrePagina';

            $datos['paginas'] = Paginas_model::buscar($busqueda, $campo, $orden, $offset, $limit, true);
            $datos['numero'] = Paginas_model::busqueda_cantidad($busqueda);

        }
        
        $this->mostrar($datos);
    }
    
    public function sincronizar(){
        if(empty($_POST)){
            redirect('404');
        }
        else{
            $nombre = $_POST['id'];
            
            $pusher = array(
                'texto' => $_POST['texto'],
                'id'    => $_POST['id'],
                'usuario' => $this->session->userdata('email'),
                'empleados' => Usuario_model::admin()
            );
            $this->pusher->trigger('editor','sincronizacion',$pusher);
        }
    }
}    
?>
