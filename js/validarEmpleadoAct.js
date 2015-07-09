$(document).ready(function () { 

    jQuery.validator.addMethod("exactlength", function(value, element, param) {
        return this.optional(element) || value.length == param;
    }, jQuery.format("Introduzca extactamente {0} caracteres."));

    $('#empleadoForm').validate({
        rules: {
            nombre: {
                minlength: 3,
                required: true
            },
            primerApellido:{
                minlength: 3,
                required: true
            },
            segundoApellido:{
                minlength: 3,
                required: true
            },
            fNacimiento:{
                required:true,
                date: true                
            },
            direccion:{
                minlength:3
            },
            telefono:{
                exactlength:9
            },
            email: {
                required: true,
                email: true
            },
            pass:{
                minlength: 6,
            },
            passconf:{
                minlength: 6,
                equalTo: "#pass",
            },
            cargo:{
                minlength: 3,
                required: true
            },
            salario:{
                minlength: 3
            },
            fAlta:{
                date:true
            },
            fBaja:{
                date:true
            }
        },
        messages:{
            nombre: {
                minlength: jQuery.format("Introduzca al menos {0} caracteres"),
                required: "Debes introducir un nombre"                
            },
            primerApellido:{
                minlength: jQuery.format("Introduzca al menos {0} caracteres"),
                required: "Debes introducir el primer apellido" 
            },
            segundoApellido:{
                minlength: jQuery.format("Introduzca al menos {0} caracteres"),
                required: "Debes introducir el segundo apellido"
            },
            fNacimiento:{
                required:"Debes introducir la fecha de nacimiento",
                date: "Debes introducir una fecha válida"  
            },
            direccion:{
                minlength: jQuery.format("Introduzca al menos {0} caracteres")
            },
            telefono:{
                exactlength:jQuery.format("Introduzca {0} números")
            },
            email:{
                required: "Debes introducir un email",
                email: "Debes introducir una email válido"
            },
            pass:{
                minlength: jQuery.format("Introduzca al menos {0} caracteres"),
                  
            },
            passconf:{
                minlength: jQuery.format("Introduzca al menos {0} caracteres"),
                equalTo: "Las contraseñas no coinciden",
                
            },
            cargo:{
                minlength: jQuery.format("Introduzca al menos {0} caracteres"),
                required: "Debes introducir el cargo"
            },
            salario:{
                minlength: jQuery.format("Introduzca al menos {0} caracteres")
            },
            fAlta:{
                date: "Debes introducir una fecha válida" 
            },
            fBaja:{
                date: "Debes introducir una fecha válida" 
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