$(document).ready(function(){
    var pusher = new Pusher('81b022d43b4477991be1', { encrypted: true }), 
        channel = pusher.subscribe('private-notificaciones'); 
        Pusher.channel_auth_endpoint = 'http://localhost/bareaarquitectos/admin/autorizacion'; 

        obtenerNotificaciones();

        function obtenerNotificaciones(){
            $.post("http://localhost/bareaarquitectos/admin/notificaciones",{ }, function(data){
                if(data.novedades!= 0)
                    $("li#novedades").append('<span class="badge badge-success" id="notificacion-novedades" value ="'+data.novedades+'">'+data.novedades+'</span>');              
                if(data.eventos != 0)    
                    $("li#calendario").append('<span class="badge badge-success" id="notificacion-evento">'+data.eventos+'</span>');
                if(data.proyectos != 0)
                    $("li#proyectos").append('<span class="badge badge-success" id="notificacion-proyecto">'+data.proyectos+'</span>');
                if(data.presupuestos != 0)    
                    $("li#presupuestos").append('<span class="badge badge-success" id="notificacion-presupuesto">'+data.presupuestos+'</span>');
                if(data.chat != 0)
                    $("li#chat").append('<span class="badge badge-success" id="notificacion-chat">'+data.chat+'</span>');
            },"json");        
        }

        
        channel.bind('proyecto-crear', function(data){
            $.post("http://localhost/bareaarquitectos/usuario/sesion",{ }, function(email){
                $.each(data.empleados, function(key, value){
                    if(value == email){
                        if ($('#notificacion-proyecto').length){
                            $("#notificacion-proyecto").html(parseInt($("#notificacion-proyecto").html()) + 1);
                        }
                        else{ 
                            $("li#proyecto-empleado").append('<span class="badge badge-success" id="notificacion-proyecto">'+1+'</span>');
                        }
                        $('#tabla-proyectos tr:first').after('<tr> <td><a href="http://localhost/bareaarquitectos/empleados/proyecto/notas/'+data.codigoProyecto+'">'+ data.nombre +'</a> </td> <td><span class="label label-info">'+data.estado+'</span></td> <td> <div class="progress"> <div class="bar bar-danger" style="width:'+data.progreso+'"></div></div> </td> <td>'+data.comienzo+'</td> <td>'+data.fin+'</td><td>'+data.tipo+'</td><td>'+data.direccion+'</td><td>'+data.ciudad+'</td><td>'+data.provincia+'</td> </tr>');
                    }
                });
            },"json");    
        });

        channel.bind('presupuesto-enviar',function(data) {
            if ($('#notificacion-novedades').length){
                $("#notificacion-novedades").html(parseInt($("#notificacion-novedades").html()) + 1);
            }
            else{
                $("li#novedades").append('<span class="badge badge-success" id="notificacion-novedades" value ="'+data.novedades+'">'+1+'</span>');             
            }

            if ($('#notificacion-presupuesto').length){
                $("#notificacion-presupuesto").html(parseInt($("#notificacion-presupuesto").html()) + 1);
            }
            else{
                $("li#presupuestos").append('<span class="badge badge-success" id="notificacion-presupuesto">'+1+'</span>');
            }

            $("#novedad-presupuesto").html(parseInt($("#novedad-presupuesto").html()) + 1);
            $("#infopresupuesto").hide("slow");
            $(".listapresupuesto").append('<li>  <div class="indice"> <div class="fecha">'+data.solicitadoFecha+'</div> <div class="hora">'+data.solicitadoHora+'</div> </div> <p class="descripcion"> '+data.cliente+'  ha solicitado un nuevo presupuesto. <br><a href="http://localhost/bareaarquitectos/admin/presupuesto/crear/'+data.codigo+'">Crear presupuesto</a> </p> </li>');
            $('#tabla-presupuestos tr:first').after('<tr> <td><input type="checkbox" name = "checkbox[]" value="'+ data.codigo +'"></td> <td>'+ data.cliente +'</td> <td>'+ data.email +'</td> <td><span class="label label-warning">'+ data.estado +'</span></td> <td>'+ data.fecha  +'</td> <td>'+data.tipo+'</td> <td>'+data.direccion+'</td> <td>'+data.ciudad+'</td> <td>'+data.provincia+'</td> <td> <div> <a href="http://localhost/bareaarquitectos/admin/presupuesto/crear/'+data.codigo+'" data-toggle="modal"> <i class="icon-plus"></i> Crear </a></div><div> <a href="http://localhost/bareaarquitectos/admin/presupuesto/borrar/'+data.codigo+'" data-confirm="¿Estás seguro?"> <i class="icon-trash"></i> Borrar </a> </div></td></tr>');
	});

        
        channel.bind('presupuesto-comprar', function(data){
            $("#infoproyecto").hide("slow");
            if(!($('#notificacion-novedades').length)){
                $("li#novedades").append('<span class="badge badge-success" id="notificacion-novedades" value ="'+0+'">'+0+'</span>');             
            }
            if(!($('#notificacion-proyecto').length)){
                $("li#proyectos").append('<span class="badge badge-success" id="notificacion-proyecto">'+0+'</span>');
            }

            $.each(data, function(key, value){
                $("#notificacion-novedades").html(parseInt($("#notificacion-novedades").html()) + 1);
                $("#novedad-proyecto").html(parseInt($("#novedad-proyecto").html()) + 1);
                $("#notificacion-proyecto").html(parseInt($("#notificacion-proyecto").html()) + 1);
                $(".listaproyecto").append('<li>  <div class="indice"> <div class="fecha">'+value.Fecha+'</div> <div class="hora">'+value.Hora+'</div> </div> <p class="descripcion"> '+value.cliente+'  ha solicitado un nuevo proyecto. <br><a href="http://localhost/bareaarquitectos/admin/proyecto/crear/'+value.codigo+'">Crear proyecto</a> </p> </li>');                
            }); 

           $.post("http://localhost/bareaarquitectos/admin/notificaciones",{ }, function(datos){
                
                if(datos.proyectos == 1){
                    $("#numProyectos").append('<span id="numero"> Actualmente existe '+ datos.proyectos +' solicitud de proyecto.</span>  <a href="#" id="mostrar">Ver más</a> <a href="#" id="ocultar">Ocultar</a> <ul id="lista"></ul>');                        
                }
                else if($("#numProyectos").html() == ''){
                    $("#numProyectos").append('<span id="numero">Actualmente existen '+ datos.proyectos +' solicitudes de proyecto. </span> <a href="#" id="mostrar">Ver más</a> <a href="#" id="ocultar">Ocultar</a> <ul id="lista"></ul>');
                }
                else{
                    $("#numero").html('Actualmente existen '+ datos.proyectos +' solicitudes de proyecto.');
                }

                $.each(data, function(key, value){
                    $("#lista").append('<li> '+value.cliente+' ha solicitado un nuevo proyecto. <a href="http://localhost/bareaarquitectos/admin/proyecto/crear/'+value.codigo+'"> Crear proyecto </a> </li>');
                });                            
                
            },"json"); 

            $("#lista").hide("slow");
            $("#ocultar").hide("slow");
            $("#mostrar").show("slow");
                     

        });

        
        channel.bind('tarea-enviar', function(data){                    

            $.post("http://localhost/bareaarquitectos/usuario/sesion",{ }, function(email){
                if(email == data.empleado){
                    if ($('#notificacion-novedades').length){
                        $("#notificacion-novedades").html(parseInt($("#notificacion-novedades").html()) + 1);
                    }
                    else{
                        $("li#novedades").append('<span class="badge badge-success" id="notificacion-novedades" value ="'+data.novedades+'">'+1+'</span>');             
                    }
                    $("#novedad-proyecto-empleado").html(parseInt($("#novedad-proyecto-empleado").html()) + 1);
                    $("#novedad-tarea-empleado").html(parseInt($("#novedad-tarea-empleado").html()) + 1);
                    $("#info-tareas-empleado").html(' ');
                    $("#lista-tarea-empleado").append('<li>  <div class="indice"> <div class="fecha">'+data.fecha+'</div> <div class="hora">'+data.hora+'</div> </div> <p class="descripcion"> '+data.admin+' te ha asignado una nueva tarea <br> "'+data.titulo+'". <br> <a href="http://localhost/bareaarquitectos/empleados/proyecto/tarea/'+data.codigo+'/'+data.codigoTarea+'">Ver tarea </a> </p> </li>');
                    $('#vacio').html(' ');
                    $("#listado-tareas-empleado").append('<a href="http://localhost/bareaarquitectos/empleados/proyecto/tarea/'+data.codigo+'/'+data.codigoTarea+'"><div class="tareas"> '+data.titulo+'</div></a> </p> </li>');
                }
            },"json");
        });

        channel.bind('enviar-mensaje', function(data){
            obtenerNotificaciones();
        });
 
});

