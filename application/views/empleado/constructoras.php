
            <div>
                <?= form_open("$user/constructora/buscar", array('class'=>'pull-right')); ?>
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
                    <?= form_open("$user/constructora/buscar/Cif/asc/".$elementos.'/'.$busq, array('class'=>'cantidad'));?>
                <?php else:?>
                    <?= form_open("$user/constructora", array('class'=>'cantidad'));?>
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
                    No se han encontrado constructoras que concuerden con esos criterios.
                </div>
            <?php else:?>
            
        <?php if($user != 'admin'):?>     
            </div>
        <?php endif;?>
        
        <?php if($user == 'admin'):?>
            <?= form_open("$user/constructora/borrar", array('name'=> 'f1')); ?>
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
                                        <?= anchor("$user/constructora/buscar/$field_name/desc/$elementos/$busq", $field_display . ' <i class=" icon-chevron-up"></i>'); ?>
                                    <?php elseif($orden == 'desc' && $campo == $field_name):?>
                                        <?= anchor("$user/constructora/buscar/$field_name/asc/$elementos/$busq", $field_display . ' <i class=" icon-chevron-down"></i>'); ?>
                                    <?php else: ?>
                                        <?= anchor("$user/constructora/buscar/$field_name/" .(($orden == 'asc' && $campo == $field_name) ? 'desc' : 'asc')."/$elementos/$busq" , $field_display); ?>
                                    <?php endif;?>
                                </th>
                            <?php else: ?>    
                                <th>
                                    <?php if($orden == 'asc' && $campo == $field_name):?>
                                        <?= anchor("$user/constructora/$field_name/desc/$elementos", $field_display . ' <i class=" icon-chevron-up"></i>'); ?>
                                    <?php elseif($orden == 'desc' && $campo == $field_name):?>
                                        <?= anchor("$user/constructora/$field_name/asc/$elementos", $field_display . ' <i class=" icon-chevron-down"></i>'); ?>
                                    <?php else: ?>
                                        <?= anchor("$user/constructora/$field_name/" .(($orden == 'asc' && $campo == $field_name) ? 'desc' : 'asc')."/$elementos" , $field_display); ?>
                                    <?php endif;?>
                                </th>
                            <?php endif;?>    
                        <?php endforeach; ?>
                        <th class="opciones">Opciones</th>
                    </tr>
                </thead>
                <tbody>                        
                    <?php if(isset($constructoras)): ?>
                        <?php foreach($constructoras as $constructora): ?>
                            <tr>
                                <?php if($user == 'admin'):?>
                                    <?php  $check= array('name' => 'checkbox[]', 'checked'=> FALSE, 'value'=>$constructora->Cif); ?>
                                    <td><?= form_checkbox($check); ?></td>
                                <?php endif;?>
                                
                                <td><?= $constructora->Cif;?></td>
                                <td><?= $constructora->RazonSocial; ?></td>
                                <td>
                                    <?php if($constructora->Email != 'Desconocida'):?>
                                        <?= anchor("$user/constructora/enviar/".urlencode($constructora->Email), $constructora->Email);?> </td>                         
                                    <?php else:?>
                                        <?= 'Desconocido';?>
                                    <?php endif;?>    
                                <td><?= $constructora->Valoracion; ?></td>                          
                                <td><?= $constructora->Proyectos; ?></td>                          
                                <td>
                                    <div class="opciones">
                                        <a href="#myModal<?= $constructora->Cif;?>" data-toggle="modal"> 
                                            <i class="icon-eye-open"></i>
                                            Ver
                                        </a>
                                        <div id="myModal<?= $constructora->Cif;?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                                <h5 id="myModalLabel"> <?= $constructora->RazonSocial; ?></h5>
                                            </div>
                                            <div class="modal-body">
                                                <address>
                                                    <strong>Dirección</strong><br>
                                                    <?= $constructora->Direccion;?>, 
                                                    <?= $constructora->Ciudad;?><br>
                                                    (<?= $constructora->Provincia;?>)<br>
                                                    <strong>Teléfono</strong><br>
                                                        <?= $constructora->Telefono;?>
                                                    <br><strong>Fax</strong><br>
                                                        <?= $constructora->Fax;?>
                                                    <br><strong>Página web</strong><br>
                                                        <?php if($constructora->Web != 'Desconocida'):?>
                                                            <?= anchor($constructora->Web, $constructora->Web);?>                         
                                                        <?php else:?>
                                                            <?= $constructora->Web;?>
                                                        <?php endif;?> 
                                                </address>    
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="opciones">
                                        <a href="<?=base_url().$user;?>/constructora/editar/<?= urlencode($constructora->Cif);?>"> 
                                            <i class="icon-edit"></i>
                                            Editar
                                        </a>                            
                                    </div>
                                    <?php if($user == 'admin'):?>
                                    <div class="opciones">
                                        <a href="<?=base_url();?>admin/constructora/borrar/<?= urlencode($constructora->Cif);?>" data-confirm="¿Estás seguro?"> 
                                            <i class="icon-trash"></i>
                                            Borrar
                                        </a>
                                    </div>
                                    <?php endif;?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif;?>         
                    </table>
                </div>
                <?php endif;?>
            <?= form_close();?>
            <?php if(!isset($vacio)): ?>
                <div class="pagination pagination-centered">
                    <?= $links; ?> 
                </div>
            <?php endif; ?>
        <?php if($user == 'admin'):?>     
            </div>
        <?php endif;?>
    </div>
</div>
