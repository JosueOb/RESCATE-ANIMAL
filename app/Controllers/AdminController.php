<?php

namespace App\Controllers;
use App\Models\User;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class AdminController extends BaseController{
    // protected $viewPath='../views/admin';
    public function __construct(){
        $this->viewPath = '../views/admin';
        parent::__construct();
    }
    
    public function getAdminIndex(){

        $userAdminSession = $_SESSION['user'];

        if($userAdminSession['userType'] == 'Admin'){
            $listUser = User::where('userType','User')->where('userStatus',true)->get();
            return $this->renderHTML('index.twig',[
                'listUser'=>$listUser,
                'userAdmin'=>$userAdminSession
            ]);
        }else{
            return $this->redirectResponse('/user');
        }
    }

    public function getUserAdd($request){
        $userAdminSession = $_SESSION['user'];

        if($userAdminSession['userType'] == 'Admin'){
            $responseMessage =null;
            $errorsMessage = null;

            if($request->getMethod() == 'POST'){
                $postData = $request->getParsedBody();
                $userNombre = $postData['userNombre'];
                $userApellido = $postData['userApellido'];
                $userCedula = $postData['userCedula'];
                $userTelefono = $postData['userTelefono'];
                $userCorreo = $postData['userCorreo'];
                $userContrasenia = $postData['userContrasenia'];
                $userContraseniaConfirm = $postData['userContraseniaConfirm'];
             
                $userValidator = v::key('userNombre', v::stringType()->notEmpty()->length(3,25))
                                ->key('userApellido', v::stringType()->notEmpty()->length(3,25))
                                ->key('userCedula', v::digit()->notEmpty()->length(10,10))
                                ->key('userTelefono', v::digit()->notEmpty()->length(7,10))
                                ->key('userCorreo', v::stringType()->notEmpty()->email())
                                ->key('userContrasenia', v::stringType()->notEmpty()->length(4,20))
                                ->key('userContraseniaConfirm', v::stringType()->notEmpty()->equals($userContrasenia));

                //excepcion
                try {
                    $userValidator->assert($postData);
                    $user = new User();
                    $user->userName = $userNombre;
                    $user->userLastName = $userApellido;
                    $user->userEmail = $userCorreo;
                    $user->userCedula = $userCedula;
                    $user->userPhoto = 'userlogo.png';
                    $user->userPhone = $userTelefono;
                    $user->userPassword = \password_hash($userContrasenia,PASSWORD_DEFAULT);
                    $user->userStatus = true;
                    $user->userType = 'User';
                    $user->save();
                    $responseMessage = 'Usuario Registrado exitosamente';
                    
                } catch (NestedValidationException $exception) {
                    $exception->findMessages([
                        'equals' => 'Passwords are not the same'
                    ]);
                    $errorsMessage = $exception->getMessages();
                }
            }
            return $this->renderHTML('addUser.twig',[
                'userAdmin'=>$userAdminSession,
                'responseMessage'=>$responseMessage,
                'errorsMessage'=>$errorsMessage
            ]);

        }else{
            return $this->redirectResponse('/user');
        }
    }
    
    public function getDeleteUser($request){
        $userAdminSession = $_SESSION['user'];
        if($userAdminSession['userType'] == 'Admin'){
            $attributes = $request->getAttributes();
            $userId = $attributes['userId'];
            $userDelete = User::find($userId);
            $userDelete->userStatus = false;
            $userDelete->save();
            // $userDelete->delete();
            return $this->redirectResponse('/admin');
        }else{
            return $this->redirectResponse('/user');
        }
    }

    public function getUpdateUser($request){
        $userAdminSession = $_SESSION['user'];
        if($userAdminSession['userType'] == 'Admin'){
            $responseMessage = null;
            $errorsMessage = null;
            $warningMessage = null;
            $attributes = $request->getAttributes();
            $userId = $attributes['userId'];
            $userUpdate = User::find($userId);
            
            if($request->getMethod() == 'POST'){
                $postData = $request->getParsedBody();
                $getUserStatus = null;

                if($userUpdate->userStatus){
                    $getUserStatus = 'activo';
                }else{
                    $getUserStatus = 'inactivo';
                }

                $userEdit = v::key('userNombre', v::equals($userUpdate->userName))
                             ->key('userApellido', v::equals($userUpdate->userLastName))
                             ->key('userCorreo', v::equals($userUpdate->userEmail))
                             ->key('userCedula', v::equals($userUpdate->userCedula))
                             ->key('userTelefono', v::equals($userUpdate->userPhone))
                             ->key('userEstado', v::equals($getUserStatus));

                if(!$userEdit->validate($postData)){
                    // echo 'El nombre del usuario a cambiado';
                    $userValidator = v::key('userNombre', v::stringType()->notEmpty()->length(3,25))
                                    ->key('userApellido', v::stringType()->notEmpty()->length(3,25))
                                    ->key('userCedula', v::digit()->notEmpty()->length(10,10))
                                    ->key('userTelefono', v::digit()->notEmpty()->length(7,10))
                                    ->key('userCorreo', v::stringType()->notEmpty()->email())
                                    ->key('userEstado', v::stringType()->notEmpty()->oneOf(v::equals('activo'),v::equals('inactivo')));

                    //excepcion
                    try {
                        $userValidator->assert($postData);

                        $userUpdate->userName = $postData['userNombre'];
                        $userUpdate->userLastName = $postData['userApellido'];
                        $userUpdate->userEmail = $postData['userCorreo'];
                        $userUpdate->userCedula = $postData['userCedula'];
                        $userUpdate->userPhone = $postData['userTelefono'];
                    
                        if($postData['userEstado']=='activo'){
                            $userUpdate->userStatus = true;
                        }elseif($postData['userEstado']=='inactivo'){
                            $userUpdate->userStatus = false;
                        }else{
                            $responseMessage ='* Estado de usuario inválido ';
                        }
                        $responseMessage .= 'Usuario Actualizado exitosamente';
                        $userUpdate->save();
                        
                    } catch (NestedValidationException $exception) {
                        $errorsMessage = $exception->getMessages();
                    }
                }else{
                    $warningMessage = 'Registro sin cambios';
                }
            }

            return $this->renderHTML('updateUser.twig',[
                'userAdmin'=>$userAdminSession,
                'responseMessage'=>$responseMessage,
                'userUpdate'=>$userUpdate,
                'errorsMessage'=>$errorsMessage,
                'warningMessage'=>$warningMessage
            ]);

        }else{
            return $this->redirectResponse('/user');
        }
    }

    public function getChangePassUser($request){
        $userAdminSession = $_SESSION['user'];
        if($userAdminSession['userType'] == 'Admin'){

            $responseMessage = null;
            $errorsMessage = null;
            $attributes = $request->getAttributes();
            $userId = $attributes['userId'];
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
            return $this->renderHTML('passUser.twig',[
                'userAdmin'=>$userAdminSession,
                'userUpdate'=>$user,
                'responseMessage'=>$responseMessage,
                'errorsMessage'=>$errorsMessage
            ]);

        }else{
            return $this->redirectResponse('/user');
        }
        

    }
    //historial de usuarios registrados
    public function getLogUser(){
        $userAdminSession = $_SESSION['user'];
        if($userAdminSession['userType'] == 'Admin'){
            $listUser = User::where('userType','User')->get();
            return $this->renderHTML('logUser.twig',[
                'userAdmin'=>$userAdminSession,
                'listUser'=>$listUser
            ]);
        }else{
            return $this->redirectResponse('/user');
        }
    }
    
}