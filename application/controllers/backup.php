<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Backup extends MY_Controller{
     public function __construct() {
        parent:: __construct();
        $this->load->helper('form');
        $this->load->helper('file');
        $this->load->helper('directory');
        $this->load->library('form_validation');        
        $this->load->library('zip'); 
        $this->load->dbforge();
    } 
    
    public function copia(){ 
        $this->permisos('admin');
        $this->pagina = 'copia';
        $this->estilo = 'copia';
        $this->carpeta = 'administrador';
        $this->javascript = array('bootstrap.file-input', 'barra');
        $this->titulo = 'Copia de seguridad';
        $datos['formulario'] = array();
        
        $archivos = get_dir_file_info(getcwd().'/backups');
        
        $datos['archivos'] = array('0'=>'');
        if(!empty($archivos)){
            foreach($archivos as $archivo){
                if(substr(strrchr($archivo['name'], '.'), 1) == 'zip'){
                    $arch = explode(".",$archivo['name']);
                    array_push($datos['archivos'], str_replace('_',':',$arch[0]));
                }
            }
        }
        
        $datos['prueba'] = $this->config->base_url() . 'archivos/backups/';
        $datos['fecha'] = date("Y-m-d H_i_s");
        $this->mostrar($datos);
    }
    
    public function copiaSeguridad(){ 
        $this->load->dbutil();
        $this->load->helper('download');
        
        $this->permisos('admin');
        
        $path = getcwd(); 
        
        $fecha = date("Y-m-d H_i_s"); 
        
        if(!is_dir(realpath("$path/backups"))){           
            mkdir("$path/backups", 0755);  
        }
        if(!is_dir(realpath("$path/backups/$fecha"))){           
            mkdir("$path/backups/$fecha", 0755);  
        }
        
        $this->_copiar("$path/archivos", "$path/backups/$fecha");
        $this->_copiar("$path/images/fotos", "$path/backups/$fecha");
        $this->_copiar("$path/images/paginas", "$path/backups/$fecha");
        
       
        $prefs = array(     
                'format'   => 'txt',             
                'filename' => 'backupTablas-'. $fecha .'.sql',
                'add_drop' => FALSE,
                'tables'   => array('Provincia', 'Ciudad', 'Consultor','Usuario', 
                    'Empresa','ci_sessions', 'captcha','Constructora', 'Empleado',
                    'Evento','Proveedor','Administrador','Chat','Noticia','Pagina',
                    'Presupuesto','LineaPresupuesto','Proyecto','ProyectoEmpleados',
                    'Tarea', 'Texto','Respuesta','Archivo','Material', 'EmpleadoTarea', 
                    'Nota', 'NotaEmpleados')
                 );
        $prefs1 = array(
                'format'   => 'txt',             
                'filename' => 'backupVistas-'. $fecha .'.sql',
                'add_drop' => FALSE,
                'add_insert' => FALSE,
                'tables'   => array('Empresas', 'Notas', 'Noticias', 'Presupuestos',
                    'Proyectos','Respuestas', 'Usuarios', 'Archivos', 'Constructoras',
                    'Empleados','Materiales', 'NotasEmpleados', 'Paginas', 'Proveedores',                     
                    'ProyectosEmpleados', 'Tareas', 'Consultores', 'Clientes', 'Chat_mensajes')
              );
        
        $backup = &$this->dbutil->backup($prefs);
        $backup1 = &$this->dbutil->backup($prefs1);
        $save = "$path/backups/$fecha/".$prefs['filename'];      
        $save1 = "$path/backups/$fecha/".$prefs1['filename'];      
        write_file($save,$backup);
        write_file($save1,$backup1);
        
        $this->zip->read_dir("$path/backups/$fecha/", FALSE);
        $this->zip->archive("$path/backups/$fecha.zip");

        $this->_eliminar("$path/backups/$fecha");
        $this->copia();
    }
    
    public function restaurar(){
        if(!$this->input->is_ajax_request()){
            redirect('404');
        }

        if($this->input->post('backups') != '0'){
            $path = getcwd();

            if(!is_dir(realpath("$path/backups/tmp"))){           
                mkdir("$path/backups/tmp", 0755);
                log_message('info', 'Carpeta "tmp" creada correctamente. ');
            }

            $archivos = get_dir_file_info("$path/backups");
            $files = array('0'=>'');
            if(!empty($archivos)){
                foreach($archivos as $archivo){
                    if(substr(strrchr($archivo['name'], '.'), 1) == 'zip')
                        array_push($files, $archivo);
                }
            }
            
            $nombre = explode(".", $files[$this->input->post('backups')]['name']);
            
            $zip = new ZipArchive;

            $zip->open("$path/backups/".$files[$this->input->post('backups')]['name']);
            $zip->extractTo("$path/backups/tmp/", array($nombre[0]."/backupTablas-".$nombre[0].".sql",$nombre[0]."/backupVistas-".$nombre[0].".sql"));
            log_message('info', 'La extracción del .zip se ha realizado correctamente'); 
            
            
           /* if(is_file("$path/backups/tmp/".$nombre[0]."/backupTablas-$nombre[0].sql") &&
               is_file("$path/backups/tmp/".$nombre[0]."/backupVistas-$nombre[0].sql")){
            
                $this->dbforge->drop_database('EstudioArquitectura');
                log_message('info', "Se ha destruido la base de datos");
                $this->dbforge->create_database('EstudioArquitectura');
                log_message('info', "Se ha creado la base de datos");
                $backup = read_file("$path/backups/tmp/".$nombre[0]."/backupTablas-$nombre[0].sql");
                $backup .= read_file("$path/backups/tmp/".$nombre[0]."/backupVistas-$nombre[0].sql");
                log_message('info', "Se ha leido los archivos de tablas y listas");
                $sql_clean = '';

                $this->db->query('use EstudioArquitectura');

                foreach (explode("\n", $backup) as $line){
                    if($line != 'utf8_general_ci;'){
                        if(isset($line[0]) && $line[0] != "#"){
                            $sql_clean .= $line."\n";
                        }
                    }
                }

                foreach (explode(";\n", $sql_clean) as $sql){
                    $sql = trim($sql);
                    if($sql){
                        $sql = str_replace("ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER", "", $sql);
                        if(!$this->db->query($sql)){
                            log_message('error', 'La operación:' .$this->db->last_query(). 'no se ha realizado');
                        }
                    }
                    log_message('info', "Se ha realizado la siguiente operación:" .$this->db->last_query());
                }  
            } 
            */
           
            /*$this->_eliminar("$path/archivos/presupuestos");
            log_message('info', "Carpeta presupuestos eliminada");
            $this->_eliminar("$path/archivos/proyectos");
            log_message('info', "Carpeta proyectos eliminada");
            $this->_eliminar("$path/archivos/tareas");
            log_message('info', "Carpeta tareas eliminada");*/
            
             if($zip->extractTo("$path/archivos/", array($nombre[0]."/presupuestos/", $nombre[0]."/proyectos/",$nombre[0]."/tareas/" )))
                log_message('info', "Carpetas extraídas");
            else
                log_message('info', "Carpetas NO extraídas");
            
           
            
            $this->_eliminar("$path/images/thumb");
            log_message('info', "Carpeta fotos eliminada");
            $zip->extractTo("$path/images/", array($nombre[0]."/thumb"));
            log_message('info', "Carpeta fotos copiada");
            
                      
            //$this->_eliminar("$path/backups/tmp");
            log_message('info', "Se ha eliminado la carpeta temporal");
            $zip->close();
            echo '<div class="alert alert-success span9">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h4>Éxito</h4>
                    Se ha restaurado correctamente al punto indicado.
                    </div>';
        }
        
        
        
    } 

    
    private function _copiar($archivo, $destino) {
        //if(file_exists($archivo)){
            if(is_dir($archivo)){ 
                @mkdir($destino); 
                $d = dir($archivo); 
                while(($entry = $d->read()) != FALSE){ 
                    if( $entry != '.' && $entry != '..' ) { 
                        $Entry = $archivo . '/' . $entry; 
                        if(is_dir( $Entry )){ 
                            $this->_copiar( $Entry, $destino . '/' . $entry );
                        }
                        else{
                            copy($Entry, $destino . '/' . $entry);
                        }
                    }         
                } 
                $d->close();             
            }
            else { 
                copy($archivo, $destino);            
            }
       // }
    }
    
    
    private function _eliminar($dir){
     
        $aux = FALSE;

	if(file_exists($dir)){
            $dh = opendir($dir);
            while ($file=readdir($dh)){
                if ($file!="." && $file!=".."){
                    $fullpath=$dir."/".$file;
                    if (!is_dir($fullpath)){
                        unlink($fullpath);
                    } 
                    else{
                        $this->_eliminar($fullpath);
                    }
                }
            }
            closedir($dh);
            if (rmdir($dir)){
                $aux = TRUE;
            } 
	}
	
        return $aux;
        
        
    }

} 