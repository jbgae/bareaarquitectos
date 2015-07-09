<div>
    <?= form_open("$user/clientes/buscar", array('class'=>'pull-right'));?>
        <?= form_input($buscador);?>
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
        <?= form_open("$user/clientes/buscar/Nombre/asc/".$elementos.'/'.$busq, array('class'=>'cantidad'));?>
    <?php else:?>
        <?= form_open("$user/clientes", array('class'=>'cantidad'));?>
    <?php endif;?> 
        <?= form_dropdown('cantidad', $opciones, $limit, 'class = input-small');?>
        <?= form_submit(array('class'=>'btn btn-info'), 'Mostrar');?>
    <?= form_close();?>
</div>

<?php if($user != 'admin'):?>
</div>
<?php endif;?>

<?php if($numero == 0):?> 
<div class="alert alert-error span10" id="Error">
    <button type="button" class="close" data-dismiss="alert">
        &times;
    </button> 
    No se han encontrado clientes que concuerden con esos criterios.
</div>
<?php else:?>

<?php if($user == 'admin'):?>
    <?= form_open('admin/clientes/borrar', array('name'=>'f1'));?>
        <div>
            <?= form_submit($borrar);?>
        </div>
<?php endif;?>

<div class="row-fluid" id="flip-scroll">            
    <table class="table-striped table-condensed cf">
        <thead class="cf">
            <tr>
                <?php if($user == 'admin'):?>
                    <th><input type="checkbox" id="input" onclick="seleccionar_checkbox(this.checked); crear_boton(this.checked);"></th>
                <?php endif;?>    
                <?php foreach($fields as $field_name => $field_display): ?>
                    <?php if(isset($busqueda)):?>
                        <th>
                             <?php if($orden == 'asc' && $campo == $field_name):?>
                                <?= anchor("$user/clientes/buscar/$field_name/desc/$elementos/$busq", $field_display . ' <i class=" icon-chevron-up"></i>'); ?>
                            <?php elseif($orden == 'desc' && $campo == $field_name):?>
                                <?= anchor("$user/clientes/buscar/$field_name/asc/$elementos/$busq", $field_display . ' <i class=" icon-chevron-down"></i>'); ?>
                            <?php else: ?>
                                <?= anchor("$user/clientes/buscar/$field_name/" .(($orden == 'asc' && $campo == $field_name) ? 'desc' : 'asc')."/$elementos/$busq" , $field_display); ?>
                            <?php endif;?>
                        </th>
                    <?php else: ?>    
                        <th>
                            <?php if($orden == 'asc' && $campo == $field_name):?>
                                <?= anchor("$user/clientes/$field_name/desc/$elementos", $field_display . ' <i class=" icon-chevron-up"></i>'); ?>
                            <?php elseif($orden == 'desc' && $campo == $field_name):?>
                                <?= anchor("$user/clientes/$field_name/asc/$elementos", $field_display . ' <i class=" icon-chevron-down"></i>'); ?>
                            <?php else: ?>
                                <?= anchor("$user/clientes/$field_name/" .(($orden == 'asc' && $campo == $field_name) ? 'desc' : 'asc')."/$elementos" , $field_display); ?>
                            <?php endif;?>
                        </th>
                    <?php endif;?>    
                <?php endforeach; ?>       
                <th class="opciones">Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($clientes)): ?>
                <?php $aux = 0; ?>
                <?php foreach($clientes as $cliente): ?>
                    <tr>
                        <?php if($user == 'admin'):?>
                            <?php  $check= array('name' => 'checkbox[]', 'checked'=> FALSE, 'value'=>$cliente->Email); ?>
                            <td><?= form_checkbox($check); ?></td>
                        <?php endif;?>
                        <td class="nombre"><?= $cliente->Nombre. ' ' . $cliente->ApellidoP. ' ' .$cliente->ApellidoM;?></td>
                        <td class="email"><?= anchor("$user/clientes/enviar/".urlencode($cliente->Email), $cliente->Email);?> </td>
                        <td class="alta"><?= date('d-m-Y H:i:s',strtotime($cliente->FechaAltaSistema));?></td>                                
                        <td class="acceso"><?= date("d-m-Y H:i:s", strtotime($cliente->FechaAltaSistema));?></td> 
                        <td class="presupuestos"><?= $cliente->Presupuestos;?></td>
                        <td class="proyectos"><?= $cliente->Proyectos;?></td>
                        <td>
                            <div>
                                <a href="#myModal<?= $aux;?>" data-toggle="modal"> 
                                    <i class="icon-eye-open"></i>
                                    Datos personales
                                </a>
                                <div id="myModal<?= $aux;?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                        <h5 id="myModalLabel"> <?= $cliente->Nombre. ' '. $cliente->ApellidoP. ' ' .$cliente->ApellidoM ; ?></h5>
                                    </div>
                                    <div class="modal-body">
                                        <address>
                                            <strong>Fecha Nacimiento</strong><br>
                                            <?= date("d-m-Y",strtotime($cliente->FechaNacimiento));?><br>
                                            <strong>Dirección</strong><br>
                                            <?= $cliente->Direccion;?>, 
                                            <?= $cliente->Ciudad;?><br>
                                            (<?= $cliente->Provincia;?>)<br>
                                            <strong>Teléfono</strong><br>
                                            <?= $cliente->Telefono;?>
                                        </address>    
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <a href="<?=base_url().$user;?>/clientes/editar/<?= urlencode($cliente->Email);?>"> 
                                    <i class="icon-edit"></i>
                                    Editar
                                </a>                            
                            </div>
                            <?php if($user == 'admin'):?>
                            <div>
                                <?php if($cliente->Presupuestos > 0 || $cliente->Proyectos > 0):?>
                                 <a href="<?=base_url().$user;?>/clientes/borrar/<?= urlencode($cliente->Email);?>" 
                                    data-confirm="El cliente <?=$cliente->Nombre.' '.$cliente->ApellidoP.' '.$cliente->ApellidoM;?> tiene  <?=$cliente->Presupuestos;?> presupuestos y <?= $cliente->Proyectos;?>  proyectos creados. Si eliminas el cliente estos se eliminarán igualmente.¿Deseas continuar?">                               
                                <?php else:?>
                                <a href="<?=base_url().$user;?>/clientes/borrar/<?= urlencode($cliente->Email);?>" data-confirm="¿Desea eliminar los datos relativos al cliente <?=$cliente->Nombre.' '.$cliente->ApellidoP.' '.$cliente->ApellidoM;?>?"> 
                                <?php endif;?>    
                                    <i class="icon-trash"></i>
                                    Borrar
                                </a>

                            </div> 
                            <?php endif;?>
                        </td>
                    </tr>
                    <?php $aux++;?>
                <?php endforeach; ?>
            <?php endif;?> 
        </tbody>
    </table>
</div>
<?php endif;?>
<?php if($user == 'admin'):?>
    <?= form_close();?>
<?php endif;?>
<?php if(!isset($vacio)): ?>
<div class="pagination pagination-centered">
    <ul>
        <?= $links; ?> 
    </ul>    
</div>
<?php endif; ?>
<?php if($user == 'admin'):?>   
    </div>
<?php endif;?>
