<?php

namespace App\Controllers;

class AdminController extends BaseController{
    // protected $viewPath='../views/admin';
    public function __construct(){
        $this->viewPath = '../views/admin';
        parent::__construct();
    }
    public function getAdminIndex(){
        // echo $this->renderHTML('admin/layout.twig');
        // echo $this->renderHTML('admin/layout.twig');
        return $this->renderHTML('index.twig');
    }
}