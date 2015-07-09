<section>
    <div class="container-fluid container">
        <div class="row-fluid">
            <div class="span6">
                <h3>Contacta con nosotros</h3>
                <div class ="text"> 
                    Si desea realizar alguna consulta o necesita más información sobre 
                    cualquiera de nuestros servicios, póngase en contacto con nosotros y le 
                    responderemos lo antes posible.
                </div>

                <?php if(isset($mensaje)):?>    
                    <?= $mensaje;?>
                <?php endif;?>
                <div class='clearfix'></div>
                
                <?= form_open('contacto', array('id'=>'contacto')); ?>
                    <?php foreach($formulario as $input): ?>
                        <div class="control-group">
                            <?php if($input['input']['name'] != 'politica'):?>
                                <label class="control-label" for="<?= $input['input']['id'];?>" accesskey ="<?= $input['label']['accesskey'];?>"><?= $input['label']['name']; ?><span class='rojo'>*</span></label>
                            <?php else:?>     
                                <label class="control-label" for="<?= $input['input']['id'];?>" accesskey ="<?= $input['label']['accesskey'];?>"><span class='rojo'>*</span><?= $input['label']['name']; ?></label>
                            <?php endif;?>        
                            <div class="controls">
                                <?php if($input['input']['name'] == 'comentario'): ?>
                                    <?= form_textarea($input['input']);?>
                                <?php elseif($input['input']['name'] == 'politica'):?>
                                    <?= form_checkbox($input['input']);?>                                    
                                <?php else: ?>                   
                                    <?= form_input($input['input']); ?>                       
                                <?php endif; ?> 

                                <?= form_error($input['input']['name']); ?>
     
                            </div>
                        </div>
                    <?php endforeach; ?>        
                    <div class="botones">
                        <?php echo form_submit($boton,'Enviar');  ?>
                        <?php echo form_reset('myreset', 'Borrar', 'class = "btn" id="borrar"'); ?>
                    </div>
                <?= form_close(); ?>
            </div>

            <div class="span6"> 
                <h3>Nos encontrarás en</h3>
                <iframe  frameborder="0" scrolling="no" 
                 marginheight="0" marginwidth="0" 
                 src="http://maps.google.es/maps?client=ubuntu&amp;
                 channel=fs&amp;oe=utf-8&amp;ie=UTF8&amp;q=google+maps+barea+arquitecto&amp;
                 fb=1&amp;gl=es&amp;hq=barea+arquitecto&amp;
                 hnear=0xd0c34bd56ad3247:0xf716d829c0c890f6,Chiclana+de+la+Frontera&amp;
                 cid=0,0,2373402858069477653&amp;t=m&amp;ll=36.422905,-6.14713&amp;
                 spn=0.006043,0.009871&amp;z=16&amp;iwloc=A&amp;output=embed">
                </iframe>
                
                
            </div>
        </div>
    </div>
</section>
