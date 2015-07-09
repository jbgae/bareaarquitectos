<h2>Crear copia de seguridad</h2>
<div>
En esta sección puedes realizar una copia de seguridad de los datos 
almacenados en la aplicación para que en caso que el sistema falle 
pueda restaurarse.
</div>
<br>
<a href="copia_seguridad" class="btn btn-primary">Crear copia seguridad</a>
<br><br>
<h2>Restaurar a un punto anterior</h2>
<div>
Elija uno de los puntos de restauración almacenados en el sistema,
</div>
<br>
<?php if(!empty($archivos)):?>
    <?= form_open('admin/restaurar', array('class'=>'form-inline', 'id'=>'formdata'));?>
        <?= form_dropdown('backups', $archivos);?>
        <?= form_submit(array('class'=>'btn btn-danger', 'id'=>'restaurar'),'Restaurar');?>
    <?= form_close();?>    
<?php endif;?>

<div id="mensaje"></div>

<div class="modal js-loading-bar" data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div>
                    <img src="<?= base_url();?>/images/progress_bar.gif" alt="cargando" id="cargando">
                </div>
                No cierres la ventana hasta que el proceso haya concluido.
                Este proceso puede durar varios minutos.
            </div>
        </div>
    </div>
</div>


