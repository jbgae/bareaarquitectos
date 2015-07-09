<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empresa extends MY_Controller{

    public function __construct() {
        parent:: __construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');        
        $this->load->model('empresa_model');
    }
    
    public function borrar($cif = ''){
        $this->permisos('admin');
        
        if($cif != ''){
            if(Empresa_model::existe(urldecode($cif))){
                $empresa = new Empresa_model;
                $empresa->cif($cif);
                $empresa->eliminar();
            }
        }
        else{
            if($this->input->post('checkbox') != ''){            
                $cifs = $this->input->post('checkbox');
                foreach($cifs as $cif){
                    if(Empresa_model::existe(urldecode($cif))){
                        $empresa = new Empresa_model;
                        $empresa->cif($cif);
                        $empresa->eliminar();
                    }
                }
            }
        }
        if($this->uri->segment(2) == 'proveedor'){
            redirect('admin/proveedor');
        }
        else{
            redirect('admin/constructora');
        }
    }
        
    public function formulario(){
        $formulario_registro = array(
            'cif'         => array('class'=>'cif', 'name'=>'cif', 'label'=>'CIF', 'maxlength'=>'9', 'size'=>'15' ,'requerido'=> TRUE, 'autofocus'=>'autofocus'),
            'razonSocial' => array('class'=>'razon', 'name'=>'razon', 'label'=>'Razón Social', 'maxlength'=>'20', 'size'=>'15','requerido'=> TRUE),
            'direccion'   => array('class'=>'direccion', 'name'=>'direccion', 'label'=>'Dirección','maxlength'=>'60', 'size'=>'15','requerido'=> FALSE),
            'provincia'   => array('class'=>'provincia', 'name'=>'provincia', 'label'=>'Provincia','maxlength'=>'60', 'size'=>'15','requerido'=> FALSE),
            'ciudad'      => array('class'=>'ciudad', 'name'=>'ciudad', 'label'=>'Ciudad', 'maxlength'=>'60', 'size'=>'15','requerido'=> FALSE),        
            'email'       => array('class'=>'email', 'name'=>'email', 'label'=>'Email', 'maxlength'=>'50', 'size'=>'15','requerido'=> FALSE),        
            'telefono'    => array('class'=>'telefono', 'name'=>'telefono', 'label'=>'Teléfono', 'maxlength'=>'9', 'size'=>'15','requerido'=> FALSE),        
            'fax'         => array('class'=>'fax', 'name'=>'fax', 'label'=>'Fax', 'maxlength'=>'9', 'size'=>'15','requerido'=> FALSE),
            'descripcion' => array('class'=>'descripcion', 'name'=>'descripcion', 'label'=>'Descripción', 'requerido'=> FALSE),
            'web'         => array('class'=>'web', 'name'=>'web', 'label'=>'Página web', 'requerido'=> FALSE)
        );
        
        return $formulario_registro;
    }
 
    public function boton(){        
        $boton = array('name'=>'button', 'id'=>'boton_registro', 'value'=>'Enviar', 'class'=>'btn btn-info');
        
        return $boton;
    }
    
     
    
    
}

?>