<?php

namespace App\Controllers;

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
}