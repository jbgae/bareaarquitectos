<div>
    <?= form_open('admin/empleados/buscar', array('class'=>'pull-right')); ?>
        <?= form_input($buscador); ?>
        <?php if(isset($busqueda) && $numero > 0):?>
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
        <?= form_open('admin/empleados/buscar', array('class'=>'cantidad'));?>
    <?php else:?>
        <?= form_open('admin/empleados', array('class'=>'cantidad'));?>
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
        No se han encontrado empleados que concuerden con esos criterios.
    </div>
<?php else:?>
    <?= form_open('admin/empleados/borrar', array('name'=> 'f1', 'id'=> 'formBorrar')); ?>
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
                                    <?= anchor("admin/empleados/buscar/$field_name/desc/$elementos/$busq", $field_display . ' <i class=" icon-chevron-up"></i>'); ?>
                                <?php elseif($orden == 'desc' && $campo == $field_name):?>
                                    <?= anchor("admin/empleados/buscar/$field_name/asc/$elementos/$busq", $field_display . ' <i class=" icon-chevron-down"></i>'); ?>
                                <?php else: ?>
                                    <?= anchor("admin/empleados/buscar/$field_name/" .(($orden == 'asc' && $campo == $field_name) ? 'desc' : 'asc')."/$elementos/$busq" , $field_display); ?>
                                <?php endif;?>
                            </th>
                        <?php else: ?>    
                            <th>
                                <?php if($orden == 'asc' && $campo == $field_name):?>
                                    <?= anchor("admin/empleados/$field_name/desc/$elementos", $field_display . ' <i class=" icon-chevron-up"></i>'); ?>
                                <?php elseif($orden == 'desc' && $campo == $field_name):?>
                                    <?= anchor("admin/empleados/$field_name/asc/$elementos", $field_display . ' <i class=" icon-chevron-down"></i>'); ?>
                                <?php else: ?>
                                    <?= anchor("admin/empleados/$field_name/" .(($orden == 'asc' && $campo == $field_name) ? 'desc' : 'asc')."/$elementos" , $field_display); ?>
                                <?php endif;?>
                            </th>
                        <?php endif;?>    
                    <?php endforeach; ?>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($empleados)): ?>
                    <?php $aux = 0; ?>
                    <?php foreach($empleados as $empleado): ?>
                        <tr>
                            <?php  $check= array('name' => 'checkbox[]', 'checked'=> FALSE, 'value'=>$empleado->Email); ?>
                            <td><?= form_checkbox($check); ?></td>
                            <td><?= $empleado->Nombre. ' ' . $empleado->ApellidoP. ' ' .$empleado->ApellidoM;?></td>
                            <td><?= $empleado->Cargo; ?></td>
                            <td><?= $empleado->Salario; ?></td>
                            <?php if($empleado->FechaContratacion == 'Desconocida'):?>
                                <td><?= $empleado->FechaContratacion;?></td>
                            <?php else:?>
                                <td><?= date("d-m-Y", strtotime($empleado->FechaContratacion)); ?></td>
                            <?php endif;?>
                            <?php if($empleado->FechaFinContrato == 'Desconocida'):?>    
                                <td><?= $empleado->FechaFinContrato;?></td>
                            <?php else:?>
                                <td><?= date("d-m-Y", strtotime($empleado->FechaFinContrato));?></td>
                            <?php endif;?>    
                            <td><?= date("d-m-Y H:i:s", strtotime($empleado->FechaUltimoAcceso));?></td>
                            <td><?= anchor('admin/empleados/enviar/'.urlencode($empleado->Email), $empleado->Email);?> </td>
                            <td>
                                <div>
                                    <a href="#myModal<?= $aux?>" data-toggle="modal"> 
                                        <i class="icon-eye-open"></i>
                                        Datos personales
                                    </a>
                                    <div id="myModal<?= $aux;?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                            <?php if($empleado->Ruta == ''):?>
                                                <img src ="<?= base_url();?>images/indice.jpeg">
                                            <?php else:?>
                                                <img src="<?= $empleado->Ruta;?>">
                                            <?php endif;?>
                                            <h5 id="myModalLabel"> <?= $empleado->Nombre. ' '. $empleado->ApellidoP. ' ' .$empleado->ApellidoM ; ?></h5>

                                        </div>
                                        <div class="modal-body">
                                            <address>
                                                <strong>Fecha Nacimiento</strong><br>
                                                <?= date("d-m-Y",strtotime($empleado->FechaNacimiento));?><br>
                                                <strong>Dirección</strong><br>
                                                <?= $empleado->Direccion;?>, 
                                                <?= $empleado->Ciudad;?><br>
                                                (<?= $empleado->Provincia;?>)<br>
                                                <strong>Teléfono</strong><br>
                                                <?= $empleado->Telefono;?>                                        
                                            </address>    
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <a href="<?=base_url();?>admin/empleados/editar/<?= urlencode($empleado->Email);?>"> 
                                        <i class="icon-edit"></i>
                                        Editar
                                    </a>                            
                                </div>    
                                <div>
                                    <a href="<?=base_url();?>admin/empleados/borrar/<?= urlencode($empleado->Email);?>" data-confirm="¿Desea eliminar los datos correspondientes al empleado <?= $empleado->Nombre. ' '. $empleado->ApellidoP. ' ' .$empleado->ApellidoM ; ?>?"> 
                                        <i class="icon-trash"></i>
                                        Borrar
                                    </a>
                                </div>    
                            </td>
                        </tr>
                        <?php $aux++;?>
                    <?php endforeach; ?>
                <?php endif;?>
            </tbody>
        </table>
    </div>
<?= form_close();?>

<?php endif;?>

           