<div class="well span9 offset1">

    <?php if(isset($actualizar)): ?>
        <?= form_open("$user/consultor/editar/$identificador", array('class'=>'form-horizontal','id'=>'consultorForm')); ?>
        <legend> Actualizar consultor </legend>
    <?php else: ?>
        <?= form_open("$user/consultor/crear", array('class'=>'form-horizontal','id'=>'consultorForm')); ?>                           
        <legend> Registrar consultor </legend>
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
            <?php if($input['name'] == 'provincia'):?>
                <?php if(isset($actualizar)): ?>
                    <?= form_dropdown($input['name'], $provincias, $input['value'], 'id="provincia"');?>
                <?php else:?>
                    <?= form_dropdown($input['name'], $provincias, '', 'id = "provincia"');?>
                <?php endif;?>
            <?php elseif($input['name'] == 'ciudad'):?>
                 <?php if(isset($actualizar)): ?>
                   <select name="ciudad" id="ciudad">                                  
                       <?php foreach($ciudades as $ciudad):?>
                            <?php if($input['value'] == $ciudad->Codigo):?>
                                 <option value="<?= $ciudad->Codigo;?>" selected><?= $ciudad->Ciudad;?></option>
                            <?php else:?>     
                                 <option value="<?= $ciudad->Codigo;?>"><?= $ciudad->Ciudad;?></option>
                            <?php endif;?>     
                       <?php endforeach;?>     
                   </select> 
               <?php else:?>
                   <select name="ciudad" id="ciudad"></select>
               <?php endif;?>
            <?php else: ?>
                <?= form_input($input); ?>
                <?= form_error($input['name']); ?>
            <?php endif;?>
            </div>
        </div>
    <?php endforeach; ?>
    <br>
    <div>
        <?php if(isset($actualizar)): ?>
            <?= form_submit($boton, 'Actualizar consultor'); ?>
        <?php else: ?>
            <?= form_submit($boton, 'Crear consultor'); ?>
        <?php endif;?>
    </div>    
    <?= form_close(); ?>
</div>

