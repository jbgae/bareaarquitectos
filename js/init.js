$(document).ready(function(){

    var pusher = new Pusher('81b022d43b4477991be1'), 
        channel = pusher.subscribe('presence-channel'); 
        
    Pusher.channel_auth_endpoint = 'http://localhost/bareaarquitectos/usuario/autorizacion';    

    channel.bind('pusher:subscription_succeeded', function(members) {
        $('#usuarios').empty();
        members.each(function(member) {
            addMember(member);
        });
    });
	
    /*channel.bind('pusher:subscription_error', function(status) {
        
            
        
    });*/

    channel.bind('pusher:member_added', function(member){
        addMember(member);
    });
	
    channel.bind('pusher:member_removed', function(member){
        removeMember(member)
    });
	
    function addMember(member){
        var p = $('<p>', { text: member.info.nombre, id: 'usuario_' + member.info.id } );
        $('#usuarios').append( p );
        $("span#contador").html( channel.members.count) ; 
    }
	
    function removeMember(member){
        $('p#usuario_'+ member.info.id).remove();
        $("span#contador").html( channel.members.count) ; 
    }

});

