
            <div>
                <?= form_open('admin/web/buscar', array('class'=>'pull-right')); ?>
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
                    <?= form_open("admin/web/buscar/NombrePagina/asc/".$elementos.'/'.$busq, array('class'=>'cantidad'));?>
                <?php else:?>
                    <?= form_open('admin/web', array('class'=>'cantidad'));?>
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
                    No se han encontrado paginas que concuerden con esos criterios.
                </div>
            <?php else:?>
            


            <div class="row-fluid" id="flip-scroll">
                <table class="table-striped table-condensed cf">
                    <thead class="cf">
                        <tr>
                            <th><input type="checkbox" id="input" onclick="seleccionar_checkbox(this.checked); crear_boton(this.checked);"></th>
                            
                            <?php foreach($fields as $field_name => $field_display): ?>
                                <?php if(isset($busqueda)):?>
                                    <th>
                                         <?php if($orden == 'asc' && $campo == $field_name):?>
                                            <?= anchor("admin/web/buscar/$field_name/desc/$elementos/$busq", $field_display. '<i class=" icon-chevron-up"></i>' ); ?>
                                        <?php elseif($orden == 'desc' && $campo == $field_name):?>
                                            <?= anchor("admin/web/buscar/$field_name/asc/$elementos/$busq", $field_display . '<i class=" icon-chevron-down"></i>'); ?>
                                        <?php else: ?>
                                            <?= anchor("admin/web/buscar/$field_name/" .(($orden == 'asc' && $campo == $field_name) ? 'desc' : 'asc')."/$elementos/$busq" , $field_display); ?>
                                        <?php endif;?>
                                    </th>
                                <?php else: ?>    
                                    <th>
                                        <?php if($orden == 'asc' && $campo == $field_name):?>
                                            <?= anchor("admin/web/$field_name/desc/$elementos", $field_display . ' <i class=" icon-chevron-up"></i>'); ?>
                                        <?php elseif($orden == 'desc' && $campo == $field_name):?>
                                            <?= anchor("admin/web/$field_name/asc/$elementos", $field_display . ' <i class=" icon-chevron-down"></i>'); ?>
                                        <?php else: ?>
                                            <?= anchor("admin/web/$field_name/" .(($orden == 'asc' && $campo == $field_name) ? 'desc' : 'asc')."/$elementos" , $field_display); ?>
                                        <?php endif;?>
                                    </th>
                                <?php endif;?>    
                            <?php endforeach; ?>
                            <th class="opciones">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>                        
                        <?php if(isset($paginas)): ?>
                            <?php foreach($paginas as $pagina): ?>
                                <tr>
                                    <?php  $check= array('name' => 'checkbox[]', 'checked'=> FALSE, 'value'=>$pagina->NombrePagina . $pagina->Posicion); ?>
                                    <td><?= form_checkbox($check); ?></td>
                                    <td><?= $pagina->NombrePagina;?></td>
                                    <td><?= $pagina->Posicion; ?></td>
                                    <?php if(isset($pagina->TextoCortado)):?>
                                        <td><?= $pagina->TextoCortado; ?></td>
                                    <?php else:?>
                                         <td><?= $pagina->Texto; ?></td>
                                    <?php endif;?>    
                                    <td><?= $pagina->Nombre . ' ' . $pagina->ApellidoP . ' ' . $pagina->ApellidoM; ?></td>
                                    <td>
                                        <div>
                                            <a href="#myModal<?= $pagina->NombrePagina.$pagina->Posicion;?>" data-toggle="modal"> 
                                                <i class="icon-eye-open"></i>
                                                Ver
                                            </a>
                                            <div id="myModal<?= $pagina->NombrePagina.$pagina->Posicion;?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                                    <h5 id="myModalLabel"> <?= $pagina->Nombre. ' '. $pagina->ApellidoP. ' ' .$pagina->ApellidoM ; ?></h5>
                                                </div>
                                                <div class="modal-body">                                                    
                                                    <strong><?= ucfirst($pagina->NombrePagina);?></strong><br>
                                                    <?= $pagina->Texto;?><br>                                             
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <a href="<?=base_url();?>admin/web/editar/<?= urlencode($pagina->NombrePagina).'/'.urlencode($pagina->Posicion);?>"> 
                                                <i class="icon-edit"></i>
                                                Editar
                                            </a>                            
                                        </div> 
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif;?>
                    </tbody>
                </table>
            </div>

            <?php endif;?>
            <?php if(!isset($vacio)): ?>
                <div class="pagination pagination-centered">
                    <ul>
                        <?= $links; ?> 
                    </ul>
                </div>
            <?php endif; ?>

