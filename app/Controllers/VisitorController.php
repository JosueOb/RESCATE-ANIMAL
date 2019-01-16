<?php
namespace App\Controllers;

class VisitorController extends BaseController{
    
    public function __construct(){
        $this->viewPath='../views/visitor';
        parent::__construct();
    }
    public function getVisitorIndex(){
        return $this->renderHTML('index.twig');
    }

}