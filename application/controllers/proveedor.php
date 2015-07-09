<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'controllers/empresa.php');

class Proveedor extends Empresa{

    public function __construct() {
        parent:: __construct();
        
        $this->load->library('pagination');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('proveedor_model');
        $this->load->model('provincia_model', 'Provincia');
        $this->load->model('ciudad_model', 'Ciudad');
    }
    
    
    private function _validar(){
         //Establecemos las reglas de validación del formulario
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
        $this->form_validation->set_rules('web','Página web','trim|xss_clean');
        $this->form_validation->set_rules('servicios','Servicios','trim|xss_clean');
        $this->form_validation->set_rules('descripcion','Descripción','trim|xss_clean');
        
        $this->form_validation->set_message('required', '%s no puede estar vacio');
        $this->form_validation->set_message('is_unique', 'Ya existe una empresa con ese %s');
        $this->form_validation->set_message('min_length', '%s debe tener mínimo %s caracteres');
        $this->form_validation->set_message('valid_email', '%s no es válido');
        $this->form_validation->set_message('xss_clean', ' %s no es válido');
        $this->form_validation->set_message('exact_legth', '%s debe tener %s caracteres');
        $this->form_validation->set_message('numeric', '%s debe contener dígitos');
        
        return $this->form_validation->run();
    }
    
    public function registrar(){
        if($this->uri->segment(1) == 'admin'){
            $this->permisos('admin');
            $datos['user'] = 'admin';
        }
        else{
            $this->permisos('empleado');
            $datos['user'] = 'empleados';
        }
        $this->form_validation->set_error_delimiters('<div class="alert alert-error"> <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <h4>Error</h4>', '</div>');
        $this->pagina = 'crear proveedor';
        $this->carpeta = 'empleado';
        $this->titulo = 'Registrar proveedor';
        $this->estilo = 'registrar';
        $this->javascript =  array('jquery.validate.min','validarEmpresa','ciudades');
        $this->menu = 'menu_empleado_proveedor';
        $datos['boton'] = $this->boton();
        if(Proveedor_model::numero() == 0){
            $this->error = array(
                'nivel'=>'2',
                'mensaje' =>"Actualmente no existe ningún proveedor. 
                            Si lo desea puede empezar a registrar proveedores"
            );
        } 
       
        //Cargamos el formulario
        $formulario = $this->formulario();        
        $formulario['servicios'] = array('class'=>'servicios', 'name'=>'servicios', 'label'=>'Servicios', 'requerido' => FALSE);        
        
        $datos['formulario'] = $formulario;        
        $datos['provincias'] = $this->Provincia->obtener();
        $datos['ciudades'] = $this->Ciudad->obtener();  
        
        if($this->_validar()){
            $proveedor = new Proveedor_model;
            if($proveedor->inicializar()){
               $this->exito = 'El proveedor ha sido registrado satisfactoriamente.';               
            }            
            else{ 
               $this->error = array(
                       'nivel'=>'2', 
                       'mensaje'=>'No se ha podido completar el registro por favor inténtelo de nuevo más tarde'
                    );                
            }                
        }
        $this->mostrar($datos);
    }
    
    
    public function modificar($cif){
        $proveedor = new Proveedor_model;
                
        if($this->uri->segment(1) == 'admin'){
            $this->permisos('admin');
            $datos['user'] = 'admin';
        }
        else{ 
            $this->permisos('empleado');
            $datos['user'] = 'empleados';
        }
        $this->form_validation->set_error_delimiters('<div class="alert alert-error"> <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <h4>Error</h4>', '</div>');
        $this->pagina = 'crear proveedor';
        $this->carpeta = 'empleado';
        $this->titulo = 'modificar proveedor';
        $this->estilo = array('registrar');
        $this->javascript = array('jquery.validate.min','validarEmpresa','ciudades');
        $this->menu = 'menu_empleado_proveedor';
        $datos['boton'] = array( 'class'=> 'btn btn-info', 'name'=>'button', 'id' => 'boton_cliente');
        $datos['actualizar'] = TRUE;
        $datos['cif'] = $cif;
        
        if(Proveedor_model::existe($cif)){
            $proveedor->datos($cif);
            
            $formulario = $this->formulario();
            $formulario['cif']['value'] = $proveedor->cif();
            $formulario['razonSocial']['value'] = $proveedor->razonSocial();
            $formulario['direccion']['value'] = $proveedor->direccion();
            $formulario['provincia']['value'] = $proveedor->provincia($cif, TRUE);
            $formulario['ciudad']['value'] =  $proveedor->ciudad($cif,TRUE);
            $formulario['email']['value'] = $proveedor->email();
            $formulario['telefono']['value'] = $proveedor->telefono();
            $formulario['fax']['value'] = $proveedor->fax();
            $formulario['web']['value'] = $proveedor->web();
            $formulario['descripcion']['value'] = $proveedor->descripcion();
            
            $formulario['servicios'] = array('class'=>'servicios', 'name'=>'servicios', 'label'=>'Servicios','value'=>$proveedor->servicios(), 'requerido' => FALSE);
            
            $datos['formulario'] = $formulario;
            $datos['provincias'] = $this->Provincia->obtener();            
            $datos['ciudades'] = $this->Ciudad->ciudades($proveedor->provincia($cif, TRUE));
            
              
            foreach($datos['formulario'] as &$input){ 
                if($input['value'] == 'Desconocido' || $input['value'] == 'Desconocida'){                
                    $input['value'] = '';
                }
            }

            if($this->_validar()){
                if($proveedor->actualizar($cif)){
                   $proveedor->datos($cif);
                   $formulario = $this->formulario();
                   $formulario['cif']['value'] = $proveedor->cif();
                   $formulario['razonSocial']['value'] = $proveedor->razonSocial();
                   $formulario['direccion']['value'] = $proveedor->direccion();
                   $formulario['provincia']['value'] = $proveedor->provincia($cif, TRUE);
                   $datos['ciudades'] = $this->Ciudad->ciudades($proveedor->provincia($cif, TRUE));
                   $formulario['ciudad']['value'] =  $proveedor->ciudad($cif,TRUE);
                   $formulario['email']['value'] = $proveedor->email();
                   $formulario['telefono']['value'] = $proveedor->telefono();
                   $formulario['fax']['value'] = $proveedor->fax();
                   $formulario['web']['value'] = $proveedor->web();
                   $formulario['descripcion']['value'] = $proveedor->descripcion();
                   $formulario['servicios'] = array('class'=>'servicios', 'name'=>'servicios', 'label'=>'Servicios','value'=>$proveedor->servicios(), 'requerido' => FALSE);
                   $datos['formulario'] = $formulario;
                   foreach($datos['formulario'] as &$input){ 
                        if($input['value'] == 'Desconocido' || $input['value'] == 'Desconocida'){                
                            $input['value'] = '';
                        }
                    }
                    
                   $this->exito = 'La actualización se ha realizado satisfactoriamente.';
               }
               else{ 
                   $this->error = array(
                       'nivel' => '2',
                       'mensaje'=>'La actualización no se ha realizado satisfactoriamente. Por favor inténtelo de nuevo más tarde.'
                   );
               }                  
            }
            
        }
        else{
            $this->error = array(
                       'nivel' => '1',
                       'mensaje'=>'El proveedor indicado no existe'
                   );
        }
        $this->mostrar($datos);
    }
    
    
    public function listar($campo = 'Cif', $orden = 'asc', $limit='5', $offset = 0){
        if($this->uri->segment(1) == 'admin'){
            $this->permisos('admin');
            $datos['user'] = 'admin';
            $this->javascript=array('marcar_checkbox', 'confirmacion');
        }        
        else{
            $this->permisos('empleado');
            $datos['user'] = 'empleados';
        }
                
        $this->pagina = 'proveedores';
        $this->carpeta = 'empleado';
        
        $this->menu = 'menu_empleado_proveedor';
        $this->titulo = 'proveedores';
        $this->estilo = array('tablas');
        $this->javascript=array('marcar_checkbox');
        $datos['buscador'] = array('class' => 'search-query input-medium', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'autofocus'=>'autofocus');
        $datos['boton'] = array('class'=>'btn', 'id'=>'buscador', 'name'=>'button', 'value'=>'Buscar');
        $datos['borrar']= array('class'=>'btn btn-danger','id'=>'borrar', 'value'=>'Borrar selección','data-confirm'=>"¿Estás seguro?");
               
        $numero = Proveedor_model::numero();
        if($numero == 0){
            $this->registrar();
        }
        else{
            $opciones = $this->seleccion($numero);
            $datos['opciones'] = $opciones;
            $datos['numero'] = $opciones;

            if($this->input->post('cantidad') != ''){
                if($opciones[$this->input->post('cantidad')] == 'Todo'){
                    $limit = $this->Proveedor->numero();
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
                    'Web' => 'Web',
                    'Servicios' => 'Servicios',
                    'Email' => 'Email',
                    'Telefono' => 'Teléfono',
                    'Fax' => 'Fax',
                    'Descripcion' => 'Descripción'
            );
            
            
            $datos['proveedores'] = Proveedor_model::obtener($campo, $orden, $offset, $limit);
 
            $config = array();
            if($this->uri->segment(1) == 'admin')
                $config['base_url'] = base_url(). "admin/proveedor/".$campo."/".$orden."/".$limit."/";
            else
                $config['base_url'] = base_url(). "empleados/proveedor/".$campo."/".$orden."/".$limit."/";
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
    
    
    public function buscar($campo = 'Cif', $orden = 'asc', $limit='5', $busqueda = '', $offset = 0){
        if($this->uri->segment(1) == 'admin'){
            $this->permisos('admin');
            $datos['user'] = 'admin';
        }
        else{
            $this->permisos('empleado');
            $datos['user'] = 'empleados';
        }
                
        $this->pagina = 'proveedores';
        $this->carpeta = 'empleado';        
        $this->menu = 'menu_empleado_proveedor';
        $this->titulo = 'Búsqueda proveedor';
        $this->estilo = 'tablas';
        $this->javascript=array('marcar_checkbox', 'redireccion');
        
        $datos['busqueda'] = TRUE;
        //$datos['buscador'] = array('class' => 'search-query', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar');
        $datos['boton'] = array('class'=>'btn', 'id'=>'buscador', 'name'=>'button', 'value'=>'Buscar');
        $datos['borrar']= array('class'=>'btn btn-danger','id'=>'borrar', 'value'=>'Borrar selección','data-confirm'=>"¿Estás seguro?");
        
        if ($busqueda != '')
            $datos['buscador'] = array('class' => 'search-query input-medium', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'value'=>urldecode($busqueda), 'autofocus'=>'autofocus');
        else
            $datos['buscador'] = array('class' => 'search-query input-medium', 'type'=>'text','name'=>'buscador', 'placeholder' => 'Buscar', 'value'=>$this->input->post('buscador'), 'autofocus'=>'autofocus');
        
        $datos['fields'] = array(
                    'Cif' => 'Cif',
                    'RazonSocial' => 'Razón Social',
                    'Web' => 'Web',
                    'Servicios' => 'Servicios',
                    'Email' => 'Email',
                    'Telefono' => 'Teléfono',
                    'Fax' => 'Fax',
                    'Descripcion' => 'Descripción'
            );
        
        $this->form_validation->set_rules('buscador', 'Buscador', 'trim|required|xss_clean');
        $this->form_validation->set_message('required', '%s no puede estar vacio');
        $this->form_validation->set_message('xss_clean', ' %s no es una búsqueda válida');
        
        if($this->form_validation->run() == FALSE){
            if($busqueda != ''){ 
                $busqueda = urldecode($busqueda);
                $opciones = $this->seleccion(Proveedor_model::busqueda_cantidad($busqueda));
                $datos['opciones'] = $opciones;
                if($this->input->post('cantidad') != ''){
                    if($opciones[$this->input->post('cantidad')] == 'Todo'){
                        $limit = Proveedor_model::busqueda_cantidad($busqueda);
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
                $datos['proveedores'] = Proveedor_model::buscar($busqueda, $campo, $orden, $offset, $limit);
                $datos['numero'] = Proveedor_model::busqueda_cantidad($busqueda);
                
                $config = array();
                
                if($this->uri->segment(1) == 'admin')
                    $config['base_url'] = base_url(). "admin/proveedor/buscar/".$campo."/".$orden."/".$limit."/".$busqueda."/";
                else    
                    $config['base_url'] = base_url(). "empleados/proveedor/buscar/".$campo."/".$orden."/".$limit."/".$busqueda."/";
                $config['total_rows'] = Proveedor_model::busqueda_cantidad($busqueda);
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
            $this->mostrar($datos);
        }
        
        else{
            $busqueda = $this->input->post('buscador');
            
            $opciones = $this->seleccion(Proveedor_model::busqueda_cantidad($busqueda));
            $datos['opciones'] = $opciones;

            if($this->input->post('cantidad') != ''){
                if($opciones[$this->input->post('cantidad')] == 'Todo'){
                    $limit = Proveedor_model::numero();
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
            $datos['proveedores'] = Proveedor_model::buscar($busqueda, $campo, $orden, $offset, $limit);
            $datos['numero'] = Proveedor_model::busqueda_cantidad($busqueda);
            
            $config = array();
            if($this->uri->segment(1) == 'admin')
                $config['base_url'] = base_url(). "admin/proveedor/buscar/".$campo."/".$orden."/".$limit."/".$busqueda."/";
            else    
                $config['base_url'] = base_url(). "empleados/proveedor/buscar/".$campo."/".$orden."/".$limit."/".$busqueda."/";
            $config['total_rows'] = Proveedor_model::busqueda_cantidad($busqueda);
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
    
    
    public function proveedores(){
        $proveedores = Proveedor_model::obtener('Cif', 'asc');
        foreach($proveedores as $proveedor){
            ?>
                <option value="<?=$proveedor->Cif;?>"><?=$proveedor->RazonSocial;?></option>
            <?php
        }
    }
    
  
}

?>
        
