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
        return $this->renderHTML('index.twig',[
            'firstFiveDog'=>$firstFiveDog
        ]);
    }
    public function getGalleryDog(){
        $listDogs = Dog::where('dogStatus','En adopción')->get();
        return $this->renderHTML('galleryDog.twig',[
            'listDogs'=>$listDogs
        ]);
    }
}