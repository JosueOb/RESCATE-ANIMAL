<?php

namespace App\Controllers;
use Zend\Diactoros\Response\HtmlResponse;

class BaseController{
    protected $templateEngine;//para utilizarla en las funciones de esta clase
    protected $viewPath='../views';
    public function __construct(){
        $loader = new \Twig_Loader_Filesystem($this->viewPath);
        $this->templateEngine =new \Twig_Environment($loader, [
            'cache' => false,
            'debug'=> true,
        ]);
    }

    public function renderHTML($fileName,$data = []){
        // return $this->templateEngine->render($fileName,$data);
        return new HtmlResponse($this->templateEngine->render($fileName,$data));
    }
}