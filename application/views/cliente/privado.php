<section>
    <div class="container-fluid container">
        <div class="row-fluid">
            
            <div class="span6">
                <h3>Inicie Sesión</h3>

                <div class="text">
                    Inicie sesión para acceder a su espacio personal. En su espacio personal 
                    podrá realizar diferentes operaciones, como realizar la solicitud de un 
                    presupuesto, o descargar la documentación asociada a los proyectos 
                    adquiridos.
                </div>
                <div class="subrayado text">
                    <?= anchor('restablecer','¿No puedes acceder a tu cuenta? (<u>X</u>)', array('accesskey'=>'X', "title"=>"Solicitar una nueva contraseña"));?>
                </div>

                 <?php if(isset($mensaje)): ?>
                    <?= $mensaje; ?>
                <?php endif;?>
                
                <?= form_open('privado', array('id'=>'sesion')); ?> 
                
                <?php foreach ($formulario as $input): ?>
                    <div class="control-group">
                        <label class="control-label" for="<?= $input['input']['id'];?>" accesskey ="<?= $input['label']['accesskey'];?>"><?= $input['label']['name']; ?><span class="rojo">*</span></label>  
                        <div class="controls">
                            <?php if($input['input']['name'] == 'pass'): ?>
                                <?= form_password($input['input']); ?>
                                <?= form_error($input['input']['name']); ?>
                            <?php else: ?>
                                <?= form_input($input['input']);  ?>
                                <?= form_error($input['input']['name']);?> 
                            <?php endif;?>
                        </div>
                    </div>
                <?php endforeach;?>   
                <div>
                    <?= form_submit($boton);  ?>
                    <?= form_reset('myreset', 'Borrar',  'class = "btn"'); ?>
                </div>

               

                <?= form_close(); ?>    
            </div>
            
            <div class="span6"> 
                <h3>Regístrese</h3>
                <div class="text">
                    Si desea registrarse como nuevo cliente pulse en el siguiente enlace y
                    rellene el formulario.<br>
                    <div class="subrayado text">
                        <?= anchor("registrar", "Registro nuevo cliente (<u>Z</u>)", array('accesskey'=>'Z', "title"=>"Formulario de registro de nuevo cliente"));?>
                    </div>

                </div>
            </div>
            
        </div>
    </div>
</section>
