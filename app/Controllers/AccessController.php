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
            if(\password_verify($postData['userPass'], $user->userPassword)){
                $_SESSION['user']= $user->getAttributes();
                // var_dump($user->getAttributes());
                if($user->userType == 'Admin'){
                    return $this->redirectResponse('/admin');
                    
                }elseif($user->userType == 'User'){
                    $responseMessage= 'User';
                    // return $this->redirectResponse('/user');
                }
                
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