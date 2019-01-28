<?php

namespace App\Controllers;
use App\Models\Dog;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class DogController extends BaseController{

    public function __construct(){
        $this->viewPath = '../views/user';
        parent::__construct();
    }

    public function getAddDog($request){
        $userSession = $_SESSION['user'];

        if($userSession['userType'] == 'User'){
            $responseMessage =null;
            $errorsMessage = null;

            if($request->getMethod() == 'POST'){

                $postData = $request->getParsedBody();
                $files = $request->getUploadedFiles();
    
                $dogFoto = $files['dogFoto'];
                $nameFoto = \strtolower($dogFoto->getClientFilename());

     
                $dogValidator = v::key('dogNombre', v::stringType()->notEmpty()->length(3,25))
                                ->key('dogGenero',v::stringType()->notEmpty()->oneOf(v::equals('Hembra'),v::equals('Macho')))
                                ->key('dogEdad',v::stringType()->notEmpty()->oneOf(v::equals('Adulto'),v::equals('Cachorro')))
                                ->key('dogTamanio',v::stringType()->notEmpty()->oneOf(v::equals('Grande'),v::equals('Mediano'),v::equals('Pequeño')))
                                ->key('dogCiudad', v::stringType()->notEmpty()->oneOf(v::equals('Quito'),v::equals('Guayaquil'),v::equals('Cuenca')))
                                ->key('dogDescripcion', v::stringType()->notEmpty()->length(50,255));
                $fotoValidator = v::stringType()->notEmpty()->oneOf(v::extension('jpg'),v::extension('jpeg'),v::extension('png'))->setName('dogPhoto');
                
                try {
                    $dogValidator->assert($postData);
                    $fotoValidator->assert($nameFoto);
                    
                    if($dogFoto->getError() == UPLOAD_ERR_OK){
                        $dogFoto->moveTo("assets/img/dogs/$nameFoto");
                    }

                    $dog = new Dog();
                    $dog->dogName = $postData['dogNombre'];
                    $dog->dogGender = $postData['dogGenero'];
                    $dog->dogAge = $postData['dogEdad'];
                    $dog->dogSize = $postData['dogTamanio'];
                    $dog->dogCity = $postData['dogCiudad'];
                    $dog->dogPhoto = $nameFoto;
                    $dog->dogDescription = $postData['dogDescripcion'];
                    $dog->dogStatus = 'En adopción';
                    $dog->save();
                    $responseMessage = 'Can registrado con exito';
                    
                } catch (NestedValidationException $exception) {
                    $errorsMessage = $exception->getMessages();

                }
            }
            return $this->renderHTML('addDog.twig',[
                'user'=>$userSession,
                'responseMessage'=>$responseMessage,
                'errorsMessage'=>$errorsMessage
            ]);
        }else{
            return $this->redirectResponse('/admin');
        }
    }

    public function getDeleteDog($request){

        $userSession = $_SESSION['user'];
        if($userSession['userType'] == 'User'){

            $attributes=$request->getAttributes();
            $dogId = $attributes['dogId'];
            $dogDelete = Dog::find($dogId);
            $dogDelete->dogStatus = 'Inactivo';
            $dogDelete->save();
            return $this->redirectResponse('/user');
        }else{
            return $this->redirectResponse('/admin');
        }
    }

    public function getUpdateDog($request){

        $userSession = $_SESSION['user'];
        if($userSession['userType'] == 'User'){

            $responseMessage = null;
            $errorsMessage = null;
            $warningMessage = null;
            $attributes = $request->getAttributes();
            $dogId = $attributes['dogId'];
            $dogUpdate = Dog::find($dogId);

            if($request->getMethod() == 'POST'){
                $postData = $request->getParsedBody();
                $files = $request->getUploadedFiles();
                $dogImagenName = \strtolower($files['dogFoto']->getClientFilename());


                if(empty($dogImagenName)){
                    $dogImagenName = $dogUpdate->dogPhoto;
                }

                $dogEdit = v::key('dogNombre', v::equals($dogUpdate->dogName))
                    ->key('dogGenero',v::equals($dogUpdate->dogGender))
                    ->key('dogEdad',v::equals($dogUpdate->dogAge))
                    ->key('dogTamanio',v::equals($dogUpdate->dogSize))
                    ->key('dogCiudad', v::equals($dogUpdate->dogCity))
                    ->key('dogEstado', v::equals($dogUpdate->dogStatus))
                    ->key('dogDescripcion', v::equals($dogUpdate->dogDescription));
                $fotoEdit = v::equals($dogUpdate->dogPhoto);

                if(!$dogEdit->validate($postData) || !$fotoEdit->validate($dogImagenName)){
                    // var_dump($dogEdit->validate($postData));
                    // var_dump($fotoEdit->validate($dogImagenName));
                    // echo 'Se a realizado un cambio';
                    // die;

                    $dogValidator = v::key('dogNombre', v::stringType()->notEmpty()->length(3,25))
                        ->key('dogGenero',v::stringType()->notEmpty()->oneOf(v::equals('Hembra'),v::equals('Macho')))
                        ->key('dogEdad',v::stringType()->notEmpty()->oneOf(v::equals('Adulto'),v::equals('Cachorro')))
                        ->key('dogTamanio',v::stringType()->notEmpty()->oneOf(v::equals('Grande'),v::equals('Mediano'),v::equals('Pequeño')))
                        ->key('dogCiudad', v::stringType()->notEmpty()->oneOf(v::equals('Quito'),v::equals('Guayaquil'),v::equals('Cuenca')))
                        ->key('dogDescripcion', v::stringType()->notEmpty()->length(50,255))
                        ->key('dogEstado', v::stringType()->notEmpty()->oneOf(v::equals('En adopción'),v::equals('Inactivo'),v::equals('Adoptado')));
                    $fotoValidator = v::stringType()->notEmpty()->oneOf(v::extension('jpg'),v::extension('jpeg'),v::extension('png'))->setName('dogPhoto');

                    try {

                        $dogValidator->assert($postData);
                        $fotoValidator->assert($dogImagenName);

                        $dogUpdate->dogName = $postData['dogNombre'];
                        $dogUpdate->dogGender = $postData['dogGenero'];
                        $dogUpdate->dogAge = $postData['dogEdad'];
                        $dogUpdate->dogSize = $postData['dogTamanio'];
                        $dogUpdate->dogCity = $postData['dogCiudad'];
                        $dogUpdate->dogPhoto = $dogImagenName;
                        $dogUpdate->dogDescription = $postData['dogDescripcion'];
                        $dogUpdate->dogStatus = $postData['dogEstado'];
                        $dogUpdate->save();
                        $responseMessage = 'Can actualizado con exito';

                    } catch (NestedValidationException $exception) {
                    $errorsMessage = $exception->getMessages();
                    }

                }else{
                    // echo 'No se ha realizado ningun cambio';
                    $warningMessage = 'No se a realizado ningún cambio';
                }

            }
            return $this->renderHTML('updateDog.twig',[
                'user'=>$userSession,
                'dog'=>$dogUpdate,
                'responseMessage'=>$responseMessage,
                'errorsMessage'=>$errorsMessage,
                'warningMessage'=>$warningMessage
            ]);

            // var_dump($dogId);
            // die;

        }else{
            return $this->redirectResponse('/admin');
        }
    }
}