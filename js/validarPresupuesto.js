$(document).ready(function () { 

    jQuery.validator.addMethod("exactlength", function(value, element, param) {
        return this.optional(element) || value.length == param;
    }, jQuery.format("Introduzca extactamente {0} caracteres."));

    $('#presupuestoForm').validate({

        rules: {
            direccion: {
                minlength: 3,
                required: true
            },
            descripcion:{
                minlength:6
            },
            nombre:{
                required:true
            },
            pem:{
                required:true
            },
            coeficiente: {
                required: true
            },
            coeficienteSeguridad:{
                required: true
            }
        },
        messages:{
            direccion: {
                minlength: jQuery.format("Introduzca al menos {0} caracteres"),
                required: "Debes introducir una dirección"                
            },
            descripcion:{
                minlength: jQuery.format("Introduzca al menos {0} caracteres")
            },
            pem:{
                required: "Debes introducir el PEM"                
            },
            coeficiente:{
                required: "Debes introducir el coeficiente"
            },
            coeficienteSeguridad:{
                required: "Debes introducir el coeficiente de seguridad"   
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