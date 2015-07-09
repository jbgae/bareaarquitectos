<section>
    <div class="container"> 
        <?php if($this->input->post('buscador') != ''):?>
            <h4> Resultados de búsqueda para: <em><u><?=$this->input->post('buscador') ;?></u></em> </h4>
        <?php endif;?> 
        <br>
        
        <?php if(empty($paginas)): ?>
            <div> No se encontraron resultados para  <em><u><?=$this->input->post('buscador') ;?></u></em>. </div>
            
        <?php else:?>      
        
            <?php foreach($paginas as $pagina): ?>
                <h4><?= ucfirst($pagina->NombrePagina);?></h4>
                <?php if(isset($pagina->TextoCortado)): ?>
                    <div class="pagina"><?= $pagina->TextoCortado; ?></div>
                <?php else: ?>
                    <div class="pagina"><?= $pagina->Texto; ?></div>
                <?php endif;?>
                <p>
                    <a href="<?= base_url();?><?= $pagina->NombrePagina;?>">
                        Leer Más
                    </a>
                </p>    
            <?php endforeach; ?>

            <div class="pagination pagination-centered">
                <ul> 
                    <?= $links;?> 
                </ul>    
            </div> 
        <?php endif;?>
    </div>
</section>