<div class ="span11">                                
    <div>

    </div>          
    <div class="pull-left span2" >
         <?php if($user == 'admin'):?>
            <button class="btn btn-info" onclick='window.location="<?=base_url();?>admin/proyecto/tareas/<?=$codigo;?>"'>Crear Tarea</button>
         <?php endif;?>
         <div id="listado-tareas-empleado">
             <?php if(isset($tareas)&& !empty($tareas)):?>            
                <?php foreach($tareas as $tareaAux):?>
                    <?= anchor("$user/proyecto/tarea/$codigo/$tareaAux->Codigo", "<div class='tareas'>". $tareaAux->Titulo . "</div>"); ?>                                                                   
                <?php endforeach;?>
             <?php else:?>
                 <span id="vacio">No existen tareas.</span>
             <?php endif;?>
         </div>
        
    </div>
    
</div>