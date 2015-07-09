<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'controllers/empresa.php');

class Constructora extends Empresa{
    
    
    public function __construct() {
        parent:: __construct(); 
        
        $this->load->library('pagination');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('constructora_model');
        $this->load->model('provincia_model', 'Provincia');
        $this->load->model('ciudad_model', 'Ciudad');
        
        $this->form_validation->set_error_delimiters('<div class="alert alert-error"> <button type="button" class="close" data-dismiss="alert">&times;</button>  <h4>Error</h4>', '</div>');
        

    }
    
    private function _validar(){
        if($this->uri->segment(1) == 'empleados' && $this->uri->segment(3) == 'editar'){
            $this->form_validation->set_rules('cif', 'CIF','trim|xss_clean|exact_length[9]|is_unique[Empresa.Cif]');
        }
        else{
            $this->form_validation->set_rules('cif', 'CIF','trim|required|xss_clean|exact_length[9]|is_unique[Empresa.Cif]');
        }
        $this->form_validation->set_rules('razon', 'Razón social','trim|required|xss_clean|min_length[3]');
        $this->form_validation->set_rules('direccion', 'Dirección','trim|min_length[3]|xss_clean');
        $this->form_validation->set_rules('ciudad', 'ciudad','trim|numeric|xss_clean');
        $this->form_validation->set_rules('provincia','Provincia','trim|numeric|xss_clean');
        $this->form_validation->set_rules('email', 'Email','trim|min_length[3]|valid_email|xss_clean');
        $this->form_validation->set_rules('telefono', 'Teléfono','trim|exact_length[9]|numeric|xss_clean');
        $this->form_validation->set_rules('fax','Fax','trim|exact_length[9]|numeric|xss_clean');
        $this->form_validation->set_rules('valoracion','Valoración','trim|xss_clean');
        $this->form_validation->set_rules('descripcion','Descripción','trim|xss_clean');
        
        $this->form_validation->set_message('required', '%s no puede estar vacio');
        $this->form_validation->set_message('is_unique', 'Ya existe una empresa con ese %s');
        $this->form_validation->set_message('min_length', '%s debe tener mínimo %s caracteres');
        $this->form_validation->set_message('valid_email', '%s no es válido');
        $this->form_validation->set_message('xss_clean', ' %s no es válido');
        $this->form_validation->set_message('exact_length', '%s debe tener %s caracteres');
        $this->form_validation->set_message('numeric', '%s debe contener dígitos');
        
        return $this->form_validation->run();
        
    }    
    
    public function registrar($cif = ''){
        
        $this->pagina = 'crear constructora';
        if($cif == '' )
            $this->titulo = 'registrar constructora';
        else 
            $this->titulo = 'actualizar constructora';
        $this->estilo = 'registrar';
        $this->javascript = array('ciudades','jquery.validate.min', 'validarEmpresa');
        $this->carpeta = 'empleado';
        $this->menu = 'menu_empleado_constructora';
        
        //Permisos para poder acceder        
        if($this->uri->segment(1) == 'admin' || $this->uri->segment(1) == 'administrador'){
            $this->permisos('admin');
            $datos['user'] = 'admin';            
        }
        else{
            $this->permisos('empleado');
            $datos['user'] = 'empleados';
        }
        
        $formulario = $this->formulario();
        $formulario['valoracion'] = array('class'=>'valoracion', 'name'=>'valoracion', 'label'=>'Valoración', 'requerido' => FALSE);
        
        $datos['provincias'] = $this->Provincia->obtener();
        
        if($cif == ''){
            $datos['ciudades'] = $this->Ciudad->obtener();
        }
        else{
            $datos['actualizar'] = TRUE;
            $datos['cif'] = $cif;
            if(Constructora_model::existe($cif)){
                $constructora = new Constructora_model;
                $constructora->datos($cif);
                
                $formulario['cif']['value'] = $constructora->cif();
                $formulario['razonSocial']['value'] = $constructora->razonSocial();
                $formulario['direccion']['value'] = $constructora->direccion();
                $formulario['ciudad']['value'] =  $constructora->ciudad($cif,TRUE);
                $formulario['provincia']['value'] = $constructora->provincia($cif, TRUE);
                $formulario['email']['value'] = $constructora->email();
                $formulario['telefono']['value'] = $constructora->telefono();
                $formulario['fax']['value'] = $constructora->fax();       
                $formulario['descripcion']['value'] = $constructora->descripcion();
                $formulario['web']['value'] = $constructora->web();
                $formulario['valoracion'] = array('class'=>'valoracion', 'name'=>'valoracion', 'label'=>'Valoración','value'=>$constructora->valoracion(), 'requerido' => FALSE);
                
                $datos['ciudades'] = $this->Ciudad->ciudades($constructora->provincia($cif, TRUE));

                foreach($formulario as &$input){ 
                    if($input['value'] == 'Desconocido' || $input['value'] == 'Desconocida'){                
                       $input['value'] = '';
                    }
                }
            }
            else{
                $this->error = array(
                                    'nivel' => '1',
                                    'mensaje' => ' La constructora indicada no existe'
                                );
            }
        }
        
        $datos['formulario'] = $formulario;
        $datos['boton'] = $this->boton();
        $datos['opc'] = array('','0','1','2','3','4','5','6','7','8','9','10');
        if(Constructora_model::numero() == 0)
            $this->error = array(
                                'nivel' => '2',
                                'mensaje' => 'No existen constructoras registradas.'
                            );
        
        if($this->_validar() == TRUE){
            
            if($cif == ''){
                $constructora = new Constructora_model;
                if($constructora->inicializar()){
                    $this->exito = 'La constructora se ha registrado satisfactoriamente';
                }
                else{
                     $this->error = array(
                         'nivel' => '2',
                         'mensaje'=>'No se ha podido registrar la constructora en este momento'
                     );
                }
            }
            else{
                if($constructora->actualizar($cif)){
                   $constructora->datos($cif);                  
                   $datos['formulario']['cif']['value'] = $constructora->cif();
                   $datos['formulario']['razonSocial']['value'] = $constructora->razonSocial();
                   $datos['formulario']['direccion']['value'] = $constructora->direccion();
                   $datos['formulario']['ciudad']['value'] =  $constructora->ciudad($cif, TRUE);
                   $datos['formulario']['provincia']['value'] = $constructora->provincia($cif, TRUE);
                   $datos['formulario']['email']['value'] = $constructora->email();
                   $datos['formulario']['telefono']['value'] = $constructora->telefono();
                   $datos['formulario']['fax']['value'] = $constructora->fax();       
                   $datos['formulario']['descripcion']['value'] = $constructora->descripcion();
                   $datos['formulario']['web']['value'] = $constructora->web(); 
                   foreach($datos['formulario'] as &$input){ 
                        if($input['value'] == 'Desconocido' || $input['value'] == 'Desconocida'){                
                           $input['value'] = '';
                        }
                   }
                   $this->exito = 'La constructora se ha actualizado satisfactoriamente';
               }
               else{ 
                   $this->error = array(
                         'nivel' => '2',
                         'mensaje'=>'No se ha podido actualizar la constructora en este momento'
                     );
               }
            }
        }
        
        $this->mostrar($datos);
    }
    
    
    
    
    public function modificar($cif){ 
        $this->registrar($cif);
    }
    
    
    public function listar($campo = 'Cif', $orden = 'asc', $limit='5', $offset = 0){
        $this->pagina = 'constructoras'; 
        $this->titulo = 'constructoras';
        $this->estilo = 'tablas';
        $this->javascript = '';
        $this->carpeta = 'empleado';
        $this->menu = 'menu_empleado_constructora';
        $this->submenu  = '';
        
        if($this->uri->segment(1) == 'admin'){
            $this->permisos('admin');
            $datos['user'] = 'admin';
            $this->javascript = array('marcar_checkbox');
        }        
        else{
            $this->permisos('empleado');
            $datos['user'] = 'empleados';
        }
        $pagina = 'constructoras';
        $carpeta = 'empleado';
        $datos['titulo'] = 'constuctoras';
        $datos['menu'] = 'menu_empleado_constructora';
        $datos['estilo'] = 'tablas';
        
        $datos['buscador'] = array('class' => 'search-query', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'autofocus'=>'autofocus');
        $datos['boton'] = array('class'=>'btn', 'id'=>'buscador', 'name'=>'button', 'value'=>'Buscar');
        $datos['borrar']= array('class'=>'btn btn-danger','id'=>'borrar', 'value'=>'Borrar selección','data-confirm'=>"¿Estás seguro?");
                
        if(Constructora_model::numero() == 0){
            $this->registrar();
        }
        else{
            $opciones = $this->seleccion(Constructora_model::numero());
            $datos['numero'] = Constructora_model::numero();
            $datos['opciones'] = $opciones;

            if($this->input->post('cantidad') != ''){
                if($opciones[$this->input->post('cantidad')] == 'Todo'){
                    $limit = $this->Constructora->numero();
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
                    'Cif' => 'Cif',
                    'RazonSocial' => 'Razón Social',
                    'Email' => 'Email',
                    'Valoracion' => 'Valoración',
                    'Proyectos'=> 'Proyectos'
            );
            
            
            $datos['constructoras'] = Constructora_model::obtener($campo, $orden, $offset, $limit);
             
            $config = array();
            if($this->uri->segment(1) == 'admin'){
                $config['base_url'] = base_url(). "admin/constructora/".$campo."/".$orden."/".$limit."/";
            }
            else if($this->uri->segment(1) == 'empleados'){
                 $config['base_url'] = base_url(). "empleados/constructora/".$campo."/".$orden."/".$limit."/";
            }
            $config['total_rows'] = Constructora_model::numero();
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
    
    
    public function buscar($campo = 'Cif', $orden = 'asc', $limit='5', $busqueda = '', $offset = 0){
        
        if($this->uri->segment(1) == 'admin'){
            $this->permisos('admin');
            $datos['user'] = 'admin';
            $datos['javascript']=array('marcar_checkbox',  'redireccion');
        }
        else{
            $this->permisos('empleado');
            $datos['user'] = 'empleados';
        }
                
        $pagina = 'constructoras';
        $carpeta = 'empleado';
        
        $datos['menu'] = 'menu_empleado_constructora';
        $datos['titulo'] =  'búsqueda constructora';        
        $datos['estilo'] =  'tablas';        
        $datos['busqueda'] = TRUE;
        //$datos['buscador'] = array('class' => 'search-query', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'autofocus'=>'autofocus');
        $datos['boton'] = array('class'=>'btn', 'id'=>'buscador', 'name'=>'button', 'value'=>'Buscar');
        $datos['borrar']= array('class'=>'btn btn-danger','id'=>'borrar', 'value'=>'Borrar selección','data-confirm'=>"¿Estás seguro?");
        
        if ($busqueda != '')
            $datos['buscador'] = array('class' => 'search-query input-medium', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'value'=>urldecode($busqueda), 'autofocus'=>'autofocus');
        else
            $datos['buscador'] = array('class' => 'search-query input-medium', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'value'=>$this->input->post('buscador'), 'autofocus'=>'autofocus');
        
        $datos['fields'] = array(
                    'Cif' => 'Cif',
                    'RazonSocial' => 'Razón Social',
                    'Email' => 'Email',
                    'Valoracion' => 'Valoración',
                    'Proyectos'=> 'Proyectos'
            );
        
        $this->form_validation->set_rules('buscador', 'Buscador', 'trim|required|xss_clean');
        $this->form_validation->set_message('required', '%s no puede estar vacio');
        $this->form_validation->set_message('xss_clean', ' %s no es una búsqueda válida');
        
        if($this->form_validation->run() == FALSE){
            if($busqueda != ''){ 
                $busqueda = urldecode($busqueda);
                $opciones = $this->seleccion(Constructora_model::busqueda_cantidad($busqueda));
                $datos['opciones'] = $opciones;
                if($this->input->post('cantidad') != ''){
                    if($opciones[$this->input->post('cantidad')] == 'Todo'){
                        $limit = Constructora_model::busqueda_cantidad($busqueda);
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
                $datos['constructoras'] = Constructora_model::buscar($busqueda, $campo, $orden, $offset, $limit);
                $datos['numero'] = Constructora_model::busqueda_cantidad($busqueda);
                
                $config = array();
                
                if($this->uri->segment(1) == 'admin')
                    $config['base_url'] = base_url(). "admin/constructora/buscar/".$campo."/".$orden."/".$limit."/".$busqueda."/";
                else
                    $config['base_url'] = base_url(). "empleados/constructora/buscar/".$campo."/".$orden."/".$limit."/".$busqueda."/";
                $config['total_rows'] = Constructora_model::busqueda_cantidad($busqueda);
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
            $this->mostrar($pagina, $datos, $carpeta);
        }
        
        else{
            $busqueda = $this->input->post('buscador');
            
            $opciones = $this->seleccion(Constructora_model::busqueda_cantidad($busqueda));
            $datos['opciones'] = $opciones;

            if($this->input->post('cantidad') != ''){
                if($opciones[$this->input->post('cantidad')] == 'Todo'){
                    $limit = $this->Constructora->numero();
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
            $datos['constructoras'] = Constructora_model::buscar($busqueda, $campo, $orden, $offset, $limit);
            $datos['numero'] = Constructora_model::busqueda_cantidad($busqueda);
            
            $config = array();
            if($this->uri->segment(1) == 'admin'){
                $config['base_url'] = base_url(). "admin/constructora/buscar/".$campo."/".$orden."/".$limit."/".$busqueda."/";
            }
            else{
                $config['base_url'] = base_url(). "empleados/constructora/buscar/".$campo."/".$orden."/".$limit."/".$busqueda."/";
            }
            $config['total_rows'] = Constructora_model::busqueda_cantidad($busqueda);
            $config['per_page'] = $limit;
            $config['uri_segment'] = 7;
            $config['prev_link'] = 'anterior';
            $config['next_link'] = 'siguiente';
            $config['first_link'] = '<<';
            $config['last_link'] = '>>'; 
            $this->pagination->initialize($config);
            $datos['links'] = $this->pagination->create_links();

            $datos['campo'] = $campo;
            $datos['orden'] = $orden;

            $this->mostrar($pagina, $datos, $carpeta);
        }       
    } 
    
     public function registrarAjax(){
        
        if(!$this->input->is_ajax_request()){
            redirect('404');
        }
        else{
           if($this->_validar() == TRUE){
                 $constructora = new Constructora_model;
                if($constructora->inicializar()){
                    echo '<div class="text-success">La constructora ha sido registrada satisfactoriamente</div>';               
                }            
                else{
                   echo '<div class="text-error">No se ha podido completar el registro por favor inténtelo de nuevo más tarde</div>';
                } 
            }
            else{
                $error = json_encode(validation_errors());
                $error = str_replace('"', "", $error);
                $error = str_replace('<\/span>\n', "", $error);                 
                echo '<div class="text-error">'.$error.'</div>';                       
            }
        }
    } 
    
}

?>
        
