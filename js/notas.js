$(document).ready(function() {  
    if($("input:radio:checked").val() == "personalizado"){
        $("#extender").css("display", "block");
    }
   
    $("input[name='permisos']").change(function() { 
      $("#extender").toggle(this.value == "personalizado");
    });
    
    
});

