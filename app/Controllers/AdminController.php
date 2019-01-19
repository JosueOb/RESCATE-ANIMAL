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
            return $this->renderHTML('index.twig');
        }else{
            echo 'No eres admin';
            die;
        }
        // return $this->renderHTML('index.twig');
    }
    public function getUserAdd($request){
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

    }
}