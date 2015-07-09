<?php

class Lineas_presupuesto_model extends CI_Model{
    
    var $Concepto = '';
    var $Cantidad = '';
    var $Descuento = '';
    
    public function __construct() {
        parent::__construct();        
        $this->load->database();
    }
    
    public function inicializar($codigoPresupuesto){
                
        $concepto = array('Redacción de proyecto básico', 
                          'Redacción de proyecto de ejecución', 
                          'Dirección de obras', 
                          'Dirección de ejecución de obras', 
                          'Coordinación de seguridad y salud');
        
        $cantidad = $this->input->post('cantidad');
        $descuento = $this->input->post('descuento');
        
        if($cantidad != ''){
            if(is_array($cantidad)){
                for($i = 0; $i < count($cantidad); $i++){
                    $data = array(
                        'CodigoPresupuesto'=> $codigoPresupuesto,
                        'Concepto'=> $concepto[$i],
                        'Cantidad' => $cantidad[$i],
                        'Descuento' => empty($descuento[$i]) ?  0 : $descuento[$i] 
                    );
                    $this -> db -> insert('LineaPresupuesto', $data);
                }
                return TRUE;
            }
            else{
                $data = array(
                    'CodigoPresupuesto'=> $codigoPresupuesto,
                    'Concepto'=> $concepto[0],
                    'Cantidad' => $cantidad,
                    'Descuento' => empty($descuento) ?  0 : $descuento
                );
                if($this -> db -> insert('LineaPresupuesto', $data)){
                    return TRUE;
                }
                else {
                    return FALSE;
                }
            }
        }
        
        else{            
            return FALSE;
        }
    }
    
    public function obtener($codigoPresupuesto){
        $query = $this->db->get_where('LineaPresupuesto', array('CodigoPresupuesto'=>$codigoPresupuesto));
        $lineas = $query->result();
        return $lineas;    
    }
        
}

?>