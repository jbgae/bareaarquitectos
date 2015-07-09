
<?= form_open('admin/web/editar/'.$nombrePagina.'/'.$posicion, array('id'=>'webForm')); ?>
    <h5 id="tituloPagina">Página: <?= ucwords($nombrePagina);?></h5>
    <h5>Posición: <?= ucwords($posicion);?></h5>
    <?php foreach($formulario as $input): ?>
        <label class="control-label" for="<?= $input['id']; ?>">
            <h5> <?= ucfirst($input['label']).':'; ?> </h5>
        </label>
    
        <?= form_textarea($input); ?>
        <?= form_error($input['name']); ?>
    <?php endforeach; ?>
    <br>
    <div>
        <?= form_submit($boton, 'Actualizar web'); ?>
    </div>    
<?= form_close(); ?>       
