<?php

namespace App\Controllers;
use App\Models\Dog;

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
}