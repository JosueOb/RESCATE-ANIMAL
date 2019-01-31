// Validacion del formulario de contacto
$(document).ready(function(){
    $('#formulario').validate({
        rules:{
            nombre:{
                required:true,
                minlength:3,
                maxlength:100
            },
            correo:{
                required:true,
                email:true
            },
            asunto:{
                required:true,
                minlength:5,
                maxlength:25,
            },
            mensaje:{
                required:true,
                minlength:10,
                maxlength:255
            },
        },
        messages:{
            nombre:{
                required:'* Ingrese el nombre',
                minlength:'* Mínimo 3 caracteres',
                maxlength:'* Máximo 25 caracteres'
            },
            correo:{
                required:'* Ingrese el correo',
                email:'* Formato del correo inválido'
            },
            asunto:{
                required:'* Ingrese el asunto',
                minlength:'* Mínimo 5 caracteres',
                maxlength:'* Máximo 100 caracteres'
            },
            mensaje:{
                required:'* Ingrese el mensaje',
                minlength:'* Mínimo 10 caracteres',
                maxlength:'* Máximo 255 caracteres'
            }
        },
        submitHandler:function(form){
            form.submit();
        }
    });
});