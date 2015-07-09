<div class="row-fluid span9" id="flip-scroll">                  
    <?php if(!empty($presupuestos)):?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Dirección</th>
                <th>Ciudad</th>
                <th>Provincia</th>
                <th>Fecha de solicitud</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
        <?php if(isset($presupuestos)): ?>
            <?php foreach($presupuestos as $presupuesto): ?> 
                <tr>                            
                    <td><?= $presupuesto->Direccion;?></td>
                    <td><?= $presupuesto->Ciudad;?></td>
                    <td><?= $presupuesto->Provincia;?></td>                                 
                    <td><?= date("d-m-Y H:i:s",strtotime($presupuesto->FechaSolicitud)); ?></td>

                    <td>
                        <div class ="descargar">
                            <a href="<?=base_url();?>cliente/presupuesto/descargar/<?= $presupuesto->CodigoArchivo;?>">                             
                                <i class="icon-download"></i>
                                Descargar
                            </a>
                        </div>
                        <div class ="comprar">
                            <a href="<?=base_url();?>cliente/presupuesto/carro/<?= $presupuesto->Codigo;?>">                             
                                <i class="icon-shopping-cart"></i>
                                Añadir 
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif;?>
        </tbody>        
    </table>
    <?php else:?>
       <div class="span6 offset1">
            <div class="alert alert-block">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h4>Advertencia</h4>
                No hay presupuestos disponibles.
            </div>
       </div>
    <?php endif;?>              
</div>
</div>
</div>




</section>

