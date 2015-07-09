<div class ="span11">                                
    <div>

    </div>          
    <div class="pull-left span2">
         <?php if($user == 'admin'):?>
            <button class="btn btn-info" onclick='window.location="<?=base_url();?>admin/proyecto/tareas/<?=$codigo;?>"'>Crear Tarea</button>
         <?php endif;?>
         <div id="listado-tareas-empleado">
             <?php if(isset($tareas)&& !empty($tareas)):?>            
                <?php foreach($tareas as $tareaAux):?>
                    <?= anchor("$user/proyecto/tarea/$codigo/$tareaAux->Codigo", "<div class='tareas'>". $tareaAux->Titulo . "</div>"); ?>                                                                   
                <?php endforeach;?>               
             <?php else:?>
                 <span id ="vacio">No existen tareas.</span>
             <?php endif;?>
         </div>     
        
    </div>
    <div class ="span8">  
        <div id="titulo">
            <h4>
                <?= ucfirst($tarea->titulo());?>
                <?php if($tarea->email() == $this->session->userdata('email')):?>
                    <?= anchor("admin/proyecto/tareas/borrar/$codigo/".$tarea->Codigo, '<i class="icon-trash"></i>', 'class="pull-right" id="tooltip" rel="tooltip" data-tigger="hover" data-animation="true" data-placement="top" data-toggle="tooltip" data-title="Eliminar" data-confirm="Va a eliminar la tarea, ¿Desea continuar?"');?>
                    <?= anchor("admin/proyecto/tareas/editar/$codigo/".$tarea->Codigo, '<i class="icon-edit"></i>', 'class="pull-right" id="tooltip" rel="tooltip" data-tigger="hover" data-animation="true" data-placement="top" data-toggle="tooltip" data-title="Editar"');?>                      
                <?php endif;?>
            </h4>

        </div>

        <div id="contenido">
            <?php if($foto == '' || $foto == 'Desconocido'):?>
                <img src="<?= base_url();?>images/indice.jpeg" alt=' <?= $tarea->Nombre.' '.$tarea->ApellidoP.' '.$tarea->ApellidoM ;?>'>
            <?php else:?> 
                <img src='<?=$foto;?>' alt=' <?= $tarea->Nombre.' '.$tarea->ApellidoP.' '.$tarea->ApellidoM ;?>'>
            <?php endif;?>    
            <?= $tarea->contenido();?>
        </div>
        <div id="pie">
            Creado el <?= date('d-m-Y H:i:s', strtotime($tarea->fechaCreacion()));?> por <?= $tarea->Nombre.' '.$tarea->ApellidoP.' '.$tarea->ApellidoM ;?><br>
            <?php if(isset($archivo)):?>
                <i class="icon-file"></i> <strong>Archivos adjuntos:</strong> 
                <?foreach($archivo as $arch):?> 
                    <?= anchor("archivo/descargar/".$arch['codigo'] ,$arch['nombre']). ' - ';?>
                <?endforeach;?>
            <?php endif;?>
        </div>
        
        <div id="respuestas">
        <?php if(isset($respuestas)):?>
            <?php foreach($respuestas as $respuesta):?>
                <div id="titulo">
                    <h4>
                        <?= 'RE:'. ucfirst($tarea->titulo());?>
                        <?php if($respuesta->Email == $this->session->userdata('email')):?>
                            <?= anchor("$user/proyecto/respuesta/borrar/$codigo/$tarea->Codigo/".$respuesta->Codigo, '<i class="icon-trash"></i>', 'class="pull-right" id="tooltip" rel="tooltip" data-tigger="hover" data-animation="true" data-placement="top" data-toggle="tooltip" data-title="Eliminar"');?><br>                           
                        <?php endif;?>
                    </h4>
                </div>
                <div id="contenido">
                    <?php if($fotoRespuesta[$respuesta->Email] == ''):?>
                        <img src="<?= base_url();?>images/indice.jpeg" alt=' <?= $respuesta->Nombre.' '.$respuesta->ApellidoP.' '.$respuesta->ApellidoM ;?>'>
                    <?php else:?> 
                        <img src='<?=$fotoRespuesta[$respuesta->Email];?>' alt=' <?= $respuesta->Nombre.' '.$respuesta->ApellidoP.' '.$respuesta->ApellidoM ;?>'>
                    <?php endif;?>    
                    <?= $respuesta->Contenido;?>
                </div>
                <div id="pie">
                    Creado el <?= date('d-m-Y H:i:s', strtotime($respuesta->Fecha));?> por <?= $respuesta->Nombre.' '.$respuesta->ApellidoP.' '.$respuesta->ApellidoM ;?><br>
                    <?php if(isset($archivoRespuesta[$respuesta->Codigo])):?>
                        <i class="icon-file"></i> <strong>Archivos adjuntos:</strong>
                        <?foreach ($archivoRespuesta[$respuesta->Codigo] as $arch):?>
                             <?= anchor("archivo/descargar/".$arch['codigo'] ,$arch['nombre']). ' - ';?>
                        <?php endforeach;?>
                    <?php endif;?>
                </div>        
            <?php endforeach;?>
        <?php endif;?>
        </div>
        <br>
        
        <?php if($tarea->estado() != 'cerrado' && $estado != 'Cerrado'): ?>
           <?= form_open_multipart("$user/proyecto/tarea/".$codigo.'/'.$tarea->Codigo); ?>

             <?php foreach($formulario as $input): ?>
                    <label class="control-label" for="<?= $input['id']; ?>">
                        <?php if($this->session->userdata('usuario') != 'admin'):?>
                            <?php if($input['label'] != 'Estado'):?>
                                <?= ucfirst($input['label']); ?>
                            <?php endif;?>
                        <?php else:?>
                            <?= ucfirst($input['label']); ?>
                        <?php endif;?> 
                    </label>
                    <?php if($input['id'] == 'contenido'): ?>
                        <?= form_textarea($input); ?>
                        <?= form_error($input['name']); ?>
                    <?php elseif($input['id'] == 'estado' && $this->session->userdata('usuario') == 'admin'):?>
                         <?= form_dropdown($input['name'],$opciones);?>
                    <?php endif;?>     
            <?php endforeach; ?>
            <label class="control-label" for="imagen">Adjuntar archivo</label>
            <input type="file"  title="Examinar" name="archivos[]" multiple="multiple" class="imagen">
             <br><br>
            <div>
                <?= form_submit($boton, 'Guardar'); ?>
            </div>    

            <?= form_close(); ?>
        <?php endif;?>
    </div>
</div>