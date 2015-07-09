<div class="span9 main-content"> 
    <noscript>
        <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">
                &times;
            </button> 
            <h4>Error.</h4>
            Para visualizar correctamente el contenido de esta web, necesitas tener activado Javascript.
            Para más información sobre como activar JavaScript en su navegador, visita <a href="https://www.google.com/adsense/support/bin/answer.py?hl=es&answer=12654">esta página</a>.
        </div>

    </noscript>
    <div class="row-fluid">                    
        <h2>
            Presupuestos
            <?php if($novedades['presupuestos'] < 5):?> 
                <span class="aviso success" id="novedad-presupuesto"> 
            <?php elseif(5 <= $novedades['presupuestos'] && $novedades['presupuestos'] <= 10):?> 
                <span class="aviso warning" id="novedad-presupuesto"> 
            <?php elseif($novedades['presupuestos'] > 10):?> 
                <span class="aviso danger" id="novedad-presupuesto"> 
            <?php endif;?>
                <?= $novedades['presupuestos']; ?> 
            </span>
            <?php if($novedades['presupuestos'] == 0): ?>
                <span class="info" id="infopresupuesto">No tienes presupuestos pendientes.</span>
            <?php endif; ?>
        </h2>
        
        <ul class="listapresupuesto">
            <?php if(!empty($presupuestos)):?>            
                <?php foreach($presupuestos as $presupuesto): ?>
                    <li>
                        <div class="indice">
                            <div class="fecha"><?= date_format(date_create($presupuesto->FechaSolicitud), 'd M Y');?></div>                            
                            <div class="hora"><?= date_format(date_create($presupuesto->FechaSolicitud), 'H:i A');?> </div>                                  
                        </div>    
                        <p class="descripcion"> 
                            <?= $presupuesto->Nombre .' '. $presupuesto->ApellidoP.' '. $presupuesto->ApellidoM;?>  ha solicitado un nuevo presupuesto.
                            <br><?= anchor('admin/presupuesto/crear/'.$presupuesto->Codigo, 'Crear presupuesto');?>                                   
                        </p>                        
                    </li>
                <?php endforeach; ?>            
           <?php endif;?>
       </ul>         


        <h2>
            Proyectos 
            <?php if($novedades['proyectos'] < 5):?> 
                <span class="aviso success" id="novedad-proyecto"> 
            <?php elseif(5 <= $novedades['proyectos'] && $novedades['proyectos'] <= 10):?> 
                <span class="aviso warning" id="novedad-proyecto"> 
            <?php elseif($novedades['proyectos'] > 10):?> 
                <span class="aviso danger" id="novedad-proyecto"> 
            <?php endif;?>
                <?= $novedades['proyectos']; ?> 
            </span>
            <?php if($novedades['proyectos'] == 0): ?>
                <span class="info" id="infoproyecto">No tienes proyectos pendientes.</span>
            <?php endif; ?>
        </h2>

        <ul class="listaproyecto">    
            <?php if(!empty($proyectos)):?>            
                <?php foreach($proyectos as $proyecto): ?>
                <li>
                    <div class="indice">
                        <div class="fecha"><?= date_format(date_create($proyecto->FechaSolicitud), 'd M Y');?></div>                            
                        <div class="hora"><?= date_format(date_create($proyecto->FechaSolicitud), 'H:i A');?> </div>                                  
                    </div>    
                    <p class="descripcion"> 
                        <?= $proyecto->Nombre .' '. $proyecto->ApellidoP.' '. $proyecto->ApellidoM;?>  ha solicitado un nuevo proyecto.
                        <br><?= anchor('admin/proyecto/crear/'.$proyecto->Codigo, 'Crear proyecto');?>                                   
                    </p>                        
                </li>
                <?php endforeach; ?>
           <?php endif;?>
        </ul>     

        <h2>
            Tareas 
            <?php if($novedades['tareas'] + $novedades['respuestas'] < 5):?> 
                <span class="aviso success" id="novedad-tarea"> 
            <?php elseif(5 <= $novedades['tareas'] + $novedades['respuestas'] && $novedades['tareas'] + $novedades['respuestas'] <= 10):?> 
                <span class="aviso warning" id="novedad-tarea"> 
            <?php elseif($novedades['tareas'] + $novedades['respuestas'] > 10):?> 
                <span class="aviso danger" id="novedad-tarea"> 
            <?php endif;?>
                <?= $novedades['tareas'] + $novedades['respuestas']; ?> 
            </span>
            <?php if($novedades['tareas'] + $novedades['respuestas'] == 0): ?>
                <span class="info" id="info-tareas-empleado">No tienes tareas pendientes.</span>
            <?php endif; ?>
        </h2>

        <ul id="listatareas"> 
            <?php if(!empty($tareas)):?>           
                <?php foreach($tareas as $tarea): ?>
                    <li>
                        <div class="indice">
                            <div class="fecha"><?= date_format(date_create($tarea->FechaCreacion), 'd M Y');?></div>                            
                            <div class="hora"><?= date_format(date_create($tarea->FechaCreacion), 'H:i A');?> </div>                                  
                        </div>    
                        <p class="descripcion"> 
                            <?= $tarea->Nombre .' '. $tarea->ApellidoP.' '. $tarea->ApellidoM;?> te ha asignado una nueva tarea.
                            <br> "<?= $tarea->Titulo;?>" 
                            <br><?= anchor('admin/proyecto/tarea/'.$tarea->CodigoProyecto, 'Ver tarea');?>                                   
                        </p>                        
                    </li>
                <?php endforeach; ?>
            <?php endif;?> 
        </ul>
        <ul id="listarespuestas">
            <?php if(!empty($respuestas)):?>             
                <?php foreach($respuestas as $respuesta): ?>
                    <li>
                        <div class="indice">
                            <div class="fecha"><?= date_format(date_create($respuesta->Fecha), 'd M Y');?></div>                            
                            <div class="hora"><?= date_format(date_create($respuesta->Fecha), 'H:i A');?> </div>                                  
                        </div>    
                        <p class="descripcion"> 
                            <?= $respuesta->Nombre .' '. $respuesta->ApellidoP.' '. $respuesta->ApellidoM;?> ha contestado en la tarea
                            <br> "<?= $respuesta->Titulo;?>" 
                            <br><?= anchor('admin/proyecto/tarea/'.$respuesta->CodigoProyecto.'/'.$respuesta->CodigoTarea, 'Ver respuesta');?>                                   
                        </p>                        
                    </li>
                <?php endforeach; ?>
            <?php endif;?>  
        </ul>


        <h2>
            Notas 
            <?php if($novedades['notas'] < 5):?> 
                <span class="aviso success" id="novedad-nota"> 
            <?php elseif(5 <= $novedades['notas'] && $novedades['tareas'] <= 10):?> 
                <span class="aviso warning" id="novedad-nota"> 
            <?php elseif($novedades['notas'] > 10):?> 
                <span class="aviso danger" id="novedad-nota"> 
            <?php endif;?>
                <?= $novedades['notas']; ?>
            </span>
            <?php if($novedades['notas'] == 0): ?>
                <span class="info" id="infoNotas">No existen notas nuevas.</span>
            <?php endif; ?>
        </h2>
        <ul id="listanotas">
            <?php if(!empty($notas)):?>
                <?php foreach($notas as $nota): ?>
                    <li>
                        <div class="indice">
                            <div class="fecha"><?= date_format(date_create($nota->FechaCreacion), 'd M Y');?></div>                            
                            <div class="hora"><?= date_format(date_create($nota->FechaCreacion), 'H:i:s A');?></div>
                        </div>    
                        <p class="descripcion"> 
                            <?= $nota->Nombre .' '. $nota->ApellidoP.' '. $nota->ApellidoM;?> ha creado una nueva nota.
                            <br> "<?= $nota->Titulo;?>"
                            <br><?= anchor('admin/proyecto/nota/'.$nota->CodigoProyecto.'/'.$nota->Codigo, 'Ver nota');?>
                        </p>                        
                    </li>
                <?php endforeach; ?>
            <?php endif;?>     
        </ul>     

        <h2>
            Archivos 
            <?php if($novedades['archivos'] < 5):?> 
                <span class="aviso success" id="novedad-archivo"> 
            <?php elseif(5 <= $novedades['archivos'] && $novedades['archivos'] <= 10):?> 
                <span class="aviso warning" id="novedad-archivo"> 
            <?php elseif($novedades['archivos'] > 10):?> 
                <span class="aviso danger" id="novedad-archivo"> 
            <?php endif;?> 
                <?= $novedades['archivos']; ?> 
            </span>
            <?php if($novedades['archivos'] == 0): ?>
                <span class="info" id="infoarchivo">No existen archivos nuevos.</span>                        
            <?php endif; ?>
        </h2>
        <ul id="listaarchivos">
            <?php if(!empty($archivos)): ?>           
                <?php foreach($archivos as $archivo): ?>
                    <li> 
                        <div class="indice">
                            <div class="fecha"><?= date_format(date_create($archivo->Fecha), 'd M Y');?></div>                            
                            <div class="hora"><?= date_format(date_create($archivo->Fecha), 'H:i A');?></div>
                        </div>    
                        <p class="descripcion">
                            <?php if($archivo->Extension != ''):?>
                                <?= $archivo->NombreEmpl .' '. $archivo->ApellidoP.' '. $archivo->ApellidoM;?> ha registrado un nuevo archivo.
                            <?php else:?>    
                                <?= $archivo->NombreEmpl .' '. $archivo->ApellidoP.' '. $archivo->ApellidoM;?> ha registrado una nueva carpeta.
                            <?php endif;?>    
                            <br>"<?= $archivo->Nombre. $archivo->Extension;?>"
                            <?php if($archivo->Pertenece != NULL):?>
                                <?php if($archivo->Extension != ''):?>
                                    <br><?= anchor('admin/proyecto/archivos/'.$archivo->CodigoProyecto.'/'.$archivo->Pertenece, 'Ver archivo');?>
                                <?php else:?>    
                                    <br><?= anchor('admin/proyecto/archivos/'.$archivo->CodigoProyecto.'/'.$archivo->Pertenece, 'Ver carpeta');?>
                                <?php endif;?> 
                            <?php else:?>
                                <?php if($archivo->Extension != ''):?>    
                                    <br><?= anchor('admin/proyecto/archivos/'.$archivo->CodigoProyecto, 'Ver archivo');?>
                                <?php else:?>    
                                    <br><?= anchor('admin/proyecto/archivos/'.$archivo->CodigoProyecto, 'Ver carpeta');?>
                                <?php endif;?>    
                            <?php endif;?>    
                        </p>                        
                    </li>
                <?php endforeach; ?>
            <?php endif;?>     
        </ul>     

    </div>
</div>     

<div class="span3 sidebar">
    <table class="table table-condensed table-bordered">
        <th><i class="icon-calendar"></i> Próximas citas</th>
            <?php if(empty($eventos)):?>
                <tr class="panelCitas">
                    <td>Actualmente no tienes citas</td>
                </tr>    
            <?php else: ?>           
                <?php foreach($eventos as $evento): ?>
                <tr class="panelCitas">
                    <td>
                        <div class="fecha">
                            <?= date_format(date_create($evento->Fecha), 'd M');?> <br>
                            <span class="label"> <?= ucfirst($evento->Cita);?></span>
                        </div>    
                        <p class="descripcion"> <?= ucfirst($evento->Descripcion);?></p>
                    </td>
                </tr>
                <?php endforeach; ?>
           <?php endif;?>     
    </table> 
    <?= anchor('admin/calendario', 'Ver mis citas', 'class="enlace"');?>

    <br>
    <table class="table table-condensed table-bordered">
        <th><i class="icon-user"></i> Nuevos clientes</th>
            <?php if(empty($clientes)):?>
                <tr class="panelCitas">
                    <td>Actualmente no tienes nuevos clientes</td>
                </tr>    
            <?php else: ?>           
                <?php foreach($clientes as $cliente): ?>
                <tr class="panelCitas">
                    <td>
                        <div class="fecha">
                            <?= date_format(date_create($cliente->FechaAltaSistema), 'd M');?> <br>
                        </div>    
                        <p class="descripcion"> <?= ucfirst($cliente->Nombre) .' '. ucfirst($cliente->ApellidoP) . ' '.  ucfirst($cliente->ApellidoM);?></p>
                    </td>
                </tr>
                <?php endforeach; ?>
           <?php endif;?>     
    </table> 
    <?= anchor('admin/clientes', 'Ver clientes', 'class="enlace"');?>

    <br>
    <table class="table table-condensed table-bordered">
        <th><i class="icon-edit"></i> Últimas noticias</th>
            <?php if(empty($noticias)):?>                <tr class="panelCitas">
                    <td>Actualmente no tienes noticias añadidas</td>
                </tr>    
            <?php else: ?>           
                <?php foreach($noticias as $noticia): ?>
                <tr class="panelCitas">
                    <td>
                        <div class="fecha">
                            <?= date_format(date_create($noticia->FechaCreacion), 'd M Y');?> <br>
                            <span class="label"> <?= $noticia->Nombre .' '. $noticia->ApellidoP;?></span>
                        </div>    
                        <p class="descripcion" id="noticias"> <?= $noticia->Titulo; ?></p>
                    </td>
                </tr>
                <?php endforeach; ?>
           <?php endif;?>     
    </table> 
    <?= anchor('admin/noticias', 'Ver noticias', 'class="enlace"');?>
</div>


