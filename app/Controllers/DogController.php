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
        // var_dump($request->getAttributes());
        // die;
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
}