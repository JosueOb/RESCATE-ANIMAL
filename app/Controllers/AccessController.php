<?php

namespace App\Controllers;
use App\Models\User;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

//Se define esta clase para acceder a la sistema
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
        $userEmail = $postData['userEmail'];
        $userPass = $postData['userPass'];
        $responseMessage = null;

        if(v::stringType()->notEmpty()->validate($userEmail)){
            if(v::email()->validate($userEmail)){
                if(v::stringType()->notEmpty()->validate($userPass)){
                    $user = User::where('userEmail',$userEmail)->first();
                    if($user){
                        if(\password_verify($userPass, $user->userPassword)){
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
                }else{
                    $responseMessage='* Ingrese su contraseña';
                }
            }else{
                $responseMessage ='* Formato del correo inválido';
            }
        }else{
            $responseMessage = '* Ingrese su correo';
        }


        return $this->renderHTML('login.twig',[
            'responseMessage'=>$responseMessage
        ]);

    }
}