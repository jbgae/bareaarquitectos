<div>

    <div id ="form">

        <?= form_open("$user/calendario/semana/$proyecto",array('class'=>'form-inline'));?>
            <div class="btn-group" data-toggle="buttons-radio">
                <a href="<?= base_url().$user;?>/calendario/<?= $proyecto;?>" class="btn">Mes</a>
                <a href="<?= base_url().$user;?>/calendario/semana/<?= $proyecto;?>" class="btn active">Semana</a>
                <a href="<?= base_url().$user;?>/calendario/dia/<?= $proyecto;?>" class="btn">Día</a>
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

    <?= $this->calendar_week->generate($data); ?>  
</div>
