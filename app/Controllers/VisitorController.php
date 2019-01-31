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
    public function getGalleryDog($request){
        $listDogs = Dog::where('dogStatus','En adopción')->get();
        $errorsMessage = null;
        $errorsMessage=null;
        $responseMessage=null;

        if($request->getMethod() == 'POST'){
            $postData = $request->getParsedBody();
            $dogGenero = $postData['dogGenero'];
            $dogEdad = $postData['dogEdad'];
            $dogTamanio = $postData['dogTamanio'];
            $dogCiudad = $postData['dogCiudad'];
            


            if(!empty($dogGenero) || !empty($dogEdad) || !empty($dogTamanio) || !empty($dogCiudad)){
                $filtroGenero = null;
                $filtroEdad = null;
                $filtroTamanio = null;
                $filtroCiudad = null;

                if(!empty($dogGenero)){
                    if($dogGenero=='Hembra'){
                        $filtroGenero = $listDogs->filter(
                            function($obj){
                                return $obj->dogGender == 'Hembra';
                            }
                        );
                        $filtroGenero->all();
                    }elseif($dogGenero=='Macho'){
                        $filtroGenero = $listDogs->filter(
                            function($obj){
                                return $obj->dogGender == 'Macho';
                            }
                        );
                        $filtroGenero->all();
                    }
                }else{
                    $filtroGenero = $listDogs;
                }

                if(!empty($dogEdad)){
                    if($dogEdad=='Adulto'){
                        $filtroEdad = $filtroGenero->filter(
                            function($obj){
                                return $obj->dogAge == 'Adulto';
                            }
                        );
                        $filtroEdad->all();
                    }elseif($dogEdad=='Cachorro'){
                        $filtroEdad = $filtroGenero->filter(
                            function($obj){
                                return $obj->dogAge == 'Cachorro';
                            }
                        );
                        $filtroEdad->all();
                    }
                }else{
                    $filtroEdad = $filtroGenero;
                }
                if(!empty($dogTamanio)){
                    if($dogTamanio=='Grande'){
                        $filtroTamanio = $filtroEdad->filter(
                            function($obj){
                                return $obj->dogSize == 'Grande';
                            }
                        );
                        $filtroTamanio->all();
                    }elseif($dogTamanio=='Mediano'){
                        $filtroTamanio = $filtroEdad->filter(
                            function($obj){
                                return $obj->dogSize == 'Mediano';
                            }
                        );
                        $filtroTamanio->all();
                    }elseif($dogTamanio=='Pequeño'){
                        $filtroTamanio = $filtroEdad->filter(
                            function($obj){
                                return $obj->dogSize == 'Pequeño';
                            }
                        );
                        $filtroTamanio->all();
                    }
                }else{
                    $filtroTamanio=$filtroEdad;
                }
                if(!empty($dogCiudad)){
                    if($dogCiudad=='Quito'){
                        $filtroCiudad = $filtroTamanio->filter(
                            function($obj){
                                return $obj->dogCity == 'Quito';
                            }
                        );
                        $filtroCiudad->all();
                    }elseif($dogCiudad == 'Guayaquil'){
                        $filtroCiudad = $filtroTamanio->filter(
                            function($obj){
                                return $obj->dogCity == 'Guayaquil';
                            }
                        );
                        $filtroCiudad->all();
                    }elseif($dogCiudad == 'Cuenca'){
                        $filtroCiudad = $filtroTamanio->filter(
                            function($obj){
                                return $obj->dogCity == 'Cuenca';
                            }
                        );
                        $filtroCiudad->all();
                    }
                }else{
                    $filtroCiudad = $filtroTamanio;
                }

                $filtroResultado = $filtroCiudad;
                if($filtroResultado->count()==0){
                    // echo '<br>Ningun resultado encontrado<br>';
                    $errorsMessage = 'Ningun resultado encontrado.';
                    $listDogs = null;
                }else{
                    $listDogs = $filtroResultado;
                    $responseMessage= 'Mostrando '.$listDogs->count().' resultados';
                }
                // var_dump($filtroCiudad);
                // var_dump($filtroCiudad->count());
            }
        }
        return $this->renderHTML('galleryDog.twig',[
            'listDogs'=>$listDogs,
            'errorsMessage'=>$errorsMessage,
            'responseMessage'=>$responseMessage
        ]);
    }
    public function getDogInfo($request){
        $attributes = $request->getAttributes();
        $dogId = $attributes['dogId'];
        $dogInfo = Dog::find($dogId);
        
        return $this->renderHTML('infoDog.twig',[
            'dogInfo'=>$dogInfo
        ]);
    }
    // public function Buscador($opcionesFiltro){
    //     $listDogs = Dog::where('dogStatus','En adopción')->get();

    //     $filtroGenero = $listDogs->filter(
    //         function($obj){
    //             return $obj->dogGender == 'Hemba';
    //         }
    //     );
    //     $filtroGenero->all();
        
    //     if(!empty($filtroGenero)){
    //         echo 'Tiene valores<br>';
    //     }else{
    //         echo 'No tiene valores<br>';

    //     }
    //     var_dump($filtroGenero);
    //     var_dump($filtroGenero->count());



    //     // foreach ($opcionesFiltro as $indice => $valor) {
    //     //     if(!empty($valor)){
    //     //         $filtro = $listDogs->filter(function($obj){
    //     //             return $obj->dogAge == 'Adulto' ;
    //     //         });
    //     //         $filtro->all();
    //     //     }
    //     // }
    //     // var_dump($filtro);
    //     // var_dump($filtro->count());



    // }
}