<div class ="span11">                                
    <div>

    </div>          
    <div class="pull-left span2">
         <button class="btn btn-info" onclick='window.location="<?=base_url().$user;?>/proyecto/notas/<?=$codigo;?>"'>Crear Nota</button>
         
         <?php if(isset($notas)&& !empty($notas)):?>
            <div id="listado-notas-empleado">
            <?php foreach($notas as $notaAux):?>
                <?= anchor("$user/proyecto/nota/$codigo/$notaAux->Codigo", "<div class='notas'>". $notaAux->Titulo . "</div>"); ?>                                                                   
            <?php endforeach;?>                           
            </div>    
        <?php endif;?>
        
    </div>
    <div class ="span8">  
        <div id="titulo">
            <h4>
                <?= ucfirst($nota->Titulo);?>
                <?php if($nota->EmailEmpleado == $this->session->userdata('email')):?>
                    <?= anchor("$user/proyecto/notas/borrar/$codigo/".$nota->Codigo, '<i class="icon-trash"></i>', 'class="pull-right" id="tooltip" rel="tooltip" data-tigger="hover" data-animation="true" data-placement="top" data-toggle="tooltip" data-title="Eliminar" data-confirm="Va a eliminar la nota, ¿Desea continuar?"');?>
                    <?= anchor("$user/proyecto/notas/editar/$codigo/".$nota->Codigo, '<i class="icon-edit"></i>', 'class="pull-right" id="tooltip" rel="tooltip" data-tigger="hover" data-animation="true" data-placement="top" data-toggle="tooltip" data-title="Editar"');?>                      
                <?php endif;?>
            </h4>

        </div>

        <div id="contenido">
            <?= $nota->Contenido;?>
        </div>
        <div id="pie">
            Creado el <?= date('d-m-Y H:i:s', strtotime($nota->FechaCreacion));?> por <?= $nota->Nombre.' '.$nota->ApellidoP.' '.$nota->ApellidoM ;?><br>
            <div>
            <?php if($nota->Permisos == 'publico'):?>
                Esta nota es pública para todos en este proyecto.
            <?php elseif($nota->Permisos == 'privado'):?>
                Esta nota es privada.
            <?php elseif($nota->Permisos == 'personalizado'):?>
                Esta nota es accesible para:
                <?php foreach($empleadosNotas as $empleado):?>
                    <?= $empleado; ?>
                <?php  endforeach;?>
            <?php endif;?>    
            </div>
        </div>
    </div>
</div>