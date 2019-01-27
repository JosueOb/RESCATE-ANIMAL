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

        $userSession = $_SESSION['user'];

        if($userSession['userType'] == 'User'){
            // $listDogs = Dog::all();
            $listDogs = Dog::where('dogStatus','En adopción')->get();
            return $this->renderHTML('index.twig',[
                'user'=>$userSession,
                'listDogs'=>$listDogs
            ]);
        }else{
            return $this->redirectResponse('/admin');
        }
    }

    public function getEditProfile($request){
        
        $userSession = $_SESSION['user'];

        if($userSession['userType'] == 'User'){

            $responseMessage = null;
            $errorsMessage = null;
            $warnigMessage = null;

            if($request->getMethod() == 'POST'){

                $postData = $request->getParsedBody();
                $files = $request->getUploadedFiles();

                $userTelefono = $postData['userTelefono'];
                $userCorreo = $postData['userCorreo'];
                $nombreImagen = 'userlogo.png';
                $userId = $userSession['userId'];
                $user = User::find($userId);
                $userImagen = $files['userImagen'];
    
                $userEdit = v::key('userCorreo', v::equals($user->userEmail))
                            ->key('userTelefono', v::equals($user->userPhone));

                if(!$userEdit->validate($postData) || !empty($userImagen->getClientFilename())){
            
                    $userValidator = v::key('userTelefono', v::digit()->notEmpty()->length(7,10))
                                ->key('userCorreo', v::stringType()->notEmpty()->email());
                    try {
                        $userValidator->assert($postData);

                        if($userImagen->getError() == UPLOAD_ERR_OK){

                            if($userImagen->getClientMediaType() == 'image/jpeg' || $userImagen->getClientMediaType() == 'imagen/png' || $userImagen->getClientMediaType() == 'imagen/jpg'){
                                
                                $nombreImagen = $userImagen->getClientFilename();
                                $userImagen->moveTo("assets/img/users/$nombreImagen");

                            }else{
                                $warnigMessage = 'Formato incorrecto de la imagen';
                            }
                        }
                        
                        $user->userEmail = $userCorreo;
                        $user->userPhone = $userTelefono;
                        $user->userPhoto = $nombreImagen;
                        $user->save();
                        $responseMessage = 'Perfil Actualizado';

                        $_SESSION['user'] = $user->getAttributes();
                        $userSession = $_SESSION['user'];

                    } catch (NestedValidationException $exception) {
                        $errorsMessage = $exception->getMessages();
                    }catch(Exception $e){
                        $warnigMessage = $e->getMessage();
                    }
                }else{
                    $warnigMessage = 'No se a realizado ningún cambio';
                }
            }
            return $this->renderHTML('editProfile.twig',[
                'user'=>$userSession,
                'responseMessage'=>$responseMessage,
                'errorsMessage'=>$errorsMessage,
                'warningMessage'=>$warnigMessage
            ]);
        }else{
            return $this->redirectResponse('/admin');
        }
    }

    public function getChangePassword($request){
        if($_SESSION['user']['userType'] == 'User'){

            $responseMessage = null;
            $errorsMessage = null;
            $userSession = $_SESSION['user'];
            $userId = $userSession['userId'];
            $user= User::find($userId);

            if($request->getMethod() == 'POST'){

                $postData = $request->getParsedBody();
                $userContrasenia = $postData['userContrasenia'];
                $userContraseniaConfirm = $postData['userContraseniaConfirm'];
                
                $userValidator =v::key('userContrasenia', v::stringType()->notEmpty()->length(4,20))
                                 ->key('userContraseniaConfirm', v::stringType()->notEmpty()->equals($userContrasenia));

                try {

                    $userValidator->assert($postData);
                    $user->userPassword = \password_hash($userContrasenia,PASSWORD_DEFAULT);
                    $user->save();
                    $responseMessage='Cambio de contraseña exitoso';
                    
                } catch (NestedValidationException $exception) {
                    $exception->findMessages([
                        'equals' => 'Passwords are not the same'
                    ]);
                    $errorsMessage = $exception->getMessages();
                }
            }
            return $this->renderHTML('changePass.twig',[
                'user'=>$userSession,
                'responseMessage'=>$responseMessage,
                'errorsMessage'=>$errorsMessage
            ]);

        }else{
            return $this->redirectResponse('/admin');
        }
    }

    public function getLogDogs(){

        $userSession = $_SESSION['user'];

        if($userSession['userType'] == 'User'){
            $allDogs = Dog::all();
            return $this->renderHTML('logDog.twig',[
                'user'=>$userSession,
                'listDogs'=>$allDogs
            ]);
        }else{
            return $this->redirectResponse('/admin');
        }
    }
}