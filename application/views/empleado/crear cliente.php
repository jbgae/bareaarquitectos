 <div class="well span9 offset1">
    <?php if(isset($actualizar)): ?>
        <?= form_open("$user/clientes/editar/".urlencode($email),array('id'=>'registroFormAct', 'class'=>'form-horizontal')); ?>
        <legend> Actualizar cliente </legend>
    <?php else: ?>
        <?= form_open("$user/clientes/crear",array('id'=>'registroForm', 'class'=>'form-horizontal')); ?>                           
        <legend> Registrar cliente </legend>
    <?php endif;?>
        <?= validation_errors();?>
    <?php foreach($formulario as $input): ?>

        <div class="control-group">
            <?php if(isset($input['label'])):?>
                <label class="control-label" for="<?= $input['input']['id'];?>" accesskey ="<?= $input['label']['accesskey'];?>"><?= $input['label']['name']; ?>
            <?php else:?>
                 <label class="control-label">
            <?php endif;?>
                <?php if(isset($input['requerido'])):?>
                    <?php if($input['requerido']):?>
                        <span class="rojo">*</span>
                    <?php endif;?>
                <?php endif;?>
            </label>
            <div class="controls">        
            <?php if(isset($input['input'])):?>
                <?php if($input['input']['name']=='email'):?>
                    <?php if(isset($actualizar)):?>
                        <span class="input-large uneditable-input"><?=$input['input']['value'];?></span>
                    <?php else:?>
                        <?= form_input($input['input']); ?>
                        <?= form_error($input['input']['name']); ?>
                    <?php endif;?> 
                <?php elseif($input['input']['name'] == 'pass' || $input['input']['name'] == 'passconf'): ?>
                    <?= form_password($input['input']);?>
                    <?= form_error($input['input']['name']);?>
                <?php elseif($input['input']['name'] == 'provincia'):?>
                    <?php if(isset($actualizar)): ?>
                        <?= form_dropdown($input['input']['name'], $provincias, $input['input']['value'], 'id="provincia"');?>
                    <?php else:?>
                        <?= form_dropdown($input['input']['name'], $provincias, '', 'id = "provincia"');?>
                    <?php endif;?>
                <?php elseif($input['input']['name'] == 'ciudad'):?>
                   <?php if(isset($actualizar)): ?>
                       <select name="ciudad" id="ciudad">                                  
                           <?php foreach($ciudades as $ciudad):?>
                                <?php if($input['input']['value'] == $ciudad->Codigo):?>
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
                    <?= form_input($input['input']); ?>
                    <?= form_error($input['input']['name']); ?>
                <?php endif;?>
            <?php endif;?>
            </div>           
        </div>
    <?php endforeach; ?>
    <br>
    <div>
        <?php if(isset($actualizar)): ?>
            <?= form_submit($boton, 'Actualizar cliente'); ?>
        <?php else: ?>
            <?= form_submit($boton, 'Crear cliente'); ?>
        <?php endif;?>
    </div>    
    <?= form_close(); ?>
</div>

