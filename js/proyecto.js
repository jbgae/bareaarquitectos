$(document).ready(function() {
    
    if($("#mostrar").is(':checked'))
        $("#ocultar").css("display", "block");
    else{
        $(".ck").removeAttr("checked");
        $("#ocultar").css("display", "none");
    }
    
    $("#mostrar").change(function(){ 
        if($("#mostrar").is(':checked'))
             $("#ocultar").css("display", "block");
        else{
             $(".ck").removeAttr("checked");
             $("#ocultar").css("display", "none");
        }

    });
    
    
});

