// Validacion del formulario de registro
$(document).ready(function(){
    $('#formulario').validate({
        rules:{
            userTelefono:{
                required:true,
                digits:true,
                minlength:7,
                maxlength:10,
            },
            userCorreo:{
                required:true,
                email:true
            },
            userImagen:{
                extension: "jpg|jpeg|png",
                accept: "image/*"
            }
        },
        messages:{
            userTelefono:{
                required:'* Ingrese el # teléfono',
                digits:'* Ingrese solo dígitos',
                minlength:'* Rango permitido entre 7 a 10 dígitos',
                maxlength:'* Fuera del límite'
            },
            userCorreo:{
                required:'* Ingrese el correo',
                email:'* Formato del correo inválido'
            },
            userImagen:{
                extension: "Extensión del archivo inválida",
                accept: "Solo se admite una imagen"
            }
        },
        submitHandler:function(form){
            form.submit();
        }
    });
});