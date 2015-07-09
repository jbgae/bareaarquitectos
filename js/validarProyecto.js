$(document).ready(function () { 

    $('#proyectoForm').validate({

        rules: {
            nombre: {
                minlength: 3,
                required: true
            },
            fechaFin:{
                date:true
            }
        },
        messages:{
            nombre: {
                minlength: jQuery.format("Introduzca al menos {0} caracteres"),
                required: "Debes introducir el nombre dle proyecto"                
            },
            fechaFin:{
                date: "Introduzca una fecha válida con el formato mm/dd/YYYY"
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