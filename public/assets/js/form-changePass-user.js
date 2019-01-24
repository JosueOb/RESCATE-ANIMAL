// Validacion del formulario de cambio de contrasenia
$(document).ready(function(){
    $('#formulario').validate({
        rules:{
            userContrasenia:{
                required:true,
                minlength:4,
                maxlength:20
            },
            userContraseniaConfirm:{
                required:true,
                equalTo:'#userContrasenia'
            }
        },
        messages:{
            userContrasenia:{
                required:'* Ingrese la contraseña',
                minlength:'* Ingrese más de 4 caracteres',
                maxlength:'* Fuera del rango permitido'
            },
            userContraseniaConfirm:{
                required:'* Repita la contraseña',
                equalTo:'* Las contraseñas no coinciden'
            }
        },
        submitHandler:function(form){
            form.submit();
        }
    });
});