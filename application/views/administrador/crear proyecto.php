<div class="well span12">                
    <?= form_open('admin/proyecto/crear/'.$codigo,array('class'=>'form-horizontal', 'id'=>'proyectoForm')); ?>
        <legend> Datos proyecto </legend>
        <div class="span5 pull-left">
        <?php foreach($formulario as $input): ?>
            <div class="control-group">
                <label class="control-label" for="<?= $input['class']; ?>">
                    <?= ucfirst($input['label']); ?>
                    <?php if($input['requerido']):?>
                        <span class="rojo">*</span>
                    <?php endif;?> 
                    <?php if($input['name'] == 'constructora'): ?>
                        <span><a href="#myModal" data-toggle="modal">(+)</a></span>
                    <?php endif;?>    
                </label>
                <?php if($input['class'] == 'uneditable-input'): ?>
                    <span class="uneditable-input"><?= $input['value'];?></span>
                <?php elseif($input['name'] == 'constructora'): ?>
                    <?= form_dropdown($input['name'], $constructoras, '');?>                                
                <?php else: ?>    
                    <?= form_input($input); ?>
                    <?= form_error($input['name']);?>
                <?php endif;?>    
            </div>                            
        <?php endforeach; ?>
        </div>
        <div class="span6 ">
            <label class="control-label span12" for="select1 select2" >
                Empleados
            </label>
            <?= form_multiselect('select1[]', $empleados, '', 'id="select1"');?>
            <select multiple id="select2" name="empleados[]"></select> 
            <br>
            <a href="#" id="add">Añadir <i class="icon-arrow-right"></i></a>    
            <a href="#" id="remove"><i class="icon-arrow-left"></i>  Eliminar </a>
            <div class="clearfix"></div>
            <div>
                    <?= form_submit($boton, 'Crear proyecto'); ?>
            </div>
        </div>                        
    <?= form_close(); ?> 
</div>

<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

        <div class="modal-header">
            <h5 id="myModalLabel"> Registrar constructora</h5>                                                       
        </div>
        <form name="register" method="post" action="" class="form-horizontal" id="formdata">
            <div class="modal-body" id="formularioModal">
                <!--<// form_open('cliente/registrar', array('name'=>'register', 'class'=>'form-inline'));?>  -->

                    <?php foreach($formularioRegistro as $input): ?>                    
                        <div>
                            <label class="control-label" for="<?php  $input['class']; ?>">
                                <?= ucfirst($input['label']); ?>
                                <?php if($input['requerido']):?>
                                    <span class="rojo">*</span>
                                <?php endif;?>    
                            </label>
                            <?= form_input($input); ?>
                            <?= form_error($input['name']); ?>

                        </div><br>
                <?php endforeach; ?>
                <div id="mensaje"></div>
            </div>
            <div class="modal-footer"> 
                 <input type="button" value="Crear constructora" id="boton_constructora" class="btn btn-info">                        
            </div>
        <?= form_close();?>
    </div>


