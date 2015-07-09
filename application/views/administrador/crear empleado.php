<div class="well span9 offset1">

    <?php if(isset($actualizar)): ?>
        <?= form_open_multipart('admin/empleados/editar/'.urlencode($email),array('class'=>'form-horizontal', 'id'=>'empleadoForm')); ?>
        <legend> Actualizar empleado </legend>
    <?php elseif(isset($datos)): ?>
        <?= form_open_multipart("$user/datos",array('class'=>'form-horizontal', 'id'=>'empleadoForm')); ?>
        <legend> Datos empleado </legend>
        <?php if($imagen == 'Desconocido'):?>
            <img src ="<?= base_url();?>images/indice.jpeg" class="hidden-phone" alt="foto de empleado">
        <?php else:?>
            <img src="<?= $imagen;?>" class="hidden-phone" alt="foto de empleado">            
        <?php endif;?>
    <?php else: ?>
        <?= form_open_multipart('admin/empleados/crear',array('class'=>'form-horizontal', 'id'=>'empleadoForm')); ?>                           
        <legend> Registrar empleado</legend>
    <?php endif;?>

    <?php foreach($formulario as $input): ?>
        <div class="control-group">
            <?php if($input['label']['accesskey'] != ''):?>
                <label class="control-label" for="<?= $input['input']['id'];?>" accesskey ="<?= $input['label']['accesskey'];?>"><?= $input['label']['name']; ?>
            <?php else:?>
                 <label class="control-label"><?= $input['label']['name']; ?>
            <?php endif;?>
                <?php if(isset($input['requerido'])):?>
                    <?php if($input['requerido']):?>
                        <span class="rojo">*</span>
                    <?php endif;?>
                <?php endif;?>
            </label>
            <div class="controls">   
            <?php if($input['input']['name'] == 'pass' || $input['input']['name'] == 'passconf'): ?>
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
            </div>
        </div>
    <?php endforeach; ?> 
        <label class="control-label" for="imagen">Imagen</label>
        <input type="file"  title="Examinar" name="archivo" class="imagen"> 
        <br>

    <br>
    <div>
        <?php if(isset($actualizar)): ?>
            <?= form_submit($boton, 'Actualizar empleado'); ?>
        <?php elseif(isset($datos)): ?>
            <?= form_submit($boton, 'Actualizar datos'); ?>
        <?php else: ?>
            <?= form_submit($boton, 'Crear empleado'); ?>
        <?php endif;?>
    </div>    
    <?= form_close(); ?>

    <?php if($imagen == ''):?>
        <img src ="<?= base_url();?>images/indice.jpeg" class="hidden-phone">
    <?php else:?>
        <img src="<?= $imagen;?>" class="hidden-phone">
    <?php endif;?>
</div>

