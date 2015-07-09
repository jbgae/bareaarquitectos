<div class='container'>
    
    <div class="span4 offset3">
        <?= form_open("paginas/my404"); ?>
            <?= form_input($buscador); ?>
            <?php if(isset($busqueda)):?>
                <div>                        
                    <?php if($numero == 0):?> 
                        <div class="text-error">
                            <?= $numero; ?> resultados encontrados.
                        </div>
                    <?php else:?>
                        <div class="text-success">
                            <?= $numero; ?> resultados encontrados.
                        </div>
                    <?php endif;?>    
                </div>
            <?php endif;?>
        <?= form_close();?>                       
    </div>
    <?php if(!isset($paginas)):?>
    <div class="alert alert-error span9">
        <button type="button" class="close" data-dismiss="alert">
            &times;
        </button>
        <h4>
            No encontrado.
        </h4>
        <h5> 
            Lo sentimos, pero estás buscando algo que no está aquí.
            Si lo desea puede usar el buscador para, intentar encontrar el contenido
            requerido.
        </h5>
    </div>
    <?php endif;?>
    <?php if(isset($paginas)):?>
    <div class="span8">
        <h4>Resultados:</h4>
        <?php if($numero == 0):?>
        <h5>No se han encontrado resultados.</h5>
        <?php endif;?>
        <ul>
            <?php foreach($paginas as $pagina): ?>
            <li>
                <h5><?= ucfirst($pagina->NombrePagina);?></h5>
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
            </li>
            <?php endforeach;?>
        </ul>
    </div>
    <?php endif;?>
    
</div>
