<?php

namespace App\Controllers;
use App\Models\User;

class AccessController extends BaseController{

    public function __construct(){
        $this->viewPath='../views/access';
        parent::__construct();
    }

    public function getLogin(){
        return $this->renderHTML('login.twig');
    }
    public function getLogout(){
        unset($_SESSION['user']);
        return $this->redirectResponse('/login');
    }
    public function postLogin($request){
        $postData = $request->getParsedBody();
        $responseMessage = null;
        $user = User::where('userEmail',$postData['userEmail'])->first();
        if($user){
            // $responseMessage='Coincide correo';
            if(\password_verify($postData['userPass'], $user->userPassword)){
                // $responseMessage='Coincide Contrasenia';
                // return new RedirectResponse('/admin');
                $_SESSION['user']=$user->userId;
                return $this->redirectResponse('/admin');
                
            }else{
                $responseMessage='Datos Incorrectos';
            }
        }else{
            $responseMessage='Datos Incorrectos';
        }
        return $this->renderHTML('login.twig',[
            'responseMessage'=>$responseMessage
        ]);

    }
}