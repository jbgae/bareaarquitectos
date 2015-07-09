<?php if($user == 'admin'):?>      
    <div id="solicitud">
        <div id="numProyectos">
            <?php if(isset($numPresupuestos)):?>
                <?php if($numPresupuestos > 0):?>
                     <span id="numero">  
                         Actualmente 
                        <?php if($numPresupuestos == 1):?>
                            existe
                        <?php else:?>
                            existen
                        <?php endif;?>    
                            <?= $numPresupuestos;?>
                        <?php if($numPresupuestos == 1):?>
                            solicitud de proyecto.
                        <?php else:?>
                            solicitudes de proyectos.
                        <?php endif;?>
                     </span>        
                    <a href="#" id="mostrar">Ver más</a>
                    <a href="#" id="ocultar">Ocultar</a>
                    <ul id="lista">
                    <?php foreach($presupuestos as $presupuesto):?>
                        <li><?= $presupuesto->Nombre.' '.$presupuesto->ApellidoP.' '.$presupuesto->ApellidoM;?> ha solicitado un nuevo proyecto. <?= anchor('admin/proyecto/crear/'.$presupuesto->Codigo,'Crear proyecto');?></li>
                    <?php endforeach;?>
                    </ul>    
                <?php endif;?> 
            <?php endif;?> 
        </div>        
           
    </div>
 <?php endif;?>   

<div>
    <?= form_open("$user/proyecto/buscar", array('class'=>'pull-right')); ?>
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
        <?= form_open("$user/proyecto/buscar", array('class'=>'cantidad'));?>
    <?php else:?>
        <?= form_open("$user/proyecto", array('class'=>'cantidad'));?>
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
        No se han encontrado proyectos que concuerden con esos criterios.
    </div>
<?php else:?>


<?php if($user == 'admin'):?>    
<?= form_open('admin/proyecto/borrar', array('name'=> 'f1')); ?>
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
                                    <?= anchor("$user/proyecto/buscar/$field_name/desc/$elementos/$busq", $field_display . ' <i class=" icon-chevron-up"></i>'); ?>
                                <?php elseif($orden == 'desc' && $campo == $field_name):?>
                                    <?= anchor("$user/proyecto/buscar/$field_name/asc/$elementos/$busq", $field_display . ' <i class=" icon-chevron-down"></i>'); ?>
                                <?php else: ?>
                                    <?= anchor("$user/proyecto/buscar/$field_name/" .(($orden == 'asc' && $campo == $field_name) ? 'desc' : 'asc')."/$elementos/$busq" , $field_display); ?>
                                <?php endif;?>
                            </th>
                        <?php else: ?>    
                            <th>
                                <?php if($orden == 'asc' && $campo == $field_name):?>
                                    <?= anchor("$user/proyecto/$field_name/desc/$elementos", $field_display . ' <i class=" icon-chevron-up"></i>'); ?>
                                <?php elseif($orden == 'desc' && $campo == $field_name):?>
                                    <?= anchor("$user/proyecto/$field_name/asc/$elementos", $field_display . ' <i class=" icon-chevron-down"></i>'); ?>
                                <?php else: ?>
                                    <?= anchor("$user/proyecto/$field_name/" .(($orden == 'asc' && $campo == $field_name) ? 'desc' : 'asc')."/$elementos" , $field_display); ?>
                                <?php endif;?>
                            </th>
                        <?php endif;?>    
                    <?php endforeach; ?>
                    <?php if($user == 'admin'):?>        
                        <th class="opciones">Opciones</th>
                    <?php endif;?>
                </tr>
            </thead>
            <tbody id ="tabla-proyectos">
                <?php if(isset($proyectos)): ?>
                    <?php foreach($proyectos as $proyecto): ?>
                        <tr>
                            <?php if($user == 'admin'):?>
                                <?php  $check= array('name' => 'checkbox[]', 'checked'=> FALSE, 'value'=>$proyecto->Codigo); ?>
                                <td><?= form_checkbox($check); ?></td>
                                <td><?= anchor("admin/proyecto/info/".$proyecto->Codigo,$proyecto->NombreProyecto);?></td>
                            <?php else:?>
                                <td><?= anchor("empleados/proyecto/notas/".$proyecto->Codigo,$proyecto->NombreProyecto);?></td>
                            <?php endif;?>     

                            <?php if($proyecto->Estado == 'Ejecución'):?>
                                <td><span class="label label-info"><?= $proyecto->Estado; ?></span></td>
                            <?php elseif($proyecto->Estado == 'Cerrado'):?>
                                <td><span class="label label-inverse"><?= $proyecto->Estado; ?></span></td>
                            <?php endif;?>                                        
                            <td>
                                <div class="progress"> 
                                    <?php if($proyecto->Progreso < '35'):?>
                                        <div class="bar bar-danger" style="width: <?= $proyecto->Progreso.'%';?>"></div>
                                    <?php elseif($proyecto->Progreso <= '70' && $proyecto->Progreso >= '35'):?>
                                         <div class="bar bar-warning" style="width: <?= $proyecto->Progreso.'%';?>"></div>
                                    <?php elseif('70'< $proyecto->Progreso):?>
                                         <div class="bar bar-success" style="width: <?= $proyecto->Progreso.'%';?>"></div>
                                    <?php endif;?>     
                                </div>
                            </td>
                            <td><?= date('d-m-Y', strtotime($proyecto->FechaComienzo)); ?></td>
                            <td><?php if($proyecto->FechaFinPrevista != NULL):?>
                                    <?= date('d-m-Y', strtotime($proyecto->FechaFinPrevista)); ?>
                                <?php else:?>
                                    <?= '---------';?>
                                <?php endif;?>
                            </td>
                            <td><?= $proyecto->Tipo;?></td>
                            <td><?= $proyecto->Direccion; ?></td>
                            <td><?= $proyecto->Ciudad;?> </td>
                            <td><?= $proyecto->Provincia;?></td>
                            <?php if($user == 'admin'):?>
                            <td>                                    
                                <div>
                                    <a href="<?=base_url();?>admin/proyecto/borrar/<?= $proyecto->Codigo;?>" data-confirm="¿Estás seguro?"> 
                                        <i class="icon-trash"></i>
                                        Borrar
                                    </a>
                                </div>    
                            </td>
                            <?php endif;?>
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
        <ul>
            <?= $links; ?> 
        </ul>
    </div>
<?php endif; ?>
