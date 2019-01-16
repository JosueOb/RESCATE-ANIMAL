<?php
// Inicializando errores
ini_set('display_errors',1);
ini_set('display_starup_errors',1);
error_reporting(E_ALL);

//Archivos requeridos
require_once '../vendor/autoload.php';
//use
use Aura\Router\RouterContainer;

// Zend-diactoros PSR-7
// Se crea un objeto Zend-Diactoros, el cual contiene la información
//de un request (petición), En este caso se utiliza para obtener el
//path o destino y archivos como imágenes.
$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

//Aura Router
$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();
$map->get('adminIndex','/',[
    'controller'=>'App\Controllers\AdminController',
    'action'=>'getAdminIndex'
]);

$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

if(!$route){
    echo 'Entrada no válida';
}else{
    $handlerData = $route->handler;
    $controllerName = $handlerData['controller'];
    $actionName = $handlerData['action'];

    $controller = new $controllerName;
    // $controller->$actionName($request);
    $response = $controller->$actionName($request);

    echo $response->getBody();//se imprime la respuesta HTML
}