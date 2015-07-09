
$(document).ready(function() {
    
    $("#boton_cliente").click(function() { 
       $('#mensaje').html(); 
   
       var emailreg = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
       var texto = /^[a-zA-Z áéíóúAÉÍÓÚÑñ]+$/;
       var fecha = /([0-9]{2})\/([0-9]{2})\/([0-9]{4})/;
       $(".error").remove();
       $("#message").hide();

       if(!texto.test($("#nombre").val())){
            var error=$('<div class="text-error">').addClass('error').hide().text('Por favor, introduzca solamente texto.');
            $('#nombre').focus().after(error);
            error.fadeIn(800);
            //aux = false;
       }
       else if($(".nombre").val().length < 3){
            var error=$('<div class="text-error">').addClass('error').hide().text('Debe introducir al menos tres caracteres');
            $('.nombre').focus().after(error);
            error.fadeIn(800);
            //aux = false;
       }
       else if(!texto.test($(".apellidoP").val())){
            var error=$('<div class="text-error">').addClass('error').hide().text('Por favor, introduzca solamente texto.');
            $('.apellidoP').focus().after(error);
            error.fadeIn(800);
            //aux = false;
       }
       else if($(".apellidoP").val().length < 3){
            var error=$('<div class="text-error">').addClass('error').hide().text('Debe introducir al menos tres caracteres');
            $('.apellidoP').focus().after(error);
            error.fadeIn(800);
            //aux = false;
       }
       else if(!texto.test($(".apellidoM").val())){
            var error=$('<div class="text-error">').addClass('error').hide().text('Por favor, introduzca solamente texto.');
            $('.apellidoM').focus().after(error);
            error.fadeIn(800);
            //aux = false;
       }
       else if($(".apellidoM").val().length < 3){
            var error=$('<div class="text-error">').addClass('error').hide().text('Debe introducir al menos tres caracteres');
            $('.apellidoM').focus().after(error);
            error.fadeIn(800);
            //aux = false;
       }
       else if(!emailreg.test($(".email").val())){
            var error=$('<div class="text-error">').addClass('error').hide().text('El email no es correcto');
            $('.apellidoM').focus().after(error);
            error.fadeIn(800);
            //aux = false;
       }
       else if(!fecha.test($(".fechaN").val())){
            var error=$('<div class="text-error">').addClass('error').hide().text('Debe introducir una fecha válida(dd/mm/aaaa)');
            $('.fechaN').focus().after(error);
            error.fadeIn(800);
            //aux = false;
       }
       else if($(".password").val()=== ''){
            var error=$('<div class="text-error">').addClass('error').hide().text('Por favor, introduzca su nueva contraseña.');
            $('.password').focus().after(error);
            error.fadeIn(800);
            //aux = false;
       }
       else if($(".password").val().length < 6){
            var error=$('<div class="text-error">').addClass('error').hide().text('Debe introducir al menos seis caracteres');
            $('.password').focus().after(error);
            error.fadeIn(800);
            //aux = false;
       }
       else if($(".passconf").val()=== ''){
            var error=$('<div class="text-error">').addClass('error').hide().text('Por favor, repita su nueva contraseña.');
            $('.passconf').focus().after(error);
            error.fadeIn(800);
            //aux = false;
       }
       else if($(".passconf").val().length < 6){
            var error=$('<div class="text-error">').addClass('error').hide().text('Debe introducir al menos seis caracteres');
            $('.passconf').focus().after(error);
            error.fadeIn(800);
            //aux = false;
       }
       else if($(".passconf").val()!== $(".password").val()){
            var error=$('<div class="text-error">').addClass('error').hide().text('La contraseña introducida no coincide con la anterior');
            $('.passconf').focus().after(error);
            error.fadeIn(800);
            //aux = false;
       }
       else{

            $.ajax({
                    type: "POST",
                    url: "http://localhost/bareaarquitectos/cliente/registrarAjax",
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
