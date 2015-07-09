$(document).ready(function () {
 
    $('#captchaForm').validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            captcha: {
                minlength: 5,
                required: true
            }
        },
        messages:{
            email: {
                minlength: jQuery.format("Introduzca al menos {0} caracteres"),
                required: "Debes introducir un email",
                email: "Introduzca un email válido"
            },
            captcha: {
                minlength: jQuery.format("Introduzca al menos {0} caracteres"),
                required: "Debes introducir el captcha"
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