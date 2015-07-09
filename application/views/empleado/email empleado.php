<?= form_open("$usuario/$dir/enviar/". urlencode($email)); ?>
    <?php foreach($formulario as $input): ?>
        <label class="control-label" for="<?= $input['id']; ?>">
            <?= ucfirst($input['label']); ?>
        </label>
        <?php if($input['id'] == 'contenido'): ?>
            <?= form_textarea($input); ?>
            <?= form_error($input['name']); ?>
        <?php else: ?>
            <?= form_input($input); ?>
            <?= form_error($input['name']); ?>
        <?php endif;?>
    <?php endforeach; ?>
    <br>
    <div>
        <?= form_submit($boton, 'Enviar email'); ?>
    </div>    
<?= form_close(); ?>       

