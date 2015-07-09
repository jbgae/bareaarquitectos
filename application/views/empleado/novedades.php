           
            <div class="span8 main-content">
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
                <div>  
 
                       <h2>Tareas 
                            <?php if($novedades['tareas'] + $novedades['respuestas'] < 5):?> 
                                <span class="aviso success" id="novedad-tarea-empleado"> 
                            <?php elseif(5 <= $novedades['tareas'] + $novedades['respuestas'] && $novedades['tareas'] + $novedades['respuestas'] <= 10):?> 
                                <span class="aviso warning" id="novedad-tarea-empleado"> 
                            <?php elseif($novedades['tareas'] + $novedades['respuestas'] > 10):?> 
                                <span class="aviso danger" id="novedad-tarea-empleado"> 
                            <?php endif;?>
                                <?= $novedades['tareas'] + $novedades['respuestas']; ?> 
                            </span>
                        <?php if($novedades['tareas'] + $novedades['respuestas'] == 0): ?>
                            <span class="info" id="info-tareas-empleado">No tienes tareas pendiente.</span>
                        <?php endif; ?>
                       </h2>
                       
                       <ul id="lista-tarea-empleado"> 
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
                                    <br><?= anchor('empleados/proyecto/tarea/'.$tarea->CodigoProyecto.'/'.$tarea->Codigo, 'Ver tarea');?>                                   
                                </p>                        
                            </li>
                          <?php endforeach; ?>                       
                       <?php endif;?> 
                       </ul>  
                        
                       <ul id="lista-respuesta-empleado"> 
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
                                    <br><?= anchor('empleados/proyecto/tarea/'.$respuesta->CodigoProyecto.'/'.$respuesta->CodigoTarea, 'Ver respuesta');?>                                   
                                </p>                        
                            </li>
                          <?php endforeach; ?>                         
                       <?php endif;?>   
                       </ul>     
                       
                            
                       <h2>Notas 
                           <?php if($novedades['notas'] < 5):?> 
                               <span class="aviso success" id="novedad-nota-empleado"> 
                           <?php elseif(5 <= $novedades['notas'] && $novedades['tareas'] <= 10):?> 
                               <span class="aviso warning" id="novedad-nota-empleado"> 
                           <?php elseif($novedades['notas'] > 10):?> 
                               <span class="aviso danger" id="novedad-nota-empleado"> 
                           <?php endif;?>
                                   <?= $novedades['notas']; ?>
                               </span>
                        <?php if($novedades['notas'] == 0): ?>
                            <span class="info" id ="infoNotas">No existen notas nuevas.</span>
                        <?php endif; ?>
                       </h2>
                       
                       <ul id="lista-notas-empleados">
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
                                    <br><?= anchor('empleados/proyecto/nota/'.$nota->CodigoProyecto.'/'.$nota->Codigo, 'Ver nota');?>
                                </p>                        
                            </li>
                            <?php endforeach; ?>
                        <?php endif;?>     
                       </ul>     
                           
                            
                       <h2>Archivos 
                           <?php if($novedades['archivos'] < 5):?> 
                               <span class="aviso success" id="novedad-archivo-empleado"> 
                           <?php elseif(5 <= $novedades['archivos'] && $novedades['archivos'] <= 10):?> 
                               <span class="aviso waarning" id="novedad-archivo-empleado"> 
                           <?php elseif($novedades['archivos'] > 10):?> 
                               <span class="aviso danger" id="novedad-archivo-empleado"> 
                           <?php endif;?> 
                                <?= $novedades['archivos']; ?> 
                               </span>
                        <?php if($novedades['archivos'] == 0): ?>
                            <span class="info">No existen archivos nuevos.</span>                        
                        <?php endif; ?>
                       </h2>                    
                            
                       <ul id="lista-archivo-empleado">
                        <?php if(!empty($archivos)): ?>                        
                            <?php foreach($archivos as $archivo): ?>
                            <li>
                                <div class="indice">
                                    <div class="fecha"><?= date_format(date_create($archivo->Fecha), 'd M Y');?></div>                            
                                    <div class="hora"><?= date_format(date_create($archivo->Fecha), 'H:i A');?></div>
                                </div>    
                                <p class="descripcion"> 
                                    <?= $archivo->NombreEmpl .' '. $archivo->ApellidoP.' '. $archivo->ApellidoM;?> ha registrado un nuevo archivo.
                                    <br><?= anchor('empleados/proyecto/archivos/'.$archivo->CodigoProyecto, 'Ver archivos');?>
                                </p>                        
                            </li>
                            <?php endforeach; ?>
                        
                        <?php endif;?>     
                       </ul>     
                    </div>
            </div>     
                
            <div class="span4 sidebar">
                <table class="table table-condensed table-bordered" id="citas">
                    <th><i class="icon-calendar"></i> Próximas citas</th>
                        <?php if(empty($citas)):?>
                            <tr class="panelCitas">
                                <td>Actualmente no tienes citas</td>
                            </tr>    
                        <?php else: ?>           
                            <?php foreach($citas as $cita): ?>
                            <tr class="panelCitas">
                                <td>
                                    <div class="fecha">
                                        <?= date_format(date_create($cita->Fecha), 'd M');?> <br>                                        
                                    </div>    
                                    <span> <?= ucfirst($cita->Cita);?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                       <?php endif;?>     
                </table> 
                <?= anchor('empleados/calendario', 'Ver mis citas', 'class="enlace"');?>

