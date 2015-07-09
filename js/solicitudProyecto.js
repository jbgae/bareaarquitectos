$(document).ready(function() { 
    $("#lista").hide();
    $("#ocultar").hide();
    
    $("#mostrar").click(function(){
        $("#lista").show("slow");
        $("#ocultar").show("slow");
        $("#mostrar").hide("slow");
    });

    $("#ocultar").click(function(){
        $("#lista").hide("slow");
        $("#ocultar").hide("slow");
        $("#mostrar").show("slow");
    });

    $("#numProyectos").on("click", "a#mostrar" ,function(){ 
        $("#lista").show("slow");
        $("#ocultar").show("slow");
        $("#mostrar").hide("slow");
    });

    $("#numProyectos").on("click", "a#ocultar" ,function(){ 
        $("#lista").hide("slow");
        $("#ocultar").hide("slow");
        $("#mostrar").show("slow");
    });
});

