<br>
<div class="well span9 offset1"> 
    <?php if($this->uri->segment(1) == 'admin'):?>
        <?= form_open('admin/calendario/crear/', array('class'=>'form-horizontal', 'id'=>'citaForm')); ?>
    <?php elseif($this->uri->segment(1) == 'empleados'):?>
        <?= form_open('empleados/calendario/crear/',array('class'=>'form-horizontal', 'id'=>'citaForm')); ?>
    <?php endif;?>
        <?php foreach($formulario as $input): ?>    
            <div class="control-group">
                <label class="control-label" for="<?= $input['class']; ?>">
                    <?= ucfirst($input['label']); ?>
                    <?php if($input['requerido']):?>
                        <span class="rojo">*</span>
                    <?php endif;?>
                </label>
                <div class="controls">
                    <?php if($input['name'] == 'Tipo'):?>
                        <?= form_dropdown($input['name'], $tipos); ?>
                    <?php elseif($input['name'] == 'Descripcion'): ?>
                        <?= form_textarea($input); ?>
                    <?php else: ?>    
                        <?= form_input($input); ?>
                        <?= form_error($input['name']); ?>
                    <?php endif;?>
                </div>
            </div>
        <?php endforeach; ?>
        <br>
        <div>
           <?= form_submit($boton, 'Crear evento'); ?>
        </div>    
    <?= form_close(); ?>
</div>

