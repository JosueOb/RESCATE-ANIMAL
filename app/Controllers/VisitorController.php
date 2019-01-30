<?php
namespace App\Controllers;
use App\Models\Dog;

class VisitorController extends BaseController{
    
    public function __construct(){
        $this->viewPath='../views/visitor';
        parent::__construct();
    }
    public function getVisitorIndex(){
        $firstFiveDog = Dog::where('dogStatus','En adopción')->orderBy('dogId', 'desc')->take(5)->get();
        // var_dump($firstFiveDog);
        // die;
        return $this->renderHTML('index.twig',[
            'firstFiveDog'=>$firstFiveDog
        ]);
    }
    public function getGalleryDog(){
        return $this->renderHTML('galleryDog.twig');
    }
}