<section>
    <div class="container">        
        <h3><?= $noticia->Titulo;?></h3>
        <div class ="datos">Escrito por <?= $escritor; ?> el <?= date("d-m-Y H:i:s", strtotime($noticia->FechaCreacion))?> </div>
        <div class="noticia"> <?= $noticia->Contenido; ?> </div>
        <?php if(isset($_SERVER['HTTP_REFERER'])):?>
            <p><a href="<?=$_SERVER['HTTP_REFERER']?>">Volver</a>
        <?php endif;?>        
    </div>
</section>