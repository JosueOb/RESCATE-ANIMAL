<?php

namespace App\Controllers;
use App\Models\User;

class AdminController extends BaseController{
    // protected $viewPath='../views/admin';
    public function __construct(){
        $this->viewPath = '../views/admin';
        parent::__construct();
    }
    public function getAdminIndex(){
        // $user = $_SESSION['user']['userType'];
        // var_dump($user);
        // die;
        
        if($_SESSION['user']['userType'] == 'Admin'){
            $listUser = User::where('userType','User')->where('userStatus',true)->get();
            return $this->renderHTML('index.twig',[
                'listUser'=>$listUser
            ]);
        }else{
            echo 'No eres admin';
            die;
        }
        // return $this->renderHTML('index.twig');
    }
    public function getUserAdd($request){
        if($_SESSION['user']['userType'] == 'Admin'){
            $responseMessage =null;

            if($request->getMethod() == 'POST'){
                $postData = $request->getParsedBody();

                //validar campo que se reciben 
                //**********/

                //excepcion
                try {
                    $user = new User();
                    $user->userName = $postData['userNombre'];
                    $user->userLastName = $postData['userApellido'];
                    $user->userEmail = $postData['userCorreo'];
                    $user->userCedula = $postData['userCedula'];
                    $user->userPhoto = 'userlogo.png';
                    $user->userPhone = $postData['userTelefono'];
                    $user->userPassword = \password_hash($postData['userContrasenia'],PASSWORD_DEFAULT);
                    $user->userStatus = true;
                    $user->userType = 'User';
                    $user->save();
                    $responseMessage = 'Usuario Registrado exitosamente';
                    
                } catch (\Exception $e) {
                    $responseMessage  = $e->getMessage();
                }
            }
            // echo 'Agregar Usuario';
            // die;
            return $this->renderHTML('addUser.twig',[
                'responseMessage'=>$responseMessage
            ]);

        }else{
            echo 'No eres admin';
            die;
        }
    }
    
    public function getDeleteUser($request){
        // echo 'Eliminar usuario';
        // var_dump($request->getAttributes());
        $attributes = $request->getAttributes();
        $userId = $attributes['userId'];
        $userDelete = User::find($userId);
        $userDelete->userStatus = false;
        $userDelete->save();
        // $userDelete->delete();
        return $this->redirectResponse('/admin');
    }
    public function getUpdateUser($request){
       
        if($_SESSION['user']['userType'] == 'Admin'){
            $responseMessage =null;
            $attributes = $request->getAttributes();
            $userId = $attributes['userId'];
            $userUpdate = User::find($userId);

            if($request->getMethod() == 'POST'){
                $postData = $request->getParsedBody();
                // var_dump($postData);
                // die;
                //validar campo que se reciben 
                //**********/

                //excepcion
                try {
                    $userUpdate->userName = $postData['userNombre'];
                    $userUpdate->userLastName = $postData['userApellido'];
                    $userUpdate->userEmail = $postData['userCorreo'];
                    $userUpdate->userCedula = $postData['userCedula'];
                    $userUpdate->userPhone = $postData['userTelefono'];
                    // $userUpdate->userPassword = \password_hash($postData['userContrasenia'],PASSWORD_DEFAULT);
                    if($postData['userEstado']=='activo'){
                        $userUpdate->userStatus = true;
                    }elseif($postData['userEstado']=='inactivo'){
                        $userUpdate->userStatus = false;
                    }else{
                        $responseMessage ='* Estado de usuario inválido ';
                    }
                    $responseMessage .= 'Usuario Actualizado exitosamente';
                    $userUpdate->save();
                    
                } catch (\Exception $e) {
                    $responseMessage  = $e->getMessage();
                }
            }

            return $this->renderHTML('updateUser.twig',[
                'responseMessage'=>$responseMessage,
                'userUpdate'=>$userUpdate
            ]);

        }else{
            echo 'No eres admin';
            die;
        }


    }
    public function getChangePassUser($request){
        // echo 'cambiar contrasenia user';
        // var_dump($request->getAttributes());
        $responseMessage=null;
        $attributes = $request->getAttributes();
        $userId = $attributes['userId'];
        $user= User::find($userId);
        if($request->getMethod() == 'POST'){
            $postData = $request->getParsedBody();
            // var_dump($postData);
            // die;
            if(!empty($postData['userContrasenia']) && !empty($postData['userContraseniaConfirm'])){
                if($postData['userContrasenia'] == $postData['userContraseniaConfirm']){
                    $user->userPassword = \password_hash($postData['userContrasenia'],PASSWORD_DEFAULT);
                    $user->save();
                    $responseMessage='Cambio de contrasenia exitoso';
                }else{
                    $responseMessage='Las contrasenias no coinciden';
                }
            }else{
                $responseMessage = 'Ingrese la contrasenia';
            }
        }
        return $this->renderHTML('passUser.twig',[
            'userUpdate'=>$user,
            'responseMessage'=>$responseMessage
        ]);

    }
    public function getLogUser(){
        if($_SESSION['user']['userType'] == 'Admin'){
            $listUser = User::where('userType','User')->get();
            return $this->renderHTML('logUser.twig',[
                'listUser'=>$listUser
            ]);
        }else{
            echo 'No eres admin';
            die;
        }
    }
    
}