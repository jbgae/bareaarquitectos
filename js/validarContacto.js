$(document).ready(function () {
    $('#contacto').validate({
        rules: {
            nombre: {
                minlength: 3,
                required: true
            },
            email: {
                required: true,
                email: true
            },
            asunto: {
                minlength: 3,
                required: true
            },
            comentario: {
                minlength: 3,
                required: true
            },
            politica:{
                required:true
            }
        },
        messages:{
            nombre: {
                minlength: jQuery.format("Introduzca al menos {0} caracteres"),
                required: "Debes introducir un nombre"                
            },
            email: {
                minlength: jQuery.format("Introduzca al menos {0} caracteres"),
                required: "Debes introducir un email",
                email: "Introduzca un email válido"
            },
            asunto: {
                minlength: jQuery.format("Introduzca al menos {0} caracteres"),
                required: "Debes introducir un asunto"
            },
            comentario: {
                minlength: jQuery.format("Introduzca al menos {0} caracteres"),
                required: "Debes introducir un comentario"
            },
            politica:{
                required:"Debes aceptar la política de privacidad"
            }
            
        },
        highlight: function(element) {
            $(element).closest('.control-group').removeClass('success').addClass('error');
        },
        success: function(element) {
            element
            .text('OK!').addClass('valid')
            .closest('.control-group').removeClass('error').addClass('success');
        }
    });
}); 