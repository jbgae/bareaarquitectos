<section>
    <div class="container">            
        <div class="span12">
            <h2><? $titulo;?></h2>
            <div class="text">
                Para restablecer la contraseña, escribe tu dirección de correo electrónico
                y los caracteres de la imagen siguiente.
            </div>                
    
            <?php if(isset($mensaje)):?>    
                <div class="alert alert-success span9">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h4>Éxito</h4>
                    Se ha enviado un email a su dirección de correo electrónico con la nueva contraseña.
                </div>
            <?php endif;?>
            <div class='clearfix'></div>
            <?= form_open('restablecer', array('id'=>'captchaForm')); ?> 
                <div class="control-group">
                    <label class="control-label" for="<?= $formulario['email']['input']['id'];?>" accesskey ="<?= $formulario['email']['label']['accesskey'];?>"><?= $formulario['email']['label']['name']; ?><span class="rojo">*</span></label>  
                    <div class="controls">
                        <?= form_input($formulario['email']['input']);?>
                        <?= form_error($formulario['email']['input']['name']); ?>
                        <?php if(isset($errorUsuario)):?>
                            <div class="alert alert-error">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <h4>Error</h4>
                                <?= $errorUsuario; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div id="comentarioCaptcha" class="text">
                    Escribe los caracteres que veas:
                </div>
                <?= $imagen; ?><br>
        
                <div class="control-group">
                    <label class="control-label" for="<?= $formulario['captcha']['input']['id'];?>" accesskey ="<?= $formulario['captcha']['label']['accesskey'];?>"><?= $formulario['captcha']['label']['name']; ?><span class="rojo">*</span></label>  
                        <div class="controls">
                            <?= form_input($formulario['captcha']['input']);?>
                            <?= form_error($formulario['captcha']['input']['name']); ?>
                            <?php if(isset($errorCaptcha)):?>
                                <div class="alert alert-error">
                                    <?= $errorCaptcha; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                </div>    
                <div>
                    <?= form_submit($boton); ?>
                    <?= form_reset('myreset', 'Borrar',  'class = "btn"'); ?>
                </div>
            <?= form_close();?>
                
        </div>
    </div>
</section>
