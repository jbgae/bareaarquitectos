$(document).ready(function () {  

    $('#noticiaForm').validate({ 
        rules: {
            titulo:{
                minlength: 3,
                required: true
            },
            contenido:{
                required: true,
                date: true
            }
        },
        messages:{
            titulo: {
                minlength: jQuery.format("Introduzca al menos {0} caracteres"),
                required: "Debes introducir un título"                
            },
            contenido:{
                date: "Debes introducir una fecha con el formato mm/dd/yyyy",
                required: "Debes introducir el contenido de la noticia" 
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