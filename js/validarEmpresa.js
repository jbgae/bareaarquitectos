$(document).ready(function () {  
    
    jQuery.validator.addMethod("exactlength", function(value, element, param) {
        return this.optional(element) || value.length == param;
    }, jQuery.format("Introduzca extactamente {0} caracteres."));

    $('#empresaForm').validate({
        rules: {
            cif: {
                exactlength: 9,
                required: true
            },
            razon:{
                minlength: 3,
                required: true
            },
            direccion:{
                minlength:3
            },
            email: {
                email: true
            },
            telefono:{
                exactlength:9,
                number: true
            },
            fax:{
                exactlength:9,
                number: true
            },
            web:{
                minlength: 3,
            },
            servicios:{
                minlength: 3,                
            },         
            descripcion:{
                minlength: 6,                
            },
            valoracion:{
                number: true
            }
        },
        messages:{
            cif: {
                exactlength: jQuery.format("Introduzca exactamente {0} caracteres"),
                required: "Debes introducir el cif"                
            },
            razon:{
                minlength: jQuery.format("Introduzca al menos {0} caracteres"),
                required: "Debes introducir la razón social" 
            },
            direccion:{
                minlength: jQuery.format("Introduzca al menos {0} caracteres")
            },
            email:{
                email: "Debes introducir una email válido"
            },
            telefono:{
                exactlength:jQuery.format("Introduzca {0} números"),
                number: "Debes introducir sólamente números"
            },
            fax:{
                exactlength:jQuery.format("Introduzca {0} números"),
                number: "Debes introducir sólamente números"
            },
            web:{
                minlength: jQuery.format("Introduzca al menos {0} caracteres")
            },
            servicios:{
                minlength: jQuery.format("Introduzca al menos {0} caracteres")                   
            },
            descripcion:{
                minlength: jQuery.format("Introduzca al menos {0} caracteres")
            },
            valoracion:{
                number: "Debes escribir sólamente números"
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