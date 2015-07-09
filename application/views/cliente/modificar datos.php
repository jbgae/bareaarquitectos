    <?php if(isset($mensaje)):?>
        <div class="mensaje span9"><?= $mensaje; ?></div>
    <?php endif; ?>

    <div class='span9'>
        <?= form_open('cliente/modificar',array('id'=>'registroForm', 'class'=>'form-horizontal span12'));  ?>
            <?php foreach ($formulario as $input): ?> 
                <div class="control-group">

                    <label class="control-label"  for="<?= $input['input']['id'];?>" accesskey ="<?= $input['label']['accesskey'];?>"><?= $input['label']['name']; ?>
                        <?php if($input['requerido']):?>
                            <span class="rojo">*</span>
                        <?php endif;?>
                    </label>


                    <div class="controls"> 
                        <?php if($input['input']['name'] == 'pass' || $input['input']['name'] == 'passconf'): ?>
                            <?= form_password($input['input']);?>
                            <?= form_error($input['input']['name']);?>
                        <?php elseif($input['input']['name'] == 'politica'):?>
                            <?= form_checkbox($input['input']);?>                         
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
                            <?= form_error($input['input']['name']);?>
                        <?php endif;?>
                    </div>
                </div>
            <?php endforeach;?>    
            <div>
                <?= form_submit($boton);  ?>
                <?= form_reset('myreset', 'Borrar', 'class = "btn"'); ?>
            </div>

            <?= form_close(); ?>
            </div>
        </div>
    </div>
</section>



