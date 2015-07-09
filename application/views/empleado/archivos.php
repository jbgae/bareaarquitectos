<div class ="span11"> 
    <?php if(isset($error)):?>
        <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">
                &times;
            </button> 
            <h4>Error.</h4><?= $error;?>
        </div>
    <?php endif;?>
    
    <?php if(empty($archivos)):?>
        <ul class="breadcrumb">
            <li>
                <a href="<?= base_url().$user;?>/proyecto/archivos/<?=$codigo;?>">Archivos</a> 
                <span class="divider">/</span>
            </li>
            <?php if(isset($enlaces)):?>
                <?php foreach($enlaces as $cod => $nombreCarp):?>
                    <li><a href="<?=base_url().$user;?>/proyecto/archivos/<?=$codigo;?>/<?= $cod;?>"><?= ucfirst($nombreCarp);?></a> <span class="divider">/</span></li>
                <?php endforeach;?>
            <?php endif;?>        
            <?php if(isset($nombreCarpeta)):?>    
                <li class="active"><?= ucfirst($nombreCarpeta);?></li>
            <?php endif;?>    
        </ul>
    
        <div class="btn-toolbar">
            <div class="btn-group">
                <a href="#myModal" data-toggle="modal" class="btn btn-small"> <i class="icon-folder-close"></i> Crear Carpeta</a>
                <a href="#myModal2" data-toggle="modal" class="btn btn-small"> <i class="icon-file"></i> Crear Archivo</a>
            </div>
        </div>
         
        <?php if(isset($codigoCarpeta)):?> 
            <div class="alert alert-warning" id ="alerta-<?=$codigo.$codigoCarpeta;?>">
        <?php else:?>        
            <div class="alert alert-warning" id ="alerta-<?=$codigo;?>">
        <?php endif;?>        
            <button type="button" class="close" data-dismiss="alert">
                &times;
            </button> 
            <h4>Advertencia</h4>
            <?php if($this->uri->segment('5') != ''):?>
                Actualmente, esta carpeta no dispone de archivos
            <?php else:?>    
                Actualmente, este proyecto no dispone de archivos
            <?php endif;?>
        </div>
        <?php if(isset($codigoCarpeta)):?>         
            <div id="insertarTabla<?=$codigo.$codigoCarpeta;?>"></div>
        <?php else:?>    
            <div id="insertarTabla<?=$codigo;?>"></div>        
        <?php endif;?>    
    <?php else: ?>
         <ul class="breadcrumb">
            <li>
                <a href="<?= base_url().$user;?>/proyecto/archivos/<?=$codigo;?>">
                    Archivos
                </a> 
                <span class="divider">/</span>
            </li>
            <?php if(isset($enlaces)):?>
                <?php foreach($enlaces as $cod => $nombreCarp):?>
                    <li><a href="<?=base_url().$user;?>/proyecto/archivos/<?=$codigo;?>/<?= $cod;?>"><?= ucfirst($nombreCarp);?></a> <span class="divider">/</span></li>
                <?php endforeach;?>
            <?php endif;?>        
            <?php if(isset($nombreCarpeta)):?>    
                <li class="active"><?= ucfirst($nombreCarpeta);?></li>
            <?php endif;?>    
        </ul>
         
    
        <div class="btn-toolbar">
            <div class="btn-group">
                <!--<button id="carpeta" class="btn btn-small" onclick="location.pathname=''"><i class="icon-folder-close"></i> Crear carpeta </button>-->
                <a href="#myModal" data-toggle="modal" class="btn btn-small"> <i class="icon-folder-close"></i> Crear Carpeta</a>
                <a href="#myModal2" data-toggle="modal" class="btn btn-small"> <i class="icon-file"></i> Crear Archivo</a>
                <?php if(isset($codigoCarpeta)):?>
                    <a id="zip" class="btn btn-small" href='<?=base_url().$user;?>/proyecto/descargar/archivos/<?=$codigo;?>/<?= $codigoCarpeta;?>'><i class="icon-download"></i> Descargar como .zip</a>
                <?php else:?>     
                    <a id="zip" class="btn btn-small" href='<?=base_url().$user;?>/proyecto/descargar/archivos/<?=$codigo;?>'><i class="icon-download"></i> Descargar como .zip</a>
                <?php endif;?>    
                <?php if($user =='admin' && $estado != 'Cerrado'):?>
                    <a id="eliminar" class="btn btn-danger btn-small" href="<?=base_url().$user;?>/proyecto/archivos/eliminar/<?=$codigo;?>" data-confirm="Se dispone a eliminar todos los archivos del proyecto. ¿Desea continuar?"><i class="icon-trash icon-white"></i> Eliminar todo</a>
                <?php endif;?>
            </div>
        </div>
    
       
    
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Tamaño</th>
                    <th>Fecha creación</th>
                    <th>Subido por</th>
                    <th></th>
                </tr>
            </thead>
            <?php if(isset($codigoCarpeta)):?>
                <tbody id="tabla-archivos-<?=$codigo.$codigoCarpeta;?>">
            <?php else:?>
                <tbody id="tabla-archivos-<?=$codigo;?>">
            <?php endif;?>        
            <?php if(isset($archivos)): ?>
                <?php $i = 0;?>
                <?php foreach($archivos as $archivo): ?> 
                    <tr>                            
                        <td>
                            <div class="archivo">
                               <?php if($archivo->Extension == '.jpg' || $archivo->Extension == '.png' || $archivo->Extension == '.jpeg' || $archivo->Extension == '.JPG'  || $archivo->Extension == '.png' || substr(strrchr($archivo->Ruta, "."),1) == '.JPEG' || substr(strrchr($archivo->Ruta, "."),1) == '.png' || $archivo->Extension == '.PNG'):?>
                                    <img src="<?=base_url();?>/images/image.png">
                                    <a data-toggle="lightbox" href="#Lightbox<?= $i;?>"><h5><?= ucfirst($archivo->Nombre);?><?= $archivo->Extension;?></h5> </a>                                     
                                    <div id="Lightbox<?= $i;?>" class="lightbox hide fade" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class='lightbox-content'>
                                            <img src="<?= $archivo->Ruta;?>">
                                        </div>
                                    </div>
                                    <?php $i++;?>
                                <?php elseif($archivo->Extension == '.pdf' ):?>
                                    <img src="<?=base_url();?>/images/file_pdf.png">
                                    <h5><?= ucfirst($archivo->Nombre);?><?= $archivo->Extension;?></h5>
                                <?php elseif($archivo->Extension == ''):?>
                                    <img src="<?=base_url();?>/images/folder.png">
                                    <a href="<?= base_url().$user;?>/proyecto/archivos/<?= $codigo;?>/<?= $archivo->Codigo;?>">
                                        <h5><?= ucfirst($archivo->Nombre);?></h5>
                                    </a>
                                <?php elseif($archivo->Extension == '.txt'):?>
                                    <img src="<?=base_url();?>/images/document-text.png" id="iconoTexto">
                                    <a href="<?= base_url().$user;?>/proyecto/archivos/editar/<?= $codigo;?>/<?= $archivo->Codigo;?>">
                                        <h5><?= ucfirst($archivo->Nombre);?><?= $archivo->Extension;?></h5>
                                    </a>                                                  
                                <?php else:?>
                                    <img src="<?=base_url();?>/images/document-text.png" id="iconoTexto">
                                    <h5><?= ucfirst($archivo->Nombre);?><?= $archivo->Extension;?></h5>
                                <?php endif;?>                         
                            </div>
                        </td>
                        <td>
                            <?php if($archivo->Extension != ''):?>
                                <?= $archivo->Tamanyo;?> KB
                            <?php endif;?>
                        </td>
                        <td>
                            <?= date("d-m-Y", strtotime($archivo->Fecha));?>
                        </td>                                 
                        <td>
                            <?= $archivo->NombreEmpl. ' '.$archivo->ApellidoP.' '.$archivo->ApellidoM?>
                        </td>

                        <td class="span3">
                            <div class="botones pull-right">
                                <?php if($archivo->Extension != ''):?>
                                    <a data-toggle="tooltip" title="descargar" href="<?= base_url().$user;?>/proyecto/archivos/descargar/<?=$archivo->Codigo;?>" class="btn btn-small"><i class="icon-download"></i> </a>                        
                                <?php else:?>    
                                    <a data-toggle="tooltip" title="descargar" href="<?=base_url().$user;?>/proyecto/descargar/archivos/<?=$codigo;?>" class="btn btn-small"><i class="icon-download"></i> </a>                                               
                                <?php endif;?>    
                                <?php if($user =='admin' || $this->session->userdata('email') == $archivo->EmailEmpleado):?>
                                    <?php if($estado != 'Cerrado'):?>                                    
                                        <?php if($this->uri->segment('6') != ''):?>                                    
                                            <a data-toggle="tooltip" title="eliminar" data-animation="animation" href="<?=base_url().$user;?>/proyecto/archivos/eliminar/<?=$codigo;?>/<?=$archivo->Codigo;?>/<?= $this->uri->segment('6');?>" class="btn btn-danger btn-small" data-confirm="¿Desea eliminar el archivo <?= $archivo->Nombre;?>?"><i class="icon-trash icon-white"></i></a>
                                        <?php else:?>
                                            <a data-toggle="tooltip" title="eliminar" data-animation="animation" href="<?=base_url().$user;?>/proyecto/archivos/eliminar/<?=$codigo;?>/<?=$archivo->Codigo;?>" class="btn btn-danger btn-small" data-confirm="¿Desea eliminar el archivo <?= $archivo->Nombre;?>?"><i class="icon-trash icon-white"></i></a>
                                        <?php endif;?>
                                    <?php endif;?>
                                <?php endif;?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif;?>
            </tbody>        
        </table>
        <?php endif;?>


        <?php if($this->uri->segment('6') != ''):?> 
            <?php $aux = $this->uri->segment('6');?>
            <?= form_open_multipart("$user/proyecto/archivos/registrar/$codigo/$aux");?>
        <?php elseif($this->uri->segment('5') != ''):?> 
            <?php $aux = $this->uri->segment('5');?>
            <?= form_open_multipart("$user/proyecto/archivos/registrar/$codigo/$aux");?>
        <?php else:?>
            <?= form_open_multipart("$user/proyecto/archivos/registrar/$codigo");?>
        <?php endif;?>
            <input type="submit" value="Subir archivo" class="btn btn-info btn-small" id="subir">
            <input type="file"  title="Examinar" name="archivos[]" multiple="multiple" class="btn btn-small"><br>
        <?= form_close();?>   
        
        <?php if($this->uri->segment('6') != ''):?> 
            <?php $aux = $this->uri->segment('6');?>
            <?= form_open("$user/proyecto/archivos/registrar/$codigo/$aux", array('name'=>'register', 'class'=>'form-horizontal'));?>  
        <?php elseif($this->uri->segment('5') != ''):?> 
            <?php $aux = $this->uri->segment('5');?>
            <?= form_open("$user/proyecto/archivos/registrar/$codigo/$aux", array('name'=>'register', 'class'=>'form-horizontal'));?>  
        <?php else:?>    
            <?= form_open("$user/proyecto/archivos/registrar/$codigo", array('name'=>'register', 'class'=>'form-horizontal'));?>  
        <?php endif;?>    
            
        <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">                    
            <div class="modal-header">
                <h5 id="myModalLabel"> Crear carpeta</h5>                                                       
            </div>
                
            <div class="modal-body" id="formularioModal">
                <div class="control-group">
                    <label class="control-label" for="inputNombre">Nombre:</label> 
                    <div class="controls">
                        <?= form_input('nombreCarpeta','','id = inputNombre autofocus="autofocus"');?>
                    </div>
                </div>
            </div>
            <div class="modal-footer"> 
                <a class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</a>
                <?= form_submit($boton, 'Guardar');?>
            </div>
               
        </div>
    <?= form_close();?>
            
            
    <?php if($this->uri->segment('6') != ''):?> 
        <?php $aux = $this->uri->segment('6');?>
        <?= form_open("$user/proyecto/archivos/crear/$codigo/$aux", array('name'=>'register', 'class'=>'form-horizontal'));?>  
    <?php elseif($this->uri->segment('5') != ''):?> 
        <?php $aux = $this->uri->segment('5');?>
        <?= form_open("$user/proyecto/archivos/crear/$codigo/$aux", array('name'=>'register', 'class'=>'form-horizontal'));?>  
    <?php else:?>    
        <?= form_open("$user/proyecto/archivos/crear/$codigo", array('name'=>'register', 'class'=>'form-horizontal'));?>  
    <?php endif;?>    

    <div id="myModal2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">                    
        <div class="modal-header">
            <h5 id="myModalLabel"> Crear archivo</h5>                                                       
        </div>

        <div class="modal-body" id="formularioModal">
            <div class="control-group">
                <label class="control-label" for="inputNombre">Nombre:</label> 
                <div class="controls">
                    <?= form_input('nombreArchivo','','id = "inputNombre" autofocus="autofocus"');?>
                </div>
            </div>
        </div>
        <div class="modal-footer"> 
            <a class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</a>
            <?= form_submit($boton, 'Guardar');?>
        </div>

    </div>
    <?= form_close();?>
            
            

</div>

