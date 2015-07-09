$(document).ready(function(){ 
    var pusher = new Pusher('81b022d43b4477991be1', { encrypted: true }), 
        channel = pusher.subscribe('private-notificaciones-empleado'); 
        Pusher.channel_auth_endpoint = 'http://localhost/bareaarquitectos/empleado/autorizacion'; 

        obtenerNotificaciones();

        function obtenerNotificaciones(){ 
            $.post("http://localhost/bareaarquitectos/empleado/notificaciones",{ }, function(data){ 
               if(data.novedades!= 0)
                    $("li#novedades-empleado").append('<span class="badge badge-success" id="notificacion-novedades" value ="'+data.novedades+'">'+data.novedades+'</span>');              
                if(data.eventos != 0)    
                    $("li#calendario-empleado").append('<span class="badge badge-success" id="notificacion-evento">'+data.eventos+'</span>');
                if(data.proyectos != 0)
                    $("li#proyecto-empleado").append('<span class="badge badge-success" id="notificacion-proyecto">'+data.proyectos+'</span>');
                if(data.chat != 0)
                    $("li#chat-empleado").append('<span class="badge badge-success" id="notificacion-chat">'+data.chat+'</span>');
            },"json");        
        }

        channel.bind('crear-nota', function(data){
            $.post("http://localhost/bareaarquitectos/usuario/sesion",{ }, function(email){                
                $.each(data.empleados, function(key, value){
                    if(value['EmailEmpleado'] == email){
                        $('#infoNotas').hide('slow');
                        $('#listado-notas').append('<a href="http://localhost/bareaarquitectos/admin/proyecto/nota/'+data.proyecto+'/'+data.cod+'" > <div class="notas">'+ data.titulo +'</div> </a>');
                        $('#listado-notas-empleado').append('<a href="http://localhost/bareaarquitectos/empleados/proyecto/nota/'+data.proyecto+'/'+data.cod+'" > <div class="notas">'+ data.titulo +'</div> </a>');
                        $('#listanotas').append('<li> <div class="indice"> <div class="fecha">'+data.fecha+'</div> <div class="hora">'+data.hora+'</div></div> <p class="descripcion"> '+data.empleado+' ha creado una nueva nota. <br> "'+data.titulo+'" <br> <a href="http://localhost/bareaarquitectos/admin/proyecto/nota/'+data.proyecto+'/'+data.codigo+'> Ver nota</a> </p>  </li>');
                        $('#lista-notas-empleados').append('<li> <div class="indice"> <div class="fecha">'+data.fecha+'</div> <div class="hora">'+data.hora+'</div></div> <p class="descripcion"> '+data.empleado+' ha creado una nueva nota. <br> "'+data.titulo+'" <br> <a href="http://localhost/bareaarquitectos/empleados/proyecto/nota/'+data.proyecto+'/'+data.codigo+'> Ver nota</a> </p>  </li>');
                        $("#novedad-nota-empleado").html(parseInt($("#novedad-nota-empleado").html()) + 1);
                        $("#novedad-nota").html(parseInt($("#novedad-nota").html()) + 1);
                        if ($('#notificacion-novedades').length){
                            $("#notificacion-novedades").html(parseInt($("#notificacion-novedades").html()) + 1);            
                        }
                        else{
                            $("li#novedades").append('<span class="badge badge-success" id="notificacion-novedades">'+1+'</span>');        
                        }
                    }
                });
            },"json");
        });


        channel.bind('respuesta-enviar', function(data){
            $.post("http://localhost/bareaarquitectos/usuario/sesion",{ }, function(email){                
                $.each(data.empleados, function(key, value){
                    if(value == email && email != data.email){
                         if ($('#notificacion-novedades').length){
                            $("#notificacion-novedades").html(parseInt($("#notificacion-novedades").html()) + 1);
                        }
                        else{
                            $("li#novedades").append('<span class="badge badge-success" id="notificacion-novedades" value ="'+data.novedades+'">'+1+'</span>');             
                        }
                        $("#novedad-tarea").html(parseInt($("#novedad-tarea").html()) + 1);
                        $("#novedad-tarea-empleado").html(parseInt($("#novedad-tarea-empleado").html()) + 1);
                        $("#info-tareas-empleado").hide('slow');
                        $("#respuestas").append('<div id="titulo"> <h4>RE: '+data.titulo+'</h4></div> <div id="contenido"> <img src="'+data.foto+'">'+data.resp+' </div> <div id="pie"> Creado el '+data.fechaCompleta+' por '+data.empleado+'</div>')
                        if(data.user == 'admin')
                            $("#lista-tarea-empleado").append('<li>  <div class="indice"> <div class="fecha">'+data.fecha+'</div> <div class="hora">'+data.hora+'</div> </div> <p class="descripcion"> '+data.empleado+' ha contestado en la tarea <br> "'+data.titulo+'". <br> <a href="http://localhost/bareaarquitectos/admin/proyecto/tarea/'+data.codigo+'/'+data.codigoTarea+'">Ver respuesta </a> </p> </li>');
                        else    
                            $("#listatareas").append('<li>  <div class="indice"> <div class="fecha">'+data.fecha+'</div> <div class="hora">'+data.hora+'</div> </div> <p class="descripcion"> '+data.empleado+' ha contestado en la tarea <br> "'+data.titulo+'". <br> <a href="http://localhost/bareaarquitectos/empleados/proyecto/tarea/'+data.codigo+'/'+data.codigoTarea+'">Ver respuesta </a> </p> </li>');

                    }
                });
            },"json");
        });

        channel.bind('crear-archivo', function(data){ 
            $.post("http://localhost/bareaarquitectos/usuario/sesion",{ }, function(email){
                $.each(data.empleados, function(key, value){
                    if(key == email){ 
                        //Si no existe ningún archivo.
                        $('#alerta-'+data.proyecto+data.carpeta).hide('slow');
                        $('#insertarTabla'+data.proyecto+data.carpeta).append('<table class="table table-striped"> <thead> <tr> <th>Nombre</th> <th>Tamaño</th> <th>Fecha creación</th> <th>Subido por</th> <th></th> </tr> </thead> <tbody id="tabla-archivos-'+data.proyecto+data.carpeta+'"></tbody> </table>');
                        
                        
                        if(data.codigo != ''){
                            if(data.extension == ''){
                                if(data.carpeta != '')
                                    $('#tabla-archivos-'+data.proyecto+data.carpeta).append('<tr><td><div class="archivo"><img src="http://localhost/bareaarquitectos/images/folder.png"> <a href="http://localhost/bareaarquitectos/'+value+'/proyecto/archivos/'+data.proyecto+'/'+data.codigo+'" > <h5>'+ data.nombre +'</h5> </a></div></td><td></td><td>'+data.fechaCompleta+'</td><td>'+data.empleado+'</td>  <td class="span3"><div class="botones pull-right"><a data-toggle="tooltip" title="descargar" href="http://localhost/bareaarquitectos/empleados/proyecto/archivos/descargar/'+data.codigo+'" class="btn btn-small"><i class="icon-download"></i> </a> </div></td>');
                                else        
                                    $('#tabla-archivos-'+data.proyecto).append('<tr><td><div class="archivo"><img src="http://localhost/bareaarquitectos/images/folder.png"> <a href="http://localhost/bareaarquitectos/'+value+'/proyecto/archivos/'+data.proyecto+'/'+data.codigo+'" > <h5>'+ data.nombre +'</h5> </a></div></td><td></td><td>'+data.fechaCompleta+'</td><td>'+data.empleado+'</td>  <td class="span3"><div class="botones pull-right"><a data-toggle="tooltip" title="descargar" href="http://localhost/bareaarquitectos/empleados/proyecto/archivos/descargar/'+data.codigo+'" class="btn btn-small"><i class="icon-download"></i> </a> </div></td>');
                                $('#listaarchivos').append('<li><div class="indice"><div class="fecha">'+ data.fecha +'</div> <div class="hora">'+data.hora+'</div> </div> <p class="descripcion"> '+data.empleado+' ha registrado una nueva carpeta. <br>"'+data.nombre+'" <br><a href="http://localhost/bareaarquitectos/admin/proyecto/archivo/'+data.proyecto+'/'+data.codigo+'"> Ver archivos </a> </p> </li>');
                                $('#lista-archivos-empleados').append('<li> <div class="indice"> <div class="fecha">'+data.fecha+'</div> <div class="hora">'+data.hora+'</div></div> <p class="descripcion"> '+data.empleado+' ha creado una nueva archivo. <br>"'+data.nombre+'" <br> <a href="http://localhost/bareaarquitectos/empleados/proyecto/archivo/'+data.proyecto+'/'+data.codigo+'"> Ver archivos</a> </p>  </li>');                            
                            }
                            else{            
                                if(data.extension == '.jpg' || data.extension == '.png' || data.extension == '.jpeg' || data.extension == '.JPG'  || data.extension == '.png' || data.extension == '.PNG'){
                                    $('#tabla-archivos-'+data.proyecto+data.carpeta).append('<tr><td><div class="archivo"><img src="http://localhost/bareaarquitectos/images/image.png"> <a data-toggle="lightbox" href="#L'+data.codigo+'"  > <h5>'+ data.nombre + data.extension +'</h5> </a>  <div id="L'+data.codigo+'" class="lightbox hide fade" tabindex="-1" role="dialog" aria-hidden="true"> <div class="lightbox-content"> <img src="'+data.ruta+'"> </div> </div></div></td><td></td><td>'+data.fechaCompleta+'</td><td>'+data.empleado+'</td>  <td class="span3"><div class="botones pull-right"><a data-toggle="tooltip" title="descargar" href="http://localhost/bareaarquitectos/empleados/proyecto/archivos/descargar/'+data.codigo+'" class="btn btn-small"><i class="icon-download"></i> </a> </div></td>');
                                }
                                else if(data.extension == '.txt'){
                                    $('#tabla-archivos-'+data.proyecto+data.carpeta).append('<tr><td><div class="archivo"><img src="http://localhost/bareaarquitectos/images/document-text.png" id="iconoTexto"> <a href="http://localhost/bareaarquitectos/'+value+'/proyecto/archivos/editar/'+data.proyecto+'/'+data.codigo+'" > <h5>'+ data.nombre +'</h5> </a></div></td><td></td><td>'+data.fechaCompleta+'</td><td>'+data.empleado+'</td>  <td class="span3"><div class="botones pull-right"><a data-toggle="tooltip" title="descargar" href="http://localhost/bareaarquitectos/empleados/proyecto/archivos/descargar/'+data.codigo+'" class="btn btn-small"><i class="icon-download"></i> </a> </div></td>');
                                }
                                else if(data.extension == '.pdf'){
                                    $('#tabla-archivos-'+data.proyecto+data.carpeta).append('<tr><td><div class="archivo"><img src="http://localhost/bareaarquitectos/images/file_pdf.png"> <h5>'+ data.nombre +'</h5> </div></td><td></td><td>'+data.fechaCompleta+'</td><td>'+data.empleado+'</td>  <td class="span3"><div class="botones pull-right"><a data-toggle="tooltip" title="descargar" href="http://localhost/bareaarquitectos/'+value+'/proyecto/archivos/descargar/'+data.codigo+'" class="btn btn-small"><i class="icon-download"></i> </a> </div></td>');
                                }
                                else{
                                    $('#tabla-archivos-'+data.proyecto+data.carpeta).append('<tr><td><div class="archivo"><img src="http://localhost/bareaarquitectos/images/document-text.png" id="iconoTexto"> <h5>'+ data.nombre +'</h5> </div></td><td></td><td>'+data.fechaCompleta+'</td><td>'+data.empleado+'</td>  <td class="span3"><div class="botones pull-right"><a data-toggle="tooltip" title="descargar" href="http://localhost/bareaarquitectos/'+value+'/proyecto/archivos/descargar/'+data.codigo+'" class="btn btn-small"><i class="icon-download"></i> </a> </div></td>');
                                }
                                $('#listaarchivos').append('<li><div class="indice"><div class="fecha">'+ data.fecha +'</div> <div class="hora">'+data.hora+'</div> </div> <p class="descripcion"> '+data.empleado+' ha registrado un nuevo archivo. <br> <a href="http://localhost/bareaarquitectos/admin/proyecto/archivo/'+data.proyecto+'/'+data.codigo+'"> Ver archivos </a> </p> </li>');
                                $('#lista-archivo-empleado').append('<li> <div class="indice"> <div class="fecha">'+data.fecha+'</div> <div class="hora">'+data.hora+'</div></div> <p class="descripcion"> '+data.empleado+' ha creado una nueva archivo. <br> <a href="http://localhost/bareaarquitectos/empleados/proyecto/archivo/'+data.proyecto+'/'+data.codigo+'"> Ver archivos</a> </p>  </li>');                        
                            }
                        }    
                        else{    
                            if(data.extension == ''){
                                $('#tabla-archivos-'+data.proyecto+data.carpeta).append('<tr><td><div class="archivo"><img src="http://localhost/bareaarquitectos/images/folder.png"> <a href="http://localhost/bareaarquitectos/'+value+'/proyecto/archivos/'+data.proyecto+'" > <h5>'+ data.nombre +'</h5> </a></div></td><td></td><td>'+data.fechaCompleta+'</td><td>'+data.empleado+'</td>  <td class="span3"><div class="botones pull-right"><a data-toggle="tooltip" title="descargar" href="http://localhost/bareaarquitectos/empleados/proyecto/archivos/descargar/'+data.codigo+'" class="btn btn-small"><i class="icon-download"></i> </a> </div></td>');
                                $('#listaarchivos').append('<li><div class="indice"><div class="fecha">'+ data.fecha +'</div> <div class="hora">'+data.hora+'</div> </div> <p class="descripcion"> '+data.empleado+' ha registrado una nueva carpeta. <br>"'+data.nombre+'" <br><a href="http://localhost/bareaarquitectos/admin/proyecto/archivo/'+data.proyecto+'"> Ver archivos </a> </p> </li>');
                                $('#lista-archivo-empleado').append('<li> <div class="indice"> <div class="fecha">'+data.fecha+'</div> <div class="hora">'+data.hora+'</div></div> <p class="descripcion"> '+data.empleado+' ha creado una nueva archivo. <br>"'+data.nombre+'" <br> <a href="http://localhost/bareaarquitectos/empleados/proyecto/archivo/'+data.proyecto+'"> Ver archivos</a> </p>  </li>');                            
                            }
                            else{
                                $('#listaarchivos').append('<li> <div class="indice"> <div class="fecha">'+data.fecha+'</div> <div class="hora">'+data.hora+'</div></div> <p class="descripcion"> '+data.empleado+' ha registrado un nuevo archivo. <br>  <a href="http://localhost/bareaarquitectos/admin/proyecto/archivo/'+data.proyecto+'"> Ver archivos</a> </p>  </li>');
                                $('#lista-archivo-empleado').append('<li> <div class="indice"> <div class="fecha">'+data.fecha+'</div> <div class="hora">'+data.hora+'</div></div> <p class="descripcion"> '+data.empleado+' ha creado una nueva archivo. <br> <a href="http://localhost/bareaarquitectos/empleados/proyecto/archivo/'+data.proyecto+'"> Ver archivos</a> </p>  </li>');
                            }    
                        }    
                        
                        $('#infoarchivo').html(' ');
                        $("#novedad-archivo-empleado").html(parseInt($("#novedad-archivo-empleado").html()) + 1);
                        $("#novedad-archivo").html(parseInt($("#novedad-archivo").html()) + 1);
                        if ($('#notificacion-novedades').length){
                            $("#notificacion-novedades").html(parseInt($("#notificacion-novedades").html()) + 1);
                        }
                        else{ 
                            $("li#novedades").append('<span class="badge badge-success" id="notificacion-novedades">'+1+'</span>');
                        }
                    }
                });
            },"json");
        });

        channel.bind('enviar-mensaje', function(data){
            obtenerNotificaciones();
        });

});