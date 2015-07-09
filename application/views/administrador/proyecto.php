
<div class ="span10"> 
    <?= form_open("admin/proyecto/info/$codigo");?>
    <div class='pull-left span6'>
        <strong>Cliente:</strong>
        <a href="#myModal" data-toggle="modal"> 
            <?= $presupuesto->nombreCliente();?> 
        </a>
        <br><br>
        <strong>Estado:</strong>
        <?php if($estado == 'Ejecución'):?> 
            <?= form_dropdown('estado',$estados,'0','class = input-medium');?> 
        <?php else:?>
            <?= form_dropdown('estado',$estados,'1','class = input-medium');?>
        <?php endif;?>        

        <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                    <?= $cliente->Telefono;?><br>
                    <strong>Email</strong><br>
                    <?= anchor("admin/clientes/enviar/".  urlencode($cliente->Email), $cliente->Email);?>
                </address>    
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
            </div>
        </div>
        
       
        <h5>Datos presupuesto</h5>    

        <dl class="dl-horizontal">

                <h6><dt>Fecha solicitud:</dt></h6>
                <dd><?= $presupuesto->fechaSolicitud();?></dd> 

                <h6><dt>Precio:</dt></h6>   
                <dd><?= $precio;?> €</dd> 

                <?php if($presupuesto->CodigoArchivo != NULL):?>
                    <h6><dt>Archivo:</dt></h6>
                    <dd><?= anchor("admin/presupuesto/descargar/$presupuesto->CodigoArchivo",'<i class="icon-download"></i> Descargar');?></dd>
                <?php endif;?>   

        </dl>


        <h5>Datos proyecto</h5>

        <div>
            <dl class="dl-horizontal">
                <h6><dt>Informe:</dt></h6>
                <dd><?= anchor("admin/proyecto/informe/$proyecto->Codigo",'<i class="icon-file"></i> Generar informe',array('target' => '_blank'));?></dd>
                
                <h6><dt>Tipo:</dt></h6> 
                <dd><?= $presupuesto->tipo();?></dd>

                <h6><dt>Dirección:</dt></h6>   
                <dd><?= $presupuesto->direccion();?></dd>

                <h6><dt>Ciudad:</dt></h6>
                <dd><?= $presupuesto->ciudad();?></dd>

                <h6><dt>Provincia:</dt></h6> 
                <dd><?= $presupuesto->provincia();?></dd>    

                <h6><dt>Comienzo:</dt></h6>
                <dd><?= date("d-m-Y",strtotime($proyecto->fechaComienzo()));?></dd>

                <h6><dt>Fin previsto:</dt></h6>
                <?php if($proyecto->fechaFinPrevista() != NULL):?>
                <dd><?= date("d-m-Y", strtotime($proyecto->fechaFinPrevista()));?></dd>
                <?php else:?>
                <dd>--------</dd>
                <?php endif;?>

                <h6><dt>Constructora:</dt></h6>
                 <?php if(isset($constructoras) && empty($constructoras)):?>
                    <dd>Actualmente no existe ninguna empresa constructora registrada.</dd>
      
                 <?php else:?>
                    <?php if(isset($constructora)):?>
                     <dd><?= form_dropdown('constructora', $constructoras,$constructora,'class = input-small');?></dd>                    
                    <?php else:?>
                     <dd><?= form_dropdown('constructora', $constructoras,'','class = input-small');?></dd>                    
                    <?php endif;?>
                <?php endif;?>
            </dl>
        </div>
        
        <h5>Materiales: </h5>

       <div>
           <?php if(!empty($materiales)):?>
            <dl class="dl-horizontal">
                <?php foreach($materiales as $material):?>
                <dt><?= $material->RazonSocial .' '.$material->Nombre;?></dt>
                <dd> <?=anchor('admin/material/eliminar/'.$codigo.'/'.$material->Codigo, 'Eliminar <i class="icon-trash"></i>');?></dd>
                <?php endforeach;?>
            </dl>
           <?php endif;?>
           <dl class="dl-horizontal"> 
               <div>
                <a href="#campos" onclick="AgregarCampos();">Agregar materiales</a>
               </div>
               <span id="titulo"></span>
               <div id="campos"> </div>
               <?php if(isset($errores)):?>
                <span class="text-error"><?= $errores;?></span>
               <?php endif;?>
           </dl>
       </div>
        
        
        <button class="btn btn-info informe">Actualizar datos</button>
        
    </div>
    
    
    <div class='span6 pull-left'>
        
        <h5>Hacer proyecto público</h5>
        <label class="checkbox" >
            <?= form_checkbox($mostrar);?>
            Marcando esta opción, información como la ciudad, provincia, tipo de proyecto, descripción del proyecto
            y las imágenes que se seleccionen serán visibles desde la sección <i>Proyectos</i> de la web.
        </label>
        
        <div id="ocultar">
            <h5>Imágenes: </h5>
            <?php if(!empty($imagenes)):?>
                <?php foreach($imagenes as $imagen):?>
                    <label class="checkbox">
                        <?= form_checkbox($imagen->Codigo,'accept',$imagen->Visible, 'class="ck"');?>
                        <!--<input class="ck" type="checkbox" name="<?= $imagen->Codigo;?>">-->
                        <h6><?= array_pop(explode("/",$imagen->Ruta));?></h6>
                    </label>
                <?php endforeach;?>
            <?php else:?>
                <h6>No hay imágenes en el proyecto.</h6>
            <?php endif;?>
            <h5>Texto:</h5>
            <?= form_textarea($editor); ?>
            <?= form_error($editor['name']); ?>
        </div>
        
        
        <h5>Empleados: </h5>
           <div>
               <dl class="dl-horizontal">
                   <?php if(!empty($empleados)):?>
                    <?php foreach($empleados as $empleado):?> 
                        <?php if($empleado->Foto != '' && $empleado->Foto != 'Desconocido'):?>    
                            <dt><img src='<?= $empleado->Foto;?>'></dt>
                        <?php else:?>
                            <dt><img src ="<?= base_url();?>images/indice.jpeg"></dt>
                        <?php endif;?>
                        <h6><dd><?= $empleado->nombre() . ' ' . $empleado->primerApellido(). ' '. $empleado->segundoApellido();?></dd> </h6>
                        <br>
                    <?php endforeach;?>
                   <?php else:?>
                     <h6>No hay empleados asignados al proyecto.</h6>   
                   <?php endif;?>
               </dl>
           </div>    



       <h5>Tareas: </h5>

       <div>
           <dl class="dl-horizontal">
               <?php if(!empty($tareas)):?>
                <?php foreach($tareas as $tarea):?>
                     <h6><dt><?= ucfirst($tarea->Titulo);?>:</dt></h6>
                     <?php if($tarea->Estado == 'cerrado'):?>
                          <dd class ='cerrado'><?= ucfirst($tarea->Estado);?></dd>
                     <?php else:?>
                          <dd class ='abierto'><?= ucfirst($tarea->Estado);?></dd>
                     <?php endif;?>
                <?php endforeach;?>
               <?php else:?>
                 <h6>No hay tareas en el proyecto.</h6>   
               <?php endif;?>           
           </dl>
       </div>

       
      
       <h5>Archivos: </h5>

        <div>
            <?php if(!empty($archivos)):?>
            Seleccione el archivo que será entregado al cliente.
            <dl class="dl-horizontal">
                
                <?php foreach($archivos as $archivo):?>
                    <label class="radio">
                        <?php if($archivo->Codigo == $mem):?>
                            <?= form_radio('mem[]',$archivo->Codigo,'1', '');?> 
                        <?php else:?>
                            <?= form_radio('mem[]',$archivo->Codigo,'', '');?> 
                        <?php endif;?>
                        <h6><?= array_pop(explode("/",$archivo->Ruta));?>  <?= $archivo->NombreEmpl.' '. $archivo->ApellidoP.' '.$archivo->ApellidoM;?></h6> 
                        
                    </label>
                <?php endforeach;?>
            </dl>
            <?php else:?>
                <h6>No hay archivos en el proyecto.</h6>  
             <?php endif;?>
        </div>
        
    </div>   
    <br> 
    <?= form_close(); ?> 
</div>
</div>
</div>
</div>