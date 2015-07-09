<section>
    <div class="container"> 
        <?php foreach($noticias as $noticia): ?>
            <h4><?= $noticia->Titulo?></h4>
            <?php if(isset($noticia->ContenidoCortado)): ?>
                <div class="noticia"><?= $noticia->ContenidoCortado; ?></div>
            <?php else: ?>
                <div class="noticia"><?= $noticia->Contenido; ?></div>
            <?php endif;?>
            <p>
                <a href="<?= base_url();?>noticia/<?= $noticia->Codigo;?>">
                    Leer Más
                </a>
            </p>    
        <?php endforeach; ?>

        <div class="pagination pagination-centered"> 
            <ul> 
                <?= $links;?>
            </ul>    
        </div>
        
    </div>
</section>


