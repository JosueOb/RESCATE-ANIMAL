// Validacion del login
$(document).ready(function(){
    $('#formulario').validate({
        rules:{
            userEmail:{
                required:true,
                email:true
            },
            userPass:{
                required:true,
                minlength:4,
                maxlength:20
            }
        },
        messages:{
            userEmail:{
                required:'* Ingrese su correo',
                email:'* Formato del correo inválido'
            },
            userPass:{
                required:'* Ingrese su contraseña',
                minlength:'* Ingrese más de 4 caracteres',
                maxlength:'* Fuera del rango permitido'
            }
        },
        submitHandler:function(form){
            form.submit();
        }
    });
});