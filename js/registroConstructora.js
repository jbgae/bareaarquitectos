
$(document).ready(function() {
    $("#boton_constructora").click(function() { 
   
        $('#mensaje').html();  
        
       var cif = /^[0-9]+[a-zA-Z]/;
       var texto = /^[a-zA-Z áéíóúAÉÍÓÚÑñ]+$/;
       $(".error").remove();
       $("#message").hide();

       if(!texto.test($("#razon").val())){
            var error=$('<div class="text-error">').addClass('error').hide().text('Por favor, introduzca solamente texto.');
            $('#razon').focus().after(error);
            error.fadeIn(800);
       }
       else if($("#cif").val().length < 9){
            var error=$('<div class="text-error">').addClass('error').hide().text('Debe introducir al menos nueve caracteres');
            $('#cif').focus().after(error);
            error.fadeIn(800);
       }
       else if(!cif.test($("#cif").val())){
            var error=$('<div class="text-error">').addClass('error').hide().text('El cif intoducido no es correcto');
            $('#cif').focus().after(error);
            error.fadeIn(800);
       }       
       else{

            $.ajax({
                    type: "POST",
                    url: "http://localhost/bareaarquitectos/constructora/registrarAjax",
                    data: $("#formdata").serialize(),
                    datatype: "text",
                     beforeSend:function(){
                        $('#mensaje').html('<b>El formulario se esta enviando</b>');
                    },
                    success:function(res){ 
                        $("#mensaje").html(res);
                        $('#formdata').each(function(){
                            this.reset();   
                        });
                        location.reload();
                    }     
            });
            
            return false;
       }
    });
});
