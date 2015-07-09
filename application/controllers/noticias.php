<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Noticias extends MY_Controller{

    public function __construct() {
        parent:: __construct();
        $this->load->model('noticias_model');
        $this->load->model('usuario_model');
        $this->load->library('pagination');
        $this->load->library('form_validation');
        
    }
    
    public function registrar($error=''){

        $this->permisos('admin');
        $this->form_validation->set_error_delimiters('<div class="alert alert-error"> <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <h4>Error</h4>', '</div>');
        $this->pagina = 'crear noticia';
        $this->carpeta = 'administrador';
        $this->titulo = 'Crear noticias';
        $this->estilo = array('jquery-te-1.3.3', 'crear noticia', 'jquery-ui');
        $this->javascript = array('jquery.validate.min','validarNoticia','editor', 'jquery-te-1.3.3.min','validarNoticia');
        $this->menu = 'menu_admin_noticias';
        
        $datos['formulario'] = array(
            'titulo' => array('class'=>'input-xlarge', 'id'=> 'titulo' ,'name'=>'titulo', 'label' => 'titulo', 'maxlength'=> '150', 'type' => 'text', 'value' => $this->input->post('titulo'), 'autofocus'=> 'autofocus'),
            'contenido' => array('class' => 'editor', 'id' => 'contenido', 'name'=>'contenido', 'label' => 'contenido', 'value' => $this->input->post('contenido'))
        );
        
        $datos['boton'] = array( 'class'=> 'btn btn-info', 'name'=>'button', 'id' => 'boton_noticia');
   
        if($error != ''){
            $datos['error'] = "<h4>Error.</h4> Actualmente no existe ninguna noticia.
                Si lo desea puede empezar a registrar noticias";
        }
        
        $this->form_validation->set_rules('titulo', 'Titulo', 'trim|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('contenido', 'Contenido', 'trim|required|min_length[3]');
        
        $this->form_validation->set_message('required', 'El campo %s no puede estar vacio');
        $this->form_validation->set_message('min_legth', 'El campo %s debe tener mínmo 3 caracteres');
        $this->form_validation->set_message('xss_clean', 'El campo %s no es válido');       
        
        
        if($this->form_validation->run() == TRUE){
            $noticia = new Noticias_model;
            if($noticia->inicializar()){
               $this->exito = 'La noticia ha sido registrada satisfactoriamente.';               
            }
            else{
                $this->error = array(
                    'nivel' => '2',
                    'mensaje'=>'No se ha podido registrar la noticia, por favor inténtelo de nuevo más tarde'
                    );
            } 
        }

        $this->mostrar($datos);
    }
    

    public function listar($campo = 'FechaCreacion', $orden = 'desc', $limit='5', $offset = 0){
        if($this->uri->segment(1) == 'admin'){
            $this->permisos('admin');

            $this->pagina = 'noticias';
            $this->carpeta = 'administrador';

            $this->titulo = 'Noticias';
            $this->menu = 'menu_admin_noticias';
            $this->estilo = array($this->pagina, 'tablas');
            $this->javascript=array('marcar_checkbox', 'confirmacion');
            $datos['buscador'] = array('class' => 'search-query input-medium', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'autofocus'=>'autofocus');
            $datos['boton'] = array('class'=>'btn', 'id'=>'buscador', 'name'=>'button', 'value'=>'Buscar');
            $datos['borrar']= array('class'=>'btn btn-danger','id'=>'borrar', 'value'=>'Borrar selección','data-confirm'=>"¿Estás seguro?");

            if(Noticias_model::numero() == 0){
                $error = TRUE;
                $this->registrar($error);
            }
            else{
                $opciones = $this->seleccion(Noticias_model::numero());
                $datos['opciones'] = $opciones;
                $datos['numero'] = $opciones;

                if($this->input->post('cantidad') != ''){
                    if($opciones[$this->input->post('cantidad')] == 'Todo'){
                        $limit = Noticias_model::numero();
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
                        'Titulo' => 'Titulo',
                        'Contenido' => 'Contenido',
                        'FechaCreacion' => 'Fecha',
                        'Escritor' => 'Escritor',
                );

                
                $datos['noticias'] = Noticias_model::obtener($campo, $orden, $offset, $limit, true);

                $config = array();
                $config['base_url'] = base_url(). "admin/noticias/".$campo."/".$orden."/".$limit."/";
                $config['total_rows'] = Noticias_model::numero();
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
        else if($this->uri->segment(1) == 'noticias'){
            $this->pagina = 'noticias';
            $this->titulo = $this->pagina;
            $this->estilo = $this->pagina;
            $this->javascript = 'tamanyo';

            if(Noticias_model::numero() == 0){
                $this->pagina = 'mensaje';            
                $datos['mensaje'] = 'Actualmente no existe ninguna noticia disponible.';            
            }
            else{
                $offset = $this->uri->segment(2);
               // $limit = 2;

                $datos['noticias'] =  Noticias_model::obtener($campo, $orden, $offset, $limit, true);
                
                $config = array();
                $config['base_url'] = base_url(). "noticias";
                $config['total_rows'] = Noticias_model::numero();
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
            }           

            $this->mostrar($datos);
        }
    }
    
        
    public function noticia($id){
        $this->pagina = 'noticia';
        
        $this->estilo = $this->pagina;
        $this->javascript = array('tamanyo', 'confirmacion');
        $noticia = new Noticias_model;
        $datos['noticia'] = $noticia->datos($id);
        $datos['escritor'] = $noticia->escritor($id);
        $this->titulo= $noticia->titulo($id);

        $this->mostrar($datos);
    }
    
    public function buscar($campo = 'Titulo', $orden = 'asc', $limit='5', $busqueda = '', $offset = 0){
        $this->permisos('admin');
        
        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');
        
        $this->pagina = 'noticias';
        $this->carpeta = 'administrador';       
        $this->titulo = 'Búsqueda';
        $this->menu = 'menu_admin_noticias';
        $this->estilo = array('noticias','tablas');
        $this->javascript =  array('marcar_checkbox', 'redireccion');     
        
        $datos['busqueda'] = TRUE;
     
        if ($busqueda != '')
            $datos['buscador'] = array('class' => 'search-query input-medium', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'value'=>urldecode($busqueda), 'autofocus'=>'autofocus');
        else
            $datos['buscador'] = array('class' => 'search-query input-medium', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'value'=>$this->input->post('buscador'), 'autofocus'=>'autofocus');
        $datos['boton'] = array('class'=>'btn', 'id'=>'buscador', 'name'=>'button', 'value'=>'Buscar');
        $datos['borrar']= array('class'=>'btn btn-danger','id'=>'borrar', 'value'=>'Borrar selección', 'data-confirm'=>"¿Estás seguro?");
        $datos['fields'] = array(
           'Titulo' => 'Titulo',
           'Contenido' => 'Contenido',
           'FechaCreacion' => 'Fecha',
           'Escritor' => 'Escritor',
        );
        
        $this->form_validation->set_rules('buscador', 'Buscador', 'trim|required|xss_clean');
        $this->form_validation->set_message('required', '%s no puede estar vacio');
        $this->form_validation->set_message('xss_clean', ' %s no es una búsqueda válida');

        if($this->form_validation->run() == FALSE){      
            if($busqueda != ''){ 
                $busqueda = urldecode($busqueda);
                $opciones = $this->seleccion(Noticias_model::busqueda_cantidad($busqueda));
                $datos['opciones'] = $opciones;
                if($this->input->post('cantidad') != ''){
                    if($opciones[$this->input->post('cantidad')] == 'Todo'){
                        $limit = Noticias_model::busqueda_cantidad($busqueda);
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
                $datos['noticias'] = Noticias_model::buscar($busqueda, $campo, $orden, $offset, $limit, true);
                $datos['numero'] = Noticias_model::busqueda_cantidad($busqueda);
                
                $config = array();
                
                $config['base_url'] = base_url(). "admin/noticias/buscar/".$campo."/".$orden."/".$limit."/".$busqueda."/";
                $config['total_rows'] = Noticias_model::busqueda_cantidad($busqueda);
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
            
            $opciones = $this->seleccion(Noticias_model::busqueda_cantidad($busqueda));
            $datos['opciones'] = $opciones;

            if($this->input->post('cantidad') != ''){
                if($opciones[$this->input->post('cantidad')] == 'Todo'){
                    $limit = $this->Noticias->numero();
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
            $datos['noticias'] = Noticias_model::buscar($busqueda, $campo, $orden, $offset, $limit, true);
            $datos['numero'] = Noticias_model::busqueda_cantidad($busqueda);
            
            $config = array();
            $config['base_url'] = base_url(). "admin/noticias/buscar/".$campo."/".$orden."/".$limit."/".$busqueda."/";
            $config['total_rows'] = Noticias_model::busqueda_cantidad($busqueda);
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
    
    
    public function modificar($id){

        $this->permisos('admin');
        $this->form_validation->set_error_delimiters('<div class="alert alert-error"> <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <h4>Error</h4>', '</div>');
        $this->pagina = 'crear noticia';
        $this->carpeta = 'administrador';
        $this->titulo = 'Editar noticia';
        $this->estilo = array('jquery-te-1.3.3', 'crear noticia', 'jquery-ui');
        $this->javascript = array('jquery.validate.min','validarNoticia','editor', 'jquery-te-1.3.3.min','sincroEditor');
        $this->menu = 'menu_admin_noticias';
        
        $datos['boton'] = array( 'class'=> 'btn btn-info', 'name'=>'button', 'id' => 'boton_cliente');
        $datos['actualizar'] = TRUE;
        $datos['id'] = $id;  
        
        $noticia = new Noticias_model;
        $noticia->datos($id);
        
        $datos['formulario'] = array(
            'titulo' => array('class'=>'input-xlarge', 'id'=> 'titulo' ,'name'=>'titulo', 'label' => 'titulo', 'maxlength'=> '150', 'type' => 'text', 'value'=>$noticia->titulo(), 'autofocus'=>'autofocus'),
            'contenido' => array('class' => 'editor', 'id' => "contenido-noticia-$id", 'name'=>'contenido', 'label' => 'contenido', 'value'=>$noticia->contenido())
        );
        
        
             
        $this->form_validation->set_rules('titulo', 'Titulo', 'trim|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('contenido', 'Contenido', 'trim|required|min_length[3]');
        
        $this->form_validation->set_message('required', 'El campo %s no puede estar vacio');
        $this->form_validation->set_message('min_legth', 'El campo %s debe tener mínmo 3 caracteres');
        $this->form_validation->set_message('xss_clean', 'El campo %s no es válido');
            
        if($this->form_validation->run() == TRUE){
            $noticia = new Noticias_model;
            if($noticia->actualizar($id)){
                $this->exito = 'La noticia se actualizado satisfactoriamente.';
                $datos['formulario']['titulo']['value'] = $this->input->post('titulo');
                $datos['formulario']['contenido']['value'] = $this->input->post('contenido');
            }    
            else{
                 $this->error = array(
                    'nivel' => '2',
                    'mensaje'=>'No se ha podido actualizar la noticia, por favor inténtelo de nuevo más tarde'
                    );
            }              
        }
        $this->mostrar($datos);
        
    } 
    

    
    public function borrar($id = ''){
        $this->permisos('admin');
         $noticia = new Noticias_model;
        if($id != ''){           
            $noticia->borrar($id);
        }
        else{
            $noticia->borrar();
        }
        $this->listar();
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