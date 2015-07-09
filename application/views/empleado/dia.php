<div>

    <div id ="form">

        <?= form_open("$user/calendario/semana/$proyecto",array('class'=>'form-inline'));?>
            <div class="btn-group" data-toggle="buttons-radio">
                <a href="<?= base_url().$user;?>/calendario/<?= $proyecto;?>" class="btn">Mes</a>
                <a href="<?= base_url().$user;?>/calendario/semana/<?= $proyecto;?>" class="btn">Semana</a>
                <a href="<?= base_url().$user;?>/calendario/dia/<?= $proyecto;?>" class="btn active">Día</a>
            </div>

            <?= form_dropdown('opciones',$opciones,$proyecto,'class=input-medium');?>
            <?= form_submit('mostrar', 'Mostrar', 'class="btn"'); ?>
        <?= form_close();?>


    </div> 
    <div class ="leyenda">
        <div class="izq"><span class="cuadradoVerde pull-left"></span> Comienzo de proyecto</div>
        <div class="izq"><span class="cuadradoRojo pull-left"></span> Fin de proyecto</div>
        <div class="izq"><span  class="cuadradoAzul pull-left"></span> Comienzo de tarea</div>
        <div class="izq"><span  class="cuadradoAmarillo pull-left"></span> Fin de tarea</div>
    </div>                

</div>
<div class="span11">
    <?= anchor("$user/calendario/dia/$proyecto/$yearLess/$monthLess/$dayLess", '<< Anterior', array('class'=>'span3'));?> 
    <h4 class="span6 offset1"><?= $fecha;?></h4>
    <?= anchor("$user/calendario/dia/$proyecto/$yearAdd/$monthAdd/$dayAdd", 'Siguiente >>', array('class'=>'span2'));?> 
</div>    
<?php $hora = '';?>
<?php if(empty($eventos) && empty($tareas)):?>
    <div class="span9 offset3">
        <div class="span5 offset1"> No se han registrado eventos para este día</div>
    </div>
<?php elseif(!empty($eventos)):?>
    <?php foreach($eventos as $evento):?>
        <div class="span9 offset2">
            <?php if($hora != date('H:i',strtotime($evento->Fecha))):?>
                <div class="fecha"><?= date('H:i',strtotime($evento->Fecha));?></div>
            <?php endif;?>    
            <h5 class="span5 cita"><?= ucfirst($evento->Cita);?></h5>
            <div class="span5 descripcion"><?= $evento->Descripcion;?></div>
        </div>
        <?php $hora = date('H:i',strtotime($evento->Fecha));?>
    <?php endforeach;?>

<?php elseif(!empty($tareas)):?>
    <?php foreach($tareas as $tarea):?>
        <div class="span9 offset2">
            <?= $tarea;?>
        </div>    
    <?php endforeach;?>
<?php endif;?>
