<div>
    <?= form_open("$user/consultor/buscar", array('class'=>'pull-right')); ?>
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
        <?= form_open("$user/consultor/buscar", array('class'=>'cantidad'));?>
    <?php else:?>
        <?= form_open("$user/consultor", array('class'=>'cantidad'));?>
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
        No se han encontrado consultores que concuerden con esos criterios.
    </div>
<?php else:?>

    <?php if($user != 'admin'):?>     
        </div>
    <?php endif;?>

    <?php if($user == 'admin'):?>
        <?= form_open('admin/consultor/borrar', array('name'=> 'f1')); ?>
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
                                    <?= anchor("$user/consultor/buscar/$field_name/desc/$elementos/$busq", $field_display . ' <i class=" icon-chevron-up"></i>'); ?>
                                <?php elseif($orden == 'desc' && $campo == $field_name):?>
                                    <?= anchor("$user/consultor/buscar/$field_name/asc/$elementos/$busq", $field_display . ' <i class=" icon-chevron-down"></i>'); ?>
                                <?php else: ?>
                                    <?= anchor("$user/consultor/buscar/$field_name/" .(($orden == 'asc' && $campo == $field_name) ? 'desc' : 'asc')."/$elementos/$busq" , $field_display); ?>
                                <?php endif;?>
                            </th>
                        <?php else: ?>    
                            <th>
                                <?php if($orden == 'asc' && $campo == $field_name):?>
                                    <?= anchor("$user/consultor/$field_name/desc/$elementos", $field_display . ' <i class=" icon-chevron-up"></i>'); ?>
                                <?php elseif($orden == 'desc' && $campo == $field_name):?>
                                    <?= anchor("$user/consultor/$field_name/asc/$elementos", $field_display . ' <i class=" icon-chevron-down"></i>'); ?>
                                <?php else: ?>
                                    <?= anchor("$user/consultor/$field_name/" .(($orden == 'asc' && $campo == $field_name) ? 'desc' : 'asc')."/$elementos" , $field_display); ?>
                                <?php endif;?>
                            </th>
                        <?php endif;?>    
                    <?php endforeach; ?>
                    <th class="opciones">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($consultores)): ?>
                    <?php foreach($consultores as $consultor): ?>
                        <tr>
                            <?php if($user == 'admin'):?>
                                <?php  $check= array('name' => 'checkbox[]', 'checked'=> FALSE, 'value'=>$consultor->Identificador); ?>
                                <td><?= form_checkbox($check); ?></td>
                            <?php endif;?>                                    
                            <td><?= $consultor->Nombre. ' ' . $consultor->ApellidoP. ' ' .$consultor->ApellidoM;?></td>
                            <td><?= $consultor->Direccion; ?></td>
                            <td><?= $consultor->Ciudad; ?></td>
                            <td><?= $consultor->Provincia;?></td>
                            <td><?= $consultor->Telefono;?></td>
                            <td><?= anchor("$user/consultor/enviar/".$consultor->Identificador, $consultor->Email);?> </td>
                            <td><?= $consultor->Fax;?></td>
                            <td><?= $consultor->Especialidad;?></td>
                            <td>
                                <div>
                                    <a href="<?=base_url().$user;?>/consultor/editar/<?= $consultor->Identificador;?>"> 
                                        <i class="icon-edit"></i>
                                        Editar
                                    </a>                            
                                </div> 
                                <?php if($user == 'admin'):?>
                                <div>
                                    <a href="<?=base_url().$user;?>/consultor/borrar/<?= $consultor->Identificador;?>" data-confirm="¿Estás seguro?"> 
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

