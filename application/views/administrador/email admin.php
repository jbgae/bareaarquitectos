<div class="content">
    <div class="container-fluid">
        <div class="row-fluid">
    
    <?php if(isset($error)): ?>
        <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">
                &times;
            </button> 
            <?=$error;?>
        </div>
    <?php elseif(isset($valido)):?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">
                &times;
            </button> 
            <?=$valido;?>
        </div>
    <?php endif; ?>
    
    <?= form_open("admin/$dir/enviar/". urlencode($email)); ?>
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
</div>
