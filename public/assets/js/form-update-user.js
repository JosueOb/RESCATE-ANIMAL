// Validacion del formulario de actualizacion de registro
$(document).ready(function(){
    $('#formulario').validate({
        rules:{
            userNombre:{
                required:true,
                minlength:3,
                maxlength:25
            },
            userApellido:{
                required:true,
                minlength:3,
                maxlength:25
            },
            userCedula:{
                required:true,
                digits:true,
                minlength:10,
                maxlength:10,

            },
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
            userEstado:{
                required:true
            }
        },
        messages:{
            userNombre:{
                required:'* Ingrese el nombre',
                minlength:'* Mínimo 3 caracteres',
                maxlength:'* Máximo 25 caracteres'
            },
            userApellido:{
                required:'* Ingrese el apellido',
                minlength:'* Mínimo 3 caracteres',
                maxlength:'* Máximo 25 caracteres'
            },
            userCedula:{
                required:'* Ingrese el # cédula',
                digits:'* Ingrese solo dígitos',
                minlength:'* La # cédula consta de 10 dígitos',
                maxlength:'* Fuera del límite'
            },
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
            userEstado:{
                required:'* Campo Obligatorio'
            }
        },
        submitHandler:function(form){
            form.submit();
        }
    });
});