<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Consultor extends MY_Controller{
    
    public function __construct() {
        parent:: __construct();
        
        $this->load->library('pagination');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('consultor_model');
        $this->load->model('provincia_model','Provincia');
        $this->load->model('ciudad_model','Ciudad');
        
        $this->form_validation->set_error_delimiters('<div class="alert alert-error"> <button type="button" class="close" data-dismiss="alert">&times;</button>   <h4>Error</h4>', '</div>');
    }
    
    private function _validar(){
        $this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('primerApellido', 'Primer Apellido', 'trim|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('segundoApellido', 'Segundo Apellido', 'trim|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('direccion', 'Dirección', 'trim|min_length[3]|xss_clean');
        $this->form_validation->set_rules('provincia', 'Provincia', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('ciudad', 'Ciudad', 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('telefono', 'Teléfono', 'trim|exact_length[9]|numeric|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[3]|valid_email|xss_clean');
        $this->form_validation->set_rules('fax', 'Fax', 'trim|exact_length[9]|numeric|xss_clean');
        $this->form_validation->set_rules('especialidad', 'Especialidad', 'trim|min_length[3]|xss_clean');
        
        
        $this->form_validation->set_message('required', '%s no puede estar vacio');
        $this->form_validation->set_message('min_length', '%s debe tener mínimo %s caracteres');
        $this->form_validation->set_message('valid_email', '%s no es válido');
        $this->form_validation->set_message('xss_clean', ' %s no es válido');
        $this->form_validation->set_message('exact_legth', '%s debe tener %s caracteres');
        $this->form_validation->set_message('numeric', '%s debe contener dígitos');
        
        return $this->form_validation->run();
    }
    
    public function registrar($id = ''){
        
        $this->pagina = 'crear consultor';
        $this->carpeta = 'empleado';
        $this->menu = 'menu_empleado_consultor';
        if($id == '')
            $this->titulo = 'registrar consultor';
        else
            $this->titulo = 'modificar consultor';
        $this->estilo = 'registrar';
        $this->javascript = array('ciudades','jquery.validate.min', 'validarConsultor');
        
        if($this->uri->segment(1) == 'admin'){
            $this->permisos('admin');
            $datos['user'] = 'admin';
        }
        else{
            $this->permisos('empleado');
            $datos['user'] = 'empleados';
        }      
        
         if(Consultor_model::numero() == 0)
            $datos['vacio'] = TRUE;
        else
            $datos['vacio'] = FALSE;
        
        $datos['boton'] = array( 'class'=> 'btn btn-info', 'name'=>'button', 'id' => 'boton_empleado');
        $datos['provincias'] = $this->Provincia->obtener();
        
        if($id == ''){
            $datos['ciudades'] = $this->Ciudad->obtener();
            $datos['formulario'] = array(
                'nombre'=>array('class'=>'nombre','label'=>'Nombre', 'name'=>'nombre', 'maxlength'=>'60', 'size'=>'15', 'requerido' => TRUE, 'autofocus' => 'autofocus'),
                'apellidoPaterno'=>array('class'=>'apellidoP','label'=>'Primer apellido', 'name'=>'primerApellido', 'maxlength'=>'60', 'size'=>'15', 'requerido' => TRUE),
                'apellidoMaterno'=>array('class'=>'apellidoM','label'=>'Segundo apellido', 'name'=>'segundoApellido', 'maxlength'=>'60', 'size'=>'15', 'requerido' => TRUE),
                'direccion'=>array('class'=>'direccion','label'=>'Dirección', 'name'=>'direccion', 'maxlength'=>'60', 'size'=>'15', 'requerido' => FALSE),
                'provincia'=>array('class'=>'provincia','label'=>'Provincia', 'name'=>'provincia', 'maxlength'=>'60', 'size'=>'15', 'requerido' => FALSE),
                'ciudad'=>array('class'=>'ciudad','label'=>'Ciudad', 'name'=>'ciudad', 'maxlength'=>'60', 'size'=>'15', 'requerido' => FALSE),
                'telefono'=>array('class'=>'telefono','label'=>'Teléfono', 'name'=>'telefono', 'maxlength'=>'9', 'size'=>'10', 'requerido' => FALSE),
                'email'=>array('class'=>'email','label'=>'Email', 'name'=>'email', 'maxlength'=>'50', 'size'=>'15', 'requerido' => TRUE),
                'fax'=>array('class'=>'fax','label'=>'Fax', 'name'=>'fax', 'maxlength'=>'9', 'size'=>'10', 'requerido' => FALSE),
                'especialidad'=>array('class'=>'especialidad','label'=>'Especialidad', 'name'=>'especialidad', 'maxlength'=>'50', 'size'=>'15', 'requerido' => FALSE),
            );
        }
        else{            
            $datos['actualizar'] = TRUE;
            $datos['identificador'] = $id;
             if(Consultor_model::existe($id)){
                $consultor = new Consultor_model; 
                $consultor->datos($id);
                $datos['provincias'] = $this->Provincia->obtener();
                $datos['ciudades'] = $this->Ciudad->ciudades($consultor->provincia($id, TRUE));

                $datos['formulario'] = array(
                    'nombre'=>array('class'=>'nombre','label'=>'Nombre', 'name'=>'nombre', 'maxlength'=>'60', 'size'=>'15', 'value'=>$consultor->nombre() ,'requerido' => TRUE),
                    'apellidoPaterno'=>array('class'=>'apellidoP','label'=>'Primer apellido', 'name'=>'primerApellido', 'maxlength'=>'60', 'size'=>'15', 'value'=>$consultor->primerApellido(), 'requerido' => TRUE),
                    'apellidoMaterno'=>array('class'=>'apellidoM','label'=>'Segundo apellido', 'name'=>'segundoApellido', 'maxlength'=>'60', 'size'=>'15', 'value'=>$consultor->segundoApellido(), 'requerido' => TRUE),
                    'direccion'=>array('class'=>'direccion','label'=>'Dirección', 'name'=>'direccion', 'maxlength'=>'60', 'size'=>'15', 'value'=>$consultor->direccion(), 'requerido' => FALSE),
                    'provincia'=>array('class'=>'provincia','label'=>'Provincia', 'name'=>'provincia', 'maxlength'=>'60', 'size'=>'15', 'value'=>$consultor->provincia($id, TRUE), 'requerido' => FALSE),
                    'ciudad'=>array('class'=>'ciudad','label'=>'Ciudad', 'name'=>'ciudad', 'maxlength'=>'60', 'size'=>'15', 'value'=>$consultor->ciudad($id, TRUE), 'requerido' => FALSE),
                    'telefono'=>array('class'=>'telefono','label'=>'Teléfono', 'name'=>'telefono', 'maxlength'=>'9', 'size'=>'10', 'value'=>$consultor->telefono(), 'requerido' => FALSE),
                    'email'=>array('class'=>'email','label'=>'Email', 'name'=>'email', 'maxlength'=>'50', 'size'=>'15', 'value'=>$consultor->email(), 'requerido' => TRUE),
                    'fax'=>array('class'=>'fax','label'=>'Fax', 'name'=>'fax', 'maxlength'=>'9', 'size'=>'10', 'value'=>$consultor->fax(), 'requerido' => FALSE),
                    'especialidad'=>array('class'=>'especialidad','label'=>'Especialidad', 'name'=>'especialidad', 'maxlength'=>'50', 'size'=>'15', 'value'=>$consultor->especialidad(), 'requerido' => FALSE),
                );

                foreach($datos['formulario'] as &$input){
                    if($input['value'] == 'Desconocido' || $input['value'] == 'Desconocida'){                
                        $input['value'] = '';
                    }
                }
             }
             else{
                 $this->error = array(
                     'nivel' => '1',
                     'mensaje' => 'No existe consultora'
                 );                 
             }
        }        
                
        if($this->_validar()){
            if($id == ''){
                $consultor = new Consultor_model;
                if($consultor->inicializar()){
                     $datos['valido'] = 'El consultor ha sido registrado satisfactoriamente';
                }
                else{
                    $datos['error'] = 'No se ha podido completar el registro por favor intentelo de nuevo más tarde';
                }
            }
            else{
                if($consultor->actualizar($id)){
                    $consultor->datos($id);              
                    $datos['ciudades'] = $this->Ciudad->ciudades($consultor->provincia($id, TRUE));
                    $datos['formulario'] = array(
                          'nombre'=>array('class'=>'nombre','label'=>'Nombre', 'name'=>'nombre', 'maxlength'=>'60', 'size'=>'15', 'value'=>$consultor->nombre() ,'requerido' => TRUE),
                          'apellidoPaterno'=>array('class'=>'apellidoP','label'=>'Primer apellido', 'name'=>'primerApellido', 'maxlength'=>'60', 'size'=>'15', 'value'=>$consultor->primerApellido(), 'requerido' => TRUE),
                          'apellidoMaterno'=>array('class'=>'apellidoM','label'=>'Segundo apellido', 'name'=>'segundoApellido', 'maxlength'=>'60', 'size'=>'15', 'value'=>$consultor->segundoApellido(), 'requerido' => TRUE),
                          'direccion'=>array('class'=>'direccion','label'=>'Dirección', 'name'=>'direccion', 'maxlength'=>'60', 'size'=>'15', 'value'=>$consultor->direccion(), 'requerido' => FALSE),
                          'provincia'=>array('class'=>'provincia','label'=>'Provincia', 'name'=>'provincia', 'maxlength'=>'60', 'size'=>'15', 'value'=>$consultor->provincia($id, TRUE), 'requerido' => FALSE),
                          'ciudad'=>array('class'=>'ciudad','label'=>'Ciudad', 'name'=>'ciudad', 'maxlength'=>'60', 'size'=>'15', 'value'=>$consultor->ciudad($id, TRUE), 'requerido' => FALSE),
                          'telefono'=>array('class'=>'telefono','label'=>'Teléfono', 'name'=>'telefono', 'maxlength'=>'9', 'size'=>'10', 'value'=>$consultor->telefono(), 'requerido' => FALSE),
                          'email'=>array('class'=>'email','label'=>'Email', 'name'=>'email', 'maxlength'=>'50', 'size'=>'15', 'value'=>$consultor->email(), 'requerido' => TRUE),
                          'fax'=>array('class'=>'fax','label'=>'Fax', 'name'=>'fax', 'maxlength'=>'9', 'size'=>'10', 'value'=>$consultor->fax(), 'requerido' => FALSE),
                          'especialidad'=>array('class'=>'especialidad','label'=>'Especialidad', 'name'=>'especialidad', 'maxlength'=>'50', 'size'=>'15', 'value'=>$consultor->especialidad(), 'requerido' => FALSE),
                    );

                    foreach($datos['formulario'] as &$input){
                        if($input['value'] == 'Desconocido' || $input['value'] == 'Desconocida'){                
                            $input['value'] = '';
                        }
                    }

                    $datos['valido'] = 'El registro se ha realizado satisfactoriamente.';
                }
                else{ 
                    $datos['error'] = 'El registro no se ha realizado satisfactoriamente. Por favor inténtelo de nuevo más tarde.';
                }
            }
        }
        $this->mostrar($datos);
    }
    
    
    public function borrar($id = ''){
        $this->permisos('admin');
        
        if($id != ''){
            if(Consultor_model::existe(urldecode($id))){
                $consultor = new Consultor_model;
                $consultor->codigo($id);
                $consultor->eliminar();
            }
        }
        else{
            if($this->input->post('checkbox') != ''){            
                $ids = $this->input->post('checkbox');
                foreach($ids as $id){
                    if(Consultor_model::existe(urldecode($id))){
                        $consultor = new Consultor_model;
                        $consultor->codigo($id);
                        $consultor->eliminar();
                    }
                }
            }
        }
        
        redirect('admin/consultor');
        
    }
    
    
    public function modificar($id){ 
        $this->registrar($id);
    } 
    
    
    public function listar($campo = 'nombre', $orden = 'asc', $limit='5', $offset = 0){
        $this->pagina = 'consultores';
        $this->carpeta = 'empleado';        
        $this->menu = 'menu_empleado_consultor';
        $this->titulo = 'Consultores';
        $this->estilo = 'tablas';
        $this->javascript = 'marcar_checkbox';
        
        $datos['buscador'] = array('class' => 'search-query input-medium', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'autofocus'=>'autofocus');
        $datos['boton'] = array('class'=>'btn', 'id'=>'buscador', 'name'=>'button', 'value'=>'Buscar');
        $datos['borrar']= array('class'=>'btn btn-danger','id'=>'borrar', 'value'=>'Borrar selección','data-confirm'=>"¿Estás seguro?");
        
        if($this->uri->segment(1) == 'admin'){
            $this->permisos('admin');
            $datos['user'] = 'admin';
        }
        else{
            $this->permisos('empleado');
            $datos['user'] = 'empleados';
        }
        
        if(Consultor_model::numero() == 0){
            $this->registrar();
        }
        else{
            $opciones = $this->seleccion(Consultor_model::numero());
            $datos['opciones'] = $opciones;
            $datos['numero'] = $opciones;

            if($this->input->post('cantidad') != ''){
                if($opciones[$this->input->post('cantidad')] == 'Todo'){
                    $limit = $this->Consultor->numero();
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
                    'Direccion' => 'Dirección',
                    'Ciudad' => 'Ciudad',
                    'Provincia' => 'Provincia',
                    'Telefono' => 'Teléfono',
                    'Email' => 'Email',
                    'Fax' => 'Fax',
                    'Especialidad' => 'Especialidad'
            );
            
            
            $datos['consultores'] = Consultor_model::obtener($campo, $orden, $offset, $limit);
 
            $config = array();
            if($this->uri->segment(1) == 'admin'){
                $config['base_url'] = base_url(). "admin/consultor/".$campo."/".$orden."/".$limit."/";
            }
            else{
                $config['base_url'] = base_url(). "empleados/consultor/".$campo."/".$orden."/".$limit."/";
            }
            $config['total_rows'] = Consultor_model::numero();
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
    
    public function buscar($campo = 'nombre', $orden = 'asc', $limit='5', $busqueda = '', $offset = 0){
        $this->pagina = 'consultores';
        $this->carpeta = 'empleado';
        $this->titulo = 'Buscar consultor';
        $this->estilo = 'tablas';
        $this->javascript = array('marcar_checkbox','redireccion');
        $this->menu = 'menu_empleado_consultor';        
        
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
            $datos['buscador'] = array('class' => 'search-query input-medium', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'value'=>$this->input->post('buscador'),'autofocus'=>'autofocus');
        $datos['boton'] = array('class'=>'btn', 'id'=>'buscador', 'name'=>'button', 'value'=>'Buscar');
        $datos['borrar']= array('class'=>'btn btn-danger','id'=>'borrar', 'value'=>'Borrar selección','data-confirm'=>"¿Estás seguro?");
        $datos['fields'] = array(
                    'Nombre' => 'Nombre',
                    'Direccion' => 'Dirección',
                    'Ciudad' => 'Ciudad',
                    'Provincia' => 'Provincia',
                    'Telefono' => 'Teléfono',
                    'Email' => 'Email',
                    'Fax' => 'Fax',
                    'Especialidad' => 'Especialidad'
        );
        
        $this->form_validation->set_rules('buscador', 'Buscador', 'trim|required|xss_clean');
        $this->form_validation->set_message('required', '%s no puede estar vacio');
        $this->form_validation->set_message('xss_clean', ' %s no es una búsqueda válida');        
        
        if($this->form_validation->run() == FALSE){
            if($busqueda != ''){ 
                $busqueda = urldecode($busqueda);
                $opciones = $this->seleccion(Consultor_model::busqueda_cantidad($busqueda));
                $datos['opciones'] = $opciones;
                if($this->input->post('cantidad') != ''){
                    if($opciones[$this->input->post('cantidad')] == 'Todo'){
                        $limit = Consultor_model::busqueda_cantidad($busqueda);
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
                $datos['consultores'] = Consultor_model::buscar($busqueda, $campo, $orden, $offset, $limit);
                $datos['numero'] = Consultor_model::busqueda_cantidad($busqueda);
                
                $config = array();
                $config['base_url'] = base_url(). "admin/consultor/".$campo."/".$orden."/".$limit."/".$busqueda."/";
                $config['total_rows'] = Consultor_model::busqueda_cantidad($busqueda);
                $config['per_page'] = $limit;
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
            $this->mostrar($datos);
        }
        
        else{
            $busqueda = $this->input->post('buscador');
            
            $opciones = $this->seleccion(Consultor_model::busqueda_cantidad($busqueda));
            $datos['opciones'] = $opciones;

            if($this->input->post('cantidad') != ''){
                if($opciones[$this->input->post('cantidad')] == 'Todo'){
                    $limit = Consultor_model::numero();
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
            $datos['consultores'] = Consultor_model::buscar($busqueda, $campo, $orden, $offset, $limit);
            $datos['numero'] = Consultor_model::busqueda_cantidad($busqueda);
            
            $config = array();
            if($this->uri->segment(1) == 'admin')
                $config['base_url'] = base_url(). "admin/consultor/".$campo."/".$orden."/".$limit."/".$busqueda."/";
            else
                $config['base_url'] = base_url(). "empleados/consultor/".$campo."/".$orden."/".$limit."/".$busqueda."/";
            $config['total_rows'] = Consultor_model::busqueda_cantidad($busqueda);
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
      
}

?>