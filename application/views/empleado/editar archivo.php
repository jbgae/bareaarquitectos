<div class ="span11"> 
    <?php if(isset($error)):?>
        <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">
                &times;
            </button> 
            <h4>Error.</h4><?= $error;?>
        </div>
    <?php endif;?>
    <?php if(isset($exito)):?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">
                &times;
            </button> 
            <h4>Éxito.</h4><?= $exito;?>
        </div>
    <?php endif;?>
    
    <?php if(empty($archivos)):?>
        <ul class="breadcrumb">
            <li><a href="<?= base_url().$user;?>/proyecto/archivos/<?=$codigo;?>">Archivos</a> <span class="divider">/</span></li>
            <?php if(isset($enlaces)):?>
                <?php foreach($enlaces as $cod => $nombreCarp):?>
                    <li><a href="<?=base_url().$user;?>/proyecto/archivos/<?=$codigo;?>/<?= $cod;?>"><?= ucfirst($nombreCarp);?></a> <span class="divider">/</span></li>
                <?php endforeach;?>
            <?php endif;?>        
            <?php if(isset($nombreArchivo)):?>    
                <li class="active"><?= ucfirst($nombreArchivo);?></li>
            <?php endif;?>    
         </ul>
    
    <?php else: ?>
         <ul class="breadcrumb">
            <li><a href="<?= base_url().$user;?>/proyecto/archivos/<?=$codigo;?>">Archivos</a> <span class="divider">/</span></li>
            <?php if(isset($enlaces)):?>
                <?php foreach($enlaces as $cod => $nombreCarp):?>
                    <li><a href="<?=base_url().$user;?>/proyecto/archivos/<?=$codigo;?>/<?= $cod;?>"><?= ucfirst($nombreCarp);?></a> <span class="divider">/</span></li>
                <?php endforeach;?>
            <?php endif;?>        
            <?php if(isset($nombreArchivo)):?>    
                <li class="active"><?= ucfirst($nombreArchivo);?></li>
            <?php endif;?>    
        </ul>
         
    <?php endif;?>
    
    <?= form_open("$user/proyecto/archivos/editar/$codigoProyecto/$codigoArchivo", array('id'=>'archivoForm')); ?>
    
    <?php foreach($formulario as $input): ?>
        <div class="control-group">    
            <?php if($input['id'] == "contenido-$codigoProyecto-$codigoArchivo"): ?>
                <?= form_textarea($input); ?>
                <?= form_error($input['name']); ?>
            <?php endif;?>
        </div>
    <?php endforeach; ?>
    <br>
    <div>
        <?= form_submit($boton, 'Actualizar archivo'); ?>
    </div>    
    <?= form_close(); ?>  
    
</div>
    