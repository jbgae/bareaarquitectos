<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Evento extends MY_Controller{
     
    public function __construct() {
        parent:: __construct();
        
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('evento_model', 'Evento');
        $this->load->model('proyecto_model');
        $this->load->model('tarea_model');
        
        $this->form_validation->set_error_delimiters('<div class="alert alert-error"> <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <h4>Error</h4>', '</div>');        
    }
    
    public function calendarioMes($proyecto = 0,$year = '', $month=''){
        $this->pagina = 'calendario';
        $this->carpeta = 'empleado';
        $this->menu = 'menu_empleado_calendario';
        $this->titulo = 'Calendario';
        $this->estilo = array('backend','calendario');
        $this->javascript = 'tooltip';      
        
        if($this->uri->segment(1) == 'admin'){
            $this->permisos('admin');
            $datos['user'] = 'admin';        
            $preferencias = array(
                'show_next_prev' => TRUE,
                'next_prev_url'=> base_url()."admin/calendario/$proyecto",
                'start_day'    => 'monday',
            );
        }
        else{
            $this->permisos('empleado');            
            $datos['user'] = 'empleados';
            $preferencias = array(
                'show_next_prev' => TRUE,
                'next_prev_url'=> base_url()."empleados/calendario/$proyecto",
                'start_day'    => 'monday',
            );
        } 
        
        if($this->input->post('opciones') != ''){
            $proyecto = $this->input->post('opciones');            
        }
        $datos['proyecto'] = $proyecto;        
        
        if(Proyecto_model::existe($proyecto) || $proyecto == 0){
            $datos['opciones'] = Proyecto_model::empleado($this->session->userdata('email'));
           
            if($year ==''){
                $month = date('m');
                $year = date('Y');
            }
            
            $eventos = $this->Evento->cargar($year, $month, $this->session->userdata('email'));

            
            if($proyecto == 0)
                $fechas = $this->_cargarFechas($eventos, $year, $month);   
            else
                $fechas = $this->_cargarFechas($eventos, $year, $month, '',$proyecto);        

            $preferencias['template'] = '
               {table_open}<table class="table table-condensed table-bordered">{/table_open}

               {heading_row_start}<tr>{/heading_row_start}

               {heading_previous_cell}<th class="prev"><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
               {heading_title_cell}<th class="cabecera" colspan="{colspan}">{heading}</th>{/heading_title_cell}
               {heading_next_cell}<th class="post"><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}

               {heading_row_end}</tr>{/heading_row_end}

               {week_row_start}<tr>{/week_row_start}
               {week_day_cell}<td class="days_week">{week_day}</td>{/week_day_cell}
               {week_row_end}</tr>{/week_row_end}

               {cal_row_start}<tr>{/cal_row_start}
               {cal_cell_start}<td>{/cal_cell_start}

               {cal_cell_content}
                    <div class="days">{day}</div>
                    {content}
               {/cal_cell_content}

               {cal_cell_content_today}
                    <div class="days" id="today">{day}</div>
                    <div>{content}</div>
               {/cal_cell_content_today}

               {cal_cell_no_content}
                    <div class="days">{day}</div>
               {/cal_cell_no_content}

               {cal_cell_no_content_today}
                    <div class="days" id="today">{day}</div>
               {/cal_cell_no_content_today}

               {cal_cell_blank}&nbsp;{/cal_cell_blank}

               {cal_cell_end}</td>{/cal_cell_end}
               {cal_row_end}</tr>{/cal_row_end}

               {table_close}</table>{/table_close}
            ';

            $this->load->library('calendar', $preferencias);
            $datos['calendario'] = $this->calendar->generate($year, $month, $fechas);
        }
        else{            
            $this->error= array(
                'nivel'  => '1',
                'mensaje'=> 'No existe el proyecto indicado',
            );
        }
        
        $this->mostrar($datos);
    }
    
    public function calendarioSemana($proyecto = 0, $year = '', $month = '', $day = ''){
        $this->pagina = 'semana';
        $this->carpeta = 'empleado';
        $this->menu = 'menu_empleado_calendario';
        $this->titulo = 'Calendario';
        $this->estilo = array('backend','calendario');
        $this->javascript = 'tooltip';
        
        if($this->uri->segment(1) == 'admin'){
            $this->permisos('admin');
            $datos['user'] = 'admin';        
        }
        else{
            $this->permisos('empleado');            
            $datos['user'] = 'empleados';
        }  
            
        if($year == '') {
            $year = date('Y');
        }        
        if($month == '') {
            $month = date('m');
        }        
        if($day == '') {
            $day = date('d');        
        }
        
        if($this->input->post('opciones') != ''){
            $proyecto = $this->input->post('opciones');            
        }
        $datos['proyecto'] = $proyecto;
        
        if(Proyecto_model::existe($proyecto) || $proyecto == 0){  

            $datos['opciones'] = Proyecto_model::empleado($this->session->userdata('email'));
            
            $eventos = $this->Evento->cargar($year, $month, $this->session->userdata('email'), '', $day);
            
            if($proyecto == 0)
                $fechas = $this->_cargarFechas($eventos, $year, $month);   
            else
                $fechas = $this->_cargarFechas($eventos, $year, $month, '',$proyecto);
            
            $dias = array();
            foreach($fechas as $dia=>$fecha){
                $dias[$dia] = date(mktime(0, 0, 0, $month, $dia, $year));
            }

            $calendarPreference = array (
                            'start_day'    => 'lunes',
                            'month_type'   => 'abr',
                            'day_type'     => 'long',
                            'date'     => date(mktime(0, 0, 0, $month, $day, $year)),
                            'url' => $datos['user'].'/calendario/semana/'. $proyecto. '/',
                        ); 

            $this->load->library('calendar_week', $calendarPreference);

            $weeks = $this->calendar_week->get_week();
            $arr_Data = Array();
            for ($i=0;$i<count($weeks);$i++){
                $aux = FALSE;
                foreach($dias as $key=>$dia){                    
                    if($weeks[$i] == $dia){ 
                        $arr_Data[$weeks[$i]] = $fechas[$key];
                        $aux = TRUE;
                    }
                    elseif(!$aux){
                        $arr_Data[$weeks[$i]] = '';
                    }
                }
            }

            $datos ['data'] = $arr_Data;
        }
        else{
            $this->error= array(
                'nivel'  => '1',
                'mensaje'=> 'No existe el proyecto indicado',
            );
        }
        $this->mostrar($datos);
    }
    
    public function calendarioDia($proyecto = 0, $year = '', $month = '', $day = ''){
        $this->pagina = 'dia';
        $this->carpeta = 'empleado';
        $this->menu = 'menu_empleado_calendario';
        $this->titulo = 'Calendario';
        $this->estilo = array('backend','calendario');
        $this->javascript = 'tooltip';
        
        if($this->uri->segment(1) == 'admin'){
            $this->permisos('admin');
            $datos['user'] = 'admin';        
        }
        else{
            $this->permisos('empleado');            
            $datos['user'] = 'empleados';
        }
        
        if($year == '') {
            $year = date('Y');
        }        
        if($month == '') {
            $month = date('m');
        }        
        if($day == '') {
            $day = date('d');        
        }
        
        if($this->input->post('opciones') != ''){
            $proyecto = $this->input->post('opciones');            
        }
        $datos['proyecto'] = $proyecto;
        
        if(Proyecto_model::existe($proyecto) || $proyecto == 0){
            $datos['opciones'] = Proyecto_model::empleado($this->session->userdata('email'));
                
            $arrayMeses = array('','Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

            $arrayDias = array( 'Domingo', 'Lunes', 'Martes','Miércoles', 'Jueves', 'Viernes', 'Sábado');
            $fecha = $year.'-'.$month.'-'.$day;        

            $datos['eventos'] = $this->Evento->evento($fecha, $this->session->userdata('email'));
            $tareas =array();
            if($proyecto == 0)
                $datos['tareas'] = $this->_cargarFechas($tareas, $year, $month, $day);
            else
                $datos['tareas'] = $this->_cargarFechas($tareas, $year, $month, $day, $proyecto);

            $mes = date('m', strtotime($fecha));
            if($mes < 10)
                $mes = $mes % 10;
            $fecha = $arrayDias[date('w',strtotime($fecha))].", ".date('d',strtotime($fecha))." de ".$arrayMeses[$mes]." de ".date('Y',strtotime($fecha));            
            $datos['fecha'] = $fecha;
            $fecha = $month.'/'.$day.'/'.$year;
            $datos['yearAdd'] = date('Y', strtotime($fecha ."+1 day" ));
            $datos['yearLess'] = date('Y', strtotime($fecha . "-1 day"));
            $datos['monthAdd'] = date('m', strtotime($fecha . "+1 day"));
            $datos['monthLess'] = date('m', strtotime($fecha . "-1 day"));
            $datos['dayAdd'] = date('d', strtotime($fecha . "+1 day"));
            $datos['dayLess'] = date('d', strtotime($fecha . "-1 day"));
        }
        else{
            $this->error = array(
                'nivel'=>'1',
                'mensaje' => 'No existe el proyecto indicado'
            );
        }
        
        $this->mostrar($datos);
    }
    
    public function registrar(){
        $this->pagina = 'crear cita';
        $this->carpeta = 'empleado';
        $this->menu = 'menu_empleado_calendario';
        $this->titulo = 'Nueva cita';
        $this->estilo = 'jquery-ui';
        $this->javascript = array('jquery.validate.min','validarCita','jquery-ui', 'fecha');
        
        if($this->uri->segment(1) == 'admin'){
            $this->permisos('admin');
            $datos['user'] = 'admin';
        }
        else{
            $this->permisos('empleado');
            $datos['user'] = 'empleados';
        }
        
        
        $datos['formulario'] = array(
            'cita' => array('class'=> 'cita', 'name'=>'Cita', 'label'=>'cita','maxlength'=>'100', 'size'=>'15', 'requerido' => TRUE, 'autofocus'=>'autofocus' ),
            'fecha' => array('class'=>'fecha input-small', 'name'=>'Fecha', 'label'=>'fecha', 'requerido' => TRUE),            
            'hora' => array('class'=>'hora input-small', 'name'=>'hora', 'label'=>'hora', 'requerido'=>FALSE, 'placeholder'=>'hh:mm'),
            'tipo' => array('class'=>'tipo', 'name'=>'Tipo', 'label'=>'Tipo de cita', 'requerido' => TRUE ),
            'descripcion' => array('class'=>'tipo', 'name'=>'Descripcion', 'label'=> 'Descripción', 'requerido' => FALSE)            
        );
        
        $datos['tipos'] = array('personal', 'trabajo');        
        $datos['boton'] = array( 'class'=> 'btn btn-info', 'name'=>'button', 'id' => 'boton_calendario');
              
        if($this->_validar()){
            if($this->Evento->inicializar()){
                $this->exito = 'La cita ha sido registrada satisfactoriamente';
            }
            else{
                 $this->error = array(
                     'nivel' => '1',
                     'mensaje' =>'La cita no ha sido registrada. Por favor inténtelo de nuevo más tarde'
                 );
            }
        }
                   
        $this->mostrar($datos);                 
    }
    
    private function _validar(){
        $this->form_validation->set_rules('Cita', 'Cita', 'trim|required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('Descripcion', 'Descripción', 'trim|min_length|xss_clean');
        $this->form_validation->set_rules('Fecha', 'Fecha', 'trim|xss_clean|required');
        $this->form_validation->set_rules('hora', 'hora', 'trim|xss_clean|callback_validar_hora');
        
        $this->form_validation->set_message('required', '%s no puede estar vacio');
        $this->form_validation->set_message('min_length', '%s debe tener mínimo %s caracteres');
        $this->form_validation->set_message('xss_clean', ' %s no es válido');
        
        return $this->form_validation->run();
    }
    
    private function _cargarFechas($eventos, $year, $month, $day='', $proyecto = ''){
               
        if($proyecto == ''){ 
            if($this->session->userdata('usuario') == 'admin'){ 
                $proyectoFechas = Proyecto_model::obtenerFechas($year, $month, $day);
                $tareasFechas = Tarea_model::obtenerFechas($year, $month, $day);
               
            }
            else{
                $proyectoFechas = Proyecto_model::obtenerFechas($year, $month, $day,$this->session->userdata('email'));
                $tareasFechas = Tarea_model::obtenerFechas($year, $month, $day, $this->session->userdata('email'));  
            }
        }
        else{ 
            $proyectoAux = new Proyecto_model;
            $datosProyecto = $proyectoAux->datos($proyecto);
            if($proyectoAux->pertenece($this->session->userdata('email'))){
                $proyectoFechas= array();
                array_push($proyectoFechas,$datosProyecto);            
            
                if($this->session->userdata('usuario') == 'admin'){ 
                    $tareasFechas = Tarea_model::obtenerFechas($year, $month, $day,'', $proyecto);
                }
                else{
                    $tareasFechas = Tarea_model::obtenerFechas($year, $month, $day,$this->session->userdata('email'), $proyecto);
                }
            }
                
        }
       
        if($day == ''){
            foreach($proyectoFechas as $proyecto){
                if($this->_mismoMes($proyecto->FechaComienzo, $year, $month)){
                    $diaI = substr($proyecto->FechaComienzo, 8, 2); 
                    if($diaI < 10) 
                        $diaI = $diaI % 10;                 

                    $asuntoI = $proyecto->NombreProyecto; 
                    if(array_key_exists($diaI, $eventos)){ 
                        if(is_array($eventos[$diaI]))
                                array_push($eventos[$diaI],'<div class="event"><span class="text-info inicio">'. $asuntoI . '</span></div>' );
                        else{
                            $aux = $eventos[$diaI];
                            $eventos[$diaI] = array();
                            array_push($eventos[$diaI],$aux);
                            array_push($eventos[$diaI],'<div class="event"><span class="text-info inicio">'. $asuntoI . '</span></div>' );
                        }
                    }
                    else{ 
                        $eventos[$diaI] = '<div class="event"><span class="text-info inicio">'. $asuntoI . '</span></div>';    
                    }  
                }

                if($this->_mismoMes($proyecto->FechaFinPrevista, $year, $month)){
                    $diaF = substr($proyecto->FechaFinPrevista, 8, 2);
                    if($diaF < 10) 
                        $diaF = $diaI % 10; 
                    $asuntoF = $proyecto->NombreProyecto; 
                    if(array_key_exists($diaF, $eventos)){ 
                        if(is_array($eventos[$diaF]))
                                array_push($eventos[$diaF],'<div class="event"><span class="text-info fin">'. $asuntoF . '</span></div>' );
                        else{
                            $aux = $eventos[$diaF];
                            $eventos[$diaF] = array();
                            array_push($eventos[$diaF],$aux);
                            array_push($eventos[$diaF],'<div class="event"><span class="text-info fin">'. $asuntoF . '</span></div>' );
                        }
                    }
                    else{
                        $eventos[$diaF] = '<div class="event"><span class="text-info fin">'. $asuntoF . '</span></div>';    
                    } 
                }                
            }
            foreach($tareasFechas as $tarea){
                if($this->_mismoMes($tarea->FechaCreacion, $year, $month)){
                    $diaI = substr($tarea->FechaCreacion, 8, 2);
                    if($diaI < 10) 
                        $diaI = $diaI % 10;
                    $asuntoI = $tarea->Titulo; 
                    if(array_key_exists($diaI, $eventos)){ 
                        if(is_array($eventos[$diaI]))
                                array_push($eventos[$diaI],'<div class="event"><span class="text-info tareaInicio">'. $asuntoI . '</span></div>' );
                        else{
                            $aux = $eventos[$diaI];
                            $eventos[$diaI] = array();
                            array_push($eventos[$diaI],$aux);
                            array_push($eventos[$diaI],'<div class="event"><span class="text-info tareaInicio">'. $asuntoI . '</span></div>' );
                        }
                    }
                    else{
                        $eventos[$diaI] = '<div class="event"><span class="text-info tareaInicio">'. $asuntoI . '</span></div>';    
                    }  
                }

                if($this->_mismoMes($tarea->FechaLimite, $year, $month)){
                    $diaF = substr($tarea->FechaLimite, 8, 2);
                    if($diaF < 10) 
                        $diaF = $diaF % 10;
                    $asuntoF = $tarea->Titulo; 
                    if(array_key_exists($diaF, $eventos)){ 
                        if(is_array($eventos[$diaF]))
                                array_push($eventos[$diaF],'<div class="event"><span class="text-info tareaFin">'. $asuntoF . '</span></div>' );
                        else{
                            $aux = $eventos[$diaF];
                            $eventos[$diaF] = array();
                            array_push($eventos[$diaF],$aux);
                            array_push($eventos[$diaF],'<div class="event"><span class="text-info tareaFin">'. $asuntoF . '</span></div>' );
                        }
                    }
                    else{
                        $eventos[$diaF] = '<div class="event"><span class="text-info tareaFin">'. $asuntoF . '</span></div>';    
                    } 
                }                
            }            
        }
        else{
            foreach($proyectoFechas as $proyecto){
                if(date("Y-m-d",strtotime($proyecto->FechaComienzo)) == date("Y-m-d",strtotime("$year-$month-$day"))){
                    array_push($eventos, '<div class="span5 inicioProyecto"> Se ha registrado el proyecto: '. ucfirst($proyecto->NombreProyecto) .'</div>');
                }
                if(date("Y-m-d",strtotime($proyecto->FechaFinPrevista)) == date("Y-m-d",strtotime("$year-$month-$day"))){
                    array_push($eventos, '<div class="span5 finProyecto"> Está previsto que el proyecto '. ucfirst($proyecto->NombreProyecto) .' termine hoy</div>');
                }
            }
            foreach($tareasFechas as $tarea){ 
                if(date("Y-m-d",strtotime($tarea->FechaLimite)) == date("Y-m-d",strtotime("$year-$month-$day"))){
                    array_push($eventos, '<div class="span5 inicioTarea"> Se ha registrado la tarea: '. ucfirst($tarea->Titulo) .'</div>');
                }
                if(date("Y-m-d",strtotime($tarea->FechaLimite)) == date("Y-m-d",strtotime("$year-$month-$day"))){
                    array_push($eventos, '<div class="span5 finTarea"> Está previsto que la tarea '. ucfirst($tarea->Titulo) .' termine hoy</div>');
                }
            }
        }
        
        return $eventos;
    }
    
    
    private function _mismoMes($fecha, $year, $month){
        $aux = FALSE;
        
        if(date('Y',strtotime($fecha)) == date("$year")){ 
            if(date('m',strtotime($fecha)) == date("$month")){ 
                if(date('d',strtotime($fecha)) >= 1 && date('d',strtotime($fecha)) <= 31){
                    $aux = TRUE;
                }
            }
        }
        
        return $aux;
    }
    
    public function validar_hora($str = ''){

        if($str != ''){
            list($hh, $mm) = explode(':', $str);

            if (!is_numeric($hh) || !is_numeric($mm)){
                $this->form_validation->set_message('validar_hora', 'Hora no válida');
                return FALSE;
            }
            else if ((int) $hh > 24 || (int) $mm > 59){
                $this->form_validation->set_message('validar_hora', 'Hora no válida');
                return FALSE;
            }
            else if (mktime((int) $hh, (int) $mm) === FALSE){
                $this->form_validation->set_message('validar_hora', 'Hora no válida');
                return FALSE;
            }
        }
        else
            return TRUE;
    }
}
?>
