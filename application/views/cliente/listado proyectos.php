<div class ="tabla span9">
    <?php if(!empty($proyectos)):?>
    <table class="table table-striped">
        <tr>
            <th>Dirección</th>
            <th>Ciudad</th>
            <th>Provincia</th>
            <th>Fecha</th>
            <th>Opciones</th>
        </tr>
        <?php if(isset($proyectos)): ?>
            <?php foreach($proyectos as $proyecto): ?> 
                <tr>                            
                    <td><?= $proyecto->Direccion;?></td>
                    <td><?= $proyecto->Ciudad;?></td>
                    <td><?= $proyecto->Provincia;?></td>                                 
                    <td><?= date("d-m-Y",strtotime($proyecto->FechaFinPrevista)); ?></td>

                    <td>
                        <div class ="descargar">
                            <a href="<?=base_url();?>cliente/proyecto/descargar/<?= $proyecto->CodigoArchivo;?>">                             
                                Descargar
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif;?>         
    </table>
    <?php else:?>
        <div class="span6 offset1">
            <div class="alert alert-block">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h4>Advertencia</h4>
                No hay proyectos disponibles.
            </div>
        </div>
    <?php endif;?>              
</div>
</div>
</div>
</section>