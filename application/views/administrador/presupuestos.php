<div>
    <?= form_open('admin/presupuesto/buscar', array('class'=>'pull-right')); ?>
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
<div>
    <?php if(isset($busqueda)):?>
        <?= form_open('admin/presupuesto/buscar', array('class'=>'cantidad'));?>
    <?php else:?>
        <?= form_open('admin/presupuesto', array('class'=>'cantidad'));?>
    <?php endif;?>
        <?= form_dropdown('cantidad',$opciones,$limit,'class = input-small');?> 
        <?= form_submit(array('class'=>'btn btn-info'),'Mostrar');?>
    <?= form_close();?>    
</div>

<?php if($numero == 0):?> 
    <div class="alert alert-error span10" id="Error">
        <button type="button" class="close" data-dismiss="alert">
            &times;
        </button> 
        No se han encontrado presupuestos que concuerden con esos criterios.
    </div>
<?php else:?>


<?= form_open('admin/presupuesto/borrar', array('name'=> 'f1')); ?>
    <div>
        <?= form_submit($borrar); ?>
    </div>
    <div class="row-fluid" id="flip-scroll">
        <table class="table-striped table-condensed cf">
            <thead class="cf">
                <tr>
                    <th><input type="checkbox" id="input" onclick="seleccionar_checkbox(this.checked); crear_boton(this.checked);"></th>
                    <?php foreach($fields as $field_name => $field_display): ?>
                        <?php if(isset($busqueda)):?>
                            <th>
                                 <?php if($orden == 'asc' && $campo == $field_name):?>
                                    <?= anchor("admin/presupuesto/buscar/$field_name/desc/$elementos/$busq", $field_display . ' <i class=" icon-chevron-up"></i>'); ?>
                                <?php elseif($orden == 'desc' && $campo == $field_name):?>
                                    <?= anchor("admin/presupuesto/buscar/$field_name/asc/$elementos/$busq", $field_display . ' <i class=" icon-chevron-down"></i>'); ?>
                                <?php else: ?>
                                    <?= anchor("admin/presupuesto/buscar/$field_name/" .(($orden == 'asc' && $campo == $field_name) ? 'desc' : 'asc')."/$elementos/$busq" , $field_display); ?>
                                <?php endif;?>
                            </th>
                        <?php else: ?>    
                            <th>
                                <?php if($orden == 'asc' && $campo == $field_name):?>
                                    <?= anchor("admin/presupuesto/$field_name/desc/$elementos", $field_display . ' <i class=" icon-chevron-up"></i>'); ?>
                                <?php elseif($orden == 'desc' && $campo == $field_name):?>
                                    <?= anchor("admin/presupuesto/$field_name/asc/$elementos", $field_display . ' <i class=" icon-chevron-down"></i>'); ?>
                                <?php else: ?>
                                    <?= anchor("admin/presupuesto/$field_name/" .(($orden == 'asc' && $campo == $field_name) ? 'desc' : 'asc')."/$elementos" , $field_display); ?>
                                <?php endif;?>
                            </th>
                        <?php endif;?>    
                    <?php endforeach; ?>
                    <th class="opciones">Opciones</th>
                </tr>
            </thead>
            <tbody id="tabla-presupuestos">
                <?php if(isset($presupuestos)): ?>
                    <?php foreach($presupuestos as $presupuesto): ?>
                        <tr>
                            <?php  $check= array('name' => 'checkbox[]', 'checked'=> FALSE, 'value'=>$presupuesto->Codigo); ?>
                            <td><?= form_checkbox($check); ?></td>
                            <td><?= $presupuesto->Nombre. ' '. $presupuesto->ApellidoP. ' ' . $presupuesto->ApellidoM;?></td>
                            <td><?= $presupuesto->EmailCliente;?></td>
                            <?php if($presupuesto->Estado == 'Abierto'):?>
                                <td id="estado<?= $presupuesto->Codigo;?>"><span class="label label-warning"><?= $presupuesto->Estado; ?></span></td>
                            <?php elseif($presupuesto->Estado == 'Enviado'):?>
                                <td id="estado<?= $presupuesto->Codigo;?>"><span class="label label-info"><?= $presupuesto->Estado; ?></span></td>
                            <?php elseif($presupuesto->Estado == 'Aceptado'):?>
                                <td id="estado<?= $presupuesto->Codigo;?>"><span class="label label-success"><?= $presupuesto->Estado; ?></span></td>
                            <?php elseif($presupuesto->Estado == 'Cerrado'):?>
                                <td id="estado<?= $presupuesto->Codigo;?>"><span class="label label-inverse"><?= $presupuesto->Estado; ?></span></td>
                            <?php endif;?>    
                            <td><?= $presupuesto->FechaSolicitud; ?></td>

                            <td><?= $presupuesto->Tipo;?></td>
                            <td><?= $presupuesto->Direccion; ?></td>
                            <td><?= $presupuesto->Ciudad;?> </td>
                            <td><?= $presupuesto->Provincia;?></td>

                            <td>
                                <?php if($presupuesto->Estado == 'Abierto'):?>
                                    <div>
                                        <a href="<?=base_url();?>admin/presupuesto/crear/<?= $presupuesto->Codigo;?>" data-toggle="modal"> 
                                            <i class="icon-plus"></i>
                                            Crear
                                        </a>
                                    </div>
                                <?php elseif($presupuesto->Estado == 'Aceptado'):?>
                                    <div>
                                        <a href="<?=base_url();?>admin/proyecto/crear/<?= $presupuesto->Codigo;?>" data-toggle="modal"> 
                                            <i class="icon-plus"></i>
                                            Crear
                                        </a>
                                    </div>
                                <?php else:?>
                                    <div>
                                        <a href="<?=base_url();?>admin/presupuesto/descargar/<?= $presupuesto->CodigoArchivo;?>" data-toggle="modal"> 
                                            <i class="icon-download"></i>
                                            Descargar
                                        </a>
                                    </div>
                                <?php endif;?>
                               <div>
                                    <a href="<?=base_url();?>admin/presupuesto/borrar/<?= $presupuesto->Codigo;?>" data-confirm="¿Estás seguro?"> 
                                        <i class="icon-trash"></i>
                                        Borrar
                                    </a>
                                </div>    
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif;?>  
            </tbody>
        </table>
    </div>
<?= form_close();?>
<?php endif;?>
<?php if(!isset($vacio)): ?>
    <div class="pagination pagination-centered">
        <ul>
            <?= $links; ?> 
        </ul>
    </div>
<?php endif; ?>
 
