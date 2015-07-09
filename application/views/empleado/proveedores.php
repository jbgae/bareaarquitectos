<div>
    <?= form_open("$user/proveedor/buscar", array('class'=>'pull-right')); ?>
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
        <?= form_open("$user/proveedor/buscar/Cif/asc/".$elementos.'/'.$busq, array('class'=>'cantidad'));?>
    <?php else:?>
        <?= form_open("$user/proveedor", array('class'=>'cantidad'));?>
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
    No se han encontrado proveedores que concuerden con esos criterios.
</div>
<?php else:?>


<?php if($user != 'admin'):?>    
</div>
<?php endif;?>
<?php if($user == 'admin'):?>
<?= form_open('admin/proveedor/borrar', array('name'=> 'f1')); ?>
    <div>
        <?= form_submit($borrar); ?>
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
                            <?= anchor("admin/proveedor/buscar/$field_name/desc/$elementos/$busq", $field_display . ' <i class=" icon-chevron-up"></i>'); ?>
                        <?php elseif($orden == 'desc' && $campo == $field_name):?>
                            <?= anchor("admin/proveedor/buscar/$field_name/asc/$elementos/$busq", $field_display . ' <i class=" icon-chevron-down"></i>'); ?>
                        <?php else: ?>
                            <?= anchor("admin/proveedor/buscar/$field_name/" .(($orden == 'asc' && $campo == $field_name) ? 'desc' : 'asc')."/$elementos/$busq" , $field_display); ?>
                        <?php endif;?>
                    </th>
                <?php else: ?>    
                    <th>
                        <?php if($orden == 'asc' && $campo == $field_name):?>
                            <?= anchor("admin/proveedor/$field_name/desc/$elementos", $field_display . ' <i class=" icon-chevron-up"></i>'); ?>
                        <?php elseif($orden == 'desc' && $campo == $field_name):?>
                            <?= anchor("admin/proveedor/$field_name/asc/$elementos", $field_display . ' <i class=" icon-chevron-down"></i>'); ?>
                        <?php else: ?>
                            <?= anchor("admin/proveedor/$field_name/" .(($orden == 'asc' && $campo == $field_name) ? 'desc' : 'asc')."/$elementos" , $field_display); ?>
                        <?php endif;?>
                    </th>
                <?php endif;?>    
            <?php endforeach; ?>
            <th class="opciones">Opciones</th>
        </tr>
    </thead>
    <tbody>
    <?php if(isset($proveedores)): ?>
        <?php foreach($proveedores as $proveedor): ?>
            <tr>
                <?php if($user == 'admin'):?>
                    <?php  $check= array('name' => 'checkbox[]', 'checked'=> FALSE, 'value'=>$proveedor->Cif); ?>
                    <td><?= form_checkbox($check); ?></td>
                <?php endif;?>     
                <td><?= $proveedor->Cif;?></td>
                <td><?= $proveedor->RazonSocial; ?></td>
                <td>
                    <?php if($proveedor->Web != 'Desconocida'):?>
                        <a href="http://<?= $proveedor->Web;?>" target="_blank"><?= $proveedor->Web;?> </a>
                    <?php else:?>
                        <?= $proveedor->Web;?>
                    <?php endif;?>
                </td>
                <td><?= $proveedor->Servicios; ?></td>
                <td>
                    <?php if($proveedor->Email != 'Desconocida'):?>
                        <?= anchor("$user/proveedor/enviar/".urlencode($proveedor->Email), $proveedor->Email);?> 
                    <?php else:?>
                        <?= $proveedor->Email;?>
                    <?php endif;?> 
                </td>    
                <td><?= $proveedor->Telefono;?></td>
                <td><?= $proveedor->Fax;?></td>
                <td><?= $proveedor->Descripcion;?></td>
                <td>
                    <div>
                        <a href="#myModal<?= $proveedor->Cif;?>" data-toggle="modal"> 
                            <i class="icon-eye-open"></i>
                            Ver
                        </a>
                        <div id="myModal<?= $proveedor->Cif;?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                <h5 id="myModalLabel"> <?= $proveedor->RazonSocial; ?></h5>
                            </div>
                            <div class="modal-body">
                                <address>
                                    <strong>Dirección</strong><br>
                                        <?= $proveedor->Direccion;?>, 
                                        <?= $proveedor->Ciudad;?><br>
                                        (<?= $proveedor->Provincia;?>)<br>
                                    <strong>Teléfono</strong><br>
                                        <?= $proveedor->Telefono;?>
                                    <br><strong>Fax</strong><br>
                                        <?= $proveedor->Fax;?>
                                    <br><strong>Página web</strong><br>
                                        <?php if($proveedor->Web != 'Desconocida'):?>
                                            <a href="http://<?= $proveedor->Web;?>" target="_blank"><?= $proveedor->Web;?> </a>
                                        <?php else:?>
                                            <?= $proveedor->Web;?>
                                        <?php endif;?>
                                </address>    
                            </div>
                            <div class="modal-footer">
                                <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                            </div>
                        </div>
                    </div>
                    <div>
                        <a href="<?=base_url().$user;?>/proveedor/editar/<?= urlencode($proveedor->Cif);?>"> 
                            <i class="icon-edit"></i>
                            Editar
                        </a>                            
                    </div>
                    <?php if($user == 'admin'):?>
                    <div>
                        <a href="<?=base_url();?>admin/proveedor/borrar/<?= urlencode($proveedor->Cif);?>" data-confirm="Se dispone a eliminar el proveedor con  cif: <?= $proveedor->Cif;?>, ¿Estás seguro?"> 
                            <i class="icon-trash"></i>
                            Borrar
                        </a>
                    </div>
                    <?php endif;?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif;?>
    </tbody>
</table>
</div>
<?php if($user == 'admin'):?>
<?= form_close();?>
<?php endif;?>
<?php endif;?>
<?php if(!isset($vacio)): ?>
<div class="pagination pagination-centered">
    <?= $links; ?> 
</div>
<?php endif; ?>
<?php if($user == 'admin'):?>   
</div>
<?php endif;?>

