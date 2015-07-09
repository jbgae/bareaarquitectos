$(document).ready(function () {  

    $('#citaForm').validate({ 
        rules: {
            Cita:{
                minlength: 3,
                required: true
            },
            Fecha:{
                required: true,
                date: true
            },
            Hora:{
                minlength: 5
            },
            Descipcion:{
                minlength: 5               
            }
        },
        messages:{
            Cita: {
                minlength: jQuery.format("Introduzca al menos {0} caracteres"),
                required: "Debes introducir la cita"                
            },
            Fecha:{
                date: "Debes introducir una fecha con el formato mm/dd/yyyy",
                required: "Debes introducir la fecha de la cita" 
            },
            Hora:{
                minlength: jQuery.format("Introduzca al menos {0} caracteres")
            },
            Descripcion:{
                minlength:jQuery.format("Introduzca al menos {0} caracteres") 
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