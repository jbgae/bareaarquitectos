    <div class="container">

           <?= form_open('admin', array('id'=>'sesion', 'class'=>'form-signin')); ?> 
           <h2 class="form-signin-heading">Iniciar sesión</h2>
                <?php foreach ($formulario as $input): ?>
                    <div class="control-group">
                        <?php if($input['input']['name'] == 'captcha'):?>
                            <?= $imagen;?>
                            <?php if(isset($errorCaptcha)):?>
                                <div class="alert alert-error">
                                    <button type="button" class="close" data-dismiss="alert">
                                        &times;
                                    </button> 
                                    <?=$errorCaptcha;?>
                                </div>
                            <?php endif;?>
                        <?php endif;?>
                        <div class="controls">
                            <?php if($input['input']['name'] == 'pass'): ?>
                                <?= form_password($input['input'],'', 'placeholder =' . ucfirst($input['input']['name']).''); ?>
                                <?= form_error($input['input']['name']); ?>
                            <?php else: ?>
                                <?= form_input($input['input'],'', 'placeholder =' . ucfirst($input['input']['name']).'');  ?>
                                <?= form_error($input['input']['name']);?> 
                            <?php endif;?>
                        </div>
                    </div>
                <?php endforeach;?>   
                <?php if(isset($mensaje)):?>
                    <?=$mensaje;?>
                <?php endif;?>
                <div>
                    <?= form_submit($boton);  ?>
                    <?= form_reset('myreset', 'Borrar',  'class = "btn"'); ?>
                </div>

               

                <?= form_close(); ?> 
        
        

    </div> 