 $(document).ready(function() {
    $("#provincia").change(function() {        
        $("#provincia option:selected").each(function() {            
            provincia = $('#provincia').val();
            $.post("http://localhost/bareaarquitectos/ciudad/ciudades", {
                provincia : provincia
            }, function(data) {
                $("#ciudad").html(data);
            });
        });        
    });
});