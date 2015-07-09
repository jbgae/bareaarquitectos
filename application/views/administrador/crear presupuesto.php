<?php if(validation_errors() != ''):?>
        <?=validation_errors();?>
<?php endif; ?>

<div>
    <?php if(isset($codigo)) :?> 
        <?= form_open('admin/presupuesto/crear/'.$codigo, array('class'=>'form-horizontal','id'=>'presupuestoForm')); ?>
    <?php else: ?>
        <?= form_open('admin/presupuesto/crear',array('class'=>'form-horizontal','id'=>'presupuestoForm')); ?>
    <?php endif;?>
    <div class="well span12">
        <legend> Generar presupuesto </legend>
        <div class="row-fluid pull-left span6">                        
            <div class="span12"> 
                <div class="margen">                                  
                    <label class="control-label" for="<?= $formulario['direccion']['class']; ?>">
                        <?= ucfirst($formulario['direccion']['label']); ?>
                        <?php if($formulario['direccion']['requerido']):?>
                            <span class="rojo">*</span>
                        <?php endif;?> 
                    </label>
                    <?php if(isset($actualizar)):?>
                        <span class="<?= $formulario['direccion']['class'];?>"><?=$formulario['direccion']['value'];?></span>
                    <?php else:?>    
                        <?= form_input($formulario['direccion']); ?>
                        <?= form_error($formulario['direccion']['name']);?>
                    <?php endif;?>
                </div>

                <div class="margen">
                    <label class="control-label" for="<?= $formulario['nombre']['class']; ?>">
                        <?= ucfirst($formulario['nombre']['label']); ?>
                        <?php if($formulario['nombre']['requerido']):?>
                            <span class="rojo">*</span>
                        <?php endif;?> 
                        <span><a href="#myModal" data-toggle="modal">(+)</a></span> 
                    </label>
                    <?php if(isset($actualizar)):?>
                        <span class="<?= $formulario['nombre']['class'];?>"><?=$formulario['nombre']['value'];?></span>
                    <?php else:?>    
                        <?= form_dropdown($formulario['nombre']['name'], $clientes);?>
                    <?php endif;?>
                </div>         

                <div class="margen">
                    <label class="control-label" for="<?= $formulario['provincia']['class']; ?>">
                        <?= ucfirst($formulario['provincia']['label']); ?>
                        <?php if($formulario['provincia']['requerido']):?>
                            <span class="rojo">*</span>
                        <?php endif;?> 
                    </label>
                    <?php if(isset($actualizar)): ?>
                        <?= form_dropdown($formulario['provincia']['name'], $provincias, $formulario['provincia']['value'], 'id="provincia"');?>
                    <?php else:?>
                        <?= form_dropdown($formulario['provincia']['name'], $provincias, '', 'id = "provincia"');?>
                    <?php endif;?>
                </div>  

                <div class="margen">
                    <label class="control-label" for="<?= $formulario['ciudad']['class']; ?>">
                        <?= ucfirst($formulario['ciudad']['label']); ?>
                        <?php if($formulario['ciudad']['requerido']):?>
                            <span class="rojo">*</span>
                        <?php endif;?> 
                    </label>
                    <?php if(isset($actualizar)): ?>
                       <select name="ciudad" id="ciudad">                                  
                           <?php foreach($ciudades as $ciudad):?>
                                <?php if($formulario['ciudad']['value'] == $ciudad->Codigo):?>
                                     <option value="<?= $ciudad->Codigo;?>" selected><?= $ciudad->Ciudad;?></option>
                                <?php else:?>     
                                     <option value="<?= $ciudad->Codigo;?>"><?= $ciudad->Ciudad;?></option>
                                <?php endif;?>     
                           <?php endforeach;?>     
                       </select> 
                   <?php else:?>
                       <select name="ciudad" id="ciudad"></select>
                   <?php endif;?>
                </div>

                <div class="margen">
                   <label class="control-label" for="<?= $formulario['ciudad']['class']; ?>">
                        <?= ucfirst($formulario['tipo']['label']); ?>
                        <?php if($formulario['tipo']['requerido']):?>
                            <span class="rojo">*</span>
                        <?php endif;?> 
                   </label>
                   <?php if(isset($codigo)) :?>
                        <?= form_dropdown($formulario['tipo']['name'], $opciones, $formulario['tipo']['value']);?>
                   <?php else: ?>
                        <?= form_dropdown($formulario['tipo']['name'], $opciones);?>
                   <?php endif;?>
                </div> 

                <div class="margen">
                   <label class="control-label" for="<?= $formulario['ciudad']['class']; ?>">
                        <?= ucfirst($formulario['superficie']['label']); ?>
                        <?php if($formulario['superficie']['requerido']):?>
                            <span class="rojo">*</span>
                        <?php endif;?> 
                   </label>
                   <div class="input-append">
                        <?= form_input($formulario['superficie']); ?>
                        <span class="add-on">m2</span>
                        <?= form_error($formulario['superficie']['name']);?>
                   </div>
                </div> 

                <div class="margen">
                    <label class="control-label" for="pem">PEM</label>
                    <?= form_input($formulario['pem']);?>  
                </div>    
                <div class="margen">
                    <label class="control-label" for="coeficiente">Coeficiente</label>
                    <?= form_input($formulario['coeficiente']);?>                                                            
                </div>    

                <div class="margen">
                    <label class="control-label" for="coeficienteSeguridad">Coef. seguridad</label>
                    <?= form_input($formulario['coeficienteSeguridad']);?>                                
                </div> 


            </div>
        </div>


        <div class="pull-right span5">                                    
            <label class="control-label span12" for="<?= $formulario['descripcion']['class']; ?>">
                <?= ucfirst($formulario['descripcion']['label']); ?>
                <?php if($formulario['descripcion']['requerido']):?>
                    <span class="rojo">*</span>
                <?php endif;?> 
            </label>
            <?= form_textarea($formulario['descripcion']); ?>
            <?= form_error($formulario['descripcion']['name']);?>                                    
        </div>                                


        <div class="row-fluid pull-left span12">
            <div class="span12" id="extender">

            </div>
            <br>
            <div>
                <?= form_submit($boton, 'Crear presupuesto'); ?>
            </div>
        </div>
    </div>    
    <?= form_close(); ?>
                    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

        <div class="modal-header">
            <h5 id="myModalLabel"> Registrar cliente</h5>                                                       
        </div>
        <div class="modal-body" id="formularioModal">
            <!--<// form_open('cliente/registrar', array('name'=>'register', 'class'=>'form-inline'));?>  -->
            <form name="register" method="post" action="" class="form-horizontal" id="formdata">
                <?php foreach($formularioRegistro as $input): ?>                    
                    <div class="control-group">
                        <label class="control-label" for="<?php  $input['class']; ?>">
                            <?= ucfirst($input['label']); ?>
                            <?php if($input['requerido']):?>
                                <span class="rojo">*</span>
                            <?php endif;?>    
                        </label>
                        <div class="controls">
                        <?php if($input['name']=='email'):?>
                            <?php if(isset($actualizar)):?>
                                <span class="input-large uneditable-input"><?php $input['value'];?></span>
                            <?php else:?>
                                <?=  form_input($input); ?>
                                <?=  form_error($input['name']); ?>
                            <?php endif;?> 
                        <?php elseif($input['name'] == 'pass' || $input['name'] == 'passconf'): ?>
                            <?= form_password($input);?>
                            <?= form_error($input['name']);?>
                        <?php else: ?>
                            <?= form_input($input); ?>
                            <?= form_error($input['name']); ?>
                        <?php endif;?>
                        </div>
                    </div>
            <?php endforeach; ?>
                <div id="mensaje"></div>
        </div>
        <div class="modal-footer"> 

            <input type="button" value="Crear cliente" id="boton_cliente" class="btn btn-info">

            <?= form_close();?>
        </div>
    </div>
</div>                           
                         
