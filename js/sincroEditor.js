$(document).ready(function(){ 
     var pusher = new Pusher('81b022d43b4477991be1', { encrypted: true }), 
        channel = pusher.subscribe('editor'); 
        Pusher.channel_auth_endpoint = 'http://localhost/bareaarquitectos/empleado/autorizacion'; 
     
   /* window.onbeforeunload = function() {
        return '¿Estás seguro que quiere abandonar la página?';
    };*/   

    $('#archivoForm').keyup(function(){
        $.post("http://localhost/bareaarquitectos/archivo/sincronizar",{
            texto:$(".editor").val(), 
            id: $(".editor").attr('id')
        });
    });

    $('#noticiaForm').keyup(function(){
        $.post("http://localhost/bareaarquitectos/noticias/sincronizar",{
            texto:$(".editor").val(), 
            id: $(".editor").attr('id')
        });
    });

    $('#webForm').keyup(function(){
        $.post("http://localhost/bareaarquitectos/paginas/sincronizar",{
            texto:$(".editor").val(), 
            id: $(".editor").attr('id')
        });
    });

    channel.bind('sincronizacion', function(data){ 
        $.post("http://localhost/bareaarquitectos/usuario/sesion",{ }, function(email){
                $.each(data.empleados, function(key, value){ 
                    if(key == email && key != data.usuario){                    
                        $("#"+data.id).jqteVal(data.texto);
                    }
                });
        },"json");
    });
    
    
});