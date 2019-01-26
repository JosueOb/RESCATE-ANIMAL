<?php

namespace App\Controllers;
use App\Models\{User,Dog};
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;


class UserController extends BaseController{

    public function __construct(){
        $this->viewPath = '../views/user';
        parent::__construct();
    }
    public function getUserIndex(){
        $user = $_SESSION['user'];
        $listDogs = Dog::all();
        return $this->renderHTML('index.twig',[
            'user'=>$user,
            'listDogs'=>$listDogs
        ]);
    }
    public function getEditProfile($request){
        $user = $_SESSION['user'];
        $responseMessage = null;
        $errorsMessage = null;
        $warnigMessage = null;

        if($request->getMethod() == 'POST'){
            // var_dump($request->getParsedBody());
            // die;
            $postData = $request->getParsedBody();
            $userTelefono = $postData['userTelefono'];
            $userCorreo = $postData['userCorreo'];

            
            $userValidator = v::key('userTelefono', v::digit()->notEmpty()->length(7,10))
            ->key('userCorreo', v::stringType()->notEmpty()->email());
            try {
                $userValidator->assert($postData);
                $files = $request->getUploadedFiles();
                $userImagen = $files['userImagen'];
                if($userImagen->getError() == UPLOAD_ERR_OK){
                    if($userImagen->getClientMediaType() == 'image/jpeg' || $userImagen->getClientMediaType() == 'imagen/png'){
                        echo 'formato correcto de imagen';
                    }else{
                        echo 'No es una imagen';
                    }
                    // var_dump($userImagen);
                    die;
                }

            } catch (NestedValidationException $exception) {
                $errorsMessage = $exception->getMessages();
            }catch(Exception $e){
                $warnigMessage = $e;
            }
        }
        return $this->renderHTML('editProfile.twig',[
            'user'=>$user,
            'responseMessage'=>$responseMessage,
            'errorsMessage'=>$errorsMessage,
            'warningMessage'=>$warnigMessage
        ]);
    }
}