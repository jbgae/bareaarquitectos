<div class ="span11">                                    
        <div>

        </div>                
        <?php if(isset($error)):?>
            <div class="alert alert-error">
                <button type="button" class="close" data-dismiss="alert">
                    &times;
                </button> 
                <h4>Error.</h4><?= $error;?>
            </div>
        <?php endif;?>    
        <?php if(isset($valido)):?>
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">
                    &times;
                </button> 
                <?= $valido;?>
            </div>    
        <?php elseif(empty($tareas)):?>
            <div class="alert alert-warning">
                <button type="button" class="close" data-dismiss="alert">
                    &times;
                </button> 
                <h4>Advertencia</h4>Actualmente, este proyecto no dispone de ninguna tarea.
            </div>
        <?php endif;?> 
        <div class="pull-left span2">
             <?php if(isset($actualizar)):?>
                <button class="btn btn-info" onclick='window.location="<?=base_url();?>admin/proyecto/tareas/<?=$codigo;?>"'>Crear Tarea</button>
             <?php else:?>
                <button class="btn btn-info">Crear Tarea</button>
             <?php endif;?>   
             
             <?php if(isset($tareas) && !empty($tareas)):?> 
                <div>
                <?php foreach($tareas as $tarea):?>
                    <?= anchor("admin/proyecto/tarea/$codigo/$tarea->Codigo", "<div class='tareas'>". $tarea->Titulo . "</div>"); ?>                                                                   
                <?php endforeach;?>
                </div>
            <?php endif;?>
             
        </div>
        <?php if($estado != 'Cerrado'):?>
        <div class="span10">
            <?php if(isset($actualizar)): ?>
                <?= form_open_multipart('admin/proyecto/tareas/editar/'.$codigo.'/'.$tarea->Codigo); ?>
            <?php else: ?>
                <?= form_open_multipart('admin/proyecto/tareas/'. $codigo); ?>
            <?php endif;?>

            <?php foreach($formulario as $input): ?>
                <label class="control-label" for="<?= $input['id']; ?>">
                    <?= ucfirst($input['label']); ?>
                </label>
                <?php if($input['id'] == 'contenido'): ?>
                    <?= form_textarea($input); ?>
                    <?= form_error($input['name']); ?>
                    
                <?php elseif($input['id'] == 'asignado'):?>
                    <?php if(isset($actualizar)):?>
                        <span class="<?= $input['class'];?>"><?=$input['value'];?></span>
                    <?php else:?> 
                        <?= form_dropdown($input['name'],$empleadosProyecto);?> 
                    <?php endif;?>
                <?php elseif($input['id'] == 'estado'):?>
                    <?= form_dropdown($input['name'],$opciones);?>          
                <?php else: ?>
                    <?= form_input($input); ?>
                    <?= form_error($input['name']); ?>
                <?php endif;?>                             
            <?php endforeach; ?>
                <label class="control-label" for="imagen">Adjuntar archivo</label>
                <input type="file"  title="Examinar" name="archivo" class="imagen">
            
            <br>
             
                <div>
                    <?= form_submit($boton, 'Guardar'); ?>
                </div>    
              
            <?= form_close(); ?>  
        </div>
        <?php else:?>
            <div class="alert alert-error span10">
                <button type="button" class="close" data-dismiss="alert">
                    &times;
                </button> 
                <h4>Error.</h4> El proyecto esta cerrado, no se puede crear nuevas tareas.
            </div>
        <?php endif;?>
</div>

