// Validacion del formulario de registro de actualización de can

$(document).ready(function(){

    $('#formulario').validate({
        rules:{
            dogNombre:{
                required:true,
                minlength:3,
                maxlength:25
            },
            dogGenero:{
                required:true
            },
            dogEdad:{
                required:true
            },
            dogTamanio:{
                required:true
            },
            dogCiudad:{
                required:true
            },
            dogFoto:{
                extension: "jpg|jpeg|png",
                accept: "image/*"
            },
            dogDescripcion:{
                required:true,
                minlength:50,
                maxlength:255
            }
        },
        messages:{
            dogNombre:{
                required:'* Ingrese el nombre',
                minlength:'* Mínimo 3 caracteres',
                maxlength:'* Máximo 25 caracteres'
            },
            dogGenero:{
                required:'* Seleccione el género'
            },
            dogEdad:{
                required:'* Seleccione la edad'
            },
            dogTamanio:{
                required:'* Seleccione el tamaño'
            },
            dogCiudad:{
                required:'* Seleccione la ciudad'
            },
            dogFoto:{
                extension: "Extensión del archivo inválida",
                accept: "Archivo inválido"
            },
            dogDescripcion:{
                required:'* Ingrese una descripción',
                minlength:'* Mínimo 50 caracteres',
                maxlength:'* Máximo 255 caracteres'
            }
        },
        submitHandler:function(form){
            form.submit();
        }
    });
});