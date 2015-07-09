<div id="formulario" class="span9">
<?= form_open('cliente/presupuesto/solicitar',array('id'=>'presupuestoForm', 'class'=>'form-horizontal span12'));  ?>
    <?php if(isset($valido)):?>
        <div class="alert alert-success span11">
        <button type="button" class="close" data-dismiss="alert">
            &times;
        </button> 
        <h4>Bien.</h4>
        <div class="valido"> <?= $valido;?> </div>
        </div>
    <?php endif;?>
    <?php if(isset($error)):?>
         <div class="alert alert-error">
                <button type="button" class="close" data-dismiss="alert">
                    &times;
                </button> 
                <h4>Error.</h4><?= $mensaje;?>.
            </div>
    <?php endif;?>
        <br>

    <div class="row-fluid pull-left span9">
        <div>
            <?= validation_errors();?>
        </div>
        <div class="span12"> 
            
            <div class="margen">                                  
                <label class="control-label" for="<?= $formulario['direccion']['input']['id']; ?>">
                    <?= ucfirst($formulario['direccion']['label']); ?>
                    <?php if($formulario['direccion']['requerido']):?>
                        <span class="rojo">*</span>
                    <?php endif;?> 
                </label>
                <?= form_input($formulario['direccion']['input']); ?>
                <?= form_error($formulario['direccion']['input']['name']);?>
            </div>
        

            <div class="margen">
                <label class="control-label" for="<?= $formulario['provincia']['input']['id']; ?>">
                    <?= ucfirst($formulario['provincia']['label']); ?>
                    <?php if($formulario['provincia']['requerido']):?>
                        <span class="rojo">*</span>
                    <?php endif;?> 
                </label>
                <?= form_dropdown($formulario['provincia']['input']['name'], $provincias, '', 'id = "provincia"');?>
            </div>  

            <div class="margen">
                <label class="control-label" for="<?= $formulario['ciudad']['input']['id']; ?>">
                    <?= ucfirst($formulario['ciudad']['label']); ?>
                    <?php if($formulario['ciudad']['requerido']):?>
                        <span class="rojo">*</span>
                    <?php endif;?> 
                </label>
                <select name="ciudad" id="ciudad"></select>
            </div>

            <div class="margen">
               <label class="control-label" for="<?= $formulario['tipo']['input']['id']; ?>">
                    <?= ucfirst($formulario['tipo']['label']); ?>
                    <?php if($formulario['tipo']['requerido']):?>
                        <span class="rojo">*</span>
                    <?php endif;?> 
               </label>
               <?= form_dropdown($formulario['tipo']['input']['name'], $opciones, ' ', 'id = "tipo"');?>
            </div> 

            <div class="margen">
               <label class="control-label" for="<?= $formulario['superficie']['input']['id']; ?>">
                    <?= ucfirst($formulario['superficie']['label']); ?>
                    <?php if($formulario['superficie']['requerido']):?>
                        <span class="rojo">*</span>
                    <?php endif;?> 
               </label>
               <div class="input-append">
                    <?= form_input($formulario['superficie']['input']); ?>
                    <span class="add-on">m2</span>
                    <?= form_error($formulario['superficie']['input']['name']);?>
               </div>
            </div> 

            
            <div class="margen">                                    
                <label class="control-label" for="<?= $formulario['descripcion']['input']['id']; ?>">
                    <?= ucfirst($formulario['descripcion']['label']); ?>
                    <?php if($formulario['descripcion']['requerido']):?>
                        <span class="rojo">*</span>
                    <?php endif;?> 
                </label>
                <?= form_textarea($formulario['descripcion']['input']); ?>
                <?= form_error($formulario['descripcion']['input']['name']);?>                                    
            </div> 
            
            <div class="margen">
                <?= form_submit($boton, 'Solicitar presupuesto'); ?>
            </div>

        </div>
    </div>
    <?= form_close(); ?>    
</div>    
</div>    
</div>    
</section>
               

