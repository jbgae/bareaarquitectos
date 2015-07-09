<section>
    <div class="container"> 
        <?php if(isset($mensaje)):?>
            <p><?= $mensaje;?></p>
        <?php else:?>
        
        <?php if(isset($proyectos)):?>    
            <?php foreach($proyectos as $proyecto): ?> 
                <h4><?= $proyecto['Tipo'].': '.$proyecto['Ciudad'].' ('.$proyecto['Provincia'].')' ?></h4>
                <div class="proyecto"><?= $proyecto['Descripcion']; ?></div>
                <?php if(!empty($proyecto['imagenes'])):?>
                    <div class="imagen">
                        <?php $i = 0;?>
                        <?php foreach($proyecto['imagenes'] as $imagen):?>
                            <a data-toggle="lightbox" href="#Lightbox<?= $i;?>" class="zoom"><img src="<?= $imagen->Ruta;?>"></a>
                            <div id="Lightbox<?= $i;?>" class="lightbox hide fade" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class='lightbox-content'>
                                    <img src="<?= $imagen->Ruta;?>">
                                </div>
                            </div>
                            <?php $i++;?>
                        <?php endforeach;?>
                    </div>

                <?php endif;?>   
            <?php endforeach; ?>
        <?php endif;?> 
    </div>
    <?php if(isset($links)):?>    
        <div class="pagination pagination-centered"> 
            <?= $links;?> 
        </div>
    <?php endif;?> 
    <?php endif;?>
</section>


                                    