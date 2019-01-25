<?php

namespace App\Controllers;

class UserController extends BaseController{
    public function __construct(){
        $this->viewPath = '../views/user';
        parent::__construct();
    }
    public function getUserIndex(){
        $user = $_SESSION['user'];
        // \var_dump($user);
        // echo "Bienvenido ".$user['userName'];
        // die;
        return $this->renderHTML('layout.twig',[
            'user'=>$user
        ]);
    }
}