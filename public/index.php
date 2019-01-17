<?php
// Inicializando errores
ini_set('display_errors',1);
ini_set('display_starup_errors',1);
error_reporting(E_ALL);

//Archivos requeridos
require_once '../vendor/autoload.php';
//use
use Aura\Router\RouterContainer;
use Illuminate\Database\Capsule\Manager as Capsule;

//eloquent - Illuminate DB
$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'rescate_animal',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);
// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

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
// Rutas del visitante
$map->get('visitorIndex','/',[
    'controller'=>'App\Controllers\VisitorController',
    'action'=>'getVisitorIndex'
]);

// Rutas del administrador
$map->get('adminIndex','/admin',[
    'controller'=>'App\Controllers\AdminController',
    'action'=>'getAdminIndex',
    'auth'=>true
]);


$map->get('loginIndex','/login',[
    'controller'=>'App\Controllers\AccessController',
    'action'=>'getLogin'
]);
$map->get('logout','/logout',[
    'controller'=>'App\Controllers\AccessController',
    'action'=>'getLogout'
]);


$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

if(!$route){
    echo 'Entrada no válida';
}else{
    $handlerData = $route->handler;
    $needsAuth = $handlerData['auth'] ?? false;

    if($needsAuth){
        $controllerName = 'App\Controllers\AccessController';
        $actionName = 'getLogout';
        
    }else{
        $controllerName = $handlerData['controller'];
        $actionName = $handlerData['action'];
    }

    $controller = new $controllerName;
    // $controller->$actionName($request);
    $response = $controller->$actionName($request);

    // en este caso se realiza el foreach para el redireccionamiento
    foreach ($response->getHeaders() as $name => $values) {
        foreach ($values as $value) {
            header(sprintf('%s: %s',$name, $value),false);//funcion que imprime los headers
        }
    }
    http_response_code($response->getStatusCode());//permite establecer cual es codigo de respuesta

    echo $response->getBody();//se imprime la respuesta HTML
}