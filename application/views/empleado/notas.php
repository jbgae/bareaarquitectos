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
        <?php elseif(empty($notas)):?>
            <div class="alert alert-warning">
                <button type="button" class="close" data-dismiss="alert">
                    &times;
                </button> 
                <h4>Advertencia</h4>Actualmente, este proyecto no dispone de ninguna nota.
            </div>
        <?php endif;?> 
        <div class="pull-left span2">
             <?php if(isset($actualizar)):?>
                 <button class="btn btn-info" onclick='window.location="<?=base_url().$user;?>/proyecto/notas/<?=$codigo;?>"'>Crear Nota</button>
             <?php else:?>
                <button class="btn btn-info">Crear Nota</button>
             <?php endif;?>   
             
             <?php if(isset($notas) && !empty($notas)):?> 
                <div id="listado-notas-empleado">
                <?php foreach($notas as $n):?>
                    <?= anchor("$user/proyecto/nota/$codigo/$n->Codigo", "<div class='notas'>". $n->Titulo . "</div>"); ?>                                                                   
                <?php endforeach;?>
                </div>
            <?php endif;?>
             
        </div>
        <?php if($estado != 'Cerrado'):?>
        <div class="span10">
            <?php if(isset($actualizar)): ?>
                <?= form_open("$user/proyecto/notas/editar/".$codigo.'/'.$nota->Codigo); ?>
            <?php else: ?>
                <?= form_open("$user/proyecto/notas/". $codigo); ?>
            <?php endif;?>

            <?php foreach($formulario as $input): ?>
                <?php if($input['id'] == 'contenido'): ?>
                    <label class="control-label" for="<?= $input['id']; ?>">
                        <?= ucfirst($input['label']); ?>
                    </label>
                    <?= form_textarea($input); ?>
                    <?= form_error($input['name']); ?>
                <?php elseif($input['id'] == 'publico' || $input['id'] == 'privado' || $input['id'] == 'personalizado'):?>
                    <div>
                        <?= form_radio($input).' '. $input['label'];?>        
                    </div>    
                <?php else: ?>
                    <label class="control-label" for="<?= $input['id']; ?>">
                        <?= ucfirst($input['label']); ?>
                    </label>
                    <?= form_input($input); ?>
                    <?= form_error($input['name']); ?>
                <?php endif;?>
            <?php endforeach; ?>
            <div id="extender">
                <?= form_multiselect('select1[]', $empleadosProyectos, '', 'id="select1"');?>
                <?= form_multiselect('empleados[]', $empleadosNotas, '', 'id="select2"');?>
                <br>
                <a href="#" id="add">Añadir <i class="icon-arrow-right"></i></a>    
                <a href="#" id="remove"><i class="icon-arrow-left"></i>  Eliminar </a>
            </div>
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
                <h4>Error.</h4> El proyecto esta cerrado, no se puede crear nuevas notas.
            </div>
        <?php endif;?>
</div>

