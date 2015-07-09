$(document).ready(function () {
 
    $('#sesion').validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            pass: {
                minlength: 6,
                required: true
            },
            captcha: {
                minlength: 6,
                required: true
            }
        },
        messages:{
            email: {
                minlength: jQuery.format("Introduzca al menos {0} caracteres"),
                required: "Debes introducir un email",
                email: "Introduzca un email válido"
            },
            pass: {
                minlength: jQuery.format("Introduzca al menos {0} caracteres"),
                required: "Debes introducir la contraseña"
            },
            captcha: {
                minlength: jQuery.format("Introduzca al menos {0} caracteres"),
                required: "Debes introducir los caracteres de la imagen."
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