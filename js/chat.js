$(document).ready(function(){

	var pusher = new Pusher('81b022d43b4477991be1', { encrypted: true }), 
            channel = pusher.subscribe('private-chat'); 
        Pusher.channel_auth_endpoint = 'http://localhost/bareaarquitectos/chat/autorizacion'; 

	channel.bind('enviar-mensaje',function(data) { 
                
                $.post("http://localhost/bareaarquitectos/usuario/sesion",{ }, function(email){
                    if(data.email != email){
                        $("div#chat_vista").append('<ul><li><img src="'+data.foto+'" alt="foto usuario"> <div class="encabezado"><span>'+data.nombre+'</span>'+data.fecha+'</div> <div class="contenido"> '+data.mensaje+' </div></li></ul>'); $("div#chat_vista").append('<ul><li><img src="'+data.foto+'" alt="foto usuario"> <div class="encabezado"><span>'+data.nombre+'</span>'+data.fecha+'</div> <div class="contenido"> '+data.mensaje+' </div></li></ul>'); 
                        if($('#notificacion-chat').length)
                            $('#notificacion-chat').html(parseInt($("#notificacion-chat").html()) + 1);
                        else
                            $("li#chat").append('<span class="badge badge-success" id="notificacion-chat">1</span>');
                     }
                     else{
                        $("div#chat_vista").append('<ul><li><img src="'+data.foto+'" alt="foto usuario"> <div class="encabezado"><span class="usuario_actual">'+data.nombre+'</span>'+data.fecha+'</div> <div class="contenido"> '+data.mensaje+' </div></li></ul>'); 
                     }
                },"json");        
	});

	$('form').submit(function(){ 
		$.post('http://localhost/bareaarquitectos/chat/mensaje', $(this).serialize());
		$('#mensaje').val('').focus();
		return false;
	});

        $('#anteriores').click(function(){
            obtenerMensajes();
        });
        
        obtenerMensajesNuevos();

        function obtenerMensajes(){
            $.post("http://localhost/bareaarquitectos/chat/obtener_mensaje",{ }, function(data){
                if(data.estado == 'ok'){
                    $("div#chat_vista").html(data.contenido)
                }
            },"json");        
        }

        function obtenerMensajesNuevos(){
            $.post("http://localhost/bareaarquitectos/chat/obtener_mensajes_nuevos",{ }, function(data){
                if(data.estado == 'ok'){
                    $("div#chat_vista").append(data.contenido)
                }
            },"json");        
        }
});



