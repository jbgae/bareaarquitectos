<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Carro extends MY_Controller{
    
    public function __construct() {
        parent:: __construct();
        $this->load->library('cart');
        $this->load->library('session');
        $this->load->model('presupuesto_model');
        $this->load->helper('form');
        $this->permisos('cliente');
        
        //$this->output->enable_profiler('TRUE'); 
    }
    
    public function mostrarCarro(){        
        $this->pagina = 'carro';
        $this->carpeta = 'cliente';
        $this->titulo = 'mi cesta';
        $this->estilo = array('general', 'menu_cliente', 'listado%20presupuesto');
        
        $this->mostrar();
    }
    
    
    public function anadirCarro($codigo){ 
        if(Presupuesto_model::existe($codigo)){
            $presupuesto = new Presupuesto_model;
            $presupuesto->datos($codigo);            
            
            $datos = array(
                'id' => $codigo,
                'qty'=> '1',
                'price' => $presupuesto->precio(),
                'name' => 'Proyecto '. $presupuesto->direccion().' '. $presupuesto->ciudad().' '.$presupuesto->Provincia
            );
           
            $this->cart->insert($datos);
           
        }
        redirect('cliente/presupuesto/listado');
    }
    
     public function actualizarCarro($codigo){

        $datos = array(
            'rowid' => $codigo,
            'qty'=> '0'                
        );

        $this->cart->update($datos);

        redirect('cliente/cesta');
    }
    
    private function _borrarCarro(){
        $this->cart->destroy();
    }
    
    public function exito(){
        $this->pagina = 'mensaje';
        $this->carpeta = 'cliente';
        $this->titulo = 'Éxito';
        $this->estilo = array('menu_cliente', 'general','resultado');
        $this->javascript =  array('menu_cliente', 'jquery-ui', 'tamanyo', 'confirmacion');
        $datos['mensaje'] = '<div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert">
                                    &times;
                                </button> 
                                <h4>Éxito.</h4>  
                                La operación se ha realizado correctamente. 
                                Recibirá un correo electrónico para notificarle que su proyecto está listo.
                             </div>';
        
        if($this->_comprobarCompra()){
            $presupuesto = new Presupuesto_model;
            $presupuestos = array();
            $opciones = array('0'=>'','1'=>'Obra Nueva','2'=>'Peritación', '3'=>'Rehabilitación', '4'=>'Adecuación de local', '5'=>'Tasación', '6'=>'Informe', '7'=>'Auditoría energética');
            //$datos = array('Estado'=>'Aceptado');
            
            foreach ($this->cart->contents() as $item){                
               array_push($presupuestos,  array(
                        'codigo'   => $item['id'],
                        'cliente'  => $presupuesto->nombreCliente($item['id']),
                        'email'    => htmlentities(strip_tags($presupuesto->email($item['id']))),
                        'estado'   => 'Aceptado',
                        'Fecha'    => date('d M Y',strtotime($presupuesto->fechaSolicitud($item['id']))),
                        'Hora'     => date('H:i A',strtotime($presupuesto->fechaSolicitud($item['id']))),
                        'tipo'     => $opciones[$presupuesto->tipo($item['id'])],
                        'direccion'=> $presupuesto->direccion($item['id']),
                        'ciudad'   => $presupuesto->ciudad($item['id']),
                        'provincia'=> $presupuesto->provincia($item['id'])
                ));             
                $presupuesto->actualizar($item['id'], array('estado'=> 'Aceptado'));
                
            }
            $this->pusher->trigger('private-notificaciones', 'presupuesto-comprar', $presupuestos);
            $this->_borrarCarro();
            $this->mostrar($datos);
        }
        else{
            $this->error();
        }
        
    }
    
    public function error(){ 
        $this->pagina = 'mensaje';
        $this->carpeta = 'cliente';
        $this->titulo = 'Error';
        $this->estilo = array('menu_cliente', 'general','resultado');
        $this->javascript =  array('menu_cliente', 'jquery-ui', 'tamanyo', 'confirmacion');
        
        $datos['mensaje'] = '<div class="alert alert-error">
                                <button type="button" class="close" data-dismiss="alert">
                                    &times;
                                </button> 
                                <h4>Error.</h4> 
                                La operación no se ha realizado correctamente. 
                                Inténtelo de nuevo más tarde.
                             </div>';
        $this->_borrarCarro();
        $this->mostrar($datos);
    }
    
    private function _comprobarCompra(){
        if($_POST){
            // Obtenemos los datos en formato variable1=valor1&variable2=valor2&...
            $raw_post_data = file_get_contents('php://input');
 
            // Los separamos en un array
            $raw_post_array = explode('&',$raw_post_data);
 
            // Separamos cada uno en un array de variable y valor
            $myPost = array();
            foreach($raw_post_array as $keyval){
                $keyval = explode("=",$keyval);
                if(count($keyval) == 2)
                    $myPost[$keyval[0]] = urldecode($keyval[1]);
            }
 
            // Nuestro string debe comenzar con cmd=_notify-validate
            $req = 'cmd=_notify-validate';
            if(function_exists('get_magic_quotes_gpc')){
                $get_magic_quotes_exists = true;
            }
            foreach($myPost as $key => $value){
                // Cada valor se trata con urlencode para poder pasarlo por GET
                if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                    $value = urlencode(stripslashes($value));
                } else {
                    $value = urlencode($value);
                }

                //Añadimos cada variable y cada valor
                $req .= "&$key=$value";
            }
            $ch = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');         // Si no usamos SandBox, debemos cambiar esta linea.
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
 
            if( !($res = curl_exec($ch)) ) {
                // Ooops, error. Deberiamos guardarlo en algún log o base de datos para examinarlo después.
                curl_close($ch);
                return false;
            }
            curl_close($ch);
            if (strcmp ($res, "VERIFIED") == 0) {
                return true;
                /**
                 * A partir de aqui, deberiamos hacer otras comprobaciones rutinarias antes de continuar. Son opcionales, pero recomiendo al menos las dos primeras. Por ejemplo:
                 *
                 * * Comprobar que $_POST["payment_status"] tenga el valor "Completed", que nos confirma el pago como completado.
                 * * Comprobar que no hemos tratado antes la misma id de transacción (txd_id)
                 * * Comprobar que el email al que va dirigido el pago sea nuestro email principal de PayPal
                 * * Comprobar que la cantidad y la divisa son correctas
                 */
            } 
            else if (strcmp ($res, "INVALID") == 0) {
                return false;
            }
        } 
        else {    
            return false;
        }
    }
}